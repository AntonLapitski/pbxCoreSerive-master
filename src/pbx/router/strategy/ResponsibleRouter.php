<?php


namespace app\src\pbx\router\strategy;


use app\src\models\User;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\MainCheckpoint;
use app\src\pbx\checkpoint\strategy\ResponsibleCheckpoint;
use app\src\pbx\checkpoint\strategy\VoicemailCheckpoint;
use app\src\pbx\router\PbxRouterInterface;
use app\src\pbx\router\Router;

/**
 * Class ResponsibleRouter
 * @package app\src\pbx\router\strategy
 */
class ResponsibleRouter extends BaseRouter implements PbxRouterInterface
{
    /**
     * засетить проверочный пункт
     *
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if (parent::exec()->model instanceof HangupCheckpoint)
            return $this->pbx->checkpoint;

        $target = $this->pbx->instance->responsibleUser;
        $step = self::step($target);

        if (Router::NEXT_FLOW === $step) {
            $this->pbx->scheme->setFlow($target->settings->nextFlow);
            return $this->pbx->checkpoint->set(MainCheckpoint::TYPE, [
                MainCheckpoint::FLOW_SID => $target->settings->nextFlow
            ]);
        }

        if (Router::END_STATUS === $step)
            return $this->pbx->checkpoint->set(MainCheckpoint::TYPE);

        if (Router::VOICEMAIL_STATUS === $step)
            return $this->pbx->checkpoint->set(VoicemailCheckpoint::TYPE, [
                Checkpoint::TARGET => $target->settings->voicemail
            ]);

        return $this->pbx->checkpoint->set(ResponsibleCheckpoint::TYPE, [
            Checkpoint::TARGET => $target->sid,
            Checkpoint::STEP => $step
        ]);
    }

    /**
     * получить шаг
     *
     * @param User $target
     * @return int
     */
    private function step(User $target): int|string
    {
        if (false === $this->pbx->checkpoint->model instanceof ResponsibleCheckpoint) {
            if ($device = $target->settings->responsible->flow[0] ?? false)
                if ($target->$device ?? false)
                    return 0;
        } else {
            $step = $this->pbx->checkpoint->model->step;
            if ($device = $target->settings->responsible->flow[++$step] ?? false)
                if ($target->$device ?? false)
                    return $step;
        }

        if ($target->settings->nextFlow ?? false)
            return Router::NEXT_FLOW;

        if ($target->settings->voicemail ?? false)
            return Router::VOICEMAIL_STATUS;

        return Router::END_STATUS;
    }
}
