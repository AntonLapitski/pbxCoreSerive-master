<?php


namespace app\src\pbx\scheme\element;


use app\src\pbx\twiml\Options;

/**
 * Class Gather
 * @property array $options
 * @property array $notice
 * @property array $handler
 * @package app\src\pbx\scheme\element
 */
class Gather extends BasicElement
{
    /**
     * массив опций
     *
     * @var array
     */
    public array $options = [];

    /**
     * массив замечаний
     *
     * @var array
     */
    public array $notice;

    /**
     * обработчик
     *
     * @var array
     */
    public array $handler;

    /**
     * установить опции
     *
     * @return array
     */
    public function build(): array
    {
        $this->options();
        return parent::build();
    }

    /**
     * установить опции
     *
     * @return void
     */
    protected function options(): void
    {
        $this->options = array_merge($this->options, [
            'method' => 'POST',
            'action' => SERVER_ADDRESS . '/call/route'
        ]);
    }

}