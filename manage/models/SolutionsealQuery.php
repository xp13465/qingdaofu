<?php

namespace app\models;
use Yii;
use app\models\AuditLog;
use app\models\Solutionseal;
/**
 * This is the ActiveQuery class for [[Solutionseal]].
 *
 * @see Solutionseal
 */
class SolutionsealQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Solutionseal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Solutionseal|array|null
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
	public function ordersGenerate($solutionsealid = null, $uid = '',$kefuID="992",$pactId=[]){
		$this->alias('seal');
		$solutionseal =$this->where(['seal.solutionsealid'=>$solutionsealid,'seal.validflag'=>1])->one();
	    //$uid = $uid?$uid:$solutionseal['personnelModel']['userid']['id'];
		if(!$solutionseal)return PARAMSCHECK;
		if($solutionseal->status!="40")return 'UNDONE';
		// var_dump($kefuID);die;
		 // $data = \app\models\Product::find()->one();;
		 // var_dump($data);exit;
		// var_dump($solutionseal->product);
		// var_dump($solutionseal->product->productOrders);
		// exit;
		if($solutionseal->product&&$solutionseal->product->productOrders)return 'EXISTED';
		// $status = $solutionseal->generate();
		// $solutionseal->productid
		// var_dump($solutionseal);
		$time = time();
		$Product = new Product();
		$number = \manage\services\Func::createCatCode(6);
		$Product->setAttributes([
			"number"=>$number,
			"category"=>"1",
			"category_other"=>"",
			"entrust"=>"4",
			"entrust_other"=>"垫资解查封",
			"account"=>$solutionseal->account,
			"type"=>$solutionseal->type,
			"typenum"=>$solutionseal->typenum,
			"overdue"=>$solutionseal->overdue,
			"province_id"=>$solutionseal->province_id,
			"city_id"=>$solutionseal->city_id,
			"district_id"=>$solutionseal->district_id,
			"status"=>'20',
			"validflag"=>'1',
			"create_at"=>$time,
			"create_by"=>$kefuID,
		]);
		$status = OK;
		if($Product->save()){
			$ProductApply = new ProductApply();
			$ProductApply->setAttributes([
				"status"=>'40',
				"productid"=>$Product->productid,
				"validflag"=>'1',
				"create_at"=>$time,
				"create_by"=>$uid,
			]);
			if($ProductApply->save()){
				$status = ProductOrders::find()->ordersGenerate($ProductApply->applyid,$uid);
			}else{
				$status = MODELDATASAVE;
			}
			
		}else{
			$status = MODELDATASAVE;
		}
		if($status==OK){
			$num = $solutionseal->updateAll(["productid"=>$Product->productid,"number"=>$Product->number],["productid"=>"0","solutionsealid"=>$solutionseal->solutionsealid]);
			
			if($pactId){
				$id = $this->pacts($pactId);
				$this->agreement($solutionseal->solutionsealid,$juid='',$fuid='',$id);
			}
			if($num>=0){
				$message = Message::find();
				$message->addMessage(109,['code'=>$Product->number],$ProductApply->create_by,$ProductApply->applyid,50,$Product->create_by);
			}else{
				$status = MODELDATASAVE;
			}
		}
		return $status;
		// $this->ordersid = $ProductOrders->ordersid;
		// return $status;
	}
	
	/**
	* 后台回款前台结案记录
	* @param solutionsealid 
	* @return status
	*/
	public function record($solutionsealid = null,$memo='',$juid='',$cuid){
		$this->alias('seal');
		$solutionseal =$this->where(['seal.solutionsealid'=>$solutionsealid,'seal.validflag'=>1])->one();
		if(!$solutionseal)return PARAMSCHECK;
		if($solutionseal->status!="60")return 'UNDONE';
		if(!$solutionseal->orderData)return 'EXISTED';
		$params = ['applymemo'=>$memo,'price'=>$solutionseal->backmoney,'price2'=>$solutionseal->actualamount,'ordersid'=>$solutionseal->orderData['ordersid']];
		$productOrders = ProductOrders::find();
		$uid = $juid?$juid:$solutionseal->orderData['create_by'];
		$status = $productOrders->ordersClosedApply($params,$uid);
		if($status == OK){
			$params = ['backmoney'=>$solutionseal->backmoney,'payinterest'=>$solutionseal->payinterest,'actualamount'=>$solutionseal->actualamount];
			$closed = $productOrders->ordersClosedAgree($solutionseal->closedid['closedid'],$memo,$solutionseal->product['create_by'],$params);
			if($closed){
				$message = Message::find();
				$message->addMessage(121,['code'=>$solutionseal->product['number']],$solutionseal->product['create_by'],$solutionseal->orderData['applyid'],50,$cuid);
				return OK;
			}else{
				return $closed;
			}
		}else{
			return $status;
		}
	}
	
	/**
	* 放款记录
	*/
	
	public function Contract($solutionsealid = null,$juid=''){
			$this->alias('seal');
			$solutionseal =$this->where(['seal.solutionsealid'=>$solutionsealid,'seal.validflag'=>1])->one();
			if(!$solutionseal)return PARAMSCHECK;
			if($solutionseal->status!="40")return 'UNDONE';
			if(!$solutionseal->orderData)return 'EXISTED';
			$uid = $juid?$juid:$solutionseal->orderData['create_by'];
			
			$ProductOrdersLog = new ProductOrdersLog;
			
			$data = $ProductOrdersLog->create($solutionseal->orderData['ordersid'],"0",'放款金额:'.$solutionseal->borrowmoney,20,20,27,1,$uid);
			if($data){
				$message = Message::find();
				$message->addMessage(120,['code'=>$solutionseal->product['number']],$solutionseal->product['create_by'],$solutionseal->orderData['applyid'],50,$juid);
			}
			return $data;
	}
	
	/**
	* 订单确认，生成协议
	*/
	
	public function agreement($solutionsealid = null,$juid='',$fuid='',$pactId=''){
			$this->alias('seal');
			$solutionseal =$this->where(['seal.solutionsealid'=>$solutionsealid,'seal.validflag'=>1])->one();
			if(!$solutionseal)return PARAMSCHECK;
			if($solutionseal->status!="40")return 'UNDONE';
			if(!$solutionseal->orderData)return 'EXISTED';
			$uid = $juid?$juid:$solutionseal->orderData['create_by'];
			$status = $solutionseal->orderData->updateAll(['status'=>'20','pact'=>$pactId,'modify_at'=>time(),'modify_by'=>$uid],['status'=>'0','validflag'=>'1']);
			if($status){
				return OK;
			}
	}
	
	
	
	/**
	* 产品审核
	* @param id
	* @return params
	* @return memo
	*/
	public function audit($id,$params=[]){
         $query = $this->where(['solutionsealid'=>$id])->one();
		 if($params['beforestatus'] == $query->status){
			 switch($params['afterstatus']){
				 case '12':
				   $status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'windcontrolId'=>Yii::$app->user->identity->personnel->id,
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
				 break;
				 case '20':
				   $status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'windcontrolId'=>Yii::$app->user->identity->personnel->id,
								'type' => $params['type'],
								'typenum'=>$params['typenum'],
								'account'=>$params['account'],
								'custname'=>$params['custname'],
								'custmobile'=>$params['custmobile']
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
				 break;
				 case '30':
				 $status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'pact' => $params['pact'],
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
				 break;
				 case '40':
					if($params['afterstatus'] == $params['beforestatus']){
							$status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'borrowmoney'=>$params['borrowmoney'],
								'borrowtime'=>time(),
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
							}else{
							$status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'earnestmoney'=>$params['earnestmoney'],
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
							}
				 break;
				 case '60':
				     	$status = $query->updateAll([
								'modify_at'=>time(),
								'modify_by'=>Yii::$app->user->getId(),
								'status'=>$params['afterstatus'],
								'payinterest'=>$params['payinterest'],
								'actualamount'=>$params['actualamount'],
								'backmoney'=>$params['backmoney'],
								'closeddate'=>time(),
								],
								[
								'solutionsealid'=>$id,
								'validflag'=>'1',
								'status'=>$params['beforestatus']
								]);
						
				break;
				 
			 }
			 if($status){
				 $AuditLog  = new AuditLog();
				 
				 $data = [
				    'relatype'=>'1',
					'relaid'=>$query->solutionsealid,
					'afterstatus'=>$params['afterstatus'],
					'beforestatus'=>$params['beforestatus'],
					'action_by'=>Yii::$app->user->getId(),
					'action_at'=>time(),
					'memo'=>$params['memo'],
					'params'=>serialize($params),
				];
				$AuditLogStatus = $AuditLog->create($data);
				if($AuditLogStatus){
					return OK;
				}else{
					return MODELDATACHECK;
				}
			 }
		 }else{
			 return PAGETIMEOUT;
		 }
	}
	
	public function auditLog(){
		$query = AuditLog::find()->where(['action_by'=>Yii::$app->user->getId(),'relatype'=>'1'])->select('relaid')->Asarray()->all();
		$data = [];
		foreach($query as $key=>$value){
			$data[] = $value['relaid'];
		}
		return $data;
	}
	
	
	public function solutionsealDelete($id,$validflag){
		if(!$id)return NotFound;
		$query = $this->andFilterWhere(['solutionsealid'=>$id])->one();
		if($query->create_by == Yii::$app->user->getId()){
			$status = $query->updateAll(['validflag'=>$validflag,'modify_by'=>Yii::$app->user->getId(),'modify_at'=>time()],['solutionsealid'=>$id,'validflag'=>['0','1']]);
			if($status){
				return OK;
			}else{
				return PARAMSCHECK;
			}
		}else{
			return USERAUTH;
		}
	}
	
	//判断产品是否和自己有关
	private function relationship($solutionsealid){
		if(!$solutionsealid)return false;
		$query = Solutionseal::find();
		$data = $query->where(['solutionsealid'=>$solutionsealid,'validflag'=>'1'])->joinWith(['audit'])->asArray()->all();
		if($data){
			return true;
		}else{
			return false;
		}
	}
	
	//产品数据展示
	public function productView($productid){
		if(!$productid)return PARAMSCHECK;
		$query = Solutionseal::find();
		$query->alias('solutionseal');
		$query->where(['solutionseal.productid'=>$productid,'solutionseal.validflag'=>'1']);
		$query->joinWith(['auditLog']);
        $data=$query->One();		
		if($query){
			// $status = $this->relationship($data['solutionsealid']);
			// if(!$status)return USERAUTH;
			return $data;
		}
	}
	
	public function statisticsQuery($field,$where ,$monthField ="create_at" ,$month = "",$curYear=''){
		$defaultField = ["create_at"=>"FROM_UNIXTIME({$monthField}, '%c')"];
		$defaultField = $defaultField+$field;

		$this->where($where)->select($defaultField)->groupBy(["FROM_UNIXTIME({$monthField}, '%Y%c')"]);
		$curYear =$curYear?$curYear:date("Y",time());
		if($month){
			$this->andWhere(["FROM_UNIXTIME({$monthField}, '%c')"=>$month]);
			$this->andWhere(["FROM_UNIXTIME({$monthField}, '%Y%c')"=>$curYear.$month]);
			$return = [$month=>"0"];
		}else{
			$this->andWhere(["FROM_UNIXTIME({$monthField}, '%Y')"=>$curYear]);
			$return = ["1"=>"0","2"=>"0","3"=>"0","4"=>"0","5"=>"0","6"=>"0","7"=>"0","8"=>"0","9"=>"0","10"=>"0","11"=>"0","12"=>"0"];
		}
		$data = $this->all();
		$keys = array_keys($field);
		foreach($data as $d){
			if(count($keys)==1){
				$return[$d['create_at']]=$d[$keys[0]]?$d[$keys[0]]:'0';
			}else{
				foreach($keys as $k){
					$return[$d['create_at']][$k]=$d[$k]?$d[$k]:'0';
				}
			}
			
		}
		return  $return;
	}
	
	
	 
	 
	
	public function statistics($month = ""){//,
		$return = [];
		$field = ["status"=>"count(*)"];
		//销售进件统计  参考标准 进件时间
		$return['jinjianNum'] = $this->statisticsQuery($field,"status <> 0","create_at",$month);
		//风控接单统计  参考标准 进件时间
		$return['jiedanNum'] = $this->statisticsQuery($field,["status"=>["40","50","60","70"]],"create_at",$month);
		//风控结案统计  参考标准 结案时间
		$return['jieanNum'] = $this->statisticsQuery($field,["status"=>["60","70"]],"closeddate",$month);
		
		//月支付利息统计 参考标准 结案时间
		$field = ["payinterest"=>"sum(payinterest)"];
		$return['payinterestSum'] = $this->statisticsQuery($field,["status"=>["60","70"]],"closeddate",$month);
		//月佣金统计 参考标准 结案时间
		$field = ["actualamount"=>"sum(actualamount)"];
		$return['actualamountSum'] = $this->statisticsQuery($field,["status"=>["60","70"]],"closeddate",$month);
		//月回款统计 参考标准 结案时间
		$field = ["backmoney"=>"sum(backmoney)"];
		$return['backmoneySum'] = $this->statisticsQuery($field,["status"=>["60","70"]],"closeddate",$month);
		
		//月放款统计 参考标准 放款时间
		$field = ["borrowmoney"=>"sum(borrowmoney)"];
		$return['borrowmoneySum'] = $this->statisticsQuery($field,["status"=>["40","50","60","70"]],"borrowtime",$month);
		
		$curMonth = date("n",time());

// var_dump($curMonth);exit;
		$return['jinjianTopList'] = $this->topList("status <> 0","create_at",$curMonth);
		$return['jiedanTopList'] = $this->topList(["status"=>["40","50","60","70"]],"create_at",$curMonth);
		$return['jieanTopList'] = $this->topList(["status"=>["60","70"]],"closeddate",$curMonth);
		
		return $return;
		// var_dump($return);
	}
	
	
	public function topList($where,$monthField,$month,$limit = 6,$curYear=''){
		
		$this->where($where)->select(["create_at"=>"FROM_UNIXTIME({$monthField}, '%c')","status"=>"count(status)","personnel_name"])->groupBy(["personnel_name","FROM_UNIXTIME({$monthField}, '%Y%c')"])->asArray()->orderBy("count(status) desc" )->limit($limit);
		$curYear =$curYear?$curYear:date("Y",time());
		$this->andWhere(["FROM_UNIXTIME({$monthField}, '%Y%c')"=>$curYear.$month]);
		$return = [];
		 
		$data = $this->all();
		// var_dump($data);

		return  $data;
	}
	
	//复制图片
	public function pacts($params=[]){
		$id = '';
		foreach($params as $value){
			$model = new \app\models\QdfFiles();
			$backFile = "./".$value['addr'];
			$wwwFile = "../../frontend/web/".$value['addr'];
			if(!file_exists($wwwFile)){
				$savePath = dirname($wwwFile);
				if (!file_exists($savePath)) {
					mkdir($savePath,0777,true);
				}
				copy($backFile,$wwwFile);
			}
            $model->isNewRecord = true;  
			$model->setAttributes($value);  
			$model->save();//&& $model->id=0;
			$id .= $id?','.$model->id:$model->id;
		};
		return $id;
	}
}
