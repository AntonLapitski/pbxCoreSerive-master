<?php

namespace app\modules\api\controllers;

use app\modules\api\models\CompanySearch;

/**
 * Class CompanyController
 * @package app\modules\api\controllers
 */
class CompanyController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Company';

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
        $searchModel = new CompanySearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}