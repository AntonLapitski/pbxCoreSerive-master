<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class HangupCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class HangupCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'hangup';

    public string $type = self::TYPE;
}