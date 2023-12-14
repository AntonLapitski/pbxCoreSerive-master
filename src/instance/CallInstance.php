<?php


namespace app\src\instance;


use app\src\models\User;
use app\src\event\Event;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\integration\Integration;
use app\src\pbx\checkpoint\Checkpoint;

/**
 * Class CallInstance
 * @package app\src\instance
 */
class CallInstance extends Instance
{
    /**
     * получить конфиг
     *
     * @param \app\src\models\Config|null $model
     * @return ConfigInterface
     * @throws config\ConfigException
     */
    protected function getConfig(\app\src\models\Config $model = null): ConfigInterface
    {
        if (Event::DIRECTION_INCOMING === $this->event->request->Direction)
            $model = $this->client->twilio->settings->studio_number_list->get(
                'number', $this->getCallInitSource($this->event->request->To))->getConfig();

        if (Event::DIRECTION_OUTGOING === $this->event->request->Direction)
            $model = $this->client->userList->get($this->getCallInitSource($this->event->request->From))->config;

        if (Event::DIRECTION_INTERNAL === $this->event->request->Direction)
            $model = $this->client->userList->get($this->getCallInitSource($this->event->request->From))->config;

        return parent::getConfig($model);
    }

    /**
     *
     * получить источник звонка
     *
     * @param string $phone
     * @return string
     */
    private function getCallInitSource(string $phone)
    {
        return $this->log->model->isNewRecord ? $phone : $this->getCallInitSourceFromTwilio();
    }

    /**
     * получить ответственного юзера
     *
     * @param null $target
     * @return User|null
     */
    protected function getResponsibleUser($target = null): ?User
    {
        if (Event::DIRECTION_OUTGOING == $this->event->request->Direction)
            $target = $this->event->request->From;

        if (Event::DIRECTION_INTERNAL == $this->event->request->Direction)
            $target = $this->event->request->To;

        if (Event::DIRECTION_INCOMING == $this->event->request->Direction) {
            if (!$this->log->model->isNewRecord)
                if (null === ($target = Checkpoint::getTarget($this->log->model->checkpoint)))
                    if (isset($this->event->request->DialCallSid)) {
                        $call = \Yii::$app->twilio->getCallData($this->event->request->DialCallSid);
                        if (in_array($this->event->request->Direction, [Event::DIRECTION_INCOMING, Event::DIRECTION_INTERNAL]))
                            $target = $call['To'];
                        if (Event::DIRECTION_OUTGOING === $this->event->request->Direction)
                            $target = $call['From'];
                    }
        }

        return parent::getResponsibleUser($target);
    }

    /**
     * установить интеграционные данные
     *
     * @return void
     */
    protected function setIntegrationData(): void
    {
        if (!isset($this->config->integration) || !$this->config->integration instanceof Integration)
            return;

        if (in_array($this->event->step, [Event::STEP_INIT, Event::STEP_STATUS, Event::STEP_DIAL_STATUS]))
            parent::setIntegrationData();

        if (Event::STEP_STATUS === $this->event->step)
            $this->config->integration->sendData($this->data->integrationRequest(), $this->event->route);

        if (Event::STEP_ROUTE === $this->event->step && $this->event->status->isAnswered())
            $this->config->integration->sendData($this->data->integrationRequest(), $this->event->route);
    }
}