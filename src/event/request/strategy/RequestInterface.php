<?php

namespace app\src\event\request\strategy;

/**
 * Interface RequestInterface
 * @package app\src\event\request\strategy
 */
interface RequestInterface
{
    /**
     * @param $countryCode
     */
    public function setData($countryCode): void;

    /**
     * @return string
     */
    public function getDirection(): string;
}