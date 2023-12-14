<?php


namespace app\src\pbx\scheme\element;


/**
 * Class Record
 * @property array $options
 * @package app\src\pbx\scheme\element
 */
class Record extends BasicElement
{
    /**
     * массив опций
     *
     * @var array
     */
    public array $options = [];

    /**
     * засетить опции
     *
     * @return array
     */
    public function build(): array
    {
        $this->options();
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
            'action' => SERVER_ADDRESS . '/call/route'
        ]);
    }

}