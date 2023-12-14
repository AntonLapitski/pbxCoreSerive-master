<?php


namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;

/**
 * Class Gather
 * @package app\src\pbx\twiml\builder
 */
class Gather extends TwimlMapper
{
    const VERB = 'gather';

    const NOTICE = 'notice';

    /**
     * установить записи т вернуть ответ
     *
     * @return \Twilio\TwiML\VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        $this->setNotice(
            parent::build()->gather(
                $this->element[Twiml::OPTIONS]
            )
        );
        return $this->response;
    }

    /**
     * установить записи
     *
     * @param \Twilio\TwiML\Voice\Gather $gather
     */
    protected function setNotice(\Twilio\TwiML\Voice\Gather $gather): void
    {
        foreach ($this->element[self::NOTICE] as $item) {
            if ($item[Twiml::VERB] === Play::VERB)
                $gather->play($item[Play::URL], $item[Twiml::OPTIONS]);
            if ($item[Twiml::VERB] === Say::VERB)
                $gather->say($item[Say::MESSAGE], $item[Twiml::OPTIONS]);
        }
    }
}