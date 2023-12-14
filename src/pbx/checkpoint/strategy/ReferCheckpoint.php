<?php

namespace app\src\pbx\checkpoint\strategy;


use app\src\pbx\checkpoint\Checkpoint;

/**
 * Class ReferCheckpoint
 * @property string $type
 * @property string $branch
 * @property string $target
 * @property array $referer
 * @package app\src\pbx\checkpoint\strategy
 */
class ReferCheckpoint extends BasicCheckpoint
{
    /**
     *
     */
    const TYPE = 'refer';
    /**
     *
     */
    const BRANCH = 'refer';

    /**
     *
     */
    const REFERER = 'referer';

    /**
     * тип
     *
     * @var string
     */
    public string $type = self::TYPE;

    /**
     * ветка
     *
     * @var string
     */
    public string $branch = self::BRANCH;

    /**
     * задание
     *
     * @var string
     */
    public string $target;

    /**
     * ссылающийся
     *
     * @var array
     */
    public array $referer;

    /**
     * ReferCheckpoint constructor.
     * @param string $status
     * @param array $config
     */
    public function __construct(string $status, $config = [])
    {
        unset($config[Checkpoint::BRANCH]);
        parent::__construct($status, $config);
    }
}