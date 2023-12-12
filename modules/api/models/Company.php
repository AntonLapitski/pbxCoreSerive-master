<?php

namespace app\modules\api\models;

use yii\helpers\Url;
use yii\web\Linkable;

/**
 * Class Company
 * @package app\modules\api\models
 */
class Company extends \app\models\Company implements Linkable
{
    /**
     * @return array
     */
    public function extraFields(): array
    {
        return [
            'users' => 'users',
            'twilio' => 'twilio',
        ];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return [
            'self' => Url::to(['company/view', 'id' => $this->id], true),
            'update' => Url::to(['company/update', 'id' => $this->id], true)
        ];
    }
}