<?php

namespace app\modules\api\controllers;

use app\modules\api\models\CallbackFlowSearch;
use app\modules\api\models\CompanySearch;

/**
 * Class CallbackFlowController
 * @package app\modules\api\controllers
 */
class CallbackFlowController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\CallbackFlow';

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
        $searchModel = new CallbackFlowSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
