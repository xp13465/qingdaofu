<?php

namespace frontend\modules\wap\controllers;
use frontend\modules\wap\components\WapController;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\models\ProductOrders;

/**
 * 一键发布产品订单控制器
 */
class ProductordersController extends WapController
{
	public $productModel = 'app\models\ProductOrders';
	
	public function actionGenerate()
    { 
		$applyid = Yii::$app->request->post("applyid");
		$ProductOrdersQuery = ProductOrders::find(); 
		$status = $ProductOrdersQuery->ordersGenerate($applyid);
		switch($status){
			case 'ok':
				$this->success("接单生成成功" ,['ordersid'=>$ProductOrdersQuery->ordersid]);
				break;
			default:
				$this->errorMsg($status);
				break ;
		}
		
    }
	
	public function actionDemo()
    { 
		 // $controller = Yii::createObject($type, [$id, $module])
		 
		// print_r(Yii::$app->controller);
		// echo "<pre>";
		$prefix = '/' . $this->uniqueId . '/';
		$class = new \ReflectionClass($this);
		foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                $id = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                $result[$id] = $id;
            }
        }
		// print_r($result);
		return $this->render("demo",['result'=>$result]);
		
    }
	/**
	 *  接单列表
	 */
	private function actionIndex($listtype='',$type='0')
    { 
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $ProductOrdersQuery = ProductOrders::find(); 
		$params = Yii::$app->request->post();
		$params['listtype'] = $listtype;
		$provider = $ProductOrdersQuery->searchOrderList($params,$uid,false,$type);
		$data = $provider->getModels();
		$data = $ProductOrdersQuery->filterAll($data);
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
	/**
	 *  进行中列表
	 */
	public function actionListProcessing()
    { 
		$type =  Yii::$app->request->post('type',0);
		$listtype = "processing";
		 
		return $this->actionIndex($listtype,$type);
    }
	/**
	 *  已完成列表
	 */
	public function actionListCompleted()
    { 
		$type =  Yii::$app->request->post('type',0);
		$listtype = "completed";
		return $this->actionIndex($listtype,$type);
    }
	/**
	 *  已终止列表
	 */
	public function actionListAborted()
    { 
		$type =  Yii::$app->request->post('type',0);
		$listtype = "aborted";
		return $this->actionIndex($listtype,$type);
    }
	
	/**
	 *  接单详情
	 */
	public function actionDetail()
    { 
		$applyid = Yii::$app->request->post("applyid",0);
		$messageId = Yii::$app->request->post('messageid');
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			if(!$messageId) $updateType = '';
			$MessageQuery->setRead($messageId,$updateType='GROUP');
		}
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $ProductOrdersQuery = ProductOrders::find(); 
		$data = $ProductOrdersQuery->ordersDetail($applyid,$uid);
		if($data){
			if((isset($data['accessOrdersREAD'])&&$data['accessOrdersREAD'])||$data['create_by']==$uid){
				$this->success("",["data"=>$data]);
			}else{
				$this->errorMsg("UserAuth");
			}
			
		}else{
			$this->errorMsg("NotFound");
		}
		
    }
	/**
	*	接单居间协议确认
	*/
	
	public function actionOrdersConfirm(){
		$ordersid = Yii::$app->request->post('ordersid');
		$memo = Yii::$app->request->post('memo','');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersComfirm($ordersid,$memo);
		switch($status){
			case 'ok':
				$this->success("接单居间确认成功",[]);
				break;
			default:
				$this->errorMsg($status);
				break ;
		}
	}
	
	/**
	*	接单协议操作
	*/
	
	public function actionOrdersPactAdd(){
		$post = Yii::$app->request->post();
		$params = ["files"=>"","ordersid"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersPactAdd($params);
		switch($status){
			case 'ok':
				$this->success("接单协议成功",['closeid'=>$ProductOrdersQuery->closedid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*	接单协议详情
	*/
	
	public function actionOrdersPactDetail(){
		
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$data = $ProductOrdersQuery->ordersPactDetail($ordersid);
		if(is_array($data)||$data===[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*	接单协议确认
	*/
	
	public function actionOrdersPactConfirm(){
		$ordersid = Yii::$app->request->post('ordersid');
		$memo = Yii::$app->request->post('memo','');
		$ProductOrdersQuery = ProductOrders::find();
		$params['ordersid']=$ordersid;
		$params['files']=Yii::$app->request->post('files');
		if($params['files']){
			$status = $ProductOrdersQuery->ordersPactAdd($params);
			if($status!="ok")$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
		}
		$status = $ProductOrdersQuery->ordersPactConfirm($ordersid,$memo);
		switch($status){
			case 'ok':
				$this->success("接单协议确认成功",[]);
				break;
			default:
				$this->errorMsg($status);
				break ;
		}
	}
	
	
	/**
	*	添加进度选项
	*/
	
	public function actionOrdersProcessActions(){
		$ordersid = Yii::$app->request->post('ordersid');
		$ProductOrdersQuery = ProductOrders::find();
		$data = $ProductOrdersQuery->ordersProcessActions($ordersid);
		 
		
		$this->success("", ['actions'=>$data]);
	}
	
	/**
	*	添加进度操作
	*/
	
	public function actionOrdersProcessAdd(){
		// 处置进度操作 
		$post = Yii::$app->request->post();
		//var_dump($post);die;
		$params = ["ordersid"=>"","type"=>"1","files"=>"","memo"=>"",/*"operator"=>"",*/];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersProcessAdd($params,1);
		switch($status){
			case 'ok':
				$this->success("进度添加成功！" , ['processid'=>$ProductOrdersQuery->processid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	/**
	*	进度详情
	*/
	
	public function actionOrdersProcessDetail(){
		$ProductOrdersQuery = ProductOrders::find();
		$processid = Yii::$app->request->post('processid');
		$data = $ProductOrdersQuery->ordersProcessDetail($processid);
		if($data){
			$this->success("",['data'=>$data->toArray()]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	/**
	*	结案操作申请
	*/ 
	
	public function actionOrdersClosedApply(){
		$post = Yii::$app->request->post();
		$params = ["ordersid"=>"","price"=>"","price2"=>"","applymemo"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersClosedApply($params);
		switch($status){
			case 'ok':
				$this->success("结案申请成功！",['closeid'=>$ProductOrdersQuery->closedid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	/**
	*	结案详情
	*/ 
	
	public function actionOrdersClosedDetail(){
		$ProductOrdersQuery = ProductOrders::find();
		$closedid = Yii::$app->request->post('closedid');
		$data = $ProductOrdersQuery->ordersClosedDetail($closedid);
		if($data){
			$datas = $data->toArray();
			$datas['priceLabel'] = round($datas['price']/10000);
			$datas['price2Label'] = round($datas['price2']/10000);
			$accessClosedAUTH = $data->orders->accessClosed("AUTH",'',[$datas['create_by']]);
			$datas['product'] = $data->orders->product->toArray();
			$datas['product'] = \app\models\Product::find()->filterOne($datas['product']);
			$this->success("",['data'=>$datas,'accessClosedAUTH'=>$accessClosedAUTH]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	/**
	*	结案操作同意
	*/
	public function actionOrdersClosedAgree(){
		$closedid = Yii::$app->request->post('closedid');
		$resultmemo = Yii::$app->request->post('resultmemo','');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersClosedAgree($closedid,$resultmemo);
		$statusArr=explode("_",$status);
		$newStatus = $statusArr[0];
		$newStatus2 = isset($statusArr[1])?$statusArr[1]:'';
		switch($newStatus){
			case 'ok':
				$this->success("结案成功！",[]);
				break;
			default:
				$this->errorMsg($newStatus,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*	结案操作否决
	*/
	public function actionOrdersClosedVeto(){
		$closedid = Yii::$app->request->post('closedid');
		$resultmemo = Yii::$app->request->post('resultmemo','');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersClosedVeto($closedid,$resultmemo);
		// var_dump($status);
		switch($status){
			case 'ok':
				$this->success("结案否决成功！",[]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*	终止操作申请
	*/
	public function actionOrdersTerminationApply(){
		$post = Yii::$app->request->post();
		$params = ["ordersid"=>"","applymemo"=>"","files"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersTerminationApply($params);
		switch($status){
			case 'ok':
				$this->success("终止申请成功！",['terminationid'=>$ProductOrdersQuery->terminationid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*	终止详情
	*/ 
	
	public function actionOrdersTerminationDetail(){
		$ProductOrdersQuery = ProductOrders::find();
		$terminationid = Yii::$app->request->post('terminationid');
		$data = $ProductOrdersQuery->ordersTerminationDetail($terminationid);
	 
		if($data){
			$datas = $data->toArray();
			$datas['filesImg'] = $data->filesImg;
			// $datas['orders'] = $data->orders->toArray();
			$dataLabel = ($data->orders->create_by == $data->create_by?"接单方申请终止":"发布方申请终止");
			$accessTerminationAUTH = $data->orders->accessTermination("AUTH",'',[$datas['create_by']]);
			$this->success("",['data'=>$datas,'accessTerminationAUTH'=>$accessTerminationAUTH,'dataLabel'=>$dataLabel,'dataLabel'=>$dataLabel]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*	终止操作同意
	*/
	public function actionOrdersTerminationAgree(){
		$terminationid = Yii::$app->request->post('terminationid');
		$resultmemo = Yii::$app->request->post('resultmemo','');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersTerminationAgree($terminationid,$resultmemo);
		$statusArr=explode("_",$status);
		$newStatus = $statusArr[0];
		$newStatus2 = isset($statusArr[1])?$statusArr[1]:'';
		switch($newStatus){
			case 'ok':
				$this->success("终止成功！",[]);
				break;
			default:
				$this->errorMsg($newStatus,$ProductOrdersQuery->formatErrors());
				break ;
		}
		
	}
	
	/**
	*	终止操作否决
	*/
	public function actionOrdersTerminationVeto(){
		
		$terminationid = Yii::$app->request->post('terminationid');
		$resultmemo = Yii::$app->request->post('resultmemo','');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->ordersTerminationVeto($terminationid,$resultmemo);
		switch($status){
			case 'ok':
				$this->success("终止否决成功！",[]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	
	/**
	*  接单经办人
	*
	*/
	public function actionOrdersOperatorList(){
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$data = $ProductOrdersQuery->ordersOperatorList($ordersid,false);
		// var_dump($data);
		if(is_string($data)){
			$this->errorMsg($data);
		}elseif($data||$data===[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*  分配经办人
	*
	*/
	public function actionOrdersOperatorSet(){
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$operatorIds = Yii::$app->request->post('operatorIds');
		$status = $ProductOrdersQuery->ordersOperatorSet($ordersid,$operatorIds);
		switch($status){
			case 'ok':
				$this->success("分配成功");
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*  取消经办人
	*
	*/
	public function actionOrdersOperatorUnset(){
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$ids = Yii::$app->request->post('ids');
		$status = $ProductOrdersQuery->ordersOperatorUnset($ordersid,$ids);
		switch($status){
			case 'ok':
				$this->success("取消成功");
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	*  接单日志
	*
	*/
	public function actionLogs(){
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$data = $ProductOrdersQuery->ordersLogs($ordersid,false);
		// var_dump($data);
		if($data||$data===[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*  接单评价列表
	*
	*/
	public function actionCommentList(){
		$ProductOrdersQuery = ProductOrders::find();
		$ordersid = Yii::$app->request->post('ordersid');
		$data = $ProductOrdersQuery->commentList($ordersid,false);
		// var_dump($data);
		if($data||$data===[]){
			$this->success("",['data'=>$data]);
		}elseif($data===false){
			$this->errorMsg('UserAuth');
		}else{
			$this->errorMsg('NotFound');
		}
	}
	
	/**
	*  接单普通评价
	*
	*/
	public function actionCommentAdd(){
		$ProductOrdersQuery = ProductOrders::find();
		$post = Yii::$app->request->post();
		$params = ["ordersid"=>"","truth_score"=>"","assort_score"=>"","response_score"=>"","memo"=>"","files"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$status = $ProductOrdersQuery->commentAdd($params);
		switch($status){
			case 'ok':
				$this->success("评价成功",["commentid" => $ProductOrdersQuery->commentid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
		 
	}
	/**
	*  接单追加评价
	*
	*/
	public function actionCommentAdditional(){
		$ProductOrdersQuery = ProductOrders::find();
		$post = Yii::$app->request->post();
		$params = ["ordersid"=>"","tocommentid"=>"","memo"=>"","files"=>"",/*"truth_score"=>"","assort_score"=>"","response_score"=>"",*/];
		foreach($params as $k=>$v)$params[$k]=isset($post[$k])?$post[$k]:$params[$k];
		$status = $ProductOrdersQuery->commentAdditional($params);
		switch($status){
			case 'ok':
				$this->success("追评成功",["commentid" => $ProductOrdersQuery->commentid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
	
	/**
	* 删除接单
	*/
	public function actionApplyDel(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->applyDel($applyid);
		switch($status){
			case 'ok':
				$this->success("删除成功",["commentid" => $ProductOrdersQuery->commentid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
 
}
