<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class ResponsibleCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class ResponsibleCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'responsible';

    public string $type = self::TYPE;
    public string $target;
}