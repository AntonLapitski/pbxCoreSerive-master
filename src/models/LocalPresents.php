<?php

namespace app\src\models;

/**
 * Class LocalPresents
 * @package app\src\models
 */
class LocalPresents extends \app\models\LocalPresents
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}