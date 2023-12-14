<?php


namespace app\src\pbx\scheme;

use app\src\pbx\Pbx;
use app\src\pbx\scheme\model\Flow;


/**
 * Class Scheme
 * @property Flow $flow
 * @property Pbx $pbx
 * @package app\src\pbx\scheme
 */
class Scheme
{
    /**
     * поток
     *
     * @var Flow
     */
    public Flow $flow;

    /**
     * поток
     *
     * @var Pbx
     */
    private Pbx $pbx;

    /**
     * Scheme constructor.
     * @param Pbx $pbx
     */
    public function __construct(Pbx $pbx)
    {
        $this->pbx = $pbx;
        $flow = $pbx->instance->config->flow($pbx->checkpoint->model->timetable_status);
        $this->flow = new Flow($pbx->checkpoint, $flow);
    }

    /**
     * установить поток
     *
     * @param string $flowSid
     */
    public function setFlow(string $flowSid): void
    {
        $flow = $this->pbx->instance->config->flow($this->pbx->checkpoint->model->timetable_status, $flowSid);
        $this->flow = new Flow($this->pbx->checkpoint, $flow);
    }

    /**
     * вернуть класс билдер
     *
     * @return array
     */
    public function build(): array|\app\src\pbx\scheme\model\Data
    {
        $class = 'app\src\pbx\scheme\strategy\\' . ucfirst($this->pbx->checkpoint->model->type) . 'Builder';
        if (class_exists($class))
            return (new $class($this->pbx))->build($this->pbx);

        //TODO Throw Exception
    }
}