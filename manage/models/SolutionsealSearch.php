<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Solutionseal;

/**
 * SolutionsealSearch represents the model behind the search form about `app\models\Solutionseal`.
 */
class SolutionsealSearch extends Solutionseal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['solutionsealid', 'productid', 'personnel_id', 'overdue', 'province_id', 'city_id', 'district_id', 'browsenumber', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['number', 'category', 'category_other', 'entrust', 'entrust_other', 'type', 'status', 'validflag'], 'safe'],
            [['account', 'typenum'], 'number'],
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
    public function search($params,$create_by ="",$status="",$isGenjin=false)
    {
        $query = Solutionseal::find()->alias("solutionseal");

        // add conditions that should always apply here
		// $query->andFilterWhere([
				// 'validflag' => 1,
			// ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);
 
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($create_by){
			 $query->andFilterWhere([
				'create_by' => $create_by,
			]);
		}
		
		if($status === '0' || $status){
			$query->andFilterWhere(['solutionseal.status'=>$status]);
		}else{
			$query->andFilterWhere([
				'solutionseal.status' => $this->status?explode(",",$this->status):[],
			]);
		}
		if($isGenjin){
			$query->joinWith(['auditLog'=>function($query){
				$query->andWhere(["audit.action_by"=>Yii::$app->user->id]);
			}]);
			$query->groupBy("solutionseal.solutionsealid");
		}
        // grid filtering conditions
        $query->andFilterWhere([
            
            'solutionseal.personnel_id' => $this->personnel_id,
            // 'account' => $this->account,
            // 'typenum' => $this->typenum,
            // 'overdue' => $this->overdue,
            'solutionseal.province_id' => $this->province_id,
            'solutionseal.city_id' => $this->city_id,
			'solutionseal.district_id' => $this->district_id,
        ]);
		if(isset($params["startMonth"])&&$params["startMonth"]){
			$time1 = strtotime(date("Y-{$params["startMonth"]}-01"));
			$time2 =strtotime("+1 month",$time1)-1;
			$query->andWhere(['between', 'create_at', $time1, $time2]);
		}elseif(isset($params["closedMonth"])&&$params["closedMonth"]){
			$time1 = strtotime(date("Y-{$params["closedMonth"]}-01"));
			$time2 =strtotime("+1 month",$time1)-1;
			$query->andWhere(['between', 'closeddate', $time1, $time2]);
			// $query->andWhere(['between', 'id', 1, 10]);
		}
        $query->andFilterWhere(['like', 'solutionseal.number', $this->number])
            ->andFilterWhere(['like', 'solutionseal.category', $this->category])
            ->andFilterWhere(['like', 'solutionseal.category_other', $this->category_other])
            ->andFilterWhere(['like', 'solutionseal.entrust', $this->entrust])
            ->andFilterWhere(['like', 'solutionseal.entrust_other', $this->entrust_other])
            ->andFilterWhere(['like', 'solutionseal.type', $this->type]);

        return $dataProvider;
    }
}
