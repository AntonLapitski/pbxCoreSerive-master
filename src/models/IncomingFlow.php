<?php

namespace app\src\models;

/**
 * Class IncomingFlow
 * @package app\src\models
 */
class IncomingFlow extends \app\models\IncomingFlow
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}