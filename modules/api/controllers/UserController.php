<?php

namespace app\modules\api\controllers;


use app\modules\api\models\UserSearch;

/**
 * Class UserController
 * @package app\modules\api\controllers
 */
class UserController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\User';

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
        $searchModel = new UserSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}