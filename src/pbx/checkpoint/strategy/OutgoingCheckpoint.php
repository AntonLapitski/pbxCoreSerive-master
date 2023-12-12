<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class OutgoingCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class OutgoingCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'outgoing';
    /**
     *
     */
    const BRANCH = 'outgoing';

    public string $type = self::TYPE;
    public string $branch = self::BRANCH;
    public mixed $step = 0;
}