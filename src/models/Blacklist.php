<?php

namespace app\src\models;

/**
 * Class Blacklist
 * @package app\src\models
 */
class Blacklist extends \app\models\Blacklist
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

}