<?php

namespace app\src\pbx\scheme\model;

use app\src\pbx\checkpoint\Checkpoint;

/**
 * Class Gather
 * @package app\src\pbx\scheme\model
 */
class Gather extends Flow
{
    /**
     *
     */
    const VERB = 'gather';

    /**
     * @return bool
     */
    public function isIssetNext(): bool
    {
        $step = $this->checkpoint->model->gather[Checkpoint::STEP];
        return isset($this->flow[++$step]);
    }

    /**
     * @return array
     */
    public function current(): array
    {
        return $this->flow[$this->checkpoint->model->gather[Checkpoint::STEP]];
    }


}