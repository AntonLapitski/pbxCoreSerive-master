<?php


namespace app\src\pbx\router\strategy;


use app\src\models\User;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\checkpoint\strategy\HangupCheckpoint;
use app\src\pbx\checkpoint\strategy\InternalCheckpoint;
use app\src\pbx\router\Router;

/**
 * Class InternalRouter
 * @package app\src\pbx\router\strategy
 */
class InternalRouter extends BaseRouter
{
    /**
     * @return Checkpoint
     */
    public function exec(): Checkpoint
    {
        if (parent::exec()->model instanceof HangupCheckpoint)
            return $this->pbx->checkpoint;

        $target = $this->pbx->instance->responsibleUser;
        $step = self::step($target);

        if (Router::END_STATUS === $step)
            return $this->pbx->checkpoint->set(HangupCheckpoint::TYPE);

        return $this->pbx->checkpoint->set(InternalCheckpoint::TYPE, [
            Checkpoint::TARGET => $target->sid,
            Checkpoint::STEP => $step,
        ]);
    }

    /**
     * @param User $user
     * @return string
     */
    private function step(User $user): string
    {
        if (false === $this->pbx->checkpoint->model instanceof InternalCheckpoint) {
            if ($user->settings->refer->flow[0] ?? false)
                return 0;
        } else {
           $step = $this->pbx->checkpoint->model->step;
           if ($user->settings->refer->flow[++$step] ?? false)
               return $step;
       }

       return Router::END_STATUS;
    }
}