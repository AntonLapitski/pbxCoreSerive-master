<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class VoicemailSearch
 * Модель, поиска по голосовой почте
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class VoicemailSearch extends Voicemail
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
     * поиск по юзеру через фильтры
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = User::find();
        $query->andFilterWhere(['like', 'voicemail.sid', $this->sid]);

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
