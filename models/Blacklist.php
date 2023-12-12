<?php

namespace app\models;

/**
 * This is the element class for table "black_list".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property array $config
 *
 * @property Company $company
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'black_list';
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
