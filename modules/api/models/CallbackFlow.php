<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class CallbackFlow
 * Модель колбекка на поток
 *
 * @package app\modules\api\models
 */
class CallbackFlow extends \app\models\CallbackFlow implements Linkable
{
    /**
     * правила валидации
     *
     * @return array
     */
    public function extraFields(): array
    {
        return [
            'company' => 'company'
        ];
    }

    /**
     * забрать ссылки
     *
     * @return array
     */
    public function getLinks()
    {
        return [
            'self' => Url::to(['user/view', 'id' => $this->id], true),
            'update' => Url::to(['user/update', 'id' => $this->id], true)
        ];
    }
}
