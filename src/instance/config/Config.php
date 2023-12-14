<?php


namespace app\src\instance\config;


use app\src\event\EventInterface;
use app\src\instance\config\src\ConfigSettings;

/**
 * Class Config
 * Класс Конфиг Настройки
 *
 * @property \Closure $fabric
 * @package app\src\instance\config\src
 */
class Config
{
    const FLOW = 'flow';
    const OPTIONS = 'options';
    const VOICEMAIL = 'voicemail';
    const SETTINGS = 'settings';
    const LIST = 'list';

    private \Closure $fabric;

    /**
     * Config constructor.
     * @param EventInterface $event
     * @param ConfigSettings $settings
     */
    public function __construct(EventInterface $event, ConfigSettings $settings)
    {
        $this->fabric = function (\app\src\models\Config $model) use ($event, $settings) {
            return $this->fabricConfig($model, $event, $settings);
        };
    }

    /**
     * создание объекта через конструктор
     *
     * @param \app\src\models\Config $model
     * @param EventInterface $event
     * @param ConfigSettings $settings
     * @return ConfigInterface
     */
    protected function fabricConfig(\app\src\models\Config $model, EventInterface $event, ConfigSettings $settings): ConfigInterface
    {
        $config = sprintf('app\src\instance\config\%s\%sConfig', $event->event, ucfirst($event->request->Direction));

        if (class_exists($config))
            return (new $config($model, $settings))->get($event->request->To);
    }

    /**
     * выбор логики, которая будет отвечать кодом
     *
     * @param \app\src\models\Config $model
     * @return ConfigInterface
     */
    public function get(\app\src\models\Config $model): ConfigInterface
    {
        return call_user_func($this->fabric, $model ?? new \app\src\models\Config());
    }

}