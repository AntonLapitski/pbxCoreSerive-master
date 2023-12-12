<?php

namespace app\src\models\twilio;

use app\src\models\Model;


/**
 * Class UserSettings
 * @package app\src\models\twilio
 */
class UserSettings extends Model
{
    public object $responsible;
    public object $refer;
    public object $internal;
    public object $callback;
    public mixed $voicemail;
    public string $nextFlow;

    /**
     * @param $data
     * @return array
     */
    protected function config($data): array
    {
        $config = parent::config($data);
        foreach ($config as $key => $value)
            if (is_array($value))
                $config[$key] = (object)$value;

        return $config;
    }
}