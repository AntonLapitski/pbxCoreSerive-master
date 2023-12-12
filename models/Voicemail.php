<?php

namespace app\models;

/**
 * This is the element class for table "voicemail_flow".
 *
 * @property int $id
 * @property string $sid
 * @property string $name
 * @property int $company_id
 * @property string $config
 *
 * @property Company $company
 */
class Voicemail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'voicemail_flow';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
