<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class InternalCheckpoint
 * @property string $type
 * @property string $branch
 * @property mixed $step
 * @property string $target
 * @package app\src\pbx\checkpoint\strategy
 */
class InternalCheckpoint extends BasicCheckpoint
{
    const TYPE = 'internal';

    const BRANCH = 'internal';

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

    /**
     * задание
     *
     * @var string
     */
    public string $target;
}