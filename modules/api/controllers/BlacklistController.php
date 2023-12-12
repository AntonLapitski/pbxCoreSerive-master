<?php

namespace app\modules\api\controllers;

use app\modules\api\models\BlacklistSearch;
use app\modules\api\models\CompanySearch;

/**
 * Class BlacklistController
 * @package app\modules\api\controllers
 */
class BlacklistController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Blacklist';

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
        $searchModel = new BlacklistSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
