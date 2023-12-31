<?php


namespace app\src\pbx\scheme\model;

use app\src\models\Model;

/**
 * Class Voicemail
 * @property array $flow
 * @package app\src\pbx\scheme\model
 */
class Voicemail extends Model
{
    /**
     * поток
     *
     * @var array
     */
    public array $flow;

    /**
     * Voicemail constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!is_array($config['flow']) || empty($config))
            unset($config['flow']);

        parent::__construct($config);
    }
}