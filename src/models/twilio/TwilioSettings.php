<?php


namespace app\src\models\twilio;


use app\src\models\Model;


/**
 * @property StudioNumberList $studio_number_list
 * @property IntegrationList $integration_list
 * @property string $message_service
 * @property string $record_from
 * @property string $callback_message
 * @property boolean $callback_phone_in_cnam
 * @property boolean $show_cname
 * @property boolean $application
 */
class TwilioSettings extends Model
{
    public StudioNumberList $studio_number_list;
    public IntegrationList $integration_list;
    public string $message_service;
    public array|string $record_from;
    public string $callback_message;
    public bool $callback_phone_in_cnam;
    public string $application;

    public bool $show_cname;

    /**
     * TwilioSettings constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($this->_config($config));
    }

    /**
     * @param $config
     * @return mixed
     */
    private function _config($config)
    {
        foreach ($config as $prop => $value) {
            if ('studio_number_list' === $prop)
                $config[$prop] = new StudioNumberList(['list' => $value]);
            if ('integration_list' === $prop)
                $config[$prop] = new IntegrationList(['list' => $value]);
        }

        return $config;
    }

    /**
     * @param $direction
     * @return string
     */
    public function getRecordFrom($direction)
    {
        return $this->record_from[$direction] ?? 'record-from-ringing';
    }

}


