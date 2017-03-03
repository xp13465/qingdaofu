<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServicesAgreement;

/**
 * ServicesAgreementSearch represents the model behind the search form about `app\models\ServicesAgreement`.
 */
class ServicesAgreementSearch extends ServicesAgreement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['desc', 'create_user', 'type', 'contacts', 'tel', 'status', 'validflag'], 'safe'],
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
        $query = ServicesAgreement::find();

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
            // 'create_user' => $this->create_user,
            // 'create_time' => $this->create_time,
            // 'modify_user' => $this->modify_user,
            // 'modify_time' => $this->modify_time,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'validflag', $this->validflag]);

        return $dataProvider;
    }
}
