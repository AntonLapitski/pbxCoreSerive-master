<?php

namespace app\models;

/**
 * This is the element class for table "local_presents".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property string $config
 *
 * @property Company $company
 */
class LocalPresents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'local_presents';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
