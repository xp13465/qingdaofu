<?php

namespace frontend\modules\wap\controllers;

use common\models\Area;
use common\models\City;
use common\models\Province;
use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class MessageController extends WapController
{
	public function actionGroupList(){
		// $MessageQuery = \app\models\Message::find();
		// $MessageQuery->addMessage(100,["code"=>"BX22222222"],1,75,40,0);
		
		$uid=''; 
		$MessageQuery = \app\models\Message::find();
		$params = Yii::$app->request->post();
		$provider = $MessageQuery->groupList($params,$uid);
		
		$SystemMessageQuery = \app\models\Message::find();
		$systemCount = $SystemMessageQuery->systemList([],$uid,true);
		
		$data = $provider->getModels();
		$data = $MessageQuery->filterAll($data);
		$this->success("",
			[
			"data"=>$data,
			"systemCount"=>$systemCount,
			"totalCount"=>$provider->getTotalCount(),
			"curCount"=>$provider->getCount(),
			"pageSize"=>$provider->pagination->getPageSize(),
			"curpage"=>$provider->pagination->getPage()+1
			]
		);
	}
	public function actionSystemList(){
		$uid=''; 
		$MessageQuery = \app\models\Message::find();
		$params = Yii::$app->request->post();
		$provider = $MessageQuery->systemList($params,$uid,false,true);
		$data = $provider->getModels();
		$data = $MessageQuery->filterAll($data);
		$this->success("",
			[
			"data"=>$data,
			"totalCount"=>$provider->getTotalCount(),
			"curCount"=>$provider->getCount(),
			"pageSize"=>$provider->pagination->getPageSize(),
			"curpage"=>$provider->pagination->getPage()+1
			]
		);
		
	}
	public function actionNoread(){
		$uid = $this->user->id;
        $numbers = \app\models\Message::find()->where(["validflag"=>"1","isRead"=>"0","belonguid"=>$uid])->count();
		echo Json::encode(['code'=>'0000','number'=>$numbers]);die;
	}
	
	public function actionType(){
		$types = Yii::$app->params['MessageType'];
		$uid = $this->user->id;
		foreach($types as $key => $value) {
			$number = Yii::$app->db->createCommand("select count(type) from zcb_message where belonguid = ".$uid." and type in (".implode(',', $value['childrenList']).") and isRead = 0 ")->queryScalar();
			$types[$key]['number'] = $number;
		}
        $numbers = Yii::$app->db->createCommand("select count(type) from zcb_message where belonguid = {$uid} and isRead = 0")->queryScalar();
		echo Json::encode(['code'=>'0000','result'=>$types,'number'=>$numbers]);die;
	}

	public function actionIndex(){
		$uid = $this->user->id;
		$types = Yii::$app->params['MessageType'];
		$MessageUrl = Yii::$app->params['MessageUrl'];

		$type = Yii::$app->request->post('type',4);
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',10);
		$limitstr = '';

		$sql = "select * from zcb_message where belonguid = ".$uid." and type in (".implode(',',$types[$type]['childrenList']).") order by isRead asc ,create_time desc";
		if(is_numeric($page)&&is_numeric($limit)){
			$page = $page<=1?1:$page;
			$limitstr .= " limit ".($page - 1)*$limit.",".$limit;
		}else{
			echo \yii\helpers\Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
		}

		
		$messages = Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
		$res['message'] = $messages;
		foreach($res['message'] as $key => $value){
			$res['message'][$key]['content'] = $value['content'];
		    $a = preg_replace("/<a[^>]*>(.*)<\/a>/is","",$value['content']);
			$res['message'][$key]['contents'] = preg_replace("/<span>(.*?)<span>/si","",$a);
		
			$res['message'][$key]['category_id'] = $value['params']?array_merge(["demo"=>'demo'],unserialize($value['params'])):["demo"=>'demo'];
			
				if($res['message'][$key]['category_id']&&count($res['message'][$key]['category_id'])>1){
				if($res['message'][$key]['category_id']['category'] == 1){
				  $product = \common\models\FinanceProduct::findOne(['id'=>$res['message'][$key]['category_id']['id'],'category'=>$res['message'][$key]['category_id']['category']]);  
				}else{
				  $product = \common\models\CreditorProduct::findOne(['id'=>$res['message'][$key]['category_id']['id'],'category'=>$res['message'][$key]['category_id']['category']]);
				}
				if($product){
					$apply = \common\models\Apply::findOne(['product_id'=>$product['id'],'uid'=>$value['uid'],'category'=>$product['category']]);
					$res['message'][$key]['app_id'] = $apply['app_id'];
					$res['message'][$key]['fuid'] = $product['uid'];
					$res['message'][$key]['progress_status'] = $product['progress_status'];
					$num = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate where uid ={$uid} and product_id ={$product['id']} and category={$product['category']}")->queryScalar();
					$res['message'][$key]['frequency'] = isset($num)?$num:'';
				}
			}
        }
		$res['type'] = $types[$type];
		$res['MessageUrl'] = $MessageUrl;
		echo \yii\helpers\Json::encode(['code'=>'0000','msg'=>'查找消息成功','result'=>$res]);die;

	}
	
	public function actionSetRead(){
		$messageId = Yii::$app->request->post('messageId');
		$MessageQuery = \app\models\Message::find();
		if(!$messageId) $updateType = '';
		$data = $MessageQuery->setRead($messageId,$updateType);
		if($data){
			$this->success("ok");
		}else{
		    $this->errorMsg(PARAMSCHECK);
		}
	}

	//阅读消息
	public function actionRead(){
		$id = Yii::$app->request->post('id');
		$pid = Yii::$app->request->post('pid');
		if(is_numeric($id)){
			$product = \common\models\CreditorProduct::findOne(['id'=>$pid]);
			if($product){
				$messages = \common\models\Message::findOne(['id'=>$id]);
			if($messages){
				if($messages['isRead'] == 0) {
					$messages->isRead = 1;
					$messages->create_time = time();
					if ($messages->save()) {
						echo Json::encode(['code' => '0000', 'msg' => '阅读成功']);
						die;
					} else {
						echo Json::encode(['code' => '1014', 'msg' => $messages->errors]);
						die;
					}
				}else{
					echo Json::encode(['code'=>'0000']);
				}
			}else{
                 echo Json::encode(['code'=>'1002','msg'=>'没有数据,请查询参数是否正确']);die;
			}
			}else{
				echo Json::encode(['code'=>'1002','msg'=>'该数据已被删除']);die;
			}
			
		}else{
			echo Json::encode(['code'=>'1002','msg'=>'参数错误']);die;
		}
	}
}
