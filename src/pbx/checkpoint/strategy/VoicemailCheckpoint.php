<?php

namespace app\src\pbx\checkpoint\strategy;


/**
 * Class VoicemailCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class VoicemailCheckpoint extends MainCheckpoint
{
    /**
     *
     */
    const TYPE = 'voicemail';
    /**
     *
     */
    const BRANCH = 'voicemail';

    public string $type = self::TYPE;
    public string $branch = self::BRANCH;
    public mixed $step = 0;
    public string $target;
}