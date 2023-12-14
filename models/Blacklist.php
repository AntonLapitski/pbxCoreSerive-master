<?php

namespace app\models;

/**
 * Class Blacklist
 * This is the element class for table "black_list".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property array $config
 * @property Company $company
 * @package app\models
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'black_list';
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
