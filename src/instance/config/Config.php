<?php


namespace app\src\instance\config;


use app\src\event\EventInterface;
use app\src\instance\config\src\ConfigSettings;

class Config
{
    const FLOW = 'flow';
    const OPTIONS = 'options';
    const VOICEMAIL = 'voicemail';
    const SETTINGS = 'settings';
    const LIST = 'list';

    private \Closure $fabric;

    public function __construct(EventInterface $event, ConfigSettings $settings)
    {
        $this->fabric = function (\app\src\models\Config $model) use ($event, $settings) {
            return $this->fabricConfig($model, $event, $settings);
        };
    }

    protected function fabricConfig(\app\src\models\Config $model, EventInterface $event, ConfigSettings $settings): ConfigInterface
    {
        $config = sprintf('app\src\instance\config\%s\%sConfig', $event->event, ucfirst($event->request->Direction));

        if (class_exists($config))
            return (new $config($model, $settings))->get($event->request->To);
    }

    public function get(\app\src\models\Config $model): ConfigInterface
    {
        return call_user_func($this->fabric, $model ?? new \app\src\models\Config());
    }

}