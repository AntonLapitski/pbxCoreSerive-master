<?php

namespace app\src\models;

use app\src\models\twilio\TwilioSettings;

/**
 * Class Twilio
 * Класс Твилио
 *
 * @property TwilioSettings $settings
 * @property Company $company
 * @package app\src\models\twilio\TwilioSettings
 */
class Twilio extends \app\models\Twilio
{
    /**
     * сохранить измененные настройки
     *
     * @return void
     */
    public function afterFind()
    {
        $this->settings = new TwilioSettings($this->settings);
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