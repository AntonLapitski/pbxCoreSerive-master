<?php

namespace app\models;

/**
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
 *
 * @property array $settings
 * @property Company $company
 * @property Config $config
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Config::className(), ['sid' => 'outgoing_config_sid']);
    }
}
