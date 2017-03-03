<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AttendanceCheckinReports]].
 *
 * @see AttendanceCheckinReports
 */
class AttendanceCheckinReportsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AttendanceCheckinReports[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AttendanceCheckinReports|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	
	public function checkInEvent($start,$end,$userid=""){
		$query = $this->where(["and",[">=","signdate",$start],["<=","signdate",$end]])->asArray();
		$returnEvent = [];
		
		if($userid){
			$query->andWhere("signstatus != '' or signstatus_valid != ''");
			$query->andWhere(["personnel_id"=>$userid]);
			$data = $query->all();
			//var_dump($data);
			foreach($data as $val){
				$signstatus_valid = $val['signstatus_valid']?:$val['signstatus'];
				$returnEvent[]=[
					'title' => $signstatus_valid,
					"eventType"=>"checkin",
					"id"	=> $val["id"],
					'start'	=> $val["signtime1"]!="0000-00-00 00:00:00"?$val["signtime1"]:$val["signdate"],
					'end' 	=> $val["signtime2"]!="0000-00-00 00:00:00"?$val["signtime2"]:'',
					'signdate'=> $val['signdate'],
					'desc' 	=> "",
					'sort'  =>"0",
					'overlap'=> false,
					'color'=>$signstatus_valid?($signstatus_valid!="正常"?$signstatus_valid=="迟到补齐"?"#0099FF":"#FF6600":"#00CC00"):"#FF0000",
				];
			}
		}else{
			$query->select([
				"signstatus_validcount"=>"count((case when signstatus_valid!='' then signstatus_valid else signstatus end ))",
				"signdate","signstatus_valid"=>"(case when signstatus_valid!='' then signstatus_valid else signstatus end )"
			
			]);
			$query->groupby("signdate,(case when signstatus_valid!='' then signstatus_valid else signstatus end )");
			
			$query->orderby("(case when signstatus_valid!='' then signstatus_valid else signstatus end )='正常' asc");
			$data = $query->all();
			 
			// echo "<pre>";
			// print_r($data);
			// exit;
			foreach($data as $val){
				$returnEvent[]=[
					'title' => $val['signstatus_valid']?("打卡:".$val['signstatus_valid'].$val['signstatus_validcount']."名"):$val['signstatus_validcount']."名无打卡",
					"eventType"=>"checkinTotal",
					'start'	=> $val["signdate"],
					'signstatus'=> $val['signstatus_valid']?:"无",
					'signdate'=> $val['signdate'],
					'end' 	=> '',
					'desc' 	=> "",
					'overlap'=> false,
					'sort'=> $val['signstatus_valid']?($val['signstatus_valid']!="正常"?$val['signstatus_valid']=="迟到"?"3":"7":"2"):"1",
					'color'=>$val['signstatus_valid']?($val['signstatus_valid']!="正常"?$val['signstatus_valid']=="迟到"?"#0099FF":"#FF6600":"#00CC00"):"#FF0000",
				];
			}
			$query->select([
				"leaveidsSum"=>"sum(case when leaveids!='' then (LENGTH (leaveids) - LENGTH (REPLACE(leaveids,',','')) )+1  else 0 end )",
				"leaveidsCount"=>"sum(case when leaveids!='' then 1 else 0 end )",
				"gooutidsSum"=>"sum(case when gooutids!='' then (LENGTH (gooutids) - LENGTH (REPLACE(gooutids,',','')) )+1  else 0 end )",
				"gooutidsCount"=>"sum(case when gooutids!='' then 1 else 0 end )",
				"overtimeidsSum"=>"sum(case when overtimeids!='' then (LENGTH (overtimeids) - LENGTH (REPLACE(overtimeids,',','')) )+1  else 0 end )",
				"overtimeidsCount"=>"sum(case when overtimeids!='' then 1 else 0 end )",
				"signdate",
			
			]);
			$query->groupby("signdate");
			$data = $query->all();
			// echo "<pre>";
			// print_r($data);
			// exit;
			foreach($data as $val){
				if($val["leaveidsSum"]||$val["leaveidsCount"]){
					$returnEvent[]=[
						'title' => $val["leaveidsSum"]."次请假(".$val["leaveidsCount"]."人)",
						"eventType"=>"leaveTotal",
						'start'	=> $val["signdate"],
						'signstatus'=> "请假",
						'signdate'=> $val['signdate'],
						'end' 	=> '',
						'desc' 	=> "",
						'overlap'=> false,
						'sort'=> "9",
						'color'=>"",
					];
				}
				if($val["overtimeidsSum"]||$val["overtimeidsCount"]){
					$returnEvent[]=[
						'title' => $val["overtimeidsSum"]."次加班(".$val["overtimeidsCount"]."人)",
						"eventType"=>"overtimeTotal",
						'start'	=> $val["signdate"],
						'signstatus'=> "加班",
						'signdate'=> $val['signdate'],
						'end' 	=> '',
						'desc' 	=> "",
						'overlap'=> false,
						'sort'=> "9",
						'color'=>"",
					];
				}
				if($val["gooutidsSum"]||$val["gooutidsCount"]){
					$returnEvent[]=[
						'title' => $val["gooutidsSum"]."次外出(".$val["gooutidsCount"]."人)",
						"eventType"=>"gooutTotal",
						'start'	=> $val["signdate"],
						'signstatus'=> "外出",
						'signdate'=> $val['signdate'],
						'end' 	=> '',
						'desc' 	=> "",
						'overlap'=> false,
						'sort'=> "9",
						'color'=>"",
					];
				}
			}
			
			// var_dump($returnEvent);exit;
		}
		return  $returnEvent;
		
	}
	
	public function leaveEvent($start,$end,$userid){
		$returnEvent = [];
		$timeS = strtotime($start);
		$timeE = strtotime($end);
		$Leaves = \app\models\AttendanceLeave::find()->where(["and",['>=', 'leaveend', $timeS],['<', 'leavestart', $timeE+86400],['=', 'personnel_id', $userid]])->asArray()->all();
		 $leavetypeLabel = \app\models\AttendanceLeave::$leavetypeLabel;
		// var_dump($Leaves);
		foreach($Leaves as $val){
			$start = date("Y-m-d H:i:s",$val["leavestart"]);
			$end =  date("Y-m-d H:i:s",$val["leaveend"]);
			$returnEvent[]=[
				'title' => (isset($leavetypeLabel[$val["leavetype"]])?$leavetypeLabel[$val["leavetype"]]:"假别异常")."　".$val["description"],
				"eventType"=>"leave",
				'start'	=> $start,
				'signstatus'=> $val["leavetype"],
				'signdate'=> $val['writedate'],
				'end' 	=>  $end,
				'desc' 	=> $val['description'],
				'overlap'=> false,
				'sort'=> 10,
				'color'=>"",
			];
		}
		return $returnEvent;
	}
	
	public function getEvent($start,$end,$userid=""){
		$returnEvent = [];
		$checkinEvent = $this->checkInEvent($start,$end,$userid);
		$returnEvent = $checkinEvent;
		if($userid){
			$leaveEvent = $this->leaveEvent($start,$end,$userid);
			$returnEvent = array_merge($returnEvent,$leaveEvent);
		}
		return  $returnEvent;
	}
}
