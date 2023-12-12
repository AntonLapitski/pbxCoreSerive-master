<?php

namespace app\src\instance\config;


/**
 * Interface ConfigInterface
 * @package app\src\instance\config
 */
interface ConfigInterface
{
    /**
     * @param string|null $number
     * @return ConfigInterface
     */
    public function get(string $number = null): ConfigInterface;

    /**
     * @param string|null $status
     * @return array
     */
    public function flow(string $status = null): array;
}