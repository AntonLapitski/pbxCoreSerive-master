<?php

namespace app\models;

/**
 * Сlass CallbackFlow
 * This is the element class for table "callback_flow".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property string $name
 * @property string $config
 * @property Company $company
 * @package app\models
 */
class CallbackFlow extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'callback_flow';
    }

    /**
     * правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['company_id', 'sid'], 'required'],
            [['company_id'], 'integer'],
            [['config'], 'safe'],
            [['sid', 'name'], 'string', 'max' => 255],
            [['sid'], 'unique'],
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
            'name' => 'Name',
            'config' => 'Config',
        ];
    }

    /**
     * получить команию по айди из бд
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
