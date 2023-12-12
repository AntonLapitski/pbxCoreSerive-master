<?php

namespace app\src\pbx\checkpoint\strategy;

/**
 * Class MessageCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
class MessageCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'message';

    /**
     *
     */
    const DIRECTION = 'direction';
    /**
     *
     */
    const CONFIG_SID = 'configSid';
    /**
     *
     */
    const INTEGRATION_SID = 'integrationSid';
    /**
     *
     */
    const BODY = 'body';

    public string $type = self::TYPE;
    public string $direction;
    public string $configSid;
    public string $integrationSid;
    public string $body;
    public mixed $step;
}