<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HousingPrice;

/**
 * HousingPriceSearch represents the model behind the search form about `app\models\HousingPrice`.
 */
class HousingPriceSearch extends HousingPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid'], 'integer'],
            [['city', 'district', 'address'], 'safe'],
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
        $query = HousingPrice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_time' => SORT_DESC,            
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

        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            // 'size' => $this->size,
            // 'create_time' => $this->create_time,
            // 'totalPrice' => $this->totalPrice,
            // 'userid' => $this->userid,
        ]);
		$query->joinWith( 
		[
			'userinfo' => function($query) {
				$query->andFilterWhere(["like","username","{$this->userid}"]); 
			},
		]);
        $query->andFilterWhere(['like', 'serviceCode', $this->serviceCode])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'address', $this->address]);
            // ->andFilterWhere(['like', 'buildingNumber', $this->buildingNumber])
            // ->andFilterWhere(['like', 'unitNumber', $this->unitNumber])
            // ->andFilterWhere(['like', 'floor', $this->floor])
            // ->andFilterWhere(['like', 'maxFloor', $this->maxFloor])
            // ->andFilterWhere(['like', 'ip', $this->ip])
            // ->andFilterWhere(['like', 'code', $this->code])
            // ->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
