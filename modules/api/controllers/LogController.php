<?php

namespace app\modules\api\controllers;

use app\modules\api\models\LogSearch;

/**
 * Class LogController
 * @package app\modules\api\controllers
 */
class LogController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Log';

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
        $searchModel = new LogSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}

