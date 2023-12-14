<?php

namespace app\src\instance\config\src\blacklist;

/**
 * Class Blacklist
 * Класс черновой список
 *
 * @property array $list
 * @package app\src\instance\config\src\blacklist
 */
class Blacklist
{
    const NUMBER = 'number';

    /**
     * список
     *
     * @var array
     */
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
     * заблокирован ли
     *
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