<?php

namespace app\src\models;


use app\src\models\twilio\UserSettings;
/**
 * Class User
 * Модель полдьзователь
 *
 * @property UserSettings $settings
 * @property string $number
 * @package app\src\models\twilio\UserSettings
 */
class User extends \app\models\User
{
    /**
     * номер
     *
     * @return void
     */
    public string $number;

    /**
     * поменяем сеттинги и номер и сохраним
     *
     * @return void
     */
    public function afterFind()
    {
        $this->settings = new UserSettings($this->settings['direct_call_settings'] ?? []);
        if ($this->mobile_number ?? false)
            $this->number = $this->mobile_number;
    }

    /**
     * найти компанию
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