<?php

namespace app\src\pbx\scheme\strategy;

use app\src\pbx\scheme\model\Data;

/**
 * Class MessageBuilder
 * @package app\src\pbx\scheme\strategy
 */
class MessageBuilder extends BasicBuilder
{
    /**
     * @return Data
     */
    public function build(): Data
    {
        $query = [
            "statusCallback" => SERVER_ADDRESS . '/message/status'
        ];

        if (isset($this->pbx->instance->event->request->Body))
            $query['body'] = $this->pbx->instance->event->request->Body;
        if (isset($this->pbx->instance->event->request->Media))
            $query['mediaUrl'] = array_values($this->pbx->instance->event->request->Media);
        if (isset($this->pbx->instance->config->messageService))
            $query['messagingServiceSid'] = $this->pbx->instance->config->messageService;
        else
            $query['from'] = $this->pbx->instance->config->studioNumber->number;

        return new Data(['config' => $query]);
    }
}