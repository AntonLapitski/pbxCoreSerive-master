<?php

namespace app\src\pbx\scheme\strategy;

use app\src\pbx\scheme\model\Data;
use app\src\pbx\twiml\builder\Say;
use app\src\pbx\twiml\Twiml;

/**
 * Class GatherBuilder
 * @package app\src\pbx\scheme\strategy
 */
class GatherBuilder extends BasicBuilder
{
    /**
     * поменять даные и сохранить
     *
     * @return Data
     */
    public function build(): Data
    {
        if ($this->pbx->checkpoint->model->gather ?? false)
            $flow = $this->pbx->scheme->flow->gather->current();
        else $flow = array_merge($this->errorMessage(), $this->pbx->scheme->flow->current());

        $this->data = array_map(function ($element) {
            return $this->element($element);
        }, $flow);

        return parent::build();
    }


    /**
     * вернуть сообщение об ошибке
     *
     * @return array
     */
    private function errorMessage()
    {
        return [[
            Twiml::VERB => Say::VERB,
            'message' => sprintf('Your choice %s is incorrect try more', $this->pbx->instance->event->request->Digits)
        ]];
    }
}