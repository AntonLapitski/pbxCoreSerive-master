<?php

namespace app\src\event\request\strategy;

use app\src\models\Model;

/**
 * Class Request
 * @package app\src\event\request\strategy
 */
abstract class Request extends Model implements RequestInterface
{
    public string $CompanySid;
    public string $EventSid;
}