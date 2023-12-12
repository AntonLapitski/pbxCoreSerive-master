<?php

namespace app\src\models;

/**
 * Class Log
 * @package app\src\models
 */
class Log extends \app\models\Log
{
    private array $checkpointList = [];

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->checkpointList = $this->checkpoint;
        $this->checkpoint = $this->checkpoint[count($this->checkpoint) - 1];
    }

    /**
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
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}