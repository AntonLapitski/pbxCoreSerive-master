<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class TimetableSearch
 * @package app\modules\api\models
 */
class TimetableSearch extends Timetable
{
    public string $companySid;

    /**
     * @return array
     */
    public function rules() {
        return [
            [['sid', 'companySid'], 'safe'],
        ];
    }

    /**
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
