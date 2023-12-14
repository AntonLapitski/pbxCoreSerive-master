<?php

namespace app\src;

use app\src\models\Model;
use app\src\event\EventInterface;

/**
 * Class Config
 * @property array $event
 * @package app\src
 */
class Config extends Model
{
    /**
     * событие
     *
     * @var array
     */
    public array $event;

    /**
     * засетить событие и вернуть его
     *
     * @param EventInterface $event
     * @return EventInterface
     */
    public function event(EventInterface $event): EventInterface
    {
        if (!isset($this->event))
            return $event;

        if (isset($this->event['direction']))
            $event->request->Direction = $this->event['direction'];

        return $event;
    }
}