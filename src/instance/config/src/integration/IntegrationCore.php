<?php


namespace app\src\instance\config\src\integration;


use crmpbx\commutator\Commutator;
use crmpbx\commutator\CommutatorException;
use crmpbx\httpClient\Response;
use crmpbx\httpClient\Timer;
use crmpbx\logger\Logger;

/**
 * Class Integration
 * @package app\src\instance\integration $model
 * @package array $data
 * @package Logger $logger
 * @package ommutator $commutator
 * @property \app\src\models\twilio\Integration $element
 */
class IntegrationCore
{
    /**
     * модель
     *
     * @var \app\src\models\twilio\Integration
     */
    public \app\src\models\twilio\Integration $model;

    /**
     * данные
     *
     * @var ?array
     */
    public ?array $data;

    /**
     * логгер
     *
     * @var Logger
     */
    private Logger $logger;

    /**
     * соединитель
     *
     * @var Commutator
     */
    private Commutator $commutator;

    /**
     * IntegrationCore constructor.
     * @param \app\src\models\twilio\Integration $model
     * @param $logData
     */
    public function __construct(\app\src\models\twilio\Integration $model, $logData)
    {
        $this->logger = \Yii::$app->logger;
        $this->commutator = \Yii::$app->commutator;

        $this->model = $model;
        $this->data = $logData;
    }

    /**
     * отправляет запрос с помощью соединителя на урл
     *
     * @param $data
     * @param $route
     * @return Response|null
     */
    protected function _serviceRequest($data, $route): ?Response
    {
        if (!$this->model->is_active) return null;
        $service = $this->model->service;

        try {
            $response = $this->commutator->send($service, 'POST', '/pbx/' . $route, $data);

            $this->log([
                'status' => $response->status,
                'body' => $response->body,
                'reason' => $response->reason,
                'timer' => $response->timer,
            ]);

            if (200 === $response->status)
                return $response;
        } catch (CommutatorException $e){
            $this->log($e);
        }
        return null;
    }

    /**
     * сделать логи
     *
     * @param array|CommutatorException $response
     * @return void
     */
    private function log(array|CommutatorException $response)
    {
        $this->logger->addInFile('integration_response', $response);
        if(is_array($response))
            $this->logger->add('integration_response', $response);
    }
}