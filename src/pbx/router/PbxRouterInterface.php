<?php


namespace app\src\pbx\router;


use app\src\pbx\checkpoint\Checkpoint;

/**
 * Interface PbxRouterInterface
 * @package app\src\pbx\router
 */
interface PbxRouterInterface
{
    /**
     * @return Checkpoint
     */
    public function exec(): Checkpoint;
}