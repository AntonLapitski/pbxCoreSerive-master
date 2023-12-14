<?php


namespace app\src\pbx\scheme\strategy;


use app\src\pbx\scheme\model\Data;

/**
 * Class InternalBuilder
 * @package app\src\pbx\scheme\strategy
 */
class InternalBuilder extends DirectBuilder
{
    /**
     * засетить дату и сохранить
     *
     * @return Data
     */
    public function build(): Data
    {
        $this->data = $this->scheme(
            $this->pbx->instance->client->userList->get(
                $this->pbx->checkpoint->model->target
            )
        );

        return parent::build();
    }
}