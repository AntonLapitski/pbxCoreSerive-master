<?php


namespace app\src\instance\config\src;


use app\src\models\twilio\TwilioSettings;
use app\src\event\EventInterface;
use app\src\instance\log\Log;

/**
 * Class ConfigSettings
 * @package app\src\instance\config\src
 */
class ConfigSettings
{
    public EventInterface $event;
    public TwilioSettings $twilio;
    public Log $log;
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