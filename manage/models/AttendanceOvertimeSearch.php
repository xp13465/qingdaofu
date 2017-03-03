<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AttendanceOvertime;

/**
 * AttendanceOvertimeSearch represents the model behind the search form about `app\models\AttendanceOvertime`.
 */
class AttendanceOvertimeSearch extends AttendanceOvertime
{
	public $overtimestart = '';
	public $overtimeend = '';
	public $applyStatus='';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personnel_id', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['employeeid', 'username', 'department', 'job', 'writedate', 'overtimetype', 'description', 'overtimefile', 'supervisormemo', 'supervisorsignature', 'supervisorsignaturefile', 'supervisordate', 'administrationmemo', 'administrationsignature', 'administrationsignaturefile', 'administrationdate', 'generalmanagermemo', 'generalmanagersignature', 'generalmanagersignaturefile', 'generalmanagerdate', 'validflag','status'], 'safe'],
            [['overtimeday', 'overtimehour', 'totalhour'], 'number'],
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
        $query = AttendanceOvertime::find();
		$query->alias('overtime');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
	   if(isset($data['toexamineid'])&&$data['toexamineid']){
		    $query->andWhere("find_in_set({$data['toexamineid']},toexamineid)");
			$query->andFilterWhere(['status'=>['20','30','40']]);
		}
		
	   
	   if(isset($data['status'])&&$data['status']){
			$query->joinWith(['auditLog'=>function($query){
				$query->andWhere(["auditLog.action_by"=>Yii::$app->user->id,'auditLog.relatype'=>'4']);
			}]);
			$query->groupBy("overtime.id");
	   }
	   
	    if(isset($data['uid'])&&$data['uid']){
		   $query->andWhere(["overtime.create_by"=>$data['uid'],'validflag'=>'1']);
	   }

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
		if($this->status&&$this->status!=='1'){
			// $status = explode(',',$this->status);
			 $query->andFilterWhere(['status'=>$this->status]);
		}else if($this->status=='1'){
			$query->andFilterWhere(['status'=>'0']);
		}
		
		// var_dump($this->status);die;

        $query->andFilterWhere([
            'id' => $this->id,
            'personnel_id' => $this->personnel_id,
            'writedate' => $this->writedate,
            'overtimeday' => $this->overtimeday,
            'overtimehour' => $this->overtimehour,
            'totalhour' => $this->totalhour,
            'supervisordate' => $this->supervisordate,
            'administrationdate' => $this->administrationdate,
            'generalmanagerdate' => $this->generalmanagerdate,
            'create_at' => $this->create_at,
            'create_by' => $this->create_by,
            'modify_at' => $this->modify_at,
            'modify_by' => $this->modify_by,
        ]);
		$startTime = isset($this->overtimestart)&&!$this->overtimestart?$this->overtimestart:strtotime($this->overtimestart .'00:00');
		$endTime = isset($this->overtimestart)&&!$this->overtimestart?$this->overtimestart:strtotime($this->overtimestart .'23:59');
	    $query->andFilterWhere(['between','overtimestart',$startTime,$endTime]);
		
		$startTimes = isset($this->overtimeend)&&!$this->overtimeend?$this->overtimeend:strtotime($this->overtimeend .'00:00');
		$endTimes = isset($this->overtimeend)&&!$this->overtimeend?$this->overtimeend:strtotime($this->overtimeend .'23:59');
	    $query->andFilterWhere(['between','overtimeend',$startTimes,$endTimes]);
		
        $query->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'overtimetype', $this->overtimetype])
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
	
	// public function process(){
		// $data = $this->joinWith(['auditLog']);
	// }
	
}
