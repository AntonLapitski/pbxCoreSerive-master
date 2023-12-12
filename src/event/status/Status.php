<?php


namespace app\src\event\status;


use app\models\request\TwilioCallbackData;
use app\src\event\Event;
use app\src\event\request\Request;
use JetBrains\PhpStorm\Pure;

/**
 * Class Status
 * @package app\src\event\status
 */
class Status
{
    protected Event $event;

    /**
     * Status constructor.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {

        if (!$this->isInProgress()) {
            if ($this->isAnswered()) return Event::STATUS_COMPLETED;
            if ($this->isVoicemail()) return Event::STATUS_VOICEMAIL;
            if ($this->isNoAnswer()) return Event::STATUS_NO_ANSWER;
        }

        return Event::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isInProgress(): bool
    {
        if ($this->isInit()) return true;
        if ($this->isDial()) {
            if ($this->isAnswered() || $this->isDialerHangup()) return false;
            return true;
        }
        if ($this->isRefer() || false == $this->isIVRHangup())
            return true;

        return false;
    }

    /**
     * @return bool
     */
    public function isInit(): bool
    {
        return Event::STATUS_RINGING === ($this->event->request->CallStatus ?? false);
    }

    /**
     * @return bool
     */
    public function isDial(): bool
    {
        return isset($this->event->request->DialBridged);
    }

    /**
     * @return bool
     */
    public function isAnswered(): bool
    {
        return filter_var($this->event->request->DialBridged ?? false, FILTER_VALIDATE_BOOL);
    }

    /**
     * @return bool
     */
    public function isDialerHangup(): bool
    {
        return (
            Event::STATUS_COMPLETED === ($this->event->request->CallStatus ?? false)
            &&
            Event::STATUS_NO_ANSWER === ($this->event->request->DialCallStatus ?? false)
        );
    }

    /**
     * @return bool
     */
    public function isRefer(): bool
    {
        return
            Event::STATUS_IN_PROGRESS === ($this->event->request->CallStatus ?? false)
            &&
            (bool)($this->event->request->ReferTransferTarget ?? false);
    }

    #[Pure] public function isIVRHangup(): bool
    {
        return ($this->isGather() && Request::DIGITS_HANGUP === strtolower($this->event->request->Digits));
    }

    public
/**
 * @return bool
 */
function isGather(): bool
    {
        return Request::DIGITS_GATHER_END === strtolower($this->event->request->msg ?? '');
    }

    #[Pure] public function isVoicemail(): bool
    {
        return (!$this->isDial() && isset($this->event->request->RecordingUrl));
    }

    public
/**
 * @return bool
 */
function isNoAnswer(): bool
    {
        return (
            $this->isDialerHangup()
            ||
            $this->isIVRHangup()
            ||
            $this->isVoicemail()
        );
    }

    public
/**
 * @return mixed
 */
function getDirection()
    {
        return $this->event->request->Direction;
    }

    public
/**
 * @param $result
 * @return bool
 */
function isHangupCallResult($result): bool
    {
        return (Event::STATUS_COMPLETED === $result || Event::STATUS_NO_ANSWER === $result || Event::STATUS_VOICEMAIL === $result);
    }

    public
/**
 * @return bool
 */
function isInternal(): bool
    {
        return Event::DIRECTION_INTERNAL === $this->event->request->Direction;
    }

    public
/**
 * @return bool
 */
function isCallback(): bool
    {
        return Event::SERVICE_CALLBACK === $this->event->event;
    }

    public
/**
 * @return bool
 */
function isOutgoing(): bool
    {
        return Event::DIRECTION_OUTGOING === $this->event->request->Direction;
    }

    public
/**
 * @return bool
 */
function isIncoming(): bool
    {
        return Event::DIRECTION_INCOMING === $this->event->request->Direction;
    }

    public
/**
 * @return bool
 */
function isDialCompleted(): bool
    {
        return Event::STATUS_COMPLETED === ($this->event->request->DialCallStatus ?? false);
    }

    public
/**
 * @return bool
 */
function isHangup(): bool
    {
        return ($this->isAnswered() || $this->isNoAnswer());
    }

    public
/**
 * @return bool
 */
function isDialTimeout(): bool
    {
        return (
            Event::STATUS_IN_PROGRESS === ($this->event->request->CallStatus ?? false)
            &&
            Event::STATUS_NO_ANSWER === ($this->event->request->DialCallStatus ?? false)
            &&
            false === (bool)($this->event->request->DialBridged ?? false)
        );
    }

    public
/**
 * @return bool
 */
function isBusy(): bool
    {
        return Event::STATUS_BUSY === ($this->event->request->DialCallStatus ?? false);
    }

    #[Pure] public function isIVRTimeout(): bool
    {
        return ($this->isGather() && !isset($this->event->request->Digits));
    }

    #[Pure] public function isIVRHandled(): bool
    {
        return ($this->isGather() && is_numeric($this->event->request->Digits));
    }

}
