<?php

namespace app\src\event\request\strategy;

use app\src\event\Event;

/**
 * Class MessageRequest
 * @package app\src\event\request\strategy
 */
class MessageRequest extends TwilioRequest implements RequestInterface
{
    public string $MessageSid;
    public string $Body;
    public string $SmsStatus;
    public string $MessageStatus;
    public array $Media;
    public string $IntegrationSid;

    /**
     * MessageRequest constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->EventSid = $this->MessageSid;
        $this->SmsStatus = $this->SmsStatus ?? 'sent';
        $this->MessageStatus = $this->MessageStatus ?? $this->SmsStatus;
    }

    /**
     * @param $data
     * @return array
     */
    protected function config($data): array
    {
        foreach ($data as $key => $value) {
            if (false !== stripos($key, 'MediaContentType'))
                $data['Media'][substr($key, strlen('MediaContentType'))]['type'] =
                    (false !== stripos($value, 'image')) ? 'image' : $value;
            if (false !== stripos($key, 'MediaUrl'))
                $data['Media'][substr($key, strlen('MediaUrl'))]['url'] = $value;
        }

        if (false === (bool)($data['Media'] ?? false))
            unset($data['Media']);

        return parent::config($data);
    }

    /**
     * @return string
     */
    protected function setDirection(): string
    {
        return (false !== stripos(\Yii::$app->request->url, 'message/get'))
            ? Event::DIRECTION_INCOMING : Event::DIRECTION_OUTGOING;
    }
}