<?php

namespace app\controllers;

use app\src\event\Event;
use crmpbx\commutator\CommutatorException;
use Yii;

/**
 * Class CallController
 * Контроллер для обрабтки звонков
 *
 * @package app\controllers
 */
class CallController extends AppController
{
    /**
     * поулчить сво-во twiml
     *
     * @return mixed
     */
    public function actionInit()
    {
        $pbx = $this->app->data();
        $this->log($this->app->checkpoint->model->asArray());

        $this->logger->addInFile('pbx', [
            'checkpoint' => $this->app->checkpoint->model->asArray(),
            'scheme' => $pbx->scheme
        ]);

        return $pbx->twiml;
    }

    /**
     * вывести в лог
     *
     * @return void
     */
    public function actionRoute()
    {
        $pbx = $this->app->data();
        $this->log($this->app->checkpoint->model->asArray());

        try {
            $this->pbx->twilioClient->updateVoiceResource($pbx->twiml, $this->app->instance->event->request->CallSid);
        } catch (\Twilio\Exceptions\RestException $e){
            $this->logger->addInFile('update_call', $e);
        }

        $this->logger->addInFile('pbx', [
            'checkpoint' => $this->app->checkpoint->model->asArray(),
            'scheme' => $pbx->scheme
        ]);
    }

    /**
     *
     * Вывести статус
     *
     * @return void
     */
    public function actionStatus()
    {
        try {
            $this->commutator->send('notification', 'POST', 'pbx/handle', $this->app->instance->data->getData());
        } catch (CommutatorException $e) {
            $this->logger->addInFile('send_notification_service', $e);
        }
    }

    /**
     *
     * Статус набраннных звонков
     *
     * @return void
     */
    public function actionDialStatus()
    {
        try {
            if (Event::DIRECTION_INCOMING === $this->app->instance->event->request->getDirection())
                $this->commutator->send('extension', 'POST', 'api/worker/event/', $this->app->instance->data->getData());
        } catch (CommutatorException $e) {
            $this->logger->addInFile('send_extension_service', $e);
        }
    }

}
