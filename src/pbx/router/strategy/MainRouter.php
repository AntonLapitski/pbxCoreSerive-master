<?php


namespace app\src\pbx\router\strategy;


use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\MainCheckpoint;
use app\src\pbx\checkpoint\strategy\VoicemailCheckpoint;

/**
 * Class MainRouter
 * @package app\src\pbx\router\strategy
 */
class MainRouter extends BaseRouter
{
    /**
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if (parent::exec()->model instanceof HangupCheckpoint)
            return $this->pbx->checkpoint;

        if ($this->pbx->instance->event->status->isInit())
            if (isset($this->pbx->scheme->flow->flow))
                return $this->pbx->checkpoint->set(MainCheckpoint::TYPE);

        if ($flow = $this->pbx->checkpoint->model->flowSid ?? false)
            $this->pbx->scheme->setFlow($flow);

        if ($this->pbx->scheme->flow->isIssetNext())
            return $this->pbx->checkpoint->setNextStep();

        if (isset($this->pbx->scheme->flow->voicemail))
            return $this->pbx->checkpoint->set(VoicemailCheckpoint::TYPE);

        return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);
    }
}
