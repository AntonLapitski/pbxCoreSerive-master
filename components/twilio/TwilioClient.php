<?php

namespace app\components\twilio;

use JetBrains\PhpStorm\ArrayShape;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;


/**
 * Class TwilioClient
 * @package app\components\twilio
 */
class TwilioClient
{
    protected Client $client;
    protected ClientToken $capability;

    protected array $recordings = [];
    protected array $dialCalls = [];

    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function init($sid, $token)
    {
        $this->client = new Client($sid, $token);
        $this->capability = new ClientToken($sid, $token);
    }

    /**
     * @param $identity
     * @param $appSid
     * @param int $expires
     * @return string
     */
    public function grantAccessToken($identity, $appSid, $expires = 3600): string
    {
        $this->capability->allowClientIncoming($identity);
        $this->capability->allowClientOutgoing($appSid);

        return $this->capability->generateToken($expires);
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function dropVoiceResource($callSid): void
    {
        $this->call($callSid)->update(["status" => "completed"]);
    }

    /**
     * @param $callSid
     * @return \Twilio\Rest\Api\V2010\Account\CallContext
     */
    public function call($callSid): \Twilio\Rest\Api\V2010\Account\CallContext
    {
        return $this->client->calls($callSid);
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function updateVoiceResource($twiml, $callSid): string
    {
        $call = $this->call($callSid)->update([
            'twiml' => $twiml
        ]);
        return $call->sid;
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function createVoiceResource($to, $from, $settings): string
    {
        $call = $this->client->calls->create($to, $from, $settings);
        return $call->sid;
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    #[ArrayShape(['sid' => "string", 'status' => "string"])]
    /**
     * @param $to
     * @param $query
     * @return array
     */
    public function createMessageResource($to, $query): array
    {
        $message = $this->client->messages
            ->create($to, $query);

        return ['sid' => $message->sid, 'status' => $message->status];
    }

    /**
     * @param $callSid
     * @return array
     */
    public function getDialCallData($callSid): array
    {
        if ($call = $this->getDialCall($callSid))
            return [
                'DialCallStatus' => $call->status,
                'DialCallSid' => $call->sid,
                'RecordingUrl' => $this->getRecordingUrl($callSid),
                'RecordingDuration' => $this->getRecordingDuration($callSid),
                'DialBridged' => ('completed' === $call->status)
            ];

        return [];
    }

    /**
     * @param $callSid
     * @return \Twilio\Rest\Api\V2010\Account\CallInstance
     */
    public function getDialCall($callSid): \Twilio\Rest\Api\V2010\Account\CallInstance
    {
        if (isset($this->dialCalls[$callSid]))
            return $this->dialCalls[$callSid];

        $calls = $this->client->calls
            ->read(["ParentCallSid" => $callSid],
                20
            );

        foreach ($calls as $call) {
            if (isset($this->dialCalls[$callSid])) {
                if ($this->dialCalls[$callSid]->dateUpdated->getTimestamp() > $call->dateUpdated->getTimestamp())
                    $this->dialCalls[$callSid] = $call;
            } else {
                $this->dialCalls[$callSid] = $call;
            }
        }

        return $this->dialCalls[$callSid];
    }

    /**
     * @param $callSid
     * @return string|null
     */
    public function getRecordingUrl($callSid): ?string
    {
        if ($rec = $this->getRecording($callSid))
            return 'https://api.twilio.com' . self::parseUrl($rec->uri);

        return null;
    }

    /**
     * @param $callSid
     * @return \Twilio\Rest\Api\V2010\Account\RecordingInstance
     */
    public function getRecording($callSid): \Twilio\Rest\Api\V2010\Account\RecordingInstance
    {
        if (isset($this->recordings[$callSid]))
            return $this->recordings[$callSid];


        $recordings = $this->client->recordings
            ->read(["callSid" => $callSid],
                20
            );

        foreach ($recordings as $recording) {
            if (isset($this->recordings[$callSid])) {
                if ($recording->startTime->getTimestamp() > $this->recordings[$callSid]->startTime->getTimestamp())
                    $this->recordings[$callSid] = $recording;
            } else {
                $this->recordings[$callSid] = $recording;
            }
        }

        return $this->recordings[$callSid];
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
     * @param $callSid
     * @return int
     */
    public function getRecordingDuration($callSid): int
    {
        $duration = $this->getRecording($callSid)->duration;
        if (0 > $duration) {
            $try = 0;
            while (0 > $duration || 10 > $try) {
                $try++;
                unset($this->recordings[$callSid]);
                $duration = $this->getRecording($callSid)->duration;
            }
        }

        return (0 > $duration) ? 0 : (int)$duration;
    }

    /**
     * @param $callSid
     * @return array
     */
    public function getCallData($callSid): array
    {
        $call = $this->client->calls($callSid)->fetch();

        return [
            'Sid' => $call->sid,
            'From' => self::parseTarget($call->from),
            'To' => self::parseTarget($call->to)
        ];
    }

    /**
     * @param $target
     * @return array
     */
    public static function parseTarget($target)
    {
        if (stripos($target, '@') && stripos($target, ':')) {
            $target = explode('@', $target);
            $target = explode(':', $target[0]);
            return $target[1];
        }

        return $target;
    }
}