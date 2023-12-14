<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class CallbackCheckpoint
 * @property string $type
 * @property string $from
 * @property string $to
 * @property mixed $step
 * @property array $list
 * @package app\src\pbx\checkpoint\strategy
 */
class CallbackCheckpoint extends BasicCheckpoint
{
    const TYPE = 'callback';

    const BRANCH = 'callback';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * отправитель
     *
     * @var string
     */
    public string $from;

    /**
     * от кого
     *
     * @var string
     */
    public string $to;

    /**
     * шаг
     *
     * @var mixed
     */
    public mixed $step = 0;

    /**
     * список
     *
     * @var array
     */
    public array $list = [];
}