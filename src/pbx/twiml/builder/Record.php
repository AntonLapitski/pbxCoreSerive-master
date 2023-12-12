<?php


namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Record
 * @package app\src\pbx\twiml\builder
 */
class Record extends TwimlMapper
{
    /**
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        parent::build()->record($this->element[Twiml::OPTIONS] ?? []);
        return $this->response;
    }
}