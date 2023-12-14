<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class Blacklist
 * Модель, управляющая черновым листом
 *
 * @package app\modules\api\models
 */
class Blacklist extends \app\models\Blacklist implements Linkable
{
    /**
     * дополнительные поля
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
