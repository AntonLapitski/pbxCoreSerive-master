<?php

namespace app\models;

/**
 * This is the element class for table "company".
 *
 * @method
 *
 * @property int $id
 * @property string $sid
 * @property string $name
 * @property string $time_zone
 * @property string $country_code
 *
 * @property IncomingFlow $callFlow
 * @property Log[] $logs
 * @property Twilio $twilio
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_code', 'time_zone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country_code' => 'Country Code',
            'time_zone' => 'Time Zone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallFlow(): \yii\db\ActiveQuery
    {
        return $this->hasOne(IncomingFlow::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallLogs(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Log::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTwilio(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Twilio::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers(): \yii\db\ActiveQuery
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }
}
