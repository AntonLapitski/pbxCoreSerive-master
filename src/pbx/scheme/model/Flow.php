<?php

namespace app\src\pbx\scheme\model;


use app\src\models\Model;
use app\src\instance\config\Config;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\twiml\Twiml;

/**
 * @property Settings $settings
 * @property Voicemail $voicemail
 * @property array $flow
 */
class Flow extends Model
{
    /**
     *
     */
    const GATHER_HANDLER = 'handler';
    /**
     *
     */
    const SETTINGS = 'settings';
    /**
     *
     */
    const FLOW = 'flow';

    public Settings $settings;
    public Voicemail $voicemail;

    public Checkpoint $checkpoint;
    public Gather $gather;

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
     * @return array
     */
    public function current(): array
    {
        return $this->flow[$this->checkpoint->model->step];
    }

    /**
     * @return bool
     */
    public function isIssetNext(): bool
    {
        $step = $this->checkpoint->model->step;
        return isset($this->flow[++$step]);
    }

    /**
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