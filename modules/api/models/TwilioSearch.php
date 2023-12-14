<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;

/**
 * Class TwilioSearch
 * Модель, управляющая поиском по твилио
 *
 * @property string $companySid
 * @package app\modules\api\models
 */
class TwilioSearch extends Twilio
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
     * поиск по твилио модели
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Twilio::find();
        $query->andFilterWhere(['like', 'twilio.sid', $this->sid]);

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
