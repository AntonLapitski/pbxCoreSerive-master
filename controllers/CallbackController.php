<?php


namespace app\controllers;


use app\src\instance\config\Config;
use yii\helpers\Json;

/**
 * Class CallbackController
 * Класс представлен для основоного коллбэков после отработки определенной логики
 *
 * @package app\controllers
 */
class CallbackController extends AppController
{
    /**
     * получить спиок вызываемыхы у модели ивентов
     *
     * @return mixed
     */
    public function actionInit()
    {
        $config = $this->app->data()->config;

        foreach ($config[Config::LIST] as $device)
            $this->app->checkpoint->model->list[] =
                $this->pbx->twilioClient->createVoiceResource($device['target'], $device['cname'], $config[Config::SETTINGS]);

        foreach ($this->app->checkpoint->model->list as $call) {
            $this->app->instance->log->set([
                'event_sid' => $call,
                'checkpoint' => $this->app->checkpoint->model->asArray()
            ]);
        }

        $this->pbx->logger->addInFile('callList', $this->app->checkpoint->model->list);

        return Json::encode(['callList' => $this->app->checkpoint->model->list]);
    }

    /**
     * обновить голосовой ресурс
     *
     * @return void
     */
    public function actionRoute()
    {
        $scheme = $this->app->data();

        if ('canceled' === $this->app->instance->log->model->result)
            $this->pbx->twilioClient->dropVoiceResource($this->app->instance->event->request->CallSid);
        else {
            foreach ($this->app->checkpoint->model->list as $callSid)
                if ($callSid !== $this->app->instance->event->request->CallSid) {
                    $log = $this->app->instance->log->get($callSid);
                    $log->save($this->app->checkpoint->model->asArray(), ['result' => 'canceled']);
                    $this->pbx->twilioClient->dropVoiceResource($callSid);
                }

            $this->pbx->twilioClient->updateVoiceResource($scheme->twiml, $this->app->instance->event->request->CallSid);
        }
    }

    /**
     * вывести в лог статус
     *
     * @return void
     */
    public function actionParentStatus()
    {
        $this->log($this->app->checkpoint->model->asArray(), [
            'result' => $this->app->instance->event->request->CallStatus
        ]);
    }

    /**
     * удалить голосовой ресурс
     *
     * @return void
     */
    public function actionChildStatus()
    {
        $this->pbx->twilioClient->dropVoiceResource($this->app->instance->event->request->CallSid);
    }
}