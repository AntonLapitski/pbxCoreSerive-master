<?php

namespace app\models;

/**
 * Сlass Config
 * This is the element class for table "config_incoming_call".
 *
 * @property int $id
 * @property int $company_id
 * @property int $phone_id
 * @property string $config
 *
 * @property Company $company
 * @package app\models
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'config';
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
