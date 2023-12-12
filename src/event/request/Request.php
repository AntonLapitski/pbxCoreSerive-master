<?php

namespace app\src\event\request;

use app\exceptions\ContentNotFound;
use app\src\event\request\strategy\RequestInterface;

/**
 * Class Request
 * @package app\src\event\request
 */
class Request
{
    /**
     *
     */
    const DIGITS_HANGUP = 'hangup';
    /**
     *
     */
    const DIGITS_TIMEOUT = 'timeout';
    /**
     *
     */
    const DIGITS_GATHER_END = 'gather end';


    /**
     * @throws ContentNotFound
     */
    public static function get(array $request, $event): RequestInterface
    {
        $class = '\app\src\event\request\strategy\\' . ucfirst($event) . 'Request';
        if (class_exists($class))
            return new $class($request);

        Throw new ContentNotFound(sprintf('Class %s was not found', $class));
    }
}