<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServicesInstrument;

/**
 * ServicesInstrumentSearch represents the model behind the search form about `app\models\ServicesInstrument`.
 */
class ServicesInstrumentSearch extends ServicesInstrument
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['province_id', 'create_user', 'city_id', 'district_id','address', 'type', 'desc', 'plaintiff', 'defendant', 'contacts', 'tel', 'status'], 'safe'],
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
        $query = ServicesInstrument::find();

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
			'provincename' => function($query) {
				$query->andFilterWhere(["like","province","{$this->province_id}"]);
			},
			'cityname' => function($query) {
				$query->andFilterWhere(["like","city","{$this->city_id}"]);
			},
			'areaname' => function($query) {
				$query->andFilterWhere(["like","area","{$this->district_id}"]);
			},
		]);
		
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'province_id' => $this->province_id,
            // 'city_id' => $this->city_id,
            // 'district_id' => $this->district_id,
            // 'create_user' => $this->create_user,
            // 'create_time' => $this->create_time,
            // 'modify_user' => $this->modify_user,
            // 'modify_time' => $this->modify_time,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'plaintiff', $this->plaintiff])
            ->andFilterWhere(['like', 'defendant', $this->defendant])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
