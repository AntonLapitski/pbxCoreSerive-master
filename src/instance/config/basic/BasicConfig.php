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
 * @package app\src\instance\config
 * @property Integration $integration
 */
abstract class BasicConfig implements ConfigInterface
{
    public Config $model;
    public Integration $integration;
    public StudioNumber $studioNumber;
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
     *
     */
    protected function fill(): void
    {
        foreach (get_class_vars(static::class) as $field => $value) {
            if (($sid = $this->model->config[$field] ?? null) && $value === null)
                $this->setProperty($field, $sid);
        }
    }

    /**
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
     * @param string|null $number
     * @return ConfigInterface
     */
    public function get(string $number = null): ConfigInterface
    {
        static::studioNumber(['number' => $number]);
        return $this;
    }

    /**
     * @param array $config
     * @return mixed
     */
    abstract protected function studioNumber(array $config = []);

    /**
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
     * @param $sid
     * @return Blacklist
     */
    private function getBlacklist($sid): Blacklist
    {
        return new Blacklist(\app\src\models\Blacklist::findOne(['sid' => $sid])->config ?? []);
    }

    /**
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