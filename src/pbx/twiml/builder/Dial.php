<?php

namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Dial
 * @package app\src\pbx\twiml\builder
 */
class Dial extends TwimlMapper
{
    /**
     *
     */
    const VERB = 'dial';
    /**
     *
     */
    const LIST = 'list';

    /**
     *
     */
    const TARGET = 'target';

    /**
     *
     */
    const NOUN_NUMBER = 'number';
    /**
     *
     */
    const NOUN_SIP = 'sip';

    /**
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        $this->setList(
            parent::build()->dial(
                '',
                $this->element[Twiml::OPTIONS] ?? []
            )
        );

        return $this->response;
    }

    /**
     * @param \Twilio\TwiML\Voice\Dial $dial
     */
    private function setList(\Twilio\TwiML\Voice\Dial $dial): void
    {
        $noun = $this->element[Twiml::NOUN];

        foreach ($this->element[self::LIST] as $elem) {
            $dial->$noun($elem[self::TARGET], $elem[Twiml::OPTIONS]);
        }

    }
}