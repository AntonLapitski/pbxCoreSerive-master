<?php

namespace app\modules\api\controllers;


use app\modules\api\models\IncomingFlowSearch;

/**
 * Class IncomingFlowController
 * @package app\modules\api\controllers
 */
class IncomingFlowController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\IncomingFLow';

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new IncomingFlowSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
