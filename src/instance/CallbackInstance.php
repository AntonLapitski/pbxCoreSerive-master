<?php


namespace app\src\instance;


use app\src\models\User;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\integration\Integration;


/**
 * Class CallbackInstance
 * @package app\src\instance
 */
class CallbackInstance extends Instance
{
    /**
     * @param \app\src\models\Config|null $model
     * @return ConfigInterface
     * @throws config\ConfigException
     */
    protected function getConfig(\app\src\models\Config $model = null): ConfigInterface
    {
        if ('incoming' === $this->event->request->Direction)
            $model = $this->client->twilio->settings->studio_number_list->get(
                'number',
                $this->log->model->isNewRecord
                    ? $this->event->request->To : $this->log->model->checkpoint['to']
            )->getConfig();

        if ('outgoing' === $this->event->request->Direction)
            $model = $this->client->userList->get(
                $this->log->model->isNewRecord
                    ? $this->event->request->From
                    : $this->log->model->checkpoint['from']
            )->config;

        return parent::getConfig($model);
    }

    /**
     * @param null $target
     * @return User|null
     */
    protected function getResponsibleUser($target = null): ?User
    {
        if ('outgoing' === $this->event->request->Direction)
            $target = ($this->log->model->isNewRecord)
                ? $this->event->request->From
                : $this->log->model->checkpoint['from'];


        if ('incoming' === $this->event->request->Direction)
            if (!$this->log->model->isNewRecord)
                $target = $this->event->request->To;

        return parent::getResponsibleUser($target);
    }


    /**
     *
     */
    protected function setIntegrationData(): void
    {
        if (!isset($this->config->integration) || !$this->config->integration instanceof Integration)
            return;

        $route = str_replace(['parent-', 'child-'], '',
            str_replace('callback', 'call', $this->event->route));

        if ('parent-status' === $this->event->step) {
            $noAnswer = true;
            foreach ($this->log->model->checkpoint[\app\src\instance\config\Config::LIST] as $callSid)
                if ($callSid === $this->event->request->CallSid)
                    $noAnswer = ('completed' === $this->event->request->CallStatus) ? false : $noAnswer;
                else {
                    $result = $this->log->get($callSid)->model->result;
                    $noAnswer = is_null($result) || ('completed' === $result) ? false : $noAnswer;
                }

            if ($noAnswer)
                $this->config->integration->sendData($this->data->integrationRequest(), $route);

            $this->log->get($this->event->request->CallSid);
        }

        if ('child-status' === $this->event->step)
            $this->config->integration->sendData($this->data->integrationRequest(), $route);

    }
}