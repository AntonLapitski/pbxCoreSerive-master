<?php

namespace app\modules\api;
/**
 * Class Api
 * api module definition class
 * @property string $controllerNamespace
 * @package app\modules\api
 */
class Api extends \yii\base\Module
{
    /**
     * неймспейс контроллера
     *
     * @var string
     */
    public $controllerNamespace = 'app\modules\api\controllers';
}
