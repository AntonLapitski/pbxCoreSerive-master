<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class CallbackCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class CallbackCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'callback';
    /**
     *
     */
    const BRANCH = 'callback';

    public string $type = self::TYPE;
    public string $from;
    public string $to;
    public mixed $step = 0;
    public array $list = [];
}