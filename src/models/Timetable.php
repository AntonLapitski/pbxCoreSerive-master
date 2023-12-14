<?php

namespace app\src\models;
/**
 * Class Timetable
 * Класс расписание
 *
 * @property array $scheme
 * @property array $settings
 * @package app\src\models
 */
class Timetable extends \app\models\Timetable
{
    /**
     * схема
     *
     * @var array
     */
    public array $scheme;

    /**
     * настройки
     *
     * @var array
     */
    public array $settings = [];

    /**
     * поменять схему и настройки и сохранить в бд
     *
     * @return void
     */
    public function afterFind()
    {
        $this->scheme = $this->config['scheme'] ?? $this->config;
        if(isset($this->config['settings']))
            $this->settings = $this->config['settings'];

        parent::afterFind();
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