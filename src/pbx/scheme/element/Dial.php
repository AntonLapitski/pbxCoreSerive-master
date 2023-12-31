<?php

namespace app\src\pbx\scheme\element;


/**
 * Class Dial
 * @property string $noun
 * @property array $list
 * @property array $options
 * @package app\src\pbx\scheme\element
 */
class Dial extends BasicElement
{
    const NOUN_SIP = 'sip';

    const NOUN_NUMBER = 'number';

    const NOUN_CLIENT = 'client';

    const TARGET = 'target';

    const OPTIONS = 'options';

    /**
     * существительное
     *
     * @var string
     */
    public string $noun;

    /**
     * список
     *
     * @var array
     */
    public array $list;

    /**
     * опции
     *
     * @var array
     */
    public array $options;

    /**
     * выстроить список и опции
     *
     * @return array
     */
    public function build(): array
    {
        $this->list();
        $this->options();
        return parent::build();
    }

    /**
     * засетить список
     *
     * @return void
     */
    private function list(): void
    {
        foreach ($this->list as $key => $value) {
            if (self::NOUN_SIP === $this->noun)
                $target = sprintf(
                    'sip:%s@%s',
                    $this->instance->client->userList->get($value)->sip,
                    $this->instance->client->twilio->domain
                );
            else if (self::NOUN_NUMBER === $this->noun)
                $target = is_numeric(str_replace('+', '', $value))
                    ? $value
                    : $this->instance->client->userList->get($value)->mobile_number;
            else if (self::NOUN_CLIENT === $this->noun)
                $target = $value;

            $this->list[$key] = $this->setElement((string)($target ?? ''));
        }
    }

    /**
     * установить массив нужными данными
     *
     * @param string $target
     * @return array
     */
    protected function setElement(string $target): array
    {
        return [
            self::TARGET => $target,
            self::OPTIONS => [
                'statusCallback' => SERVER_ADDRESS . '/'.$this->instance->event->event.'/dial-status',
                'statusCallbackMethod' => 'POST',
                'statusCallbackEvent' => 'initiated ringing answered completed'
            ],
        ];
    }

    /**
     * засетить опции
     *
     * @return void
     */
    protected function options(): void
    {
        $options = [
            'action' => SERVER_ADDRESS . '/'.$this->instance->event->event.'/route',
            'record' => $this->instance->client->twilio->settings->getRecordFrom(
                $this->instance->event->request->Direction),
            'referUrl' => SERVER_ADDRESS . '/'.$this->instance->event->event.'/route',
            'referMethod' => 'POST'
        ];

        if (
            $this->settings->show_cname
            && self::NOUN_NUMBER !== $this->noun
            && $cnam = $this->instance->getCallerName()
        )
            $options['callerId'] = $cnam;

        $this->options += $options;
    }

}