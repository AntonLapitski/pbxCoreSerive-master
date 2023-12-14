<?php

namespace app\src\pbx\scheme\model;


use app\src\models\Model;
use app\src\instance\config\Config;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\twiml\Twiml;

/**
 * Class Flow
 * @property Settings $settings
 * @property Voicemail $voicemail
 * @property array $flow
 * @package app\src\pbx\scheme\model
 */
class Flow extends Model
{
    const GATHER_HANDLER = 'handler';

    const SETTINGS = 'settings';

    const FLOW = 'flow';

    /**
     * настройки
     *
     * @var Settings
     */
    public Settings $settings;

    /**
     * голосовая почта
     *
     * @var Voicemail
     */
    public Voicemail $voicemail;

    /**
     * проверочный пункт
     *
     * @var Checkpoint
     */
    public Checkpoint $checkpoint;

    /**
     * собран
     *
     * @var Gather
     */
    public Gather $gather;

    /**
     * поток
     *
     * @var array
     */
    public array $flow;

    /**
     * Flow constructor.
     * @param Checkpoint $checkpoint
     * @param array $config
     */
    public function __construct(Checkpoint &$checkpoint, $config = [])
    {
        $this->checkpoint = &$checkpoint;
        parent::__construct($config);
    }

    /**
     * текущий шаг
     *
     * @return array
     */
    public function current(): array
    {
        return $this->flow[$this->checkpoint->model->step];
    }

    /**
     * следующий ли шаг установлен
     *
     * @return bool
     */
    public function isIssetNext(): bool
    {
        $step = $this->checkpoint->model->step;
        return isset($this->flow[++$step]);
    }

    /**
     * собран ли
     *
     * @param $input
     * @return Flow
     */
    public function withGather($input): self
    {
        $gather = function ($flow, $input) {
            foreach ($flow as $element)
                if (Gather::VERB === $element[Twiml::VERB])
                    return $element[self::GATHER_HANDLER][$input] ?? false;

            return false;
        };

        if
        (
            $gather = $gather
            (
                $this->flow[$this->checkpoint->model->step],
                $input
            )
        )
            $this->gather = new Gather($this->checkpoint, [
                self::FLOW => $gather,
                self::SETTINGS => $this->settings->asArray(),
                Checkpoint::STEP => $this->checkpoint->model->gather[Checkpoint::STEP] ?? 0
            ]);

        return $this;
    }

    /**
     * получить конфиг
     *
     * @param $data
     * @return array
     */
    protected function config($data): array
    {
        $config = parent::config($data);
        $config[Config::SETTINGS] = new Settings($config[Config::SETTINGS] ?? []);
        if (is_array($config[Config::VOICEMAIL] ?? false))
            $config[Config::VOICEMAIL] = new Voicemail([Config::FLOW => $config[Config::VOICEMAIL]]);

        return $config;
    }

}