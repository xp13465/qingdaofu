<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Organization;


/**
 * OrganizationSearch represents the model behind the search form about `app\models\Organization`.
 */
class OrganizationSearch extends Organization
{
	public $created_at = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_id', 'status', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['organization_name', 'organization_full_name', 'office', 'validflag', 'created_at'], 'safe'],
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
        $query = Organization::find();

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
            'organization_id' => $this->organization_id,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'modifyd_by' => $this->modifyd_by,
        ]);
		$startTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'00:00');
		$endTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'23:59');
	    $query->andFilterWhere(['between','created_at',$startTime,$endTime]);

        $query->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'organization_full_name', $this->organization_full_name])
            ->andFilterWhere(['like', 'office', $this->office])
            ->andFilterWhere(['like', 'validflag', $this->validflag]);

        return $dataProvider;
    }
	

}
