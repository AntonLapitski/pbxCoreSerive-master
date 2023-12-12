<?php


namespace app\src\instance;


use app\src\models\Config;
use app\src\event\Event;
use app\src\instance\config\ConfigInterface;
use app\src\pbx\checkpoint\strategy\MessageCheckpoint;

/**
 * Class MessageInstance
 * @package app\src\instance
 */
class MessageInstance extends Instance
{
    /**
     *
     */
    protected function setIntegrationData(): void
    {
        if (in_array($this->event->step, ['status', 'get']))
            $this->config->integration->sendData($this->data->integrationRequest(), $this->event->route);
    }

    /**
     * @param Config|null $model
     * @return ConfigInterface
     * @throws config\ConfigException
     */
    protected function getConfig(\app\src\models\Config $model = null): ConfigInterface
    {
        if (Event::DIRECTION_OUTGOING === $this->event->request->Direction)
            if (Event::STEP_STATUS === $this->event->step)
                $model = Config::findOne([
                    'company_id' => $this->client->company->id,
                    'direction' => $this->event->request->Direction,
                    'sid' => $this->log->model->checkpoint[MessageCheckpoint::CONFIG_SID] ?? null
                ]);
            else
                $model = $this->client->userList->get($this->event->request->From)->config;

        if (Event::DIRECTION_INCOMING === $this->event->request->Direction)
            $model = $this->client->twilio->settings->studio_number_list
                ->get('number', $this->event->request->To)->getConfig();

        return parent::getConfig($model);
    }
}