<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AttendanceGoout;

/**
 * AttendanceGooutSearch represents the model behind the search form about `app\models\AttendanceGoout`.
 */
class AttendanceGooutSearch extends AttendanceGoout
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personnel_id', 'gooutstart', 'gooutend', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['employeeid', 'username', 'department', 'job', 'writedate', 'gooutdate', 'description', 'overtimefile', 'supervisormemo', 'supervisorsignature', 'supervisorsignaturefile', 'supervisordate', 'administrationmemo', 'administrationsignature', 'administrationsignaturefile', 'administrationdate', 'generalmanagermemo', 'generalmanagersignature', 'generalmanagersignaturefile', 'generalmanagerdate', 'validflag','status'], 'safe'],
            [['gooutend_valid', 'totalhour'], 'number'],
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
    public function search($params,$data=[])
    {
        $query = AttendanceGoout::find();
		$query->alias('goout');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		if(isset($data['toexamineid'])&&$data['toexamineid']){
		    $query->andWhere("find_in_set({$data['toexamineid']},toexamineid)");
			$query->andFilterWhere(['status'=>['20','30','40']]);
		}
		
	   
	   if(isset($data['status'])&&$data['status']){
			$query->joinWith(['auditLog'=>function($query){
				$query->andWhere(["auditLog.action_by"=>Yii::$app->user->id,'auditLog.relatype'=>'3']);
			}]);
			$query->groupBy("goout.id");
	   }
	   
	   if(isset($data['uid'])&&$data['uid']){
		   $query->andWhere(["goout.create_by"=>$data['uid'],'validflag'=>'1']);
	   }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
		if($this->status&&$this->status !== '0'){
			 $query->andFilterWhere(['status'=>$this->status]);
		}else if($this->status == '0'){
			$query->andFilterWhere(['status'=>'0']);
		}
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'personnel_id' => $this->personnel_id,
            'writedate' => $this->writedate,
            'gooutdate' => $this->gooutdate,
            'gooutstart' => $this->gooutstart,
            'gooutend' => $this->gooutend,
            'gooutend_valid' => $this->gooutend_valid,
            'totalhour' => $this->totalhour,
            'supervisordate' => $this->supervisordate,
            'administrationdate' => $this->administrationdate,
            'generalmanagerdate' => $this->generalmanagerdate,
            'create_at' => $this->create_at,
            'create_by' => $this->create_by,
            'modify_at' => $this->modify_at,
            'modify_by' => $this->modify_by,
        ]);

        $query->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'overtimefile', $this->overtimefile])
            ->andFilterWhere(['like', 'supervisormemo', $this->supervisormemo])
            ->andFilterWhere(['like', 'supervisorsignature', $this->supervisorsignature])
            ->andFilterWhere(['like', 'supervisorsignaturefile', $this->supervisorsignaturefile])
            ->andFilterWhere(['like', 'administrationmemo', $this->administrationmemo])
            ->andFilterWhere(['like', 'administrationsignature', $this->administrationsignature])
            ->andFilterWhere(['like', 'administrationsignaturefile', $this->administrationsignaturefile])
            ->andFilterWhere(['like', 'generalmanagermemo', $this->generalmanagermemo])
            ->andFilterWhere(['like', 'generalmanagersignature', $this->generalmanagersignature])
            ->andFilterWhere(['like', 'generalmanagersignaturefile', $this->generalmanagersignaturefile])
            ->andFilterWhere(['like', 'validflag', $this->validflag]);

        return $dataProvider;
    }
}
