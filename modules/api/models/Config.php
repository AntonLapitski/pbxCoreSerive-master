<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class Config
 * Модель, управляющая классом конфигурации
 *
 * @package app\modules\api\models
 */
class Config extends \app\models\Config implements Linkable
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
