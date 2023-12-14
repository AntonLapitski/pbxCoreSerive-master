<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class IncomingFlowSearch
 * Модель, ищущая по потоку
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class IncomingFlowSearch extends IncomingFlow
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
     * поиск по входящему потоку
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = IncomingFlow::find();
        $query->andFilterWhere(['like', 'incoming_flow.sid', $this->sid]);

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
