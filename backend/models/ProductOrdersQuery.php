<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use Yii;
/**
 * This is the ActiveQuery class for [[ProductOrders]].
 *
 * @see ProductOrders
 */
class ProductOrdersQuery extends \yii\db\ActiveQuery
{
	public $errors = [];
	public $ordersid = '';
	public $uid = '';
	public $closedid = '';
	public $commentid = '';
	public $logid = '';
	public $operatorid = '';
	public $processid = '';
	public $terminationid = '';
	public $productid = '';
	public $orderid = '';
	public $applyid = '';
	public $listtype = '';
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
	
	
    /**
     * @inheritdoc
     * @return ProductOrders[]|array
     */
    public function all($db = null)
    {
		// $this->asArray();
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductOrders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	/**
	* 接单生成（根据面谈成功ID，仅生成一次接单记录）
	* @param applyid uid
	* @return status
	*/
	public function ordersGenerate($applyid = null, $uid = ''){
		$ProductOrders = new ProductOrders;
		$apply =\app\models\ProductApply::find()->where(['applyid'=>$applyid,'validflag'=>1,'status'=>'40'])->one(); 
		if(!$apply)return PARAMSCHECK;
		if($apply->productOrders)return 'EXISTED';
		$status = $ProductOrders->create($apply->toArray());
		$this->ordersid = $ProductOrders->ordersid;
		return $status;
	}
	
	
	/**
	* 	接单居间确认 （启动接单）
	*
	*/
	public function ordersComfirm($ordersid = null, $memo='', $uid = ''){
		
		$Orders = $this->where(['ordersid'=>$ordersid,'validflag'=>1])->one();
		if(!$Orders)return PARAMSCHECK;		
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSCONFIRM'){
			return $statusLabel;
		}
		if(!$Orders->accessOrders('ORDERCOMFIRM',$uid)){
			return USERAUTH;
		}
		
		$status = $Orders->ordersConfirm($uid);
		
		//发送通知
		if($status == 'ok'){
			$message = \app\models\Message::find();
			$data = $message->addMessage(208,['code'=>$Orders->product->number],$Orders->product->create_by,$Orders->productid,40,$Orders->product->create_by);
			return $status;
		}else{
			return $status;
		}
		
		
	}
	
	/**
	* 	接单协议详情
	*
	*/
	
	public function ordersPactAdd($data,$uid = ''){
		$params = ["files"=>"","ordersid"=>""];
		foreach($params as $k=>$v)$params[$k]=isset($data[$k])?$data[$k]:$params[$k];
		
		$Orders = $this->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one();
		if(!$Orders)return PARAMSCHECK;	
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPACT'){
			return $statusLabel;
		}
		if(!$Orders->accessOrders('ORDERCOMFIRM',$uid)){
			return USERAUTH;
		}
		$Orders->pact=$params['files'];
		$status = $Orders->save(true);
		if($status){
			return OK;
		}else{
			$this->errors=$Orders->errors;
			return MODELDATASAVE;
		}
	}
	
	/**
	* 	接单协议详情
	*
	*/
	
	public function ordersPactDetail($ordersid = null,$uid = ''){
		$Orders = $this->where(['ordersid'=>$ordersid,'validflag'=>1])->one();
		if(!$Orders)return PARAMSCHECK;		
		if(!$Orders->accessOrders('READ',$uid)){
			return false;
		}
		return ["accessOrdersORDERCOMFIRM"=>$Orders->accessOrders('ORDERCOMFIRM'),"OrdersStatus"=>$Orders->checkStatus(),"pact"=>$Orders->pact,"pacts"=>$Orders->pacts];
	}
	
	/**
	* 	接单协议确认 （开始处置）
	*
	*/
	public function ordersPactConfirm($ordersid = null, $memo='', $uid = ''){
		
		$Orders = $this->where(['ordersid'=>$ordersid,'validflag'=>1])->one();
		if(!$Orders)return PARAMSCHECK;		
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPACT'){
			return $statusLabel;
		}
		if(!$Orders->accessOrders('ORDERCOMFIRM',$uid)){
			return USERAUTH;
		}
		if(count($Orders->pacts)==0){
			return ORDERSPACT;
		}
		$status = $Orders->pactConfirm($uid);
		
		//发送通知
		if($status == 'ok'){
			$message = Message::find();
			$data = $message->addMessage(200,['code'=>$Orders->product->number],$Orders->product->create_by,$Orders->productid,40,$Orders->product->create_by);
			return $status;
		}else{
			return $status;
		}
	}
	
	
	/**
	* 接单进度
	* @param params 数组表单数据
	* @param type 1清收，2查封。
	* @param uid 用户id。
	* @return status
	*/
	public function ordersProcessAdd($params,$type=1, $uid = ''){
		$data = ['memo'=>'','files'=>'','operator'=>'','type'=>'','ordersid'=>'','productid'=>''];
		foreach($data as $key=>$val){
			if(isset($params[$key]))$data[$key]=$params[$key];
		}
		$ProductOrdersProcess = new \app\models\ProductOrdersProcess;
		$status = $ProductOrdersProcess->create($data, $uid );
		$this->processid=$ProductOrdersProcess->processid;
		$this->errors=$ProductOrdersProcess->errors;
		if($status == 'ok'){
			$message = Message::find();
			if($ProductOrdersProcess->create_by == $ProductOrdersProcess->product->create_by){
				$data = $message->addMessage(117,['code'=>$ProductOrdersProcess->product->number],$ProductOrdersProcess->orders->create_by,$ProductOrdersProcess->orders->applyid,50,$ProductOrdersProcess->product->create_by);
			}else{
				$data = $message->addMessage(201,['code'=>$ProductOrdersProcess->product->number],$ProductOrdersProcess->product->create_by,$ProductOrdersProcess->productid,40,$ProductOrdersProcess->create_by);
			}
			return $status;
		}else{
			return $status;
		}
		
	}
	
	/**
	* 结案详情
	* @param producessid 操作id
	* @return 本人操作返回process否则返回false
	*/
	public function ordersProcessDetail($processid = null){
		$Process = ProductOrdersProcess::find()->where(['processid'=>$processid,'validflag'=>1])->one();
		if(!$Process->orders->accessOrders('READ')){
			return false;
		}
		return $Process;
	}
	/**
	* 结案申请业务逻辑
	* @param params 表单数据
	* @param uid 用户id
	* @return status
	*/
	public function ordersClosedApply($params, $uid = ''){
		$data = ['applymemo'=>'','price'=>'','price2'=>'','ordersid'=>''];
		foreach($data as $key=>$val){
			if(isset($params[$key]))$data[$key]=$params[$key];
		}
		
		$ProductOrdersClosed = new ProductOrdersClosed;
		$status = $ProductOrdersClosed->apply($data, $uid );
		$this->closedid=$ProductOrdersClosed->closedid;
		$this->errors=$ProductOrdersClosed->errors;
		if($status == 'ok'){
			$message = \app\models\Message::find();
			$data = $message->addMessage(202,['code'=>$ProductOrdersClosed->product->number],$ProductOrdersClosed->product->create_by,$ProductOrdersClosed->productid,40,$ProductOrdersClosed->create_by);
			return $status;
		}else{
			return $status;
		}
		
	}
	/**
	* 结案详情
	* @param closeddid 结案字段ID
	* @return closed 结案详情
	*/
	public function ordersClosedDetail($closedid = null){
		$Closed = ProductOrdersClosed::find()->where(['closedid'=>$closedid,'validflag'=>1])->one();
		if(!$Closed->orders->accessClosed('READ')){
			return false;
		}
		return $Closed;
	}
	
	/**
	* 结案同意业务逻辑
	* @param closedid 结案字段ID
	* @param resultmemo 结案原因字段
	* @param uid 用户id
	* @return status 结案结果
	*/
	public function ordersClosedAgree($closedid = null, $resultmemo='', $uid = ''){
		
		$Closed = ProductOrdersClosed::find()->where(['closedid'=>$closedid,'status'=>'0','validflag'=>1])->one();
		if(!$Closed||!$Closed->orders||!$Closed->orders->product)return PARAMSCHECK;		
		$statusLabel = $Closed->orders->checkStatus();
		if(!$Closed->orders->accessClosed('AUTH',$uid,[$Closed->create_by])){
			return USERAUTH;
		}
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		// $statusLabel = $Termination->orders->product->checkStatus();
		// if($statusLabel!='ORDERSPROCESS'){
			// return $statusLabel;
		// }
		$Termination = ProductOrdersTermination::find()->where(['productid'=>$Closed->productid,'status'=>'0','validflag'=>1])->one();
		if($Termination){
			$status = $Termination->veto($resultmemo, $uid = '');
		}
		
		//业务事务逻辑
		$innerTransaction = Yii::$app->db->beginTransaction();  
		
		$status = $Closed->agree($resultmemo, $uid);
		if($status!='ok'){
			$innerTransaction->rollBack();
			$this->errors=[["结案不在可操作状态"]];
			return $status."_1";
		}
		$status = $Closed->orders->closed($uid);
		if($status!='ok'){
			$innerTransaction->rollBack();  
			$this->errors=[["接单不在可结案状态"]];
			return $status."_2";
		}
		$status = $Closed->orders->product->closed($uid);
		if($status!='ok'){
			$innerTransaction->rollBack(); 
			$this->errors=[["产品不在可结案状态"]];			
			return $status."_3";
		}
		$innerTransaction->commit();  
		
		//发送通知
		if($status == 'ok'){
			$message = \app\models\Message::find();
			$data = $message->addMessage(110,['code'=>$Closed->product->number],$Closed->create_by,$Closed->orders->applyid,50,$Closed->product->create_by);
			return $status;
		}else{
			return $status;
		}
		
	}
	
	/**
	* 结案否决业务逻辑
	* @param closedid 结案字段ID
	* @param resultmemo 结案原因字段
	* @param uid 用户id
	* @return status 结案是否成功
	*/
	public function ordersClosedVeto($closedid = null, $resultmemo='', $uid = ''){
		$Closed = ProductOrdersClosed::find()->where(['closedid'=>$closedid,'status'=>'0','validflag'=>1])->one();
		if(!$Closed)return PARAMSCHECK;	
		if(!$Closed->orders->accessClosed('AUTH',$uid,[$Closed->create_by])){
			return USERAUTH;
		}
		$status = $Closed->veto($resultmemo, $uid = '');
		if($status!='ok'){ 
			return $status."_1";
		}
		//发送通知
		if($status == 'ok'){
			$message = \app\models\Message::find();
			$data = $message->addMessage(111,['code'=>$Closed->product->number],$Closed->create_by,$Closed->orders->applyid,50,$Closed->product->create_by);
			return $status;
		}else{
			return $status;
		}
		
	}
	
	
	/**
	* 终止申请业务逻辑
	* @param params 数组参数
	* @param uid 用户ID
	*/
	public function ordersTerminationApply($params, $uid = ''){
		$data = ['applymemo'=>'','files'=>'','ordersid'=>''];
		foreach($data as $key=>$val){
			if(isset($params[$key]))$data[$key]=$params[$key];
		}
		$ProductOrdersClosed = new ProductOrdersTermination;
		$status = $ProductOrdersClosed->apply($data, $uid );
		$this->terminationid=$ProductOrdersClosed->terminationid;
		$this->errors=$ProductOrdersClosed->errors;
		if($status == 'ok'){
			$message = \app\models\Message::find();
			if($ProductOrdersClosed->create_by == $ProductOrdersClosed->orders->product->create_by){
			$data = $message->addMessage(112,['code'=>$ProductOrdersClosed->orders->product->number],$ProductOrdersClosed->orders->create_by,$ProductOrdersClosed->orders->applyid,50,$ProductOrdersClosed->orders->product->create_by);
			}else{
				$data = $message->addMessage(203,['code'=>$ProductOrdersClosed->orders->product->number],$ProductOrdersClosed->orders->product->create_by,$ProductOrdersClosed->productid,40,$ProductOrdersClosed->create_by);
			}
			return $status;
		}else{
			return $status;
		}
		
	}
	
	/**
	* 终止详情
	* @param terminationid 状态表的ID
	* @return 终止状态
	*/
	public function ordersTerminationDetail($terminationid = null){
		$Termination = ProductOrdersTermination::find()->where(['terminationid'=>$terminationid,'validflag'=>1])->one();
		if(!$Termination->orders->accessTermination('READ')){
			return false;
		}
		return $Termination;
	}
	
	
	/**
	* 终止同意业务逻辑
	* @param terminationid 状态表的ID
	* @param resultmemo 终止原因
	* @param uid 用户ID
	*/
	public function ordersTerminationAgree($terminationid = null, $resultmemo='', $uid = ''){
		
		$Termination = ProductOrdersTermination::find()->where(['terminationid'=>$terminationid,'status'=>'0','validflag'=>1])->one();
		if(!$Termination||!$Termination->orders||!$Termination->orders->product)return PARAMSCHECK;	
		if(!$Termination->orders->accessTermination('AUTH',$uid,[$Termination->create_by])){
			return USERAUTH;
		}
		$statusLabel = $Termination->orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		$Closed = ProductOrdersClosed::find()->where(['productid'=>$Termination->productid,'status'=>'0','validflag'=>1])->one();
        if($Closed){
			$status = $Closed->veto($resultmemo, $uid = '');
		}
		
		//业务事务逻辑
		$innerTransaction = Yii::$app->db->beginTransaction();  
		
		$status = $Termination->agree($resultmemo, $uid);
		if($status!='ok'){
			$innerTransaction->rollBack();  
			$this->errors=[["终止不在可操作状态"]];			
			return $status."_1";
		}
		$status = $Termination->orders->termination($uid);
		if($status!='ok'){
			$innerTransaction->rollBack();  
			$this->errors=[["产品不在可终止状态"]];			
			return $status."_2";
		}
		$status = $Termination->orders->product->termination($uid);
		if($status!='ok'){
			$innerTransaction->rollBack();  
			$this->errors=[["产品不在可终止状态"]];			
			return $status."_3";
		}
		$innerTransaction->commit();  
		
		//发送通知
		if($status == 'ok'){
			$message = \app\models\Message::find();
			if($Termination->create_by == $Termination->orders->create_by){
				$data = $message->addMessage(113,['code'=>$Termination->orders->product->number],$Termination->orders->create_by,$Termination->orders->applyid,50,$Termination->orders->create_by);	
			}else{
				$data = $message->addMessage(204,['code'=>$Termination->orders->product->number],$Termination->orders->product->create_by,$Termination->productid,40,$Termination->create_by);
			}
			return $status;
		}else{
			return $status;
		}
		
	}
	/**
	* 终止否决业务逻辑
	* @param terminationid 状态表的ID
	* @param resultmemo 终止原因
	* @param uid 用户ID
	*/
	public function ordersTerminationVeto($terminationid = null, $resultmemo='', $uid = ''){
		$Termination = ProductOrdersTermination::find()->where(['terminationid'=>$terminationid,'status'=>'0','validflag'=>1])->one();
		
		if(!$Termination)return PARAMSCHECK;	
		if(!$Termination->orders->accessTermination('AUTH',$uid,[$Termination->create_by])){
			return USERAUTH;
		}
		
		$status = $Termination->veto($resultmemo, $uid = '');
		if($status!='ok'){ 
			return $status."_1";
		}
		//发送通知
		if($status == 'ok'){
			$message = \app\models\Message::find();
			if($Termination->create_by == $Termination->orders->create_by){
				$data = $message->addMessage(114,['code'=>$Termination->orders->product->number],$Termination->orders->create_by,$Termination->orders->applyid,50,$Termination->create_by);	
			}else{
				$data = $message->addMessage(205,['code'=>$Termination->orders->product->number],$Termination->orders->product->create_by,$Termination->productid,40,$Termination->create_by);
			}
			return $status;
		}else{
			return $status;
		}
		
	}
	
	public function formatErrors($isAll=false)
    {
        $result = '';
        foreach($this->errors as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
			if(!$isAll)break;
        }
        return $result;
    }
	
	/**
	 * 订单查询条件模块
	 * @param params 数组参数
	 * @param uid 用户id
	 * @param isobj true 返回对象，false返回数组
	 * @param pageload true 需要分页，false不需要。
	 */
	public  function searchOrder($params=[],$uid='',$isobj=true,$pageload = true,$isOperators = 0,$isdel = false)
    {
		$query = ProductApply::find();
		
		$orderby = isset($params["orderby"])?$params["orderby"]:'';
		$this->listtype = isset($params["listtype"])?$params["listtype"]:'';
		$this->uid = $uid;
		$query->alias("apply");
		if(!$isobj){
			$query->asArray();
		}
		if($pageload){
			$page = isset($params["page"])?$params["page"]:1;
			$limit = isset($params["limit"])?$params["limit"]:10;
			$query->offset(($page-1)*$limit);
			$query->limit($limit);
		}
		if(!$isdel){
			$query->where(['apply.validflag' => '1']);
		}
		
		
		switch($this->listtype){
			case "processing":
				$query->andFilterWhere(["or",["not in" , 'orders.status' ,['30','40']],'orders.ordersid is null']);
				break;
			case "completed":
				$query->andFilterWhere(['orders.status' => '40']);
				break;
			case "aborted":
				$query->andFilterWhere(['orders.status' => '30']);
				break;
		}
		$query->joinWith( 
		[
			'product'=>function($query){
				$query->joinWith([
					"provincename",
					"cityname",
					"areaname",
					"fabuuser",
					"productComment",
				]);
			},
			'createuser',
			"certification",
			'orders',
		]);
		
		if($this->uid){
			if($isOperators){
				$query->joinWith( 
					[
						'orders'=>function($query){
							$query->joinWith([
								// "product"
								// "product"
								'productOrdersOperators'=>function($query){
									$query->andWhere(["operators.operatorid"=>$this->uid]);
								},
								// 'productOrdersProcesses',
								// 'productOrdersComments',
							]);
						},
					]);
			}else{
				$query->andFilterWhere(['apply.create_by' => $this->uid]);
			}
			
		}
		if(isset($params['applyid'])){
			$query->andFilterWhere(['apply.applyid' => $params['applyid']]);
		}
        return $query;
    }
	/**
	 * 订单查询列表模块
	 * @param params 数组参数
	 * @param uid 用户id
	 * @param isobj true 返回对象，false返回数组
	 */
	public  function searchOrderList($params=[],$uid='',$isobj=true,$isOperators = 0)
    {
		
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$orderby = isset($params["orderby"])?$params["orderby"]:'';
		$query = $this->searchOrder($params,$uid,$isobj,false,$isOperators);
		// $query->joinWith(
			// ['productOrdersLogs',
			// 'productOrdersProcesses',
			// 'productOrdersComments',]
		// );
		
        $dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_at' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);


        return $dataProvider;
    }
	
	/**
	 * 返回数据处理
	 */
	public function filterOne($data){
		$ApplyStatus = ProductApply::$status;
		// 状态处理
		switch($data['status']){
			case 40:
				if(isset($data['product'])&&$data['product']){
					$ProductStatus = Product::$status;
					$data['statusLabel']=$ProductStatus[$data['product']['status']];
				}else{
					$data['statusLabel']=$ApplyStatus[$data['status']];
				}
				break;
			default :
				$data['statusLabel']=$ApplyStatus[$data['status']];
				break;
		}
		if(isset($data['product'])&&$data['product']){
			$data['product'] = Product::find()->filterOne($data['product']);
		}
		// 
		return $data;
	}
	
	/**
	 * 返回数据处理
	 */
	public function filterAll($list){
		foreach($list as $item=>$data){
			$list[$item]= $this->filterOne($data);
		}
		return $list;
	}
	
	/**
	* 接单经办人
	* @param ordersid 接单经办人的ID
	* @param isobj true 返回对象，false返回数组
	*/
	public function ordersOperatorList($ordersid,$isobj = true){
		$this->where(['ordersid'=>$ordersid]);
		$isArray = $isobj?false:true;
		$Order = $this->one();
		if(!$Order)return PARAMSCHECK;
		if(!$Order->accessOrders('READ')){
			return PARAMSCHECK;
		}
		
		$productOrdersOperators['operators'] = $Order->getProductOrdersOperators($isArray);
		$productOrdersOperators['accessOrdersADDOPERATOR'] = $Order->accessOrders('ADDOPERATOR');
		$productOrdersOperators['orders']=$Order->toArray();
		$productOrdersOperators['orders']['createuser']=$Order->createuser->toArray();
		$productOrdersOperators['orders']['createuser']['headimg']=$Order->createuser->headimg->toArray();
		return $productOrdersOperators;
		 
		 
	}
	
	/**
	*  分配经办人
	* @param ordersid 接单经办人的ID
	* @param uid 用户ID
	* @param operatorIds 经办人的ID
	*/
	public function ordersOperatorSet($ordersid, $operatorIds, $uid = ''){
		$params['ordersid']=$ordersid;
		$params['operatorIds']=$operatorIds;
		
		$ProductOrdersOperator = new ProductOrdersOperator;
		$status = $ProductOrdersOperator->set($params, $uid );
		$this->errors = $ProductOrdersOperator->errors;
		if($status == 'ok'){
			$message = Message::find();
			$data = $message->addMessage(209,['code'=>$ProductOrdersOperator->orders->product->number],$ProductOrdersOperator->operatorid,$ProductOrdersOperator->orders->applyid,50,$ProductOrdersOperator->orders->product->create_by);
			return $status;
		}else{
			return $status;
		}
		
		
		
		
		
	}
	
	/**
	*  取消经办人
	* @param ordersid 接单经办人的ID
	* @param uid 用户ID
	* @param operatorIds 经办人的ID
	*/
	public function ordersOperatorUnset($ordersid, $ids, $uid = ''){
		$params['ordersid']=$ordersid;
		$params['ids']=$ids;
		$ProductOrdersOperator = new ProductOrdersOperator;
		$status = $ProductOrdersOperator->recy($params, $uid );
		$this->errors = $ProductOrdersOperator->errors;
		//$message = Message::find();
		//var_dump($ProductOrdersOperator->orders->product->number);die;
		//$data = $message->addMessage(209,['code'=>$ProductOrdersOperator->orders->product->number],$ProductOrdersOperator->create_by,$ProductOrdersOperator->orders->applyid,50);
		return $status;
	}
	
	/**
	*  接单日志
	* @param ordersid 接单经办人的ID
	* @param isobj true 返回对象，false返回数组
	*/
	public function ordersLogs($ordersid,$isobj = true){
		$this->where(['ordersid'=>$ordersid]);
		$isArray = $isobj?false:true;
		$Order = $this->one();
		if(!$Order)return PARAMSCHECK;
		if(!$Order->accessOrders('READ')){
			return false;
		}
		$productOrdersOperators = $Order->getProductOrdersLogs($isArray);
		
		return $productOrdersOperators;
		 
	}
	
	/**
	*  接单评价列表
	* @param ordersid 接单经办人的ID
	* @param isobj true 返回对象，false返回数组
	*/
	public function commentList($ordersid,$isobj = true){
		$this->where(['ordersid'=>$ordersid]);
		$isArray = $isobj?false:true;
		$Order = $this->one();
		if(!$Order)return PARAMSCHECK;
		if(!$Order->accessOrders('READ')){
			return false;
		}
		// return $Order->getProductOrdersComments($isArray);
		$productOrdersOperators ["orders"]= $Order->toArray();
		$productOrdersOperators ["accessOrdersADDCOMMENT"]= $Order->accessOrders('ADDCOMMENT');
		$productOrdersOperators ["Comments1"]= $Order->getProductOrdersComments1($isArray);
		$productOrdersOperators ["Comments2"]= $Order->getProductOrdersComments2($isArray);
		foreach($productOrdersOperators ["Comments1"] as $key => $value){
			  if($value['files']){
				 $ids=explode(",",$value["files"]);
				 $productOrdersOperators ["Comments1"][$key]["filesImg"]=\app\models\Files::getFiles($ids); 
			  }
		}
		foreach($productOrdersOperators ["Comments2"] as $key => $value){
			  if($value['files']){
				 $ids=explode(",",$value["files"]);
				 $productOrdersOperators ["Comments2"][$key]["filesImg"]=\app\models\Files::getFiles($ids); 
			  }
		}
		
		return $productOrdersOperators;
		 
	}
	/**
	*  接单普通评价
	* @param params 数组参数
	* @param uid 用户ID
	*/
	public function commentAdd($params, $uid = ''){
		
		$params['type'] = "1";
		$ProductOrdersComment = new ProductOrdersComment;
		$status = $ProductOrdersComment->add($params, $uid );
		$this->errors = $ProductOrdersComment->errors;
		$this->commentid = $ProductOrdersComment->commentid;
		if($status == 'ok'){
			$message = \app\models\Message::find();
			if($ProductOrdersComment->action_by == $ProductOrdersComment->orders->product->create_by){
				$data = $message->addMessage(115,['code'=>$ProductOrdersComment->orders->product->number],$ProductOrdersComment->orders->create_by,$ProductOrdersComment->orders->applyid,50,$ProductOrdersComment->orders->product->create_by);
			}else{
				$data = $message->addMessage(206,['code'=>$ProductOrdersComment->orders->product->number],$ProductOrdersComment->orders->product->create_by,$ProductOrdersComment->productid,40,$ProductOrdersComment->action_by);
			}
			return $status;
		}else{
			return $status;
		}
		
		 
	}
	/**
	*  接单普通评价
	* @param params 数组参数
	* @param uid 用户ID
	*/
	public function commentAdditional($params, $uid = ''){
		
		$params['type'] = "2";
		$params['tocommentid']=$params['tocommentid']?$params['tocommentid']:0;
		$ProductOrdersComment = new ProductOrdersComment;
		$status = $ProductOrdersComment->add($params, $uid );
		$this->errors = $ProductOrdersComment->errors;
		$this->commentid = $ProductOrdersComment->commentid;
		if($status == 'ok'){
			$message = \app\models\Message::find();
			if($ProductOrdersComment->action_by == $ProductOrdersComment->orders->product->create_by){
				$data = $message->addMessage(116,['code'=>$ProductOrdersComment->orders->product->number],$ProductOrdersComment->orders->create_by,$ProductOrdersComment->orders->applyid,50,$ProductOrdersComment->orders->product->create_by);
			}else{
				$data = $message->addMessage(207,['code'=>$ProductOrdersComment->orders->product->number],$ProductOrdersComment->orders->product->create_by,$ProductOrdersComment->productid,40,$ProductOrdersComment->action_by);
			}
			return $status;
		}else{
			return $status;
		}
		
		 
	}
	
	/**
	*  接单详情
	* @param params 数组参数
	* @param uid 用户ID
	*/
	public function ordersDetail($applyid, $uid = ''){
		
		if(!$applyid)return [];
		$query = $this->searchOrder(["applyid"=>$applyid],'',false,true,0,true); 
		$query->joinWith( 
		[
			'orders'=>function($query){
				$query->joinWith([
					'productOrdersOperators', 
					'productOrdersLogs'=>function($query){
						$query->joinWith("actionUser");
					} 
				]);
			},
		]);
		$data = $query->one();
		if($data){
			$data = $this->filterOne($data);
			$dataobj = $this->where(["applyid"=>$data['applyid']])->one();
			// var_dump($data);
			
			if($dataobj){
				$data['accessOrdersREAD'] = $dataobj->accessOrders('READ');
				$data['accessOrdersADDPROCESS'] = $dataobj->accessOrders('ADDPROCESS');
				$data['accessOrdersADDOPERATOR'] = $dataobj->accessOrders('ADDOPERATOR');
				$data['accessOrdersADDCOMMENT'] = $dataobj->accessOrders('ADDCOMMENT');
				$data['accessOrdersORDERCOMFIRM'] = $dataobj->accessOrders('ORDERCOMFIRM');
				$data['accessClosedREAD'] = $dataobj->accessClosed('READ');
				$data['accessClosedAPPLY'] = $dataobj->accessClosed('APPLY');
				$data['accessClosedAUTH'] = $dataobj->accessClosed('AUTH');
				$data['accessTerminationREAD'] = $dataobj->accessTermination('READ');
				$data['accessTerminationAPPLY'] = $dataobj->accessTermination('APPLY');
				$data['accessTerminationAUTH'] = $dataobj->accessTermination('AUTH');
				$data['checkStatus']= $dataobj->checkStatus();
				$data['myCommentTotal'] = ProductOrdersComment::find()->where(['productid'=>$data['productid'],'validflag'=>'1','action_by'=>Yii::$app->user->getId()])->count('productid');
				$data['productOrdersCommentsNum'] = $dataobj->ProductOrdersCommentsNum;
				if($data['status']=='40'){
					$data['SignPicture'] = $dataobj->pacts;	
					$data['productOrdersClosed'] = $dataobj->productOrdersClosed;
				}
				$Operators = [];
				// var_dump($data['orders']['productOrdersOperators']);
				foreach($data['orders']['productOrdersOperators'] as $v)$Operators[]=$v['operatorid'];
				$data['orders']['Operators']=$Operators;
				$actionLabels = ProductOrdersLog::$actionLabels;
				if($data['orders']&&$data['orders']['productOrdersLogs']){
					$data['orders']['productOrdersLogs'] = ProductOrdersLog::filterAll(
					$data['orders']['productOrdersLogs'],
					$data['accessTerminationAUTH'],
					$data['accessClosedAUTH'],
					$data['checkStatus'],
					$data['product']["create_by"],
					$data['orders']["create_by"],
					$data['orders']['productOrdersOperators'],
					$data
					);
					/*foreach($data['orders']['productOrdersLogs'] as $key => $productOrdersLogs){
						
						$data['orders']['productOrdersLogs'][$key]['actionLabel']=$actionLabels[$productOrdersLogs['action']];
						
						if($productOrdersLogs["files"]){
							$ids=explode(",",$productOrdersLogs["files"]);
							$data['orders']['productOrdersLogs'][$key]["filesImg"]=\app\models\Files::getFiles($ids);
						}else{
							$data['orders']['productOrdersLogs'][$key]["filesImg"]=[];
						}
						$data['orders']['productOrdersLogs'][$key]['trigger']=false;
						$data['orders']['productOrdersLogs'][$key]['triggerLabel']='';
						if(in_array($productOrdersLogs['action'],[40,50])){
							if($productOrdersLogs['action']==50&&$productOrdersLogs['action_by']!=$uid&&$data['accessTerminationAUTH']==true&&$data['checkStatus']=='ORDERSPROCESS'&&$productOrdersLogs['relatrigger']=='0'){
								$data['orders']['productOrdersLogs'][$key]['trigger']=true;
								$data['orders']['productOrdersLogs'][$key]['triggerLabel']="点击处理";
							}else if($productOrdersLogs['action']==40&&$productOrdersLogs['action_by']!=$uid&&$data['accessClosedAUTH']==true&&$data['checkStatus']=='ORDERSPROCESS'&&$productOrdersLogs['relatrigger']=='0'){
								$data['orders']['productOrdersLogs'][$key]['trigger']=true;
								$data['orders']['productOrdersLogs'][$key]['triggerLabel']="点击处理";
							}
						}
						
						$label = "系";
						$class = "system";
						if($productOrdersLogs['action']!=10){
							$userid = Yii::$app->user->getId();
							if($productOrdersLogs['action_by']==$userid){
								$label = "我";
								$class = "me";
							}else{
								if(in_array($productOrdersLogs['action_by'],$Operators)){
									$label = "经";
									$class = "jing";
								}else if($productOrdersLogs['action_by']==$data['orders']["create_by"]){
									$label = "接";
									$class = "me";
								}else if($productOrdersLogs['action_by']==$data['product']["create_by"]){
									$label = "发";
									$class = "";
								}
							}
						}
						$data['orders']['productOrdersLogs'][$key]['class']=$class;
						$data['orders']['productOrdersLogs'][$key]['label']=$label;
						
					}*/
				}
				
			}
			$data['productOrdersTerminationsApply']= $dataobj?$dataobj->productOrdersTerminationsApply:[];
			$data['productOrdersClosedsApply']= $dataobj?$dataobj->productOrdersClosedsApply:[];
			// $data['productOrdersOperators']= $dataobj?$dataobj->productOrdersOperators:[];
			$data['productOrdersTerminationsApplyCount']= count($data['productOrdersTerminationsApply']);
			$data['productOrdersClosedsApplyCount']= count($data['productOrdersClosedsApply']);
			$data['productOrdersOperatorsCount']= count($data['orders']['productOrdersOperators']);
			$data['hascertification']= (new Product)->CertificationUser()?"1":"0";
		}

		return $data;
		 
	}
	
	/**
	* 删除接单
	*/
	public function applyDel($applyid){
		$Apply = ProductApply::findOne(['applyid'=>$applyid]);
		if(!$Apply)return PARAMSCHECK;
		// if(!$Apply->productOrders->accessOrders('READ')){
			// return false;
		// }
		$status = $Apply->applyDel($applyid);
		if($status){
			return OK;
		}else{
			$this->errors = $Order->errors;
			return MODELDATASAVE;
		}
		
	}
	
}
