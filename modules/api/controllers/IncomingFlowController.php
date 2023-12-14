<?php

namespace app\modules\api\controllers;


use app\modules\api\models\IncomingFlowSearch;

/**
 * Class IncomingFlowController
 * Контроллер, управляющий входным потоком
 *
 * @property string $modelClass
 * @package app\modules\api\controllers
 */
class IncomingFlowController extends BaseController
{
    /**
     * название модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\models\IncomingFLow';

    /**
     * возможные действия
     *
     * @return array
     */
    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * сделает поиск по модели
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new IncomingFlowSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
