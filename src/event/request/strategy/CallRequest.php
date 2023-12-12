<?php

namespace app\src\event\request\strategy;


use app\src\event\Event;

/**
 * Class CallRequest
 * @package app\src\event\request\strategy
 */
class CallRequest extends TwilioRequest implements RequestInterface
{
    //all
    public string $CallerName;
    public string $CallSid;
    public string $CallStatus;
    //route
    public string $DialBridged;
    public string $DialCallSid;
    public string $DialCallStatus;
    public string $Digits;
    public string $msg;
    public string $RecordingDuration;
    public string $RecordingSid;
    public string $RecordingUrl;
    public string $ParentCallSid;
    public string $ReferTransferTarget;
    public string $Result;
    public string $SipCallId;
    //status
    public string $CallDuration;
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
     * @return string
     */
    private function setDigits(): string
    {
        if (strtolower($this->Digits) === \app\src\event\request\Request::DIGITS_HANGUP) return $this->Digits;
        if (is_numeric($this->Digits)) return $this->Digits;
        return '';
    }

    /**
     * @return |null
     */
    private function setReferTransferTarget()
    {
        $regSipUri = "/^<sip:((.*)@(.*))/";
        preg_match($regSipUri, $this->ReferTransferTarget, $matches);

        return $matches ? $matches[2] : null;
    }

    /**
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
     * @param array $data
     */
    public function setDialCallData(array $data): void
    {
        if ($data)
            foreach ($data as $attr => $value)
                $this->$attr = $value;
    }

    /**
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