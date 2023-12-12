<?php

namespace app\src\pbx\router\strategy;

use app\src\models\User;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\ReferCheckpoint;
use app\src\pbx\checkpoint\strategy\VoicemailCheckpoint;
use app\src\pbx\router\PbxRouterInterface;
use app\src\pbx\router\Router;


/**
 * Class ReferRouter
 * @package app\src\pbx\router\strategy
 */
class ReferRouter extends BaseRouter implements PbxRouterInterface
{
    /**
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if (parent::exec()->model instanceof HangupCheckpoint)
            return $this->pbx->checkpoint;

        $target = $this->target();

        if ($this->pbx->instance->event->status->isRefer() && $target->isNewRecord)
            if ($this->pbx->checkpoint->model instanceof ReferCheckpoint)
                return $this->pbx->checkpoint->set(
                    $this->pbx->checkpoint->model->referer[Checkpoint::TYPE],
                    $this->pbx->checkpoint->model->referer
                );
            else
                return $this->pbx->checkpoint;

        $step = $this->step($target);

        if (Router::END_STATUS === $step) {
            if ($this->pbx->scheme->flow->voicemail->flow ?? false)
                return $this->pbx->checkpoint->set(VoicemailCheckpoint::TYPE);
            return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);
        }

        if (Router::VOICEMAIL_STATUS === $step)
            return $this->pbx->checkpoint->set(
                VoicemailCheckpoint::TYPE, [Checkpoint::TARGET => $target->settings->voicemail]
            );

        return $this->pbx->checkpoint->set(ReferCheckpoint::TYPE, [
            Checkpoint::TARGET => $target->sid,
            Checkpoint::STEP => $step,
            ReferCheckpoint::REFERER => $this->referer($target->sid)
        ]);
    }

    /**
     * @return User
     */
    private function target(): User
    {
        if ($this->pbx->instance->event->status->isRefer())
            return $this->pbx->instance->getReferTarget();
        if ($this->pbx->checkpoint->model instanceof ReferCheckpoint)
            return $this->pbx->instance->client->userList->get($this->pbx->checkpoint->model->target);
    }

    /**
     * @param User $target
     * @return string
     */
    private function step(User $target): string
    {
        if ($this->pbx->instance->event->status->isRefer()) {
            if (false === $this->pbx->checkpoint->model instanceof ReferCheckpoint
                || $this->pbx->checkpoint->model->target !== $target->sid)
                if ($target->settings->refer->flow[0] ?? false)
                    return 0;
        } else {
            $step = $this->pbx->checkpoint->model->step;
            if ($target->settings->refer->flow[++$step] ?? false)
                return $step;
        }

        if ($target->settings->voicemail ?? false)
            return Router::VOICEMAIL_STATUS;

        return Router::END_STATUS;
    }

    /**
     * @param string $targetSid
     * @return array
     */
    private function referer(string $targetSid): array
    {
        if ($this->pbx->checkpoint->model instanceof ReferCheckpoint
            && $targetSid === $this->pbx->checkpoint->model->target)
            return $this->pbx->checkpoint->model->referer;

        return $this->pbx->checkpoint->model->asArray();
    }
}
