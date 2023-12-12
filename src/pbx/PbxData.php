<?php


namespace app\src\pbx;


use app\src\models\Model;

/**
 * Class PbxData
 * @package app\src\pbx
 */
class PbxData extends Model
{
    public array $scheme;
    public array $config;
    public string $twiml;

    /**
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