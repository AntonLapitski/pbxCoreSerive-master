<?php


namespace app\src\pbx\scheme\strategy;


use app\src\pbx\scheme\model\Data;
use app\src\pbx\twiml\builder\Hangup;
use app\src\pbx\twiml\Twiml;

/**
 * Class HangupBuilder
 * @package app\src\pbx\scheme\strategy
 */
class HangupBuilder extends BasicBuilder
{
    /**
     * установить данные и сохранить
     *
     * @return Data
     */
    public function build(): Data
    {
        $this->data = [
            [
                Twiml::VERB => Hangup::VERB
            ]
        ];

        return parent::build();
    }
}