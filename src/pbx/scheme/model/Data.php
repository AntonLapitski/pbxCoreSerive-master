<?php

namespace app\src\pbx\scheme\model;

use app\src\models\Model;

/**
 * Class Data
 * @property array $scheme
 * @property array $config
 * @package app\src\pbx\scheme\model
 */
class Data extends Model
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
     * @var array
     */
    public array $config;
}