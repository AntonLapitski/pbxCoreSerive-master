<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class CallbackFlowSearch
 * @package app\modules\api\models
 */
class CallbackFlowSearch extends CallbackFlow
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
