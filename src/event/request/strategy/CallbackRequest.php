<?php

namespace app\src\event\request\strategy;

/**
 * Class CallbackRequest
 * Запрошенный коллбэк
 *
 * @package app\src\event\request\strategy
 */
class CallbackRequest extends CallRequest implements RequestInterface
{
    /**
     * CallbackRequest constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (!isset($this->CallSid))
            $this->CallSid = 'CB' . md5(time() . rand(0, 999));

        $this->EventSid = $this->CallSid;
    }
}