<?php


namespace app\src\pbx;


use app\src\Config;
use app\src\models\Model;

/**
 * Class PbxData
 * @property array $scheme
 * @property array $config
 * @property string $twiml
 * @package app\src\pbx
 */
class PbxData extends Model
{
    /**
     * схема
     *
     * @var array
     */
    public array $scheme;

    /**
     * конфиг
     *
     * @var Config
     */
    public array $config;

    /**
     * twiml
     *
     * @var string
     */
    public string $twiml;

    /**
     * получить конфиг
     *
     * @param $data
     * @return array
     */
    protected function config($data): array
    {
        foreach ($data as $key => $value)
            if (is_null($value))
                unset($data[$key]);

        return parent::config($data);
    }
}