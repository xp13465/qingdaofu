<?php
namespace frontend\controllers;
use frontend\components\FrontController;
use Yii;

class ScriptController extends FrontController
{
    public function actionIndex()
    {
        var_dump($_SERVER['HTTP_HOST']);
	    $relatype = ["10","20","40","50"];
		$time = time()-86400;
		$messageQuery=\app\models\Message::find();
		$data = $messageQuery->where(["validflag"=>"1","wxpush"=>"0","relatype"=>$relatype])->andWhere("create_time >= '{$time}'")->count();
		var_dump($data);
	    $url ="http://localhost/script/messagepush";
	    \frontend\services\Func::asyncExecute($url);
    }
	public function actionMessagepush()
    {
		if(in_array($_SERVER['HTTP_HOST'],["localhost"])){
			ignore_user_abort(true);
			$messageQuery=\app\models\Message::find();
			$messageQuery->MessagePush();
		}else{
			echo "Not Found";
		}
      
    }
	
	

}
