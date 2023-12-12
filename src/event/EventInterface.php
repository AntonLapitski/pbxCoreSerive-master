<?php


namespace app\src\event;


/**
 * Interface EventInterface
 * @package app\src\event
 */
interface EventInterface
{
    /**
     * @param array $settings
     */
    public function setData(array $settings): void;
}