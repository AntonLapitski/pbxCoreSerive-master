<?php


namespace app\src\pbx\scheme\element;


/**
 * Class Gather
 * @package app\src\pbx\scheme\element
 */
class Gather extends BasicElement
{
    public array $options = [];
    public array $notice;
    public array $handler;

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