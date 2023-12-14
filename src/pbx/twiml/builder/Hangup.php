<?php


namespace app\src\pbx\twiml\builder;


/**
 * Class Hangup
 * @package app\src\pbx\twiml\builder
 */
class Hangup extends TwimlMapper
{
    const VERB = 'hangup';

    /**
     * засетить повешение трубки
     *
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        parent::build()->hangup();
        return $this->response;
    }
}