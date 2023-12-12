<?php

namespace app\src\pbx\scheme\strategy;


use app\src\instance\config\Config;
use app\src\pbx\scheme\model\Data;
use app\src\pbx\twiml\builder\Hangup;
use app\src\pbx\twiml\Twiml;

/**
 * Class VoicemailBuilder
 * @package app\src\pbx\scheme\strategy
 */
class VoicemailBuilder extends BasicBuilder
{
    /**
     * @return Data
     */
    public function build(): Data
    {
        if ($flow = $this->voicemail($this->pbx->checkpoint->model->target ?? null))
            foreach ($flow as $key => $step)
                $flow[$key] = $this->element($step);
        else
            $flow = $this->element([Twiml::VERB => Hangup::VERB]);

        $this->data = $flow;
        return parent::build();
    }

    /**
     * @param $target
     * @return bool
     */
    public function voicemail($target)
    {
        if ($target)
            if ($vm = \app\src\models\Voicemail::findOne(['sid' => $target]))
                if ($flow = $vm->config[Config::FLOW][0] ?? false)
                    return $flow;

        return $this->pbx->scheme->flow->voicemail->flow[0] ?? false;
    }
}