<?php

namespace app\src\models;

use app\src\models\twilio\TwilioSettings;

/**
 * @property TwilioSettings $settings
 * @property Company $company
 */
class Twilio extends \app\models\Twilio
{
    /**
     *
     */
    public function afterFind()
    {
        $this->settings = new TwilioSettings($this->settings);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}