<?php


namespace app\src\models\twilio;


use app\exceptions\ContentNotFound;
use app\src\models\Model;

/**
 * Class ListModel
 * @package app\src\models\twilio
 */
abstract class ListModel extends Model
{
    public array $list;

    /**
     * @throws ContentNotFound
     */
    public function get(string $prop, string $value)
    {
        $class = str_replace('List', '', static::class);

        if ('id' === $prop && isset($this->list[$value]))
            return new $class($this->list[$value]);
        if ($int = $this->list[array_search($value, array_column($this->list, $prop))] ?? false)
            return new $class($int);

        throw new ContentNotFound(sprintf('%s %s not found.', $class, $value));
    }
}