<?php

namespace app\src\pbx\checkpoint\strategy;

/**
 * Class ExtensionCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class ExtensionCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'extension';
    /**
     *
     */
    const BRANCH = 'extension';

    public string $type = self::TYPE;
    public string $from;
    public string $to;
}