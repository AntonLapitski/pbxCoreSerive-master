<?php


namespace app\src\pbx\scheme\element;


/**
 * Class Redirect
 * @package app\src\pbx\scheme\element
 */
class Redirect extends BasicElement
{
    public string $url;
    public array $options = [];

    /**
     * @return array
     */
    public function build(): array
    {
        if (!isset($this->url))
            $this->url = SERVER_ADDRESS . '/call/route';

        return parent::build();
    }

    /**
     *
     */
    protected function options(): void
    {
        $this->options = array_merge($this->options, [
            'method' => 'POST',
        ]);
    }

}