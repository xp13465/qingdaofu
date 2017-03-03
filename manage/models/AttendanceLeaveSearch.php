<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AttendanceLeave;

/**
 * AttendanceLeaveSearch represents the model behind the search form about `app\models\AttendanceLeave`.
 */
class AttendanceLeaveSearch extends AttendanceLeave
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personnel_id', 'leavestart', 'leaveend', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['employeeid', 'username', 'department', 'job', 'writedate', 'leavetype', 'description', 'leavefile', 'supervisormemo', 'supervisorsignature', 'supervisorsignaturefile', 'supervisordate', 'administrationmemo', 'administrationsignature', 'administrationsignaturefile', 'administrationdate', 'generalmanagermemo', 'generalmanagersignature', 'generalmanagersignaturefile', 'generalmanagerdate', 'validflag','status'], 'safe'],
            [['leaveday', 'leavehour', 'totalhour'], 'number'],
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
        $query = AttendanceLeave::find();
		$query->alias('leave');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		if(isset($data['toexamineid'])&&$data['toexamineid']){
		    $query->andWhere("find_in_set({$data['toexamineid']},toexamineid)");
			$query->andFilterWhere(['status'=>['20','30','40']]);
		}
		
	   
	   if(isset($data['status'])&&$data['status']){
			$query->joinWith(['auditLog'=>function($query){
				$query->andWhere(["auditLog.action_by"=>Yii::$app->user->id,'auditLog.relatype'=>'2']);
			}]);
			$query->groupBy("leave.id");
	   }
	   
	   if(isset($data['uid'])&&$data['uid']){
		   $query->andWhere(["leave.create_by"=>$data['uid'],'validflag'=>'1']);
	   }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		if($this->status&&$this->status!=='0'){
			// $status = explode(',',$this->status);
			 $query->andFilterWhere(['status'=>$this->status]);
		}else if($this->status=='0'){
			$query->andFilterWhere(['status'=>'0']);
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'writedate' => $this->writedate,
            'leavestart' => $this->leavestart,
            'leaveend' => $this->leaveend,
            'leaveday' => $this->leaveday,
            'leavehour' => $this->leavehour,
            'totalhour' => $this->totalhour,
            'supervisordate' => $this->supervisordate,
            'administrationdate' => $this->administrationdate,
            'generalmanagerdate' => $this->generalmanagerdate,
            'create_at' => $this->create_at,
            'modify_at' => $this->modify_at,
            'modify_by' => $this->modify_by,
        ]);

        $query->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'leavetype', $this->leavetype])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'leavefile', $this->leavefile])
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
