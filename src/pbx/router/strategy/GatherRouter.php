<?php


namespace app\src\pbx\router\strategy;


use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\GatherCheckpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\MainCheckpoint;
use app\src\pbx\router\Router;

/**
 * Class GatherRouter
 * @package app\src\pbx\router\strategy
 */
class GatherRouter extends BaseRouter
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

        if ($this->pbx->instance->event->status->isGather()) {
            $data = isset($this->pbx->scheme->flow->withGather($this->pbx->instance->event->request->Digits)->gather)
                ? [Router::GATHER_HANDLER => [
                    Checkpoint::BRANCH => $this->pbx->instance->event->request->Digits,
                    Checkpoint::STEP => 0
                ]]
                : [];
            return $this->pbx->checkpoint->set(GatherCheckpoint::TYPE, $data);
        } else if ($this->pbx->checkpoint->model instanceof GatherCheckpoint) {
            if ($this->pbx->scheme->flow->withGather(
                $this->pbx->checkpoint->model->gather[Checkpoint::BRANCH])->gather->isIssetNext()
            )
                return $this->pbx->checkpoint->setNextStepForGatherHandler();
            else {
                $this->pbx->checkpoint->set(MainCheckpoint::TYPE, $this->pbx->checkpoint->model->asArray());
                return (new MainRouter($this->pbx))->exec();
            }

        }

        return $this->pbx->checkpoint;
    }
}