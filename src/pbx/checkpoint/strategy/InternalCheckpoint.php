<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class InternalCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class InternalCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'internal';
    /**
     *
     */
    const BRANCH = 'internal';

    public string $type = self::TYPE;
    public string $branch = self::BRANCH;
    public mixed $step = 0;
    public string $target;
}