<?php

namespace app\src\models;

/**
 * Class CallbackFlow
 * @package app\src\models
 */
class CallbackFlow extends \app\models\CallbackFlow
{
    /**
     * получить компанию
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}