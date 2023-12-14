<?php

namespace app\src\instance\config\message;


use app\src\instance\config\Config;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\integration\Integration;
use app\src\pbx\checkpoint\strategy\MessageCheckpoint;
use app\src\pbx\twiml\builder\Hangup;
use app\src\pbx\twiml\Twiml;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class OutgoingConfig
 * Исходящий конфиг
 *
 * @property string $messageService
 * @package app\src\instance\config\message
 */
class OutgoingConfig extends \app\src\instance\config\basic\OutgoingConfig implements ConfigInterface
{

    /**
     * сервис сообщений
     *
     * @var string
     */
    public string $messageService;

    #[ArrayShape([Config::FLOW => "array[]"])]
    /**
     * вернуть конфиг
     *
     * @param null $status
     * @return array
     */
    public function flow($status = null): array
    {
        return [Config::FLOW => array([
            Twiml::VERB => Hangup::VERB
        ])];
    }

    /**
     * установить свой-во
     *
     * @param $fieldName
     * @param $sid
     */
    protected function setProperty($fieldName, $sid): void
    {
        if (isset($this->settings->twilio->message_service))
            $this->messageService = $this->settings->twilio->message_service;

        parent::setProperty($fieldName, $sid); // TODO: Change the autogenerated stub
    }

    /**
     * забрать интеграцию
     *
     * @param $sid
     * @return Integration
     */
    protected function getIntegration($sid): Integration
    {
        return new Integration(
            $this->settings->twilio->integration_list->get('sid', $this->getIntegrationSid($sid)),
            $this->settings->log->model->integration_data
        );
    }

    /**
     * забрать айди интеграции
     *
     * @param $sid
     * @return mixed
     */
    private function getIntegrationSid($sid)
    {
        if ($this->settings->event->request->IntegrationSid ?? false)
            return $this->settings->event->request->IntegrationSid;
        else if ($this->settings->log->model->checkpoint[MessageCheckpoint::INTEGRATION_SID] ?? false)
            return $this->settings->log->model->checkpoint[MessageCheckpoint::INTEGRATION_SID];

        return $sid;
    }
}