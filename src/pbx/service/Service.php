<?php

namespace app\src\pbx\service;

use app\src\models\User;
use app\src\event\Event;
use app\src\instance\config\src\integration\Integration;
use app\src\instance\InstanceInterface;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\ExtensionCheckpoint;
use app\src\pbx\checkpoint\strategy\GatherCheckpoint;
use app\src\pbx\checkpoint\strategy\MessageCheckpoint;
use app\src\pbx\checkpoint\strategy\ReferCheckpoint;
use app\src\pbx\checkpoint\strategy\ResponsibleCheckpoint;
use app\src\pbx\checkpoint\strategy\VoicemailCheckpoint;
use app\src\pbx\scheme\model\Flow;


/**
 * Class Service
 * @property InstanceInterface $instance
 * @package app\src\pbx\service
 */
class Service
{
    /**
     * образ
     *
     * @var InstanceInterface
     */
    private InstanceInterface $instance;

    /**
     * Service constructor.
     * @param InstanceInterface $instance
     */
    public function __construct(InstanceInterface $instance)
    {
        $this->instance = $instance;
    }

    /**
     * вернуть тип проверочного пункта
     *
     * @param Checkpoint $checkpoint
     * @param Flow $flow
     * @return string
     */
    public function service(Checkpoint $checkpoint, Flow $flow): string
    {
        if (Event::SERVICE_MESSAGE === $this->instance->event->event)
            return MessageCheckpoint::TYPE;

        $eventService = $this->instance->event->status->getService();
        if (ReferCheckpoint::TYPE === $eventService || $checkpoint->model instanceof ReferCheckpoint)
            return ReferCheckpoint::TYPE;

        if (Event::SERVICE_EXTENSION === $this->instance->event->event)
            return ExtensionCheckpoint::TYPE;

        if ($this->isResponsible($checkpoint, $flow))
            return ResponsibleCheckpoint::TYPE;

        if ($checkpoint->model instanceof VoicemailCheckpoint)
            return VoicemailCheckpoint::TYPE;

        if ($checkpoint->model instanceof GatherCheckpoint)
            return GatherCheckpoint::TYPE;

        return $eventService;
    }

    /**
     * является ли ответственным
     *
     * @param Checkpoint $checkpoint
     * @param Flow $flow
     * @return bool
     */
    private function isResponsible(Checkpoint $checkpoint, Flow $flow): bool
    {
        return (
            true === $flow->settings->call_to_resp
            &&
            $flow->settings->isEnabledResponsibleCallForCurrentUser($this->instance->responsibleUser ?? new User())
            &&
            $flow->settings->isNotDisabledResponsibleCallForCurrentUser($this->instance->responsibleUser ?? new User())
            &&
            (
                $checkpoint->model instanceof ResponsibleCheckpoint
                ||
                $this->isRespFilter()
            )
        );
    }

    /**
     * фильтр по ответственным
     *
     * @return bool
     */
    public function isRespFilter(): bool
    {
        return (
            Event::STEP_INIT === $this->instance->event->step
            &&
            $this->instance->config->integration instanceof Integration
            &&
            false === $this->instance->config->integration->isNewContact()
            &&
            false !== (bool)$this->instance->responsibleUser
        );
    }
}
