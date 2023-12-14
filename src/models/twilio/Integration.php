<?php


namespace app\src\models\twilio;


use app\src\models\Model;

/**
 * Class Integration
 * Класс интеграция
 *
 * @property string $sid
 * @property string $service
 * @property string $name
 * @property bool $is_active
 * @package app\src\models\twilio
 */
class Integration extends Model
{
    /**
     * айди
     *
     * @var string
     */
    public string $sid;

    /**
     * сервис
     *
     * @var string
     */
    public string $service;

    /**
     * имя
     *
     * @var string
     */
    public string $name;

    /**
     * активирован ли
     *
     * @var bool
     */
    public bool $is_active;
}

