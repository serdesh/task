<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Boss;

/**
 * BossSearch represents the model behind the search form about `app\models\Boss`.
 */
class BossSearch extends Boss
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'messenger_id'], 'integer'],
            [['name', 'messenger_number', 'notes'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Boss::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'messenger_id' => $this->messenger_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'messenger_number', $this->messenger_number])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
