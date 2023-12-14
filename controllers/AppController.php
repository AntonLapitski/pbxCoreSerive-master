<?php


namespace app\controllers;

use app\src\Pbx;
use crmpbx\commutator\Commutator;
use crmpbx\logger\Logger;
use Yii;
use yii\web\ForbiddenHttpException as ForbiddenHttpExceptionAlias;
use yii\web\Response;

/**
 * Class AppController
 * Класс представлен для основоного рест апи ендпоинта
 *
 * @property Pbx $pbx
 * @property \app\src\pbx\Pbx $app
 * @property Logger $logger
 * @property Commutator $commutator
 * @package app\controllers
 */
abstract class AppController extends \yii\rest\Controller
{

    /**
     * Объект переменная, используется для создания комплексного объекта, включающего компанию и прочие составные эл-ты
     *
     * @var Pbx $pbx
     */
    protected Pbx $pbx;

    /**
     * Объект переменная, используется для создания комплексного объекта, включающего компанию и прочие составные эл-ты
     *
     * @var \app\src\pbx\Pbx $app
     */
    protected \app\src\pbx\Pbx $app;

    /**
     * Логгер
     *
     * @var Logger $logger
     */
    protected Logger $logger;

    /**
     * Объект переменная, который соединяет сущности
     *
     * @var Commutator $commutator
     */
    protected Commutator $commutator;

    /**
     * моды поведения
     *
     * @return array
     */
    public function behaviors(): array
    {
        $this->build();

        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['*/*'] = Response::FORMAT_RAW;
        return $behaviors;
    }

    /**
     * типичный билдер для создания обьекта
     *
     * @throws ForbiddenHttpException
     * @return void
     */
    private function build(): void
    {
        $this->commutator = Yii::$app->commutator;
        $this->logger = Yii::$app->logger;

        $this->pbx = new Pbx(
            Yii::$app->request->bodyParams,
            Yii::$app->controller->id,
            Yii::$app->controller->action->id,
            Yii::$app->twilio,
            Yii::$app->logger
        );

        $this->app = $this->pbx->build();

        if ($this->app->instance->isInBlackList())
            throw new ForbiddenHttpException('Client is in blacklist');
    }

    /**
     * функия логирования
     *
     * @param array $checkpoint
     * @param array $params
     * @return void
     */
    protected function log($checkpoint = array(), $params = array())
    {
        if ('call' === $this->app->instance->event->event)
            $params['result'] = $this->app->instance->event->status->getCallResult();
        if (!in_array('hangup', $checkpoint, true))
            $params['checkpoint'] = $checkpoint;

        $this->app->instance->log->save($params);
    }
}