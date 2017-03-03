<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AttendanceCheckinReports;

/**
 * AttendanceCheckinReportsSearch represents the model behind the search form about `app\models\AttendanceCheckinReports`.
 */
class AttendanceCheckinReportsSearch extends AttendanceCheckinReports
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personnel_id', 'latetime_valid', 'status', 'updatetime', 'updateuser', 'dayofweekkey', 'year', 'month', 'overtimeids', 'leaveids', 'gooutids'], 'integer'],
            [['employeeid', 'username', 'signdate', 'dayofweek', 'signtime1', 'signtime2', 'signstatus', 'signstatus_valid', 'memo', 'modify_at'], 'safe'],
            [['timediff', 'latetime', 'overtime', 'overtime_valid'], 'number'],
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
        $query = AttendanceCheckinReports::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'signdate' => SORT_ASC,            
				]
			],
			'pagination' => [
                'pagesize' => '20',
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
            'id' => $this->id,
            'personnel_id' => $this->personnel_id,
            'signdate' => $this->signdate,
            // 'signtime1' => $this->signtime1,
            // 'signtime2' => $this->signtime2,
            // 'timediff' => $this->timediff,
            // 'latetime' => $this->latetime,
            // 'latetime_valid' => $this->latetime_valid,
            // 'overtime' => $this->overtime,
            // 'overtime_valid' => $this->overtime_valid,
            'status' => $this->status,
            // 'updatetime' => $this->updatetime,
            // 'updateuser' => $this->updateuser,
            // 'modify_at' => $this->modify_at,
            'dayofweekkey' => $this->dayofweekkey,
            'year' => $this->year,
            'month' => $this->month,
        ]);
		if($this->dayofweek){
			if($this->dayofweek=="work"){
				$query->andFilterWhere([
					'dayofweekkey' => [1,2,3,4,5],
				]);
			}else{
				$query->andFilterWhere([
					'dayofweekkey' => $this->dayofweek,
				]);
			}
		}
		if($this->signstatus){
			if($this->signstatus=="无"){
				$query->andWhere("(signstatus_valid ='' and signstatus = '') ");
			}else{
				$query->andWhere("((signstatus_valid ='' and signstatus = '{$this->signstatus}') or signstatus_valid = '{$this->signstatus}')");
			}
		}
		if(in_array($this->leaveids,[1,2])){
			if($this->leaveids=="2"){
				$query->andWhere("leaveids =''");
			}elseif($this->leaveids=="1"){
				$query->andWhere("leaveids <>''");
			}
		}
		if(in_array($this->gooutids,[1,2])){
			if($this->gooutids=="2"){
				$query->andWhere("gooutids =''");
			}elseif($this->gooutids=="1"){
				$query->andWhere("gooutids <>''");
			}
		}
		if(in_array($this->overtimeids,[1,2])){
			if($this->overtimeids=="2"){
				$query->andWhere("overtimeids =''");
			}elseif($this->overtimeids=="1"){
				$query->andWhere("overtimeids <>''");
			}
		}
		
        $query->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'username', $this->username]);
            // ->andFilterWhere(['=', 'dayofweekkey', $this->dayofweek])
			 // ->andFilterWhere(['=', '(case when signstatus_valid then signstatus_valid else signstatus)', $this->signstatus]);
            // ->andFilterWhere(['like', 'signstatus', $this->signstatus]);
            // ->andFilterWhere(['like', 'signstatus_valid', $this->signstatus_valid])
            // ->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    } 
	
	public function statistictalSearch($params)
    {
        $query = AttendanceCheckinReports::find();
		$query->groupby("year,month,personnel_id");
		$query->select(["year"=>"year","month"=>"month","personnel_id"=>"personnel_id","username"=>"username","overtime"=>"sum(overtime)","overtime_valid"=>"sum(overtime_valid)"]);
  
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'signdate' => SORT_ASC,            
				]
			],
			'pagination' => [
                'pagesize' => '20',
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
            'id' => $this->id,
            'personnel_id' => $this->personnel_id,
            'signdate' => $this->signdate,
            'signtime1' => $this->signtime1,
            'signtime2' => $this->signtime2,
            'timediff' => $this->timediff,
            'latetime' => $this->latetime,
            'latetime_valid' => $this->latetime_valid,
            'overtime' => $this->overtime,
            'overtime_valid' => $this->overtime_valid,
            'status' => $this->status,
            'updatetime' => $this->updatetime,
            'updateuser' => $this->updateuser,
            'modify_at' => $this->modify_at,
            'dayofweekkey' => $this->dayofweekkey,
            'year' => $this->year,
            'month' => $this->month,
        ]);
		if($this->signstatus){
			$query->andWhere("((signstatus_valid ='' and signstatus = '{$this->signstatus}') or signstatus_valid = '{$this->signstatus}')");
		}
        $query->andFilterWhere(['like', 'employeeid', $this->employeeid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'dayofweek', $this->dayofweek])
            // ->andFilterWhere(['like', 'signstatus', $this->signstatus])
            ->andFilterWhere(['like', 'signstatus_valid', $this->signstatus_valid])
            ->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    }
}
