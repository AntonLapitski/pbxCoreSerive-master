<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class LocalPresents
 * Модель локальных представлений
 *
 * @package app\modules\api\models
 */
class LocalPresents extends \app\models\LocalPresents implements Linkable
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
