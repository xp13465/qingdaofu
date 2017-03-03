<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Protectright;

/**
 * ProtectrightSearch represents the model behind the search form about `app\models\Protectright`.
 */
class ProtectrightSearch extends Protectright
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_pid', 'area_id', 'fayuan_id', 'category', 'type', 'step', 'status',  'modify_user', 'modify_time'], 'integer'],
            [['number', 'fayuan_name', 'address', 'fayuan_address', 'name', 'cardNo', 'phone', 'qisu', 'caichan', 'zhengju', 'anjian', 'jietiao', 'yinhang', 'danbao', 'other', 'create_user'], 'safe'],
           
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
        $query = Protectright::find()->alias("protectright");

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
				$query->andFilterWhere(["like","username","{$this->create_user}"]);
			},
		]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'area_pid' => $this->area_pid,
            'area_id' => $this->area_id,
            'fayuan_id' => $this->fayuan_id,
            'category' => $this->category,
            'type' => $this->type,
            'account' => $this->account,
            'step' => $this->step,
            'protectright.status' => $this->status,
            // 'create_user' => $this->create_user,
            // 'create_time' => $this->create_time,
            // 'modify_user' => $this->modify_user,
            // 'modify_time' => $this->modify_time,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'fayuan_name', $this->fayuan_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'fayuan_address', $this->fayuan_address])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cardNo', $this->cardNo])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'qisu', $this->qisu])
            ->andFilterWhere(['like', 'caichan', $this->caichan])
            ->andFilterWhere(['like', 'zhengju', $this->zhengju])
            ->andFilterWhere(['like', 'anjian', $this->anjian])
            ->andFilterWhere(['like', 'jietiao', $this->jietiao])
            ->andFilterWhere(['like', 'yinhang', $this->yinhang])
            ->andFilterWhere(['like', 'danbao', $this->danbao])
            ->andFilterWhere(['like', 'other', $this->other]);
			// $query->orderBy("id desc"); 
        return $dataProvider;
    }
}
