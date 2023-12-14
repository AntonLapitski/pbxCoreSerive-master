<?php

namespace app\models;

/**
 * Сlass IncomingFlow
 * This is the element class for table "incoming_flow".
 *
 * @property int $id
 * @property string $sid
 * @property int $company_id
 * @property string $name
 * @property array $config
 * @property Company $company
 * @package app\models
 */
class IncomingFlow extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'incoming_flow';
    }

    /**
     * правила валидации
     *
     * @return array
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
     * названия полей формы
     *
     * @return array
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
     * получить компанию
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
