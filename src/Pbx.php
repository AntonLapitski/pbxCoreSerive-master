<?php

namespace app\src;

use app\components\twilio\TwilioClient;
use app\src\client\Client;
use app\src\event\EventInterface;
use app\src\instance\InstanceInterface;
use crmpbx\logger\Logger;


/**
 * Class Pbx
 * @package app\src
 */
class Pbx
{
    public TwilioClient $twilioClient;
    public Logger $logger;
    private \Closure $callback;

    /**
     * Pbx constructor.
     * @param array $request
     * @param string $event
     * @param string $step
     * @param TwilioClient $twilioClient
     * @param Logger $log
     */
    public function __construct(array $request, string $event, string $step, TwilioClient $twilioClient, Logger $log)
    {
        $this->twilioClient = $twilioClient;
        $this->logger = $log;
        $this->callback = function (Config $config) use ($request, $event, $step) {

            try {
                $event = $this->event($request, $event, $step);
                $client = new Client($event->request);
                $this->logger->init(\Yii::$app->request->url, $client->company->sid, $event->request->EventSid);
            } catch (\Throwable  $e) {
                $this->logger->addInFile('pbx_init', $e);
                throw $e;
            }

            $this->twilioClient->init($client->twilio->sid, $client->twilio->token);
            $instance = $this->instance($event, $client);
            $config->event($event);

            return new \app\src\pbx\Pbx($instance);
        };
    }

    /**
     * @param $request
     * @param $event
     * @param $step
     * @return EventInterface
     */
    private function event($request, $event, $step): EventInterface
    {
        $class = 'app\src\event\\' . ucfirst($event) . 'Event';
        return new $class($event, $step, $request);
    }

    /**
     * @param EventInterface $event
     * @param Client $client
     * @return InstanceInterface
     */
    private function instance(EventInterface $event, Client $client): InstanceInterface
    {
        $class = 'app\src\instance\\' . ucfirst($event->event) . 'Instance';
        return new $class($event, $client);
    }

    /**
     * @param array $config
     * @return pbx\Pbx
     */
    public function build($config = []): \app\src\pbx\Pbx
    {
        return call_user_func($this->callback, new Config($config));
    }
}