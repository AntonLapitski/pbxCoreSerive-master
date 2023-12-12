<?php

namespace app\src\pbx\scheme\strategy;

use app\src\models\User;
use app\src\event\Event;
use app\src\instance\config\Config;
use app\src\pbx\scheme\element\Dial;
use app\src\pbx\scheme\model\Data;

/**
 * Class CallbackBuilder
 * @package app\src\pbx\scheme\strategy
 */
class CallbackBuilder extends BasicBuilder
{
    /**
     * @return Data
     */
    public function build(): Data
    {
        $method = $this->pbx->checkpoint->model->branch;
        return $this->$method();
    }

    /**
     * @return Data
     */
    private function callback(): Data
    {
        if (Event::DIRECTION_INCOMING === $this->pbx->instance->event->request->Direction)
            $list = $this->list($this->pbx->scheme->flow->current()[Config::LIST] ?? []);

        if (Event::DIRECTION_OUTGOING === $this->pbx->instance->event->request->Direction)
            $list = $this->list([$this->pbx->instance->event->request->From]);

        return new Data([
            'config' => [
                Config::LIST => $list,
                Config::SETTINGS => [
                    'url' => SERVER_ADDRESS . '/callback/route',
                    "statusCallback" => SERVER_ADDRESS . '/callback/parent-status',
                    "statusCallbackMethod" => "POST",
                    'method' => 'POST',
                ]
            ]
        ]);
    }

    /**
     * @return Data
     */
    private function outgoing(): Data
    {
        $flow = $this->pbx->scheme->flow->flow;
        if (!empty($this->pbx->instance->responsibleUser->settings->callback->welcome_message))
            $flow = array_merge($this->pbx->instance->responsibleUser->settings->callback->welcome_message, $flow);

        return new Data([
            'scheme' => array_map(function ($element) {
                return $this->element($element);
            }, $flow)
        ]);
    }

    /**
     * @param array $list
     * @return array
     */
    private function list(array $list): array
    {
        foreach ($list as $key => $sid) {
            $user = $this->pbx->instance->client->userList->get($sid);
            $list[$key] = [
                'target' => $this->device($user),
                'cname' => $this->source($user)
            ];
        }

        return $list;
    }

    /**
     * @param User $user
     * @return string
     */
    private function device(User $user){
        $device = $user->settings->callback->device ?? Dial::NOUN_SIP;
        if (Dial::NOUN_SIP === $device)
            return sprintf('sip:%s@%s', $user->sip, $this->pbx->instance->client->twilio->domain);
        if (Dial::NOUN_NUMBER === $device)
            return $user->mobile_number;
        if (Dial::NOUN_CLIENT === $device)
            return 'client:' . $user->sid;
    }

    /**
     * @param User $user
     * @return string
     */
    private function source(User $user): string
    {
        $cname = $user->settings->callback->show_cname ?? false;
        if (is_string($cname))
            if ($cname = $this->pbx->instance->getCallerName($cname))
                return $cname;

        return (!is_string($cname) && $cname)
            ? (Event::DIRECTION_INCOMING === $this->pbx->instance->event->request->Direction
                ? $this->pbx->instance->event->request->From
                : $this->pbx->instance->event->request->To
            )
            : Event::SERVICE_CALLBACK;
    }
}