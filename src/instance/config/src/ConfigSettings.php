<?php


namespace app\src\instance\config\src;


use app\src\models\twilio\TwilioSettings;
use app\src\event\EventInterface;
use app\src\instance\log\Log;

/**
 * Class ConfigSettings
 * Класс Конфиг Настройки
 *
 * @property EventInterface $event
 * @property TwilioSettings $twilio
 * @property Log $log
 * @property string $timezone
 * @package app\src\instance\config\src
 */
class ConfigSettings
{
    /**
     * событие
     *
     * @var EventInterface $event
     */
    public EventInterface $event;

    /**
     * твилио
     *
     * @var TwilioSettings $twilio
     */
    public TwilioSettings $twilio;

    /**
     * логгер
     *
     * @var Log $log
     */
    public Log $log;

    /**
     * таймзона
     *
     * @var string $timezone
     */
    public string $timezone;

    /**
     * ConfigSettings constructor.
     * @param EventInterface $event
     * @param TwilioSettings $twilio
     * @param Log $log
     * @param string $timezone
     */
    public function __construct(EventInterface $event, TwilioSettings $twilio, Log $log, string $timezone)
    {
        $this->event = $event;
        $this->twilio = $twilio;
        $this->log = $log;
        $this->timezone = $timezone;
    }

}