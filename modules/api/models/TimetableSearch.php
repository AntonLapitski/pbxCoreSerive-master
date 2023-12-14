<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class TimetableSearch
 * Модель, которая ищет по расписанию
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class TimetableSearch extends Timetable
{
    /**
     * айди компании
     *
     * @var string
     */
    public string $companySid;

    /**
     * правила валидации
     *
     * @return array
     */
    public function rules() {
        return [
            [['sid', 'companySid'], 'safe'],
        ];
    }

    /**
     * поиск по расписанию
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Timetable::find();
        $query->andFilterWhere(['like', 'timetable.sid', $this->sid]);

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
