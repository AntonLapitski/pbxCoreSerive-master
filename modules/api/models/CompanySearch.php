<?php

namespace app\modules\api\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class CompanySearch
 * @package app\modules\api\models
 */
class CompanySearch extends Company
{
    public string $twilioSid;
    public string $userSid;

    /**
     * @return array
     */
    public function rules() {
        return [
            [['sid', 'twilioSid', 'userSid'], 'safe'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Company::find();
        $query->andFilterWhere(['like', 'sid', $this->sid]);

        if ($this->twilioSid ?? false){
            $query->joinWith('twilio');
            $query->andFilterWhere(['like', 'twilio.sid', $this->twilioSid]);
        }

        if ($this->userSid ?? false){
            $query->joinWith('users');
            $query->andFilterWhere(['like', 'user.sid', $this->userSid]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'id' => SORT_ASC,
            ]]
        ]);
    }
}
