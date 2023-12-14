<?php

namespace app\models;

/**
 * Сlass Timetable
 * This is the element class for table "timetable".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property array $config
 * @property Company $company
 * @package app\models
 */
class Timetable extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'timetable';
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
            [['config'], 'safe'],
            [['sid'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'company_id' => 'Company ID',
            'sid' => 'Sid',
            'config' => 'Config',
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