<?php


namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Redirect
 * @package app\src\pbx\twiml\builder
 */
class Redirect extends TwimlMapper
{
    /**
     * засетить редирект урл
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        parent::build()->redirect($this->element['url'], $this->element[Twiml::OPTIONS] ?? []);
        return $this->response;
    }
}