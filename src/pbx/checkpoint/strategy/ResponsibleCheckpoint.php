<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class ResponsibleCheckpoint
 * @property string $type
 * @property string $target
 * @package app\src\pbx\checkpoint\strategy
 */
class ResponsibleCheckpoint extends BasicCheckpoint
{
    const TYPE = 'responsible';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * задание
     *
     * @var string
     */
    public string $target;
}