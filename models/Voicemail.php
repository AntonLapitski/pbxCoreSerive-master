<?php

namespace app\models;

/**
 * Сlass Voicemail
 * This is the element class for table "voicemail_flow".
 *
 * @property int $id
 * @property string $sid
 * @property string $name
 * @property int $company_id
 * @property string $config
 * @property Company $company
 * @package app\models
 */
class Voicemail extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'voicemail_flow';
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
            [['sid', 'name'], 'string', 'max' => 255],
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
            'sid' => 'Sid',
            'name' => 'Name',
            'company_id' => 'Company ID',
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
