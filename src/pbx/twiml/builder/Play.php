<?php


namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Play
 * @package app\src\pbx\twiml\builder
 */
class Play extends TwimlMapper
{

    const VERB = 'play';

    const URL = 'url';

    /**
     * засетить проигрывание
     *
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        parent::build()->play($this->element['url'], $this->element[Twiml::OPTIONS] ?? []);
        return $this->response;
    }
}