<?php

namespace app\modules\api\controllers;

use app\modules\api\models\BlacklistSearch;
use app\modules\api\models\CompanySearch;

/**
 * Class BlacklistController
 * Контроллер, который управляет черновым списком
 *
 * @property string $modelClass
 * @package app\modules\api\controllers
 */
class BlacklistController extends BaseController
{
    /**
     * Название модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Blacklist';

    /**
     * возможные методы
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
     * поиск по черновому списку
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new BlacklistSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
