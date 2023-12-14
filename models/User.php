<?php

namespace app\models;

/**
 * Сlass User
 * This is the element class for table "user".
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $sip
 * @property string $sid
 * @property string $mobile_number
 * @property string $auth_token
 * @property string $outgoing_config_sid
 * @property array $settings
 * @property Company $company
 * @property Config $config
 * @package app\models
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'user';
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

    /**
     * получить конфиг
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfig(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Config::className(), ['sid' => 'outgoing_config_sid']);
    }
}
