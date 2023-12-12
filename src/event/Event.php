<?php

namespace app\src\event;

use app\src\event\request\Request;
use app\src\event\request\strategy\RequestInterface;
use app\src\event\status\Tracker;

/**
 * @property string $event
 * @property string $step
 * @property string $route
 * @property Tracker $status
 * @property RequestInterface $request
 */
abstract class Event implements EventInterface
{
    /**
     *
     */
    const EVENT = 'event';
    /**
     *
     */
    const STATUS = 'status';

    /**
     *
     */
    const DIRECTION_OUTGOING = 'outgoing';
    /**
     *
     */
    const DIRECTION_INTERNAL = 'internal';
    /**
     *
     */
    const DIRECTION_INCOMING = 'incoming';

    /**
     *
     */
    const SERVICE_CALL = 'call';
    /**
     *
     */
    const SERVICE_MESSAGE = 'message';
    /**
     *
     */
    const SERVICE_CALLBACK = 'callback';
    /**
     *
     */
    const SERVICE_EXTENSION = 'extension';

    /**
     *
     */
    const STEP_INIT = 'init';
    /**
     *
     */
    const STEP_ROUTE = 'route';
    /**
     *
     */
    const STEP_STATUS = 'status';
    /**
     *
     */
    const STEP_DIAL_STATUS = 'dial-status';
    /**
     *
     */
    const STEP_CALLBACK = 'callback';
    /**
     *
     */
    const STEP_MESSAGE_GET = 'get';
    /**
     *
     */
    const STEP_MESSAGE_SEND = 'send';

    /**
     *
     */
    const STATUS_HANGUP = 'hangup';
    /**
     *
     */
    const STATUS_COMPLETED = 'completed';
    /**
     *
     */
    const STATUS_NO_ANSWER = 'no-answer';
    /**
     *
     */
    const STATUS_BUSY = 'busy';
    /**
     *
     */
    const STATUS_RINGING = 'ringing';
    /**
     *
     */
    const STATUS_VOICEMAIL = 'voicemail';
    /**
     *
     */
    const STATUS_IN_PROGRESS = 'in-progress';
    /**
     *
     */
    const STATUS_GATHER_HANDLED = 'handled';
    /**
     *
     */
    const STATUS_TIMEOUT = 'timeout';

    public string $event;
    public string $step;
    public string $route;

    public Tracker $status;
    public RequestInterface $request;

    /**
     * Event constructor.
     * @param string $event
     * @param string $step
     * @param array $request
     */
    public function __construct(string $event, string $step, array $request)
    {
        $this->event = $event;
        $this->step = $step;
        $this->route = $event . '-' . $step;
        $this->request = Request::get($request, $this->event);
    }

    /**
     * @param array $settings
     */
    public function setData(array $settings): void
    {
        $this->request->setData($settings['country_code']);
        $this->status = new Tracker($this);
    }
}