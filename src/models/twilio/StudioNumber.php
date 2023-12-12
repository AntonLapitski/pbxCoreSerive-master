<?php


namespace app\src\models\twilio;


use app\src\models\Config;
use app\src\models\Model;

/**
 * Class StudioNumber
 * @package app\src\models\twilio
 */
class StudioNumber extends Model
{
    public string $sid;
    public string $name;
    public string $number;
    public string $incoming_config_sid;

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return Config::findOne(['sid' => $this->incoming_config_sid]);
    }

}