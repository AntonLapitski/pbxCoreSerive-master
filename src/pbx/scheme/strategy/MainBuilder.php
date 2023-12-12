<?php


namespace app\src\pbx\scheme\strategy;


use app\src\pbx\scheme\model\Data;

/**
 * Class MainBuilder
 * @package app\src\pbx\scheme\strategy
 */
class MainBuilder extends BasicBuilder
{
    /**
     * @return Data
     */
    public function build(): Data
    {
        $flow = array_map(function ($element) {
            return $this->element($element);
        }, $this->pbx->scheme->flow->current());

        if ($this->pbx->instance->event->status->isInit())
            if (!empty($this->pbx->scheme->flow->settings->welcome_message))
                $flow = array_merge($this->pbx->scheme->flow->settings->welcome_message, $flow);

        $this->data = $flow;
        return parent::build();
    }
}