<?php


namespace app\src\pbx\scheme\strategy;


use app\src\models\User;
use app\src\pbx\twiml\builder\Dial;
use app\src\pbx\twiml\Twiml;


/**
 * Class DirectBuilder
 * @package app\src\pbx\scheme\strategy
 */
abstract class DirectBuilder extends BasicBuilder
{
    /**
     * схема
     *
     * @param User $user
     * @return array
     */
    protected function scheme(User $user): array
    {
        $direction = $this->pbx->checkpoint->model->type;

        return [
            $this->element([
                Twiml::VERB => Dial::VERB,
                Dial::LIST => [$user->sid],
                Twiml::NOUN => $user->settings->$direction->flow[$this->pbx->checkpoint->model->step],
                Twiml::OPTIONS => $user->settings->$direction->settings
            ])
        ];
    }
}