<?php


namespace app\src\pbx\scheme\element;


/**
 * Class Redirect
 * @property string $url
 * @property array $options
 * @package app\src\pbx\scheme\element
 */
class Redirect extends BasicElement
{
    /**
     * урл
     *
     * @var string
     */
    public string $url;

    /**
     * опции
     *
     * @var array
     */
    public array $options = [];

    /**
     * сбилдить поменяный урл
     *
     * @return array
     */
    public function build(): array
    {
        if (!isset($this->url))
            $this->url = SERVER_ADDRESS . '/call/route';

        return parent::build();
    }

    /**
     * засетить опции
     *
     * @return void
     */
    protected function options(): void
    {
        $this->options = array_merge($this->options, [
            'method' => 'POST',
        ]);
    }

}