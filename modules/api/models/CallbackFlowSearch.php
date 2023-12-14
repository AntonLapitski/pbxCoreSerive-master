<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class CallbackFlowSearch
 * Модель, управляющая черновым листом
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class CallbackFlowSearch extends CallbackFlow
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
     * поиск по CallbackFlow
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = CallbackFlow::find();
        $query->andFilterWhere(['like', 'callback_flow.sid', $this->sid]);

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
