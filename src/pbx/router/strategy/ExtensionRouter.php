<?php

namespace app\src\pbx\router\strategy;

use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\ExtensionCheckpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\OutgoingCheckpoint;

/**
 * Class ExtensionRouter
 * @package app\src\pbx\router\strategy
 */
class ExtensionRouter extends BaseRouter
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
            return $this->pbx->checkpoint->set(ExtensionCheckpoint::TYPE, [
                Checkpoint::BRANCH => OutgoingCheckpoint::BRANCH,
                Checkpoint::FROM => $this->pbx->instance->event->request->From,
                Checkpoint::TO => $this->pbx->instance->event->request->To,
            ]);

        return $this->pbx->checkpoint;
    }

}