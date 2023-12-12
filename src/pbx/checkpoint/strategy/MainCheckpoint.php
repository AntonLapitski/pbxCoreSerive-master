<?php


namespace app\src\pbx\checkpoint\strategy;


/**
 * Class MainCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class MainCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'main';
    /**
     *
     */
    const BRANCH = 'main';
    /**
     *
     */
    const FLOW_SID = 'flowSid';

    public string $type = self::TYPE;
    public string $branch = self::BRANCH;
    public mixed $step = 0;
    public string $flowSid = '';
}
