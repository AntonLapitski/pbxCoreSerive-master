<?php

namespace app\src\pbx\checkpoint\strategy;

/**
 * Class ExtensionCheckpoint
 * @property string $type
 * @property string $from
 * @property string $to
 * @package app\src\pbx\checkpoint\strategy
 */
class ExtensionCheckpoint extends BasicCheckpoint
{
    const TYPE = 'extension';

    const BRANCH = 'extension';

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
     * куда отправлять
     *
     * @var string
     */
    public string $to;
}