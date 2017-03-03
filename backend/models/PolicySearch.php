<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Policy;

/**
 * PolicySearch represents the model behind the search form about `app\models\Policy`.
 */
class PolicySearch extends Policy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_pid', 'area_id', 'fayuan_id', 'type', 'status', 'shenhe_status',  'updated_at', 'updated_by'], 'integer'],
            [['orderid', 'area_name', 'phone', 'address', 'fayuan_address', 'qisu', 'caichan', 'zhengju', 'anjian', 'fayuan_name', 'anhao', 'created_by'], 'safe'],
            // [['money'], 'number'],
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
        $query = Policy::find()->alias("policy");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => '10',
			]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		$query->joinWith( 
		[
			'createuser' => function($query) {
				$query->andFilterWhere(["like","username","{$this->created_by}"]); 
			},
		]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'area_pid' => $this->area_pid,
            'area_id' => $this->area_id,
            'fayuan_id' => $this->fayuan_id,
            'type' => $this->type,
            'money' => $this->money,
            'policy.status' => $this->status,
            'shenhe_status' => $this->shenhe_status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            // 'createuser.username' => $this->created_by,
            // 'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'orderid', $this->orderid])
            ->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'fayuan_address', $this->fayuan_address])
            ->andFilterWhere(['like', 'qisu', $this->qisu])
            ->andFilterWhere(['like', 'caichan', $this->caichan])
            ->andFilterWhere(['like', 'zhengju', $this->zhengju])
            ->andFilterWhere(['like', 'anjian', $this->anjian])
            ->andFilterWhere(['like', 'fayuan_name', $this->fayuan_name])
            ->andFilterWhere(['like', 'anhao', $this->anhao]);
		
		
        return $dataProvider;
    }
}
