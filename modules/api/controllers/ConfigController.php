<?php

namespace app\modules\api\controllers;

use app\modules\api\models\ConfigSearch;

/**
 * Class ConfigController
 * @package app\modules\api\controllers
 */
class ConfigController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Config';

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
        $searchModel = new ConfigSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
