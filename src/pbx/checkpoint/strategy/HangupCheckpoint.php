<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class HangupCheckpoint
 * @property string $type
 * @package app\src\pbx\checkpoint\strategy
 */
class HangupCheckpoint extends BasicCheckpoint
{
    const TYPE = 'hangup';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;
}