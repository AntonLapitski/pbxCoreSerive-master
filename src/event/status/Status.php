<?php


namespace app\src\event\status;


use app\models\request\TwilioCallbackData;
use app\src\event\Event;
use app\src\event\request\Request;
use JetBrains\PhpStorm\Pure;

/**
 * Class Status
 * класс опысывающий статус звонков
 *
 * @property Event $event;
 * @package app\src\event\status
 */
class Status
{
    /**
     * объект переменная события
     *
     * @var Event
     */
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
     * получить статус
     *
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
     * является ли в обработке
     *
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
     * проинициализирован ли
     *
     * @return bool
     */
    public function isInit(): bool
    {
        return Event::STATUS_RINGING === ($this->event->request->CallStatus ?? false);
    }

    /**
     * набран ли
     *
     * @return bool
     */
    public function isDial(): bool
    {
        return isset($this->event->request->DialBridged);
    }

    /**
     * является ли опрошенным
     *
     * @return bool
     */
    public function isAnswered(): bool
    {
        return filter_var($this->event->request->DialBridged ?? false, FILTER_VALIDATE_BOOL);
    }

    /**
     *
     * зависал ли при звонке
     *
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
     * ссылается ли
     *
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


    /**
     * собран ли
     *
     * @return bool
     */
    public function isGather(): bool
        {
            return Request::DIGITS_GATHER_END === strtolower($this->event->request->msg ?? '');
        }

        #[Pure] public function isVoicemail(): bool
        {
            return (!$this->isDial() && isset($this->event->request->RecordingUrl));
        }


    /**
     * является ли неотвеченным
     *
     * @return bool
     */
    public function isNoAnswer(): bool
        {
            return (
                $this->isDialerHangup()
                ||
                $this->isIVRHangup()
                ||
                $this->isVoicemail()
            );
        }

    /**
     * получить направлене события
     *
     * @return mixed
     */
    public function getDirection()
        {
            return $this->event->request->Direction;
        }


    /**
     * резуллтат после зависания звонка
     *
     * @param $result
     * @return bool
     */
    public function isHangupCallResult($result): bool
        {
            return (Event::STATUS_COMPLETED === $result || Event::STATUS_NO_ANSWER === $result || Event::STATUS_VOICEMAIL === $result);
        }


    /**
     * является ли внутренним
     *
     * @return bool
     */
    public function isInternal(): bool
        {
            return Event::DIRECTION_INTERNAL === $this->event->request->Direction;
        }


    /**
     * будет ли отработка ивента после звонка
     *
     * @return bool
     */
    public function isCallback(): bool
        {
            return Event::SERVICE_CALLBACK === $this->event->event;
        }


    /**
     * является ли исходящим
     *
     * @return bool
     */
    public function isOutgoing(): bool
        {
            return Event::DIRECTION_OUTGOING === $this->event->request->Direction;
        }


    /**
     * является ли входящим
     *
     * @return bool
     */
    public function isIncoming(): bool
        {
            return Event::DIRECTION_INCOMING === $this->event->request->Direction;
        }


    /**
     * прошло ли созвон
     *
     * @return bool
     */
    public function isDialCompleted(): bool
        {
            return Event::STATUS_COMPLETED === ($this->event->request->DialCallStatus ?? false);
        }


    /**
     * завис ли
     *
     * @return bool
     */
    public function isHangup(): bool
        {
            return ($this->isAnswered() || $this->isNoAnswer());
        }


    /**
     * таймаут при набирании
     *
     * @return bool
     */
    public function isDialTimeout(): bool
        {
            return (
                Event::STATUS_IN_PROGRESS === ($this->event->request->CallStatus ?? false)
                &&
                Event::STATUS_NO_ANSWER === ($this->event->request->DialCallStatus ?? false)
                &&
                false === (bool)($this->event->request->DialBridged ?? false)
            );
        }


    /**
     * занят ли
     *
     * @return bool
     */
    public function isBusy(): bool
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
