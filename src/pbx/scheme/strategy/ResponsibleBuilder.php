<?php

namespace app\src\pbx\scheme\strategy;


use app\src\pbx\scheme\model\Data;

/**
 * Class ResponsibleBuilder
 * @package app\src\pbx\scheme\strategy
 */
class ResponsibleBuilder extends DirectBuilder
{
    /**
     * @return Data
     */
    public function build(): Data
    {
        $target = $this->pbx->instance->client->userList->get($this->pbx->checkpoint->model->target);

        $flow = [];
        if ($this->pbx->instance->event->status->isInit())
            if (!empty($this->pbx->scheme->flow->settings->welcome_message))
                $flow = array_merge($flow, $this->pbx->scheme->flow->settings->welcome_message);

        $this->data = array_merge($flow, $this->scheme($target));

        return parent::build();
    }
}