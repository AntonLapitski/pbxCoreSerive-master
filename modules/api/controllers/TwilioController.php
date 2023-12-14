<?php

namespace app\modules\api\controllers;

use app\modules\api\models\TwilioSearch;

/**
 * Class TwilioController
 * Контроллер, управляющий твилио
 *
 * @property string $modelClass
 * @package app\modules\api\controllers
 */
class TwilioController extends BaseController
{
    /**
     * название модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\models\Twilio';

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
        $searchModel = new TwilioSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}
