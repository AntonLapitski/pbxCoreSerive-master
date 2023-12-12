<?php

namespace app\src\models;


/**
 * Class Voicemail
 * @package app\src\models
 */
class Voicemail extends \app\models\Voicemail
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}