<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Task;

/**
 * TaskSearch represents the model behind the search form about `app\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'project_id', 'paid'], 'integer'],
            [['description', 'start', 'all_time', 'notes', 'done_date', 'agreed_price'], 'safe'],
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
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$params || (count($params) == 1 && isset($params['_pjax']))) {
            $query->andWhere(['paid' => 0]);
            $query->orWhere(['status' => Task::TASK_STATUS_IN_WORK]);
        }
        \Yii::info($params, 'test');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'start' => $this->start,
            'all_time' => $this->all_time,
            'status' => $this->status,
            'project_id' => $this->project_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'done_date', $this->done_date]);

        return $dataProvider;
    }
}
