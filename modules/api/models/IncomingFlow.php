<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class IncomingFlow
 * Модель входящий поток
 *
 * @package app\modules\api\models
 */
class IncomingFlow extends \app\models\IncomingFlow  implements Linkable
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
