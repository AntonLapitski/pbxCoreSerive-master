<?php

namespace app\src;

use app\src\models\Model;
use app\src\event\EventInterface;

/**
 * Class Config
 * @package app\src
 */
class Config extends Model
{
    public array $event;

    /**
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