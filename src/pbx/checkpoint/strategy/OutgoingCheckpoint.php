<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class OutgoingCheckpoint
 * @property string $type
 * @property string $branch
 * @property mixed $step
 * @package app\src\pbx\checkpoint\strategy
 */
class OutgoingCheckpoint extends BasicCheckpoint
{
    const TYPE = 'outgoing';

    const BRANCH = 'outgoing';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * ветка
     *
     * @var string
     */
    public string $branch = self::BRANCH;

    /**
     * шаг
     *
     * @var mixed
     */
    public mixed $step = 0;
}