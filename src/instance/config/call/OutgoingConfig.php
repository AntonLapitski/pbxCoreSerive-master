<?php


namespace app\src\instance\config\call;

use app\src\instance\config\Config;
use app\src\instance\config\ConfigInterface;
use app\src\pbx\twiml\builder\Dial;
use app\src\pbx\twiml\Twiml;
use JetBrains\PhpStorm\ArrayShape;


/**
 * Class OutgoingConfig
 * Исходящий конфиг
 * @package app\src\instance\config\call
 */
class OutgoingConfig extends \app\src\instance\config\basic\OutgoingConfig implements ConfigInterface
{
    #[ArrayShape([Config::FLOW => "array[]"])]
    /**
     * верунть массив с конфигом
     *
     * @param null $status
     * @return array
     */
    public function flow($status = null): array
    {
        return [
            Config::FLOW => array(
                [
                    Twiml::VERB => Dial::VERB,
                    Twiml::NOUN => Dial::NOUN_NUMBER,
                    Dial::LIST => [
                        $this->settings->event->request->To
                    ],
                    Twiml::OPTIONS => [
                        'callerId' => $this->studioNumber->number,
                        'timeout' => 60
                    ]
                ]
            )
        ];
    }
}