<?php

namespace app\models;

/**
 * This is the element class for table "incoming_flow".
 *
 * @property int $id
 * @property string $sid
 * @property int $company_id
 * @property string $name
 * @property array $config
 *
 * @property Company $company
 */
class IncomingFlow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incoming_flow';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['name', 'sid'], 'string'],
            [['main', 'refer', 'out', 'voicemail', 'resp', 'timetable'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'company_id' => 'Company Id',
            'sid' => 'Log Sid',
            'name' => 'Name',
            'main' => 'Main',
            'refer' => 'Refer',
            'out' => 'Outgoing',
            'voicemail' => 'Voicemail',
            'timetable' => 'Timetable',
            'resp' => 'Responsible'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
