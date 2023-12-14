<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class GatherCheckpoint
 * @property string $type
 * @property array $gather
 * @package app\src\pbx\checkpoint\strategy
 */
class GatherCheckpoint extends MainCheckpoint
{
    const TYPE = 'gather';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * собран
     *
     * @var array
     */
    public array $gather;
}