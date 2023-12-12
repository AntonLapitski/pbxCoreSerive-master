<?php


namespace app\src\pbx\scheme\element;


/**
 * Class Record
 * @package app\src\pbx\scheme\element
 */
class Record extends BasicElement
{
    public array $options = [];

    /**
     * @return array
     */
    public function build(): array
    {
        $this->options();
        return parent::build();
    }

    /**
     *
     */
    protected function options(): void
    {
        $this->options = array_merge($this->options, [
            'method' => 'POST',
            'action' => SERVER_ADDRESS . '/call/route'
        ]);
    }

}