<?php


namespace app\src\models;


/**
 * Class Model
 * @package app\src\models
 */
class Model extends \yii\base\Model
{
    /**
     * Model constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($this->config($config));
    }

    /**
     * @param $data
     * @return array
     */
    protected function config($data): array
    {
        $config = [];
        foreach ($data as $field => $value)
            if (property_exists(static::class, $field))
                $config[$field] = $value;

        return $config;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        $data = [];
        foreach (self::fields() as $field)
            if (isset($this->$field))
                $data[$field] = $this->$field;

        return $data;
    }
}