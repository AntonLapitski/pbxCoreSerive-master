<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class GatherCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class GatherCheckpoint extends MainCheckpoint
{
    /**
     *
     */
    const TYPE = 'gather';

    public string $type = self::TYPE;
    public array $gather;
}