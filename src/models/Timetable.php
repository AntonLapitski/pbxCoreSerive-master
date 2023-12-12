<?php

namespace app\src\models;
/**
 * @property array $scheme
 * @property array $settings
 */
class Timetable extends \app\models\Timetable
{
    public array $scheme;
    public array $settings = [];

    /**
     *
     */
    public function afterFind()
    {
        $this->scheme = $this->config['scheme'] ?? $this->config;
        if(isset($this->config['settings']))
            $this->settings = $this->config['settings'];

        parent::afterFind();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}