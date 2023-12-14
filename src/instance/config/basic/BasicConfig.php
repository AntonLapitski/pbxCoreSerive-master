<?php

namespace app\src\instance\config\basic;

use app\exceptions\ContentNotFound;
use app\src\models\Config;
use app\src\models\twilio\StudioNumber;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\blacklist\Blacklist;
use app\src\instance\config\src\ConfigSettings;
use app\src\instance\config\src\integration\Integration;
use app\src\instance\config\src\timetable\Timetable;

/**
 * Class BasicConfig
 * @property Config $model
 * @property Integration $integration
 * @property StudioNumber $studioNumber
 * @property ConfigSettings $settings
 * @package app\src\instance\config\basic
 */
abstract class BasicConfig implements ConfigInterface
{

    /**
     * конфг модель
     *
     * @var Config
     */
    public Config $model;

    /**
     * интеграция
     *
     * @var Integration
     */
    public Integration $integration;

    /**
     * студийный номер
     *
     * @var StudioNumber
     */
    public StudioNumber $studioNumber;

    /**
     * сетинги конфигурации
     *
     * @var ConfigSettings
     */
    protected ConfigSettings $settings;

    /**
     * BasicConfig constructor.
     * @param Config $model
     * @param ConfigSettings $settings
     */
    public function __construct(Config $model, ConfigSettings $settings)
    {
        $this->model = $model;
        $this->settings = $settings;
        $this->fill();
    }

    /**
     * заполнение данными
     *
     * @return void
     */
    protected function fill(): void
    {
        foreach (get_class_vars(static::class) as $field => $value) {
            if (($sid = $this->model->config[$field] ?? null) && $value === null)
                $this->setProperty($field, $sid);
        }
    }

    /**
     * установить сво-во
     *
     * @param $fieldName
     * @param $sid
     */
    protected function setProperty($fieldName, $sid): void
    {
        $model = sprintf('app\src\models\%s', ucfirst($fieldName));
        if (in_array($fieldName, ['integration', 'timetable', 'blacklist'])) {
            $method = 'get' . ucfirst($fieldName);
            $this->$fieldName = $this->$method($sid);
        } else if (class_exists($model))
            if ($model = $model::findOne(['company_id' => $this->model->company->id, 'sid' => $sid]))
                $this->$fieldName = $model->config;
    }

    /**
     * забрать конфиг объект
     *
     * @param string|null $number
     * @return ConfigInterface
     */
    public function get(string $number = null): ConfigInterface
    {
        static::studioNumber(['number' => $number]);
        return $this;
    }

    /**
     * студийный номер
     *
     * @param array $config
     * @return mixed
     */
    abstract protected function studioNumber(array $config = []);

    /**
     * вернуть массив пустой
     *
     * @param null $status
     * @return array
     */
    public function flow($status = null): array
    {
        return [];
    }

    /**
     * @param array|null $data
     */
    public function setIntegrationData(array $data = null)
    {
    }

    /**
     * вернуть черновой список
     *
     * @param $sid
     * @return Blacklist
     */
    private function getBlacklist($sid): Blacklist
    {
        return new Blacklist(\app\src\models\Blacklist::findOne(['sid' => $sid])->config ?? []);
    }

    /**
     * получить объект сформированной интеграции
     *
     * @param $sid
     * @return Integration
     */
    protected function getIntegration($sid): Integration
    {
        return new Integration(
            $this->settings->twilio->integration_list->get('sid', $sid),
            $this->settings->log->model->integration_data
        );
    }

    /**
     * забрать расписание
     *
     * @param $sid
     * @return Timetable
     */
    private function getTimetable($sid): Timetable
    {
        if ($model = \app\src\models\Timetable::findOne(['company_id' => $this->model->company->id, 'sid' => $sid]))
            return Timetable::getForCurrentSchedule($model, $this->settings->timezone);

        throw new ContentNotFound(sprintf('Timetable %s was nod found', $sid), 404);
    }
}