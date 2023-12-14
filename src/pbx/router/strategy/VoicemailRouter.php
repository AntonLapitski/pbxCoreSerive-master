<?php


namespace app\src\pbx\router\strategy;


use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\router\PbxRouterInterface;

/**
 * Class VoicemailRouter
 * @package app\src\pbx\router\strategy
 */
class VoicemailRouter extends BaseRouter implements PbxRouterInterface
{
    /**
     * засетить проверочный пункт
     *
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);
    }
}