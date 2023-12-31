<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class BlacklistSearch
 * Модель, управляющая черновым листом
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class BlacklistSearch extends Blacklist
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
     * поиск по черновому листу
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Blacklist::find();
        $query->andFilterWhere(['like', 'sid', $this->sid]);

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
