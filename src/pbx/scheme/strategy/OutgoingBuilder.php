<?php


namespace app\src\pbx\scheme\strategy;


use app\src\pbx\scheme\model\Data;

/**
 * Class OutgoingBuilder
 * @package app\src\pbx\scheme\strategy
 */
class OutgoingBuilder extends BasicBuilder
{
    /**
     * засетить дата и сохранить
     *
     * @return Data
     */
    public function build(): Data
    {
        $this->data = array_map(function ($element) {
            return $this->element($element);
        }, $this->pbx->scheme->flow->flow);

        return parent::build();
    }

}