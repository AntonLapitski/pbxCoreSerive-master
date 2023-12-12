<?php


namespace app\src\pbx\scheme\strategy;

use app\src\pbx\Pbx;
use app\src\pbx\scheme\model\Data;
use app\src\pbx\twiml\Twiml;

/**
 * Class BasicBuilder
 * @package app\src\pbx\scheme\strategy
 */
abstract class BasicBuilder
{
    protected Pbx $pbx;
    protected array $data;

    /**
     * BasicBuilder constructor.
     * @param Pbx $pbx
     */
    public function __construct(Pbx $pbx)
    {
        $this->pbx = $pbx;
    }

    /**
     * @return Data
     */
    public function build(): Data
    {
        return new Data(['scheme' => $this->data ?? []]);
    }

    /**
     * @param $element
     * @return array
     */
    protected function element($element): array
    {
        $class = 'app\src\pbx\scheme\element\\' . ucfirst($element[Twiml::VERB]);
        if (class_exists($class))
            return (new $class($this->pbx->instance, $this->pbx->scheme->flow->settings, $element))->build();

        return $element;
    }
}