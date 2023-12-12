<?php

namespace app\models;

/**
 * This is the element class for table "config_incoming_call".
 *
 * @property int $id
 * @property int $company_id
 * @property int $phone_id
 * @property string $config
 *
 * @property Company $company
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
