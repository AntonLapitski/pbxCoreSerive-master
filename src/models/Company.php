<?php

namespace app\src\models;

/**
 * @property Log[] $logs
 * @property Twilio $twilio
 * @property User[] $users
 * @property IncomingFlow $callFlow
 */
class Company extends \app\models\Company
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallFlow(): \yii\db\ActiveQuery
    {
        return $this->hasOne(IncomingFlow::class, ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallLogs(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Log::class, ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTwilio(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Twilio::class, ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers(): \yii\db\ActiveQuery
    {
        return $this->hasMany(User::class, ['company_id' => 'id']);
    }

    /**
     * @param $condition
     * @return array
     */
    public function getUser($condition): array|\yii\db\ActiveRecord|null
    {
        return $this->hasOne(User::class, ['company_id' => 'id'])
            ->where($condition)->one();
    }
}