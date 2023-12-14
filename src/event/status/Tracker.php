<?php


namespace app\src\event\status;

use app\src\event\CallEvent;
use app\src\event\Event;
use JetBrains\PhpStorm\Pure;

/**
 * Class Tracker
 *
 *
 * @property Event $event
 * @package app\src\event\status
 */
class Tracker extends Status
{
    /**
     * событие
     *
     * @var Event
     */
    protected Event $event;

    #[Pure] public function getService()
    {
        if ($this->isCallback()) return Event::SERVICE_CALLBACK;
        if ($this->isRefer()) return CallEvent::SERVICE_REFER;
        if ($this->isInternal()) return CallEvent::SERVICE_INTERNAL;
        if ($this->isOutgoing()) return CallEvent::SERVICE_OUTGOING;
        if ($this->isIncoming()) {
            if ($this->isGather()) return CallEvent::SERVICE_GATHER;
            if ($this->isDial()) return CallEvent::SERVICE_MAIN;
            return CallEvent::SERVICE_MAIN;
        }
    }

    /**
     * плучить статус
     *
     * @return string
     */
    public function getIvrStatus()
    {
        if ($this->isIVRHangup()) return Event::STATUS_HANGUP;
        if ($this->isIVRTimeout()) return Event::STATUS_TIMEOUT;
        if ($this->isIVRHandled()) return Event::STATUS_GATHER_HANDLED;
    }

    /**
     * получить результат звонка
     *
     * @return string
     */
    public function getCallResult(): string
    {
        if ($this->isBusy()) return Event::STATUS_NO_ANSWER;
        if ($this->isVoicemail()) return Event::STATUS_VOICEMAIL;
        if ($this->isAnswered()) return Event::STATUS_COMPLETED;
        if ($this->isNoAnswer()) return Event::STATUS_NO_ANSWER;
        if ($this->isHangup()) return Event::STATUS_HANGUP;

        return Event::STATUS_NO_ANSWER;
    }
}