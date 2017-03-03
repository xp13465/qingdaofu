<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Property;

/**
 * PropertySearch represents the model behind the search form about `app\models\Property`.
 */
class PropertySearch extends Property
{
	public $start;
	public $end;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cid', 'uptime', 'status'], 'integer'],
            [['province', 'city', 'address', 'phone', 'orderId', 'uid', 'kdorder', 'start', 'end', 'time'], 'safe'],
            [['money'], 'number'],
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
		// var_dump($params);
		
        $query = Property::find()->alias("property")->select("property.*");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'time' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => '10',
			]
        ]);
		$this->start=isset($params['start'])?$params['start']:'';
		$this->end=isset($params['end'])?$params['end']:'';
		
        $this->load($params);
		
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$query->joinWith( 
		[
			// 'username' => function($query) {
				// $query->andFilterWhere(["like","username","{$this->uid}"]); 
			// },
			'provincename' => function($query) {
				$query->andFilterWhere(["like","provincename.name","{$this->province}"]); 
			},
			'cityname' => function($query) {
				$query->andFilterWhere(["like","cityname.name",$this->city]); 
			},
			'expressdata' => function($query) {
				$query->andFilterWhere(["like","expressdata.orderId",$this->kdorder]); 
				if($this->status==6){
					$query->andFilterWhere([">","expressdata.id",0]); 
				}else if($this->status==7){
					$query->andFilterWhere([">","expressdata.orderId",0]); 
				}
			},
		]);
		
		// if(isset($params['start'])&&$params['start']){
			
			if($start=strtotime($this->start)){
				$query->andFilterWhere([">=","property.time",$start]); 
			}
		// }
		
		// if(isset($params['end'])&&$params['end']){
			
			if($end=strtotime($this->end)){
				$query->andFilterWhere(["<=","property.time",$end+86400]); 
			}
		// }
		// date_format(date,'%Y-%m-%d'
        // grid filtering conditions
        $query->andFilterWhere([
            'property.id' => $this->id,
            'property.uid' => $this->uid,
            'property.cid' => $this->cid,
            'property.money' => $this->money,
            // 'property.time' => $this->time,
            // 'property.uptime' => $this->uptime, 
        ]);
		if(in_array($this->status,[2,6,7])){
			$query->andFilterWhere([
				'property.status' => 2,
			]);
		}else{
			$query->andFilterWhere([
				'property.status' => $this->status,
			]);
		}
		
        $query
		// ->andFilterWhere(['like', 'province', $this->province])
            // ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'property.address', $this->address])
            ->andFilterWhere(['like', 'property.phone', $this->phone])
            ->andFilterWhere(['like', 'property.orderId', $this->orderId]);

        return $dataProvider;
    }
}
