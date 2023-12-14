<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class LocalPresentsSearch
 * Модель, поиска по лакальным представлениям
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class LocalPresentsSearch extends LocalPresents
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
     * Поиск полокальным представлениям
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = LocalPresents::find();
        $query->andFilterWhere(['like', 'local_presents.sid', $this->sid]);

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

