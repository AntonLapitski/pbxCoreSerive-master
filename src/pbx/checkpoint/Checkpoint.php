<?php

namespace app\src\pbx\checkpoint;

use app\src\event\Event;
use app\src\instance\InstanceInterface;
use app\src\pbx\checkpoint\strategy\BasicCheckpoint;
use app\src\pbx\checkpoint\strategy\CallbackCheckpoint;
use app\src\pbx\checkpoint\strategy\MainCheckpoint;
use app\src\pbx\checkpoint\strategy\MessageCheckpoint;
use app\src\pbx\checkpoint\strategy\VoicemailCheckpoint;
use JetBrains\PhpStorm\Pure;


/**
 * Class Checkpoint
 * @package app\src\pbx\checkpoint
 */
class Checkpoint
{
    /**
     *
     */
    const BRANCH = 'branch';
    /**
     *
     */
    const STEP = 'step';
    /**
     *
     */
    const TARGET = 'target';
    /**
     *
     */
    const TYPE = 'type';

    /**
     *
     */
    const FROM = 'from';
    /**
     *
     */
    const TO = 'to';
    public BasicCheckpoint $model;
    private \Closure $callback;

    /**
     * Checkpoint constructor.
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->callback = function ($strategy, $config) use ($status) {
            $class = 'app\src\pbx\checkpoint\strategy\\' . ucfirst($strategy) . 'Checkpoint';
            if (class_exists($class))
                return new $class($status, $config);
        };
    }

    /**
     * @param InstanceInterface $instance
     * @return Checkpoint
     */
    public static function build(InstanceInterface $instance): self
    {
        $checkpoint = $instance->log->model->checkpoint ?? self::getDefaultCheckpoint($instance);

        return
            (new self($instance->config->timetable->status ?? ''))
                ->set($checkpoint[Checkpoint::TYPE], $checkpoint);
    }

    #[Pure]

    /**
     * @param InstanceInterface $instance
     * @return array
     */
    private static function getDefaultCheckpoint(InstanceInterface $instance): array
    {
        $checkpoint = function ($type) {
            return [
                Checkpoint::TYPE => $type
            ];
        };

//        if (Event::STEP_CALLBACK === $instance->event->event)
//            return $checkpoint(CallbackCheckpoint::TYPE);

        if (Event::SERVICE_MESSAGE === $instance->event->event)
            return $checkpoint(MessageCheckpoint::TYPE);

//        if ($instance->event->status->isGather())
//            return $checkpoint(GATHER);

        return $checkpoint(MainCheckpoint::TYPE);
    }

    /**
     * @param $type
     * @param array $config
     * @return Checkpoint
     */
    public function set($type, $config = []): self
    {
        $this->model = call_user_func($this->callback, $type, $config);
        return $this;
    }

    /**
     * @param array $checkpoint
     * @return string|null
     */
    public static function getTarget(array $checkpoint): ?string
    {
        $obj = new self($checkpoint['timetable_status']);
        $obj->set($checkpoint['type'], $checkpoint);
        if ($target = ($obj->model->target ?? false))
            if (VoicemailCheckpoint::TYPE !== $obj->model->type)
                return $target;

        return null;
    }

    /**
     * @return Checkpoint
     */
    public function setNextStep(): self
    {
        $this->model->step++;
        return $this;
    }

    /**
     * @return Checkpoint
     */
    public function setNextStepForGatherHandler(): self
    {
        $this->model->gather[self::STEP]++;
        return $this;
    }
}