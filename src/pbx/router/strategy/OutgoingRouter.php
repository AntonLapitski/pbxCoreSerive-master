<?php


namespace app\src\pbx\router\strategy;


use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\OutgoingCheckpoint;

/**
 * Class OutgoingRouter
 * @package app\src\pbx\router\strategy
 */
class OutgoingRouter extends BaseRouter
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

        if (OutgoingCheckpoint::TYPE === $this->pbx->checkpoint->model->type)
            return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);

        return $this->pbx->checkpoint->set(OutgoingCheckpoint::TYPE);
    }

}