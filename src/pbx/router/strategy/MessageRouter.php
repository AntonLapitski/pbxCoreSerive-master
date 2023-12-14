<?php

namespace app\src\pbx\router\strategy;


use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\MessageCheckpoint;

/**
 * Class MessageRouter
 * @package app\src\pbx\router\strategy
 */
class MessageRouter extends BaseRouter
{
    /**
     * засетить проверочный пункт
     *
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        return $this->pbx->checkpoint->set(MessageCheckpoint::TYPE, [
            MessageCheckpoint::DIRECTION => $this->pbx->instance->event->request->Direction,
            MessageCheckpoint::CONFIG_SID => $this->pbx->instance->config->model->sid,
            MessageCheckpoint::INTEGRATION_SID => $this->pbx->instance->event->request->IntegrationSid ?? null,
            MessageCheckpoint::BODY => $this->pbx->instance->event->request->Body,
            Checkpoint::STEP => $this->pbx->instance->event->request->MessageStatus
        ]);
    }

}