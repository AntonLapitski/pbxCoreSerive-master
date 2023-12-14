<?php

namespace app\src\event\request\strategy;

use app\src\models\Model;

/**
 * Class Request
 * Объект рест апи запроса
 *
 * @property string $CompanySid
 * @property array $EventSid
 * @package app\src\event\request\strategy
 */
abstract class Request extends Model implements RequestInterface
{
    /**
     * айди компании
     *
     * @var string
     */
    public string $CompanySid;

    /**
     * айди события
     * @var string
     */
    public string $EventSid;
}