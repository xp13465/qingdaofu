<?php

namespace manage\controllers;


use Yii;
use app\models\AttendanceCheckinReports;
use app\models\AttendanceCheckinReportsSearch;
use manage\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AttendanceController implements the CRUD actions for AttendanceCheckinReports model.
 */
class AttendanceController  extends BackController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	/**
     * Lists all AttendanceCheckinReports models.
     * @return mixed
     */
    public function actionIndex($showlayout="")
    {
		if($showlayout=="dialog"){
			$this->layout = "dialog";
		}
        $searchModel = new AttendanceCheckinReportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/**
     * Lists all AttendanceCheckin models.
     * @return mixed
     */
    public function actionData()
    {
        $searchModel = new \app\models\AttendanceCheckinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	/**
     * Lists all AttendanceCheckinReports models.
     * @return mixed
     */
    public function actionStatistical()
    {
        $searchModel = new AttendanceCheckinReportsSearch();
        $dataProvider = $searchModel->statistictalSearch(Yii::$app->request->queryParams);
		
// var_dump($dataProvider->getModels());
		
		// exit;
        return $this->render('statistical', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AttendanceCheckinReports model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	/**
     * 迟到有效处理
     */
    public function actionValidLate()
    {
		$id= Yii::$app->request->post('id');
		$model= $this->findModel($id);
		$latetime = Yii::$app->request->post('latetime');
        $updateAttr = ["latetime_valid"=>$latetime];
		if($model->signstatus=="迟到补齐"&&$model->signstatus_valid=="迟到"){
			$updateAttr["signstatus_valid"]="正常";
		}
		$count = $model->updateAll($updateAttr,["id"=>$id,"latetime_valid"=>"0"]);
		if($count>=0){
			$this->success("迟到时间已清零！");
		}else{
			$this->errorMsg("ModelDataSave","操作失败");
		}
    }
    /**
     * 加班有效处理
     */
    public function actionValidOver()
    {
		$id= Yii::$app->request->post('id');
		$model= $this->findModel($id);
		$overtime = Yii::$app->request->post('overtime');
        $updateAttr = ["overtime_valid"=>$overtime];
		 
		$count = $model->updateAll($updateAttr,["id"=>$id,"overtime_valid"=>"0"]);
		if($count>=0){
			$this->success("加班时长已生效！");
		}else{
			$this->errorMsg("ModelDataSave","操作失败");
		}
    }
	
	/**
     * 纠正处理
     */
    public function actionValidRedress()
    {
		$id= Yii::$app->request->post('id');
		$model= $this->findModel($id);
		$redressstatus = Yii::$app->request->post('redressstatus');
		$redressmemo = Yii::$app->request->post('redressmemo');
		if(empty($redressstatus)||empty($redressmemo)){
			$this->errorMsg("ModelDataCheck","纠正状态和纠正备注不能为空！");
		}
        $updateAttr = [
			"signstatus_valid"=>$redressstatus,
			"memo"=>$redressmemo,
			"status"=>'1',
			"updatetime"=>time(),
			"updateuser"=>Yii::$app->user->getId(),
		
		];
		 
		$count = $model->updateAll($updateAttr,["id"=>$id]);
		if($count>=0){
			$this->success("考勤纠正成功");
		}else{
			$this->errorMsg("ModelDataSave","操作失败");
		}
    }
	
    /**
     * Finds the AttendanceCheckinReports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AttendanceCheckinReports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AttendanceCheckinReports::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	
	
	  /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionOutputIndex()
    {
		set_time_limit(0);
        $searchModel = new AttendanceCheckinReportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		
		$sort = Yii::$app->request->queryParams['sort']?Yii::$app->request->queryParams['sort']:'signdate';
		
		if($sort{0}=="-"){
			$orderBy = substr($sort,1)."  desc";
		}else{
			$orderBy = " {$sort} ASC";
		}
		$result = $dataProvider->query->orderBy($orderBy)->asArray()->all();
		// var_dump($result);exit;
        $arr = [];
        $ic = 1; 
        foreach($result as $r) {
			$r['signtime1'] = $r["signtime1"]=="0000-00-00 00:00:00"?"":$r["signtime1"];
			$r['signtime2'] = $r["signtime2"]=="0000-00-00 00:00:00"?"":$r["signtime2"];
			
			
			$r['signstatus'] = $r['signstatus_valid']?:$r['signstatus'];
			$r['latetime'] = $r['latetime']-$r['latetime_valid'];
			$r['overtime'] = $r['overtime_valid'];
            $arr[] = $r;
        }
		$header = [
            'id' => 'ID',
            'personnel_id' => '员工ID',
            'employeeid' => '工号',
            'username' => '员工姓名',
            'signdate' => '签到日期',
            'dayofweek' => '星期几',
            'signtime1' => '上班签到',
            'signtime2' => '下班签到',
            'timediff' => '工作时长',
            'signstatus' => '签到状态',
            'latetime' => '迟到分钟',
            'overtime' => '加班时长',
            'memo' => '备注',
        ];
		$columns = array_keys($header);
		
		 
        \moonland\phpexcel\Excel::export([
			'isMultipleSheet' => true,
			'models' => [
				'考勤报表' => $arr, 
			],
			'columns' => [
				'考勤报表' => $columns,
			], 
			'headers' => [
				'考勤报表' => $header, 
			],
        ]);
    }
	
	  /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionOutputStatistical()
    {
		set_time_limit(0);
        $searchModel = new AttendanceCheckinReportsSearch();
        $dataProvider = $searchModel->statistictalSearch(Yii::$app->request->queryParams);
		 
		$sort = Yii::$app->request->queryParams['sort']?Yii::$app->request->queryParams['sort']:'signdate';
		
		if($sort{0}=="-"){
			$orderBy = substr($sort,1)."  desc";
		}else{
			$orderBy = " {$sort} ASC";
		}
		$result = $dataProvider->query->orderBy($orderBy)->asArray()->all();
        $arr = []; 
        foreach($result as $r) {
            $arr[] = $r;
        }
		$header = [
            'year' => '年', 
            'month' => '月', 
            'personnel_id' => '员工ID', 
            'username' => '员工姓名', 
            'overtime' => '当月加班时长（计算）', 
            'overtime_valid' => '当月加班时长（有效）', 
        ];
		$columns = array_keys($header);
        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => true,
			'models' => [
				'考勤统计' => $arr, 
			],
			'columns' => [
				'考勤统计' => $columns,
			], 
			'headers' => [
				'考勤统计' => $header, 
			],
        ]);
    }
	
	
	public function actionCalendarAll($userid=""){ 
	
		return $this->render('calendar', [
			"type" =>"all",
			"eventUrl" =>"/attendance/event-all?userid=".$userid,
        ]);
	}
	public function actionCalendarMy(){
		return $this->render('calendar', [
			"type" =>"my",
			"eventUrl" =>"/attendance/event-my",
        ]);
	}
	
	public function actionEventMy($start,$end){
		
		$userid= Yii::$app->user->identity->personnelid;
		$returnEvent = $userid?\app\models\AttendanceCheckinReports::find()->getEvent($start,$end,$userid):[];
		echo json_encode($returnEvent);
	}
	
	public function actionEventAll($start,$end,$userid=""){
		$returnEvent = \app\models\AttendanceCheckinReports::find()->getEvent($start,$end,$userid);
		echo json_encode($returnEvent);
	}
	
	public function actionImport()
    {
		$model = new \common\models\UploadForm();
        if (Yii::$app->request->isPost) { 
            $model->imageFile = \frontend\components\UploadedFile::getInstance($model, 'Filedata');
			// var_dump($model->imageFile);
			if(!in_array($model->imageFile->type,["application/vnd.ms-excel"])){
				exit;
			}
			// $data = $return = $model->upload($filetype,true);
            // unset($return['tempName']);
			// echo \yii\helpers\Json::encode($return);
        }
		// exit;
		// $fileName ="./12.xls";
		$fileName =$model->imageFile->tempName;
		$exlceData = \moonland\phpexcel\Excel::import($fileName, [
				'setFirstRecordAsKeys' => false, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
				'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
				// 'getOnlySheet' => 'Sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
		]);
		// var_dump($exlceData);exit;
		$personnelArray =[];
		$employeeidArray =[];
		$model = new \app\models\AttendanceCheckin();
		if(isset($exlceData["Sheet1"])){
			$CheckinDatas=$exlceData["Sheet1"];
		}else{
			$CheckinDatas = $exlceData;
		}
		// var_dump($exlceData);
		if(count($CheckinDatas)&&!$CheckinDatas[1]["B"]&&!$CheckinDatas[1]["C"]){
			exit("文件读取错误，请打开上传文件另存为新文件后重新上传！");
		}
		// exit;
		
		if($CheckinDatas){
			foreach($CheckinDatas as $item=>$data){
				
				if($item<=1)continue;
				if(isset($data["D"])&&isset($data["E"])&&isset($data["F"])){
					$number =$data["A"];
					$username =$data["B"];
					$signtime =$data["C"]?strtotime($data["C"]):0;
					$signdate =$data["C"];
					$signtype =$data["D"];
					$gengzheng =$data["E"];
					$yichang =$data["F"];	
				}else{
					$number ='';
					$username =$data["A"];
					$signtime =$data["B"]?strtotime($data["B"]):0;
					$signdate =$data["B"];
					$signtype =$data["C"];
					$gengzheng ='';
					$yichang ='';
				}
				
				$employeeid = array_search($username,$employeeidArray);
				$personnel_id = array_search($username,$personnelArray);
				
				if(!$personnel_id){
					$Personnel =\app\models\Personnel::find()->select(["id","name","employeeid"])->where(["name"=>$username])->one();
					if($Personnel){
						$personnel_id = $Personnel["id"];
						$employeeid = $Personnel["employeeid"];
					}else{
						$personnel_id = 0;
						$employeeid = '';
					}
					$personnelArray[$personnel_id] = $username;
					$employeeidArray[$employeeid] = $username;
				}
				$attributes= [
					"number"=>$number,
					"gengzheng"=>$gengzheng,
					"yichang"=>$yichang,
					"username"=>$username,
					"signtime"=>$signtime,
					"signtype"=>$signtype,
					"signdate"=>$signdate,
					"employeeid"=>$employeeid,
					"personnel_id"=>$personnel_id,
				];
				$model->isNewRecord = true;
				$model->id = NULL;
				$model->setAttributes($attributes);
				if($model->validate()){
					$model->save();
				}else{
					if(isset($model->errors["signtime"])&&$model->errors["signtime"][0]=="员工姓名在相同出勤时间已有相同的出勤状态记录存在（一分钟内多次打卡）"){
						echo "第",$item,"行记录已存在　",$attributes["username"],"已在",$attributes["signdate"],"有过一次",$attributes["signtype"];
					}else{
						echo "<br/>错误信息：";
						print_r($model->errors);
					}
					echo "<hr/>";
					// exit;
				}
				
				
				
			}
			
			 
		}
		echo "导入操作已执行完毕。<script>document.body.scrollTop = document.body.scrollHeight;</script>";
		
    }
    /**
     * Lists all AttendanceCheckinReports models.
     * @return mixed
     */
    public function actionScript($startdate = "2016-12-01",$enddate = "2016-12-31")
    {
		if(!strtotime($startdate)||!strtotime($enddate)){
			
			echo "日期错误";
			exit;
		}
		ignore_user_abort(true);
		$data = \app\models\AttendanceCheckinView::find()->where(["and",['>=', 'signdate', $startdate],['<=', 'signdate', $enddate]])->asArray()->all();//->andWhere("personnel_id != 0")
		$Personnels = \app\models\Personnel::find()->where(["validflag"=>"1","checkin"=>"1"])->asArray()->all();
		// var_dump($data);exit;
		// echo "<pre>";
		$result = \yii\helpers\ArrayHelper::index($data, "personnel_id", 'signdate');
		// echo "<pre>";
		// print_r($Personnels);
		// print_r($result);
		// exit;
		// echo date('w');exit;
		$startdateInt = strtotime($startdate);
		$enddateInt = strtotime($enddate);
		$weekArr=["0"=>"星期日","1"=>"星期一","2"=>"星期二","3"=>"星期三","4"=>"星期四","5"=>"星期五","6"=>"星期六"];
		for($time = $startdateInt;$time<=$enddateInt;$time+=86400){
			// var_dump(date("Y-m-d",$time));
			// echo $time;
			// echo "<hr/>";
			// echo $time+86400;
			$Leaves = \app\models\AttendanceLeave::find()->where(["and",['>=', 'leaveend', $time],['<', 'leavestart', $time+86400]])->asArray()->all();
			$LeavesDatas = \yii\helpers\ArrayHelper::index($Leaves,  "id","personnel_id");
			
			$Overtimes = \app\models\AttendanceOvertime::find()->where(["and",['>=', 'overtimestart', $time],['<', 'overtimestart', $time+86400]])->asArray()->all();
			$OvertimesDatas = \yii\helpers\ArrayHelper::index($Overtimes,  "id","personnel_id");
		 
			
			$goouts = \app\models\AttendanceGoout::find()->where(["gooutdate"=>date("Y-m-d",$time)])->asArray()->all();
			$gooutDatas = \yii\helpers\ArrayHelper::index($goouts,  "id","personnel_id");
			
			
			// var_dump($goouts);
			// var_dump($gooutDatas);
			// continue;
			// exit;
			
			$curYear = date("Y",$time);
			$curMonth = date("m",$time);
			$curDate = date("Y-m-d",$time);
			$curDayofWeekKey = date("w",$time);
			$curDayofWeek = $weekArr[$curDayofWeekKey];
			foreach($Personnels as $personnel){
				$model =\app\models\AttendanceCheckinReports::find()->where(["personnel_id"=>$personnel["id"],"signdate"=>$curDate])->one()?:new \app\models\AttendanceCheckinReports();
				// var_dump($model);exit;
				$model->personnel_id = $personnel['id'];
				$model->employeeid = $personnel['employeeid'];
				$model->username = $personnel['name'];
				$model->signdate = $curDate;
				$model->year = $curYear;
				$model->month = $curMonth;
				$model->dayofweekkey = $curDayofWeekKey;
				$model->dayofweek = $curDayofWeek;
				
				//考勤记录
				if(isset($result[$curDate])&&isset($result[$curDate][$personnel['id']])){
					$userSign= $result[$curDate][$personnel['id']];
					// var_dump($userSign);exit;
					$model->signstatus = $userSign["daytype"];
					if($userSign["daytype"]=="迟到补齐"&&$model->signstatus_valid==''){
						$model->signstatus_valid ='迟到';
					}
					$model->latetime = $userSign["uplate"]>0?$userSign["uplate"]:0;
					$model->overtime = $userSign["downlate"]>0?floor($userSign["downlate"]/30)*0.5:0;
					$model->timediff = $userSign["timediff"]?:0;
					$model->signtime1 = $userSign["upsigntime"]?date("Y-m-d H:i:s",$userSign["upsigntime"]):'0000-00-00 00:00:00';
					$model->signtime2 = $userSign["downsigntime"]?date("Y-m-d H:i:s",$userSign["downsigntime"]):'0000-00-00 00:00:00';
				}
				
				//请假记录
				if(isset($LeavesDatas[$personnel['id']])){
					$userLeaves= $LeavesDatas[$personnel['id']];
					$leaveids="";
					$leavehour=0;
					$leavememo="";
					foreach($userLeaves as  $leaveid => $leave){
						
						
						$timeE = min(strtotime($curDate." 18:00:00"),$leave["leaveend"]);
						$timeS = max(strtotime($curDate." 09:00:00"),$leave["leavestart"]);
						
						$difftime=$timeE-$timeS;
						// echo date("Y-m-d H:i:s",$timeS);
						// echo date("Y-m-d H:i:s",$timeE);
						// var_dump($difftime);
						
						$timeAM = strtotime($curDate." 12:00:00");
						$timePM = strtotime($curDate." 13:00:00");
						if($timeS<=$timeAM&&$timeE>=$timePM){
							$difftime-=3600;
						}elseif($timeS<=$timeAM&&$timeE>=$timeAM){
							$difftime-=($timeE-$timeAM);
						}elseif($timeS<=$timePM&&$timeE>=$timePM){
							$difftime-=($timePM-$timeS);
						}
						$totalhour =$difftime>0?floor($difftime/60/30)*0.5:0;
						$leaveids.=$leaveids?(",". $leave["id"]):$leave["id"];
						$leavehour += $totalhour;
						$leavememo .= $leavememo?"||,||":"";
						
						$leavememo .= "id：".$leave["id"]."\n时长：".date("Y-m-d H:i",$timeS)."~".date("Y-m-d H:i",$timeE)."(".$totalhour."小时)\n说明：".$leave["description"];
						
					}
					$model->leaveids = $leaveids;
					$model->leavehour = $leavehour;
					$model->leavememo = $leavememo;
				}else{
					$model->leaveids = "";
					$model->leavehour = 0;
					$model->leavememo = "";
				}
				
				//加班记录
				if(isset($OvertimesDatas[$personnel['id']])){
					$userOvertimes= $OvertimesDatas[$personnel['id']];
					$overtimeids="";
					$overtimehour=0;
					$overtimememo="";
					foreach($userOvertimes as  $overtimeid => $overtime){
						$overtimeids.=$overtimeids?(",". $overtime["id"]):$overtime["id"];
						$overtimehour += $overtime["totalhour"];
						$overtimememo .= $overtimememo?"||,||":"";
						$overtimememo .= "id：".$overtime["id"]."\n时长：".date("Y-m-d H:i",$overtime['overtimestart'])."~".date("Y-m-d H:i",$overtime['overtimeend'])."(".$overtime["totalhour"]."小时)\n说明：".$overtime["description"];
						
					}
					$model->overtimeids = $overtimeids;
					$model->overtimehour = $overtimehour;
					$model->overtimememo = $overtimememo;
				}else{
					$model->overtimeids = '';
					$model->overtimehour = 0;
					$model->overtimememo = '';
				}
				
				//外出记录
				if(isset($gooutDatas[$personnel['id']])){
					$userGoout= $gooutDatas[$personnel['id']];
					$gooutids="";
					$goouthour=0;
					$gooutmemo="";
					foreach($userGoout as  $gooutid => $goout){
						$gooutids.=$gooutids?(",". $goout["id"]):$goout["id"];
						$goouthour += $goout["totalhour"];
						$gooutmemo .= $gooutmemo?"||,||":"";
						$gooutmemo .= "id：".$goout["id"]."\n时长："."(".$overtime["totalhour"]."小时)\n说明：".$overtime["description"];
						
					}
					$model->gooutids = $gooutids;
					$model->goouthour = $goouthour;
					$model->gooutmemo = $gooutmemo;
				}else{
					$model->gooutids = '';
					$model->goouthour = 0;
					$model->gooutmemo = '';
				}
				
				if($model->save()){
					
				}else{
					var_dump($model->errors);
				}
				
			}
			
		}
		echo "从",$startdate,"到",$enddate,"考勤脚本执行完毕！";
    }
}
