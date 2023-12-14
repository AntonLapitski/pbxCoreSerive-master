<?php

namespace app\src\pbx\checkpoint\strategy;

/**
 * Class MessageCheckpoint
 * @property string $type
 * @property string $direction
 * @property string $configSid
 * @property string $integrationSid
 * @property mixed $step
 * @package app\src\pbx\checkpoint\strategy
 */
class MessageCheckpoint extends BasicCheckpoint
{

    const TYPE = 'message';

    const DIRECTION = 'direction';

    const CONFIG_SID = 'configSid';

    const INTEGRATION_SID = 'integrationSid';

    const BODY = 'body';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * направление
     *
     * @var string
     */
    public string $direction;

    /**
     * айди конфиг
     *
     * @var string
     */
    public string $configSid;

    /**
     * айди интеграции
     *
     * @var string
     */
    public string $integrationSid;

    /**
     * тело сообщения
     *
     * @var string
     */
    public string $body;

    /**
     * шаг
     *
     * @var string
     */
    public mixed $step;
}