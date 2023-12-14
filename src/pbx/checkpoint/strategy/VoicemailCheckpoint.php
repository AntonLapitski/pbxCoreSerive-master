<?php

namespace app\src\pbx\checkpoint\strategy;


/**
 * Class VoicemailCheckpoint
 * @property string $type
 * @property string $branch
 * @property mixed $step
 * @property string $target
 * @package app\src\pbx\checkpoint\strategy
 */
class VoicemailCheckpoint extends MainCheckpoint
{
    const TYPE = 'voicemail';

    const BRANCH = 'voicemail';

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