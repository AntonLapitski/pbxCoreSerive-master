<?php


namespace app\src\models\twilio;


use app\src\models\Model;

/**
 * Class Integration
 * @package app\src\models\twilio
 */
class Integration extends Model
{
    public string $sid;
    public string $service;
    public string $name;
    public bool $is_active;
}

