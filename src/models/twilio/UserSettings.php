<?php

namespace app\src\models\twilio;

use app\src\models\Model;


/**
 * Class UserSettings
 * @property object $responsible
 * @property object $refer
 * @property object $internal
 * @property object $callback
 * @property mixed $voicemail
 * @property string $nextFlow
 * @package app\src\models\twilio
 */
class UserSettings extends Model
{
    /**
     * ответственный
     *
     * @var object
     */
    public object $responsible;

    /**
     * ссылающийся
     *
     * @var object
     */
    public object $refer;

    /**
     * внутренний
     *
     * @var object
     */
    public object $internal;

    /**
     * коллбэк
     *
     * @var object
     */
    public object $callback;

    /**
     * голосовая почта
     *
     * @var mixed
     */
    public mixed $voicemail;

    /**
     * новый поток
     *
     * @var string
     */
    public string $nextFlow;

    /**
     * вернуть конфиг
     *
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