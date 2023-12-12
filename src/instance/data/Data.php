<?php

namespace app\src\instance\data;

use app\src\models\User;
use app\src\event\Event;
use app\src\event\request\strategy\RequestInterface;
use app\src\instance\Instance;
use app\src\instance\log\Log;
use app\src\pbx\checkpoint\Checkpoint;
use JetBrains\PhpStorm\ArrayShape;


/**
 * class Data.
 * @property array $data
 * @property Instance $instance
 */
class Data
{
    public array $data;
    protected Instance $instance;

    protected array $integrationData;

    /**
     * Data constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
        $this->integrationData = $this->instance->config->integration->model->attributes ?? [];
    }

    /**
     * @return array
     */
    public function integrationRequest(): array
    {
        $data = $this->getData();
        if (isset($data['_integration']['data']))
            unset($data['_integration']['data']);

        return $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = $this->setData();
        $data['user'] = self::setUser();

        return $data;
    }

    /**
     * @return array
     */
    protected function setData(): array
    {
        $data = [
            'companySid' => $this->instance->client->company->sid,
            'companyCountry' => $this->instance->client->company->country_code,
            'companyName' => $this->instance->client->company->name,
            'accountSid' => $this->instance->event->request->AccountSid ?? null,
            'event' => $this->setEvent(),
            'callSid' => $this->instance->event->request->CallSid ?? null,
            'phone' => $this->setPhone(),
            'callerName' => $this->instance->event->request->CallerName ?? null,
            'direction' => self::getDirection(),
            'callDirection' => $this->getDirection(),
            'messageSid' => $this->instance->event->request->MessageSid ?? null,
            'messageStatus' => $this->instance->event->request->MessageStatus ?? null,
            'messageMedia' => array_values($this->instance->event->request->Media ?? []),
            'from' => $this->instance->event->request->From ?? null,
            'to' => $this->instance->event->request->To ?? null,
            'body' => $this->instance->event->request->Body ?? null,
            'url' => $this->instance->event->request->Url ?? null,
            'studioNumber' => $this->instance->config->studioNumber->attributes,
            'user' => self::setUser(),
            '_integration' => $this->integrationData
        ];

        foreach ($data as $key => $val)
            if (!$val)
                unset($data[$key]);

        if (false !== stripos($this->instance->event->step, 'status') && in_array($this->instance->event->event, ['callback', 'call']))
            $data = array_merge($data, $this->_call($this->instance->log));

        return $data;
    }

    /**
     * @return array|null
     */
    protected function setUser(): ?array
    {
        if (Event::SERVICE_CALLBACK === $this->instance->event->event)
            if ($user = $this->instance->responsibleUser)
                return self::_user($user);

        if (Event::STEP_STATUS === $this->instance->event->step)
            return null;
        if ($user = $this->instance->responsibleUser)
            return self::_user($user);

        return null;
    }

    /**
     * @param User $user
     * @return array
     */
    protected function _user(User $user): array
    {
        return [
            'name' => $user->name,
            'sid' => $user->sid,
            'sip' => $user->sip,
            'mobileNumber' => $user->mobile_number,
            'outgoingConfigSid' => $user->outgoing_config_sid,
            'studioNumber' => $this->instance->config->studioNumber->asArray(),
        ];
    }

    /**
     * @param Log $log
     * @return array
     */
    protected function _call(Log $log): array
    {
        return [
            'time' => $log->model->time,
            'callerName' => $this->instance->getCallerName(),
            'callResult' => $log->model->result,
            'callDuration' => $log->model->duration,
            'callDirection' => $log->model->direction,
            'callRecordingUrl' => $log->model->record_url,
        ];
    }

    /**
     * @param $integrationData
     */
    public function setIntegrationData($integrationData): void
    {
        $this->integrationData['data'] = $integrationData;
    }

    /**
     * @return string|null
     */
    protected function getDirection(): ?string
    {
        if (
            Event::SERVICE_CALLBACK === $this->instance->event->event
            &&
            false !== stripos(Event::STEP_STATUS, $this->instance->event->step)
        )
            return Event::DIRECTION_OUTGOING;

        return $this->instance->log->model->isNewRecord
            ? $this->instance->event->request->Direction
            : $this->instance->log->model->direction;
    }

    /**
     * @return array
     */
    private function setEvent(): array
    {
        return [
            'type' => (in_array($this->instance->event->event, ['callback', 'extension'])) ? 'call' : $this->instance->event->event,
            'step' => false !== stripos($this->instance->event->step, 'status') ? 'status' : $this->instance->event->step,
            'time' => $this->instance->log->model->time,
            'timezone' => $this->instance->client->company->time_zone,
            'status' => (Event::SERVICE_MESSAGE === $this->instance->event->event)
                ? $this->instance->event->request->MessageStatus
                : $this->instance->log->model->status,
            'result' => $this->instance->log->model->result
        ];
    }

    /**
     * @return mixed
     */
    private function setPhone()
    {
        if (Event::SERVICE_CALLBACK === $this->instance->event->event)
            if (Event::DIRECTION_INCOMING === $this->instance->event->request->Direction)
                return $this->instance->log->model->checkpoint[Checkpoint::FROM] ?? $this->instance->event->request->From;
        if (Event::DIRECTION_OUTGOING === $this->instance->event->request->Direction)
            return $this->instance->log->model->checkpoint[Checkpoint::TO] ?? $this->instance->event->request->To;

        if (in_array($this->instance->event->event, [Event::SERVICE_MESSAGE, Event::SERVICE_CALL]))
            return (self::getDirection() == Event::DIRECTION_INCOMING) ?
                $this->instance->event->request->From : $this->instance->event->request->To;
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    private function setMessageMedia(RequestInterface $request): array
    {
        $i = 0;
        $media = array();
        $field = 'MediaUrl' . $i;
        $contentType = 'MediaContentType' . $i;

        while (
            (false !== ($url = $request->$field ?? false))
            &&
            (false !== ($type = $request->$contentType ?? false))
        ) {
            $media[$i] = [
                'url' => $url,
                'type' => explode('/', $type)[0],
            ];

            $i++;
            $field = 'MediaUrl' . $i;
            $contentType = 'MediaContentType' . $i;
        }

        return $media;
    }
}