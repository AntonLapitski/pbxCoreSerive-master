<?php

namespace app\src\instance\config\src\blacklist;

/**
 * Class Blacklist
 * @package app\src\instance\config\src\blacklist
 */
class Blacklist
{
    /**
     *
     */
    const NUMBER = 'number';

    private array $list;

    /**
     * Blacklist constructor.
     * @param array $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @param $number
     * @return bool
     */
    public function isBlocked($number): bool
    {
        foreach ($this->list as $item)
            if ($number === $item[self::NUMBER])
                return true;

        return false;
    }

}