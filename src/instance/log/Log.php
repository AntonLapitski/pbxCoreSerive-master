<?php

namespace app\src\instance\log;

use app\src\instance\Instance;

/**
 * Class Log
 * @property \app\src\models\Log $element
 * @property Instance $instance
 * @package app\src\instance\log
 */
class Log
{
    /**
     * модель
     *
     * @var \app\src\models\Log $model
     */
    public \app\src\models\Log $model;

    /**
     * образ
     *
     * @var Instance $instance
     */
    protected Instance $instance;

    /**
     * Log constructor.
     * @param $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
        $this->model = $this->setModel();
        if (!$this->model->isNewRecord)
            $this->instance->event->request->Direction = $this->model->direction;
    }

    /**
     * установить модель
     *
     * @param null $eventSid
     * @return \app\src\models\Log
     */
    protected function setModel($eventSid = null): \app\src\models\Log
    {
        $model = $this->getModel($eventSid);

        $data = [
            'status' => $this->getStatus($model->event_type),
            'direction' => $model->direction ?? $this->instance->event->request->Direction,
            'record_url' => $this->getRecord($model),
            'duration' => isset($this->instance->event->request->RecordingDuration)
                ? (int)$this->instance->event->request->RecordingDuration
                : $model->duration,
        ];

        if ($model->isNewRecord) {
            $data['company_id'] = $this->instance->client->company->id;
            $data['time'] = time();
            $data['event_sid'] = $this->instance->event->request->EventSid;
        }

        if ('callback' === $this->instance->event->event && str_contains($this->instance->event->step, 'status'))
            $data['result'] = (str_contains($this->instance->event->step, 'child'))
                ? $this->instance->event->request->DialCallStatus
                : $this->instance->event->request->CallStatus;
        if ('message' === $this->instance->event->event)
            $data['result'] = $this->instance->event->request->MessageStatus;

        $model->load($data, '');
        return $model;
    }

    /**
     * получить модель
     *
     * @param $eventSid
     * @return \app\src\models\Log
     */
    private function getModel($eventSid): \app\src\models\Log
    {
        if (null !== $eventSid)
            $log = \app\src\models\Log::findOne([
                'company_id' => $this->instance->client->company->id,
                'event_sid' => $eventSid
            ]);

        else if (isset($this->instance->event->request->CallSid))
            $log = \app\src\models\Log::findOne([
                'company_id' => $this->instance->client->company->id,
                'event_sid' => $this->instance->event->request->CallSid
            ]);

        else if (isset($this->instance->event->request->MessageSid))
            $log = \app\src\models\Log::findOne([
                'company_id' => $this->instance->client->company->id,
                'event_sid' => $this->instance->event->request->MessageSid
            ]);

        return $log ?? new \app\src\models\Log(['event_type' => $this->instance->event->event]);
    }

    /**
     * забрать статус
     *
     * @param $event_type
     * @return string
     */
    protected function getStatus($event_type): string
    {
        if ('call' === $event_type)
            return $this->instance->event->request->DialCallStatus ?? $this->instance->event->request->CallStatus;
        if ('message' === $event_type)
            return $this->instance->event->request->MessageStatus;

        return $event_type;
    }

    /**
     * получить записанный урл
     *
     * @param \app\src\models\Log $model
     * @return string|null
     */
    protected function getRecord(\app\src\models\Log $model): ?string
    {
        return $this->instance->event->request->RecordingUrl ?? $model->record_url;
    }

    /**
     * уствновить интеграционные данные
     *
     * @param $data
     * @return void
     */
    public function setIntegrationData($data)
    {
        $this->model->integration_data = $data;
    }

    /**
     * обновить с параметрами
     *
     * @param $eventSid
     * @param $params
     * @return bool
     */
    public function update($eventSid, $params): bool
    {
        $this->get($eventSid);
        return $this->save($params);
    }

    /**
     * вернуть модель
     *
     * @param null $eventSid
     * @return $this
     */
    public function get($eventSid = null)
    {
        $this->model = $this->setModel($eventSid);
        return $this;
    }

    /**
     * сохранить модель
     *
     * @param array $params
     * @return bool
     */
    public function save($params = array()): bool
    {
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                if (array_key_exists($param, $this->model->attributes))
                    $this->model->load([$param => $value], '');
            }
        }

        return $this->model->save();
    }

    /**
     * установить
     *
     * @param array $params
     * @return bool
     */
    public function set($params = array()): bool
    {
        $this->get();
        return $this->save($params);
    }

    /**
     * поулчить записанный урл
     *
     * @param $sid
     * @return bool
     */
    protected function getRecordingUrl($sid): bool|string
    {
        return \Yii::$app->twilio->getRecordingUrl($sid);
    }
}

