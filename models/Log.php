<?php

namespace app\models;

/**
 * Сlass Log
 * This is the element class for table "log".
 *
 * @property int $id
 * @property int $company_id
 * @property string $sid
 * @property string $event_sid
 * @property string $event_type
 * @property string $status
 * @property int $time
 * @property int $duration
 * @property string $direction
 * @property string $record_url
 * @property string $result
 * @property array $checkpoint
 * @property array $integration_data
 * @property Company $company
 * @package app\models
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * поулчить название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['company_id', 'time', 'duration'], 'integer'],
            [['sid', 'event_sid', 'event_type', 'status', 'direction', 'result'], 'string', 'max' => 255],
            [['record_url'], 'string'],
            [['checkpoint', 'integration_data'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
