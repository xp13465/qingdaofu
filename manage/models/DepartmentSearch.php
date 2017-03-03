<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;

/**
 * DepartmentSearch represents the model behind the search form about `app\models\Department`.
 */
class DepartmentSearch extends Department
{
	public $created_at = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'status', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['name', 'description', 'validflag', 'created_at'], 'safe'],
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
        $query = Department::find();
		$query->alias('department');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
	   
        $query->andFilterWhere([
            'department.id' => $this->id,
            'department.pid' => $this->pid,
            'department.organization_id' => $this->organization_id,
            'department.status' => $this->status,
            'department.updated_at' => $this->updated_at,
            'department.created_by' => $this->created_by,
            'department.modifyd_by' => $this->modifyd_by,
        ]);
		$startTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'00:00');
		$endTime = isset($this->created_at)&&!$this->created_at?$this->created_at:strtotime($this->created_at .'23:59');
	    $query->andFilterWhere(['between','department.created_at',$startTime,$endTime]);

        $query->andFilterWhere(['like', 'department.name', $this->name])
            ->andFilterWhere(['like', 'department.description', $this->description])
            ->andFilterWhere(['like', 'department.validflag', $this->validflag]);
		
		 $query->joinWith(['organization']);
        return $dataProvider;
    }
}
