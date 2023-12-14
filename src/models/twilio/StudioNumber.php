<?php


namespace app\src\models\twilio;


use app\src\models\Config;
use app\src\models\Model;

/**
 * Class StudioNumber
 * Модель студийный номер
 *
 * @property string $sid
 * @property string $name
 * @property string $number
 * @property string $incoming_config_sid
 * @package app\src\models\twilio
 */
class StudioNumber extends Model
{
    /**
     * айди
     *
     * @var string
     */
    public string $sid;

    /**
     * string
     *
     * @var string
     */
    public string $name;

    /**
     * номер
     *
     * @var string
     */
    public string $number;

    /**
     * конфиг айди
     *
     * @var string
     */
    public string $incoming_config_sid;

    /**
     * получить конфиг
     *
     * @return mixed
     */
    public function getConfig()
    {
        return Config::findOne(['sid' => $this->incoming_config_sid]);
    }

}