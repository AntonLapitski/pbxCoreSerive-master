<?php

namespace app\src\models;


use app\src\models\twilio\UserSettings;
/**
 * @property UserSettings $settings
 */
class User extends \app\models\User
{
    public string $number;

    /**
     *
     */
    public function afterFind()
    {
        $this->settings = new UserSettings($this->settings['direct_call_settings'] ?? []);
        if ($this->mobile_number ?? false)
            $this->number = $this->mobile_number;
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