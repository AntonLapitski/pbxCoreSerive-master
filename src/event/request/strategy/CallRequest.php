<?php

namespace app\src\event\request\strategy;


use app\src\event\Event;

/**
 * Class CallRequest
 * Вызов запроса
 *
 * @property string $CallerName
 * @property string $CallSid
 * @property string $CallStatus
 * @property string $DialBridged
 * @property string $DialCallSid
 * @property string $DialCallStatus
 * @property string $Digits
 * @property string $msg
 * @property string $RecordingDuration
 * @property string $RecordingSid
 * @property string $RecordingUrl
 * @property string $ParentCallSid
 * @property string $ReferTransferTarget
 * @property string $Result
 * @property string $SipCallId
 * @property string $CallDuration
 * @property string $Duration
 * @package app\src\event\request\strategy
 */
class CallRequest extends TwilioRequest implements RequestInterface
{
    //all
    /**
     * имя звонящего
     *
     * @var string
     */
    public string $CallerName;

    /**
     * айди колла
     *
     * @var string
     */
    public string $CallSid;

    /**
     * статус звонка
     *
     * @var string
     */
    public string $CallStatus;

    //route
    /**
     * набор связи
     *
     * @var string
     */
    public string $DialBridged;

    /**
     * айди набранного звонка
     *
     * @var string
     */
    public string $DialCallSid;

    /**
     * статус набранного звонка
     *
     * @var string
     */
    public string $DialCallStatus;

    /**
     * цифры
     *
     * @var string
     */
    public string $Digits;

    /**
     * сообщение
     *
     * @var string
     */
    public string $msg;

    /**
     * запись продолжительность
     *
     * @var string
     */
    public string $RecordingDuration;

    /**
     * айди записи
     *
     * @var string
     */
    public string $RecordingSid;

    /**
     * урл записи
     *
     * @var string
     */
    public string $RecordingUrl;


    /**
     * родительский айди звонка
     *
     * @var string
     */
    public string $ParentCallSid;

    /**
     * ссылка трансфертного задания
     *
     * @var string
     */
    public string $ReferTransferTarget;

    /**
     * результат
     *
     * @var string
     */
    public string $Result;

    /**
     * айпи айди звонка
     *
     * @var string
     */
    public string $SipCallId;

    //status
    /**
     * прожолжительность звонка
     *
     * @var string
     */
    public string $CallDuration;

    /**
     * продолжительность
     *
     * @var string
     */
    public string $Duration;

    /**
     * CallRequest constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (isset($this->CallSid))
            $this->CallSid = $this->ParentCallSid ?? $this->CallSid;

        if (isset($this->CallSid))
            $this->EventSid = $this->CallSid;

        if (isset($this->Digits))
            $this->Digits = $this->setDigits();

        if (isset($this->ReferTransferTarget))
            $this->ReferTransferTarget = $this->setReferTransferTarget();

        if (isset($this->RecordingUrl))
            $this->RecordingUrl = self::parseUrl($this->RecordingUrl);
    }

    /**
     * установить цифры
     *
     * @return string
     */
    private function setDigits(): string
    {
        if (strtolower($this->Digits) === \app\src\event\request\Request::DIGITS_HANGUP) return $this->Digits;
        if (is_numeric($this->Digits)) return $this->Digits;
        return '';
    }

    /**
     * установить сссылочный обен заданием
     *
     *
     * @return array|null
     */
    private function setReferTransferTarget()
    {
        $regSipUri = "/^<sip:((.*)@(.*))/";
        preg_match($regSipUri, $this->ReferTransferTarget, $matches);

        return $matches ? $matches[2] : null;
    }

    /**
     * отпарсить урл
     *
     * @param $url
     * @return string|null
     */
    private static function parseUrl($url): ?string
    {
        $url = str_replace([stristr($url, '.json'), '\\'], '', $url);
        if ($url = urldecode($url))
            return $url;

        return null;
    }

    /**
     * установить данные набора звонка
     *
     * @param array $data
     */
    public function setDialCallData(array $data): void
    {
        if ($data)
            foreach ($data as $attr => $value)
                $this->$attr = $value;
    }

    /**
     * установить напрвление звонка
     *
     * @return string
     */
    protected function setDirection(): string
    {
        if ($target = self::outgoingPhone($this->To))
            if (strlen($target) < 5)
                return Event::DIRECTION_INTERNAL;
            else
                return Event::DIRECTION_OUTGOING;

        return (isset($this->SipCallId) || Event::DIRECTION_OUTGOING === $this->Direction)
            ? Event::DIRECTION_OUTGOING
            : Event::DIRECTION_INCOMING;
    }
}