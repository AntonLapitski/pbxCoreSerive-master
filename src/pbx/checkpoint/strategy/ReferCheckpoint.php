<?php

namespace app\src\pbx\checkpoint\strategy;


use app\src\pbx\checkpoint\Checkpoint;

/**
 * Class ReferCheckpoint
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

    public string $type = self::TYPE;
    public string $branch = self::BRANCH;
    public string $target;
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