<?php

namespace app\models;


/**
 * Сlass Twilio
 * This is the element class for table "twilio".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property string $token
 * @property string $domain
 * @property array $settings
 * @property Company $company
 * @package app\models
 */
class Twilio extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'twilio';
    }

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
