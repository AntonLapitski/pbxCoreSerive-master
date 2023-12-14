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
 * @property BasicCheckpoint $model
 * @property \Closure $callback
 * @package app\src\pbx\checkpoint
 */
class Checkpoint
{
    const BRANCH = 'branch';

    const STEP = 'step';

    const TARGET = 'target';

    const TYPE = 'type';

    const FROM = 'from';

    const TO = 'to';

    /**
     * модель
     *
     * @var BasicCheckpoint
     */
    public BasicCheckpoint $model;

    /**
     * замыкание
     *
     * @var \Closure
     */
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
     * создать объект образ
     *
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
     * получить дефолтный проверочный
     *
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
     * получить модель
     *
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
     * получить задание
     *
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
     * проинкрементировать шаг
     *
     * @return Checkpoint
     */
    public function setNextStep(): self
    {
        $this->model->step++;
        return $this;
    }

    /**
     * засетить новый шаг для собрания
     *
     * @return Checkpoint
     */
    public function setNextStepForGatherHandler(): self
    {
        $this->model->gather[self::STEP]++;
        return $this;
    }
}