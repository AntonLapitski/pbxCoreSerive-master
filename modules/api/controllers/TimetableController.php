<?php

namespace app\modules\api\controllers;

use app\modules\api\models\TimetableSearch;

/**
 * Class TimetableController
 * @package app\modules\api\controllers
 */
class TimetableController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Timetable';

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
        $searchModel = new TimetableSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
