<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configuration;

/**
 * BracketsConfigurationSearch represents the model behind the search form of `app\models\BracketsConfiguration`.
 */
class ConfigurationSearch extends Configuration
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'yBracketsStart', 'team_w', 'team_h', 'noTeams'], 'integer'],
            [['yTeamSeparationFactor', 'xTeamSeparationFactor'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Configuration::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'yBracketsStart' => $this->yBracketsStart,
            'yTeamSeparationFactor' => $this->yTeamSeparationFactor,
            'team_w' => $this->team_w,
            'team_h' => $this->team_h,
            'noTeams' => $this->noTeams,
            'xTeamSeparationFactor' => $this->xTeamSeparationFactor,
        ]);

        return $dataProvider;
    }
}
