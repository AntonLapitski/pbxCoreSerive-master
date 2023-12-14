<?php

namespace app\src\pbx\router\strategy;

use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\CallbackCheckpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\OutgoingCheckpoint;

/**
 * Class CallbackRouter
 * @package app\src\pbx\router\strategy
 */
class CallbackRouter extends BaseRouter
{
    /**
     * засетить проверочный пункт
     *
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if (parent::exec()->model instanceof HangupCheckpoint)
            return $this->pbx->checkpoint;

        if ($this->pbx->instance->log->model->isNewRecord)
            return $this->pbx->checkpoint->set(CallbackCheckpoint::TYPE, [
                Checkpoint::BRANCH => CallbackCheckpoint::BRANCH,
                Checkpoint::FROM => $this->pbx->instance->event->request->From,
                Checkpoint::TO => $this->pbx->instance->event->request->To,
            ]);

        $this->pbx->checkpoint->model->branch = OutgoingCheckpoint::BRANCH;
        return $this->pbx->checkpoint;
    }

}