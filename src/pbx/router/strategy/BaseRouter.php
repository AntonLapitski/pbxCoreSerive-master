<?php

namespace app\src\pbx\router\strategy;

use app\src\event\Event;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\Pbx;
use app\src\pbx\router\PbxRouterInterface;

/**
 * Class BaseRouter
 * @property Pbx $pbx
 * @package app\src\pbx\router\strategy
 */
abstract class BaseRouter implements PbxRouterInterface
{
    /**
     *
     *
     * @var Pbx
     */
    protected Pbx $pbx;

    /**
     * BaseRouter constructor.
     * @param Pbx $pbx
     */
    public function __construct(Pbx $pbx)
    {
        $this->pbx = $pbx;
    }

    /**
     * засетить проверочный шаг
     *
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if ($this->pbx->instance->isInBlackList())
            return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);

        if ($this->isHangup())
            return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);

        return $this->pbx->checkpoint;
    }

    /**
     * зависает ли
     *
     * @return bool
     */
    private function isHangup(): bool
    {
        return (
            $this->pbx->instance->event->status->isHangup()
            ||
            $this->pbx->checkpoint instanceof HangupCheckpoint
            ||
            (
            in_array
            (
                $this->pbx->instance->event->status->getCallResult(),
                [Event::STATUS_HANGUP, Event::STATUS_COMPLETED]
            )
            )
        );
    }
}
