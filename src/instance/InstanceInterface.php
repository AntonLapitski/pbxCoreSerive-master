<?php

namespace app\src\instance;

/**
 * Interface InstanceInterface
 * @package app\src\instance
 */
interface InstanceInterface
{
    /**
     * @return string|null
     */
    public function getCallerName(): ?string;

    /**
     * @return bool
     */
    public function isInBlackList(): bool;
}