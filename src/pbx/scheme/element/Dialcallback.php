<?php

namespace app\src\pbx\scheme\element;

/**
 * Class Dialcallback
 * @package app\src\pbx\scheme\element
 */
class Dialcallback extends Dial
{
    /**
     * @return array
     */
    public function build(): array
    {
        $this->verb = \app\src\pbx\twiml\builder\Dial::VERB;
        return parent::build();
    }

    /**
     *
     */
    protected function options(): void
    {
        $options = [
            'action' => SERVER_ADDRESS . '/callback/child-status',
            'record' => $this->instance->client->twilio->settings->getRecordFrom(
                $this->instance->event->request->Direction),
        ];

        $this->options += $options;
    }

    /**
     * @param string $target
     * @return array
     */
    protected function setListElement(string $target): array
    {
        return [
            self::TARGET => $target,
            self::OPTIONS => []
        ];
    }
}