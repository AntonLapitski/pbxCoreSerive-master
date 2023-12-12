<?php

namespace app\src\models;

/**
 * Class Config
 * @package app\src\models
 */
class Config extends \app\models\Config
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}