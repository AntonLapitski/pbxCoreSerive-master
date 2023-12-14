<?php


namespace app\src\instance\config\callback;


use app\src\event\Event;
use app\src\instance\config\Config;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\twiml\builder\Dial;
use app\src\pbx\twiml\Twiml;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class OutgoingConfig
 * Исходящий конфиг
 * @package app\src\instance\config\callback
 */
class OutgoingConfig extends \app\src\instance\config\basic\OutgoingConfig
{
    #[ArrayShape([Config::FLOW => "array[]"])]
    /**
     * вернуть конфиг с потоком
     *
     * @param null $status
     * @return array
     */
    public function flow($status = null): array
    {
        $target = Event::DIRECTION_OUTGOING === $this->settings->event->request->Direction
            ? ($this->settings->log->model->isNewRecord
                ? $this->settings->event->request->To
                : $this->settings->log->model->checkpoint[Checkpoint::TO])
            : ($this->settings->log->model->isNewRecord
                ? $this->settings->event->request->From
                : $this->settings->log->model->checkpoint[Checkpoint::FROM]);

        return [
            Config::FLOW => array(
                [
                    Twiml::VERB => Dial::VERB . Event::SERVICE_CALLBACK,
                    Twiml::NOUN => Dial::NOUN_NUMBER,
                    Dial::LIST => [
                        $target
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