<?php

namespace app\modules\api\controllers;

use app\modules\api\models\CallbackFlowSearch;
use app\modules\api\models\CompanySearch;

/**
 * Class CallbackFlowController
 * Контроллер, который управлят потоками на вызовы
 *
 * @property string $modelClass
 * @package app\modules\api\controllers
 */
class CallbackFlowController extends BaseController
{
    /**
     * название модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\models\CallbackFlow';

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
        $searchModel = new CallbackFlowSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
