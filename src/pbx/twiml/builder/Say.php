<?php

namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Say
 * @package app\src\pbx\twiml\builder
 */
class Say extends TwimlMapper
{
    /**
     *
     */
    const VERB = 'say';
    /**
     *
     */
    const MESSAGE = 'message';

    /**
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        parent::build()->say(
            $this->element['message'],
            $this->element[Twiml::OPTIONS] ?? []
        );

        return $this->response;
    }
}


