<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class LogSearch
 * Модель, управляющая поиском ипо логу
 *
 * @property string $companySid
 * @property string $eventSid
 * @package app\modules\api\models
 */
class LogSearch extends Log
{
    /**
     * айди компании
     *
     * @var string
     */
    public string $companySid;

    /**
     * айди события
     *
     * @var string
     */
    public string $eventSid;

    /**
     * правила валидации
     *
     * @return array
     */
    public function rules() {
        return [
            [['sid', 'companySid', 'eventSid', 'event_sid'], 'safe'],
        ];
    }

    /**
     * поиск по логу
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');
        if ($this->eventSid ?? false)
            $this->event_sid = $this->eventSid;

        $query = Log::find();
        $query->andFilterWhere(['like', 'log.sid', $this->sid]);
        $query->andFilterWhere(['like', 'log.event_sid', $this->event_sid]);

        if ($this->companySid ?? false){
            $query->joinWith('company');
            $query->andFilterWhere(['like', 'company.sid', $this->companySid]);
        }


        return new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'id' => SORT_ASC,
            ],
            ]
        ]);
    }
}

