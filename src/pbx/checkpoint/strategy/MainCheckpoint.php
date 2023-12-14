<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class MainCheckpoint
 * @property string $type
 * @property string $branch
 * @property string $flowSid
 * @property mixed $step
 * @package app\src\pbx\checkpoint\strategy
 */
class MainCheckpoint extends BasicCheckpoint
{
    const TYPE = 'main';

    const BRANCH = 'main';

    const FLOW_SID = 'flowSid';

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
     * айди потока
     *
     * @var string
     */
    public string $flowSid = '';
}
