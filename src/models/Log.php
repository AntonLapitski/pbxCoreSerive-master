<?php

namespace app\src\models;

/**
 * Class Log
 * @property array $checkpointList
 * @package app\src\models
 */
class Log extends \app\models\Log
{
    /**
     * проверочный лист
     *
     * @var array
     * */
    private array $checkpointList = [];

    /**
     * сохраняем новый проверочный лист
     *
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->checkpointList = $this->checkpoint;
        $this->checkpoint = $this->checkpoint[count($this->checkpoint) - 1];
    }

    /**
     * перед сохранением
     *
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if ($this->isNewRecord)
            $this->sid = 'LG' . md5($this->event_sid . time() . rand(1000, 9999));

        $this->checkpointList[] = $this->checkpoint;
        $this->checkpoint = $this->checkpointList;
        return parent::beforeSave($insert);
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