<?php  
namespace frontend\modules\wap\controllers;
use yii;
use frontend\modules\wap\components\WapController;
use app\models\Protectright;
use frontend\modules\wap\services\Func;
//use frontend\modules\wap\components\WapNoLoginController;

/**
 * Producedata
 * 
 * @package git
 * @author www.bokeboke.net
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class ProducedataController extends WapController{
    
    
    /**
     * 保全接口
     * 
     */
     
     public function actionIndex(){
          if(Yii::$app->request->post()){
            if(Yii::$app->request->post('id')){
                  $model = $model = \app\models\Protectright::findOne(['id'=>Yii::$app->request->post('id')]);;
		          $model->account=(int)($model->account/10000);
		          $post=Yii::$app->request->post();
                  if ($post&&$model->change($post,false)) {
                     echo json_encode(['code'=>'0000','msg'=>'编辑成功']);
                    }else{
                     $msg = explode(' ',$model->formatErrors());
                     echo json_encode(['code'=>'8001','msg'=>$msg[0]]);
                 }  
            }else{
                $model = new \app\models\Protectright();
  		        $post=Yii::$app->request->post();

                if(!isset($post['area_pid'])){
					  $this->errorMsg("BaoquanArea_id",'');die;  
				  }
                if(!isset($post['area_id'])){
					  $this->errorMsg("BaoquanArea_id",'');die;  
				       }
                if(!isset($post['fayuan_id'])){
					  $this->errorMsg("BaoquanFayuan_id",'');die;  
				       }
                if(!isset($post['category'])){
					  $this->errorMsg("BaoquanCategory",'');die;  
				       }
                if(!isset($post['phone'])){
					  $this->errorMsg("BaoquanPhone",'');die;  
				   }else if(isset($post['phone'])&&!Func::isTel($post['phone'])){
						 $this->errorMsg("BaoquanPhones",'');die; 
				}
                if(!isset($post['account']) || isset($post['account'])&&trim($post['account']) == ''){
					  $this->errorMsg("BaoquanAccount",'');die;  
				 }else if(isset($post['account'])&&!Func::isDecimal(trim($post['account']))){
					 $this->errorMsg("BaoquanAccounts",'');die; 
				 }			 
				if($post['type'] == 1){
					if(!isset($post['fayuan_address'])|| isset($post['fayuan_address'])&&trim($post['fayuan_address']) == ''){
					  $this->errorMsg("BaoquanAddresss",'');die;  
				       }
				}else{
					if(!isset($post['address']) || isset($post['address'])&&trim($post['address']) == ''){
					  $this->errorMsg("BaoquanAddress",'');die;
				  }
				}
				
                if ($post&&$model->change($post,true)){
					$smsbak = array( 
								// 'mobile' => '15316602556',
								'mobile' => '13918500509',
								'msg' => $model->phone . '已申请保全,请进入后台处理...'
							);
					\frontend\services\Func::curl(2,$smsbak);
					
					$smsbak = array( 
								'mobile' => '15658635519',
								'msg' => $model->phone . '已申请保全,请进入后台处理...'
							);
					\frontend\services\Func::curl(2,$smsbak);
					$message = \app\models\Message::find();
					$data = $message->addMessage(300,['code'=>$model->number],Yii::$app->user->getId(),$model->id,10,$model->create_user);
					echo json_encode(['code'=>'0000','msg'=>'申请成功',"result"=>['id'=>$model->id]]);
                 }else{
					$msg = explode(' ',$model->formatErrors());
					echo json_encode(['code'=>'8001','msg'=>$msg[0]]);
                 }    
            }        
          }        
     }
	 
     //保全列表
     public function actionBaoquanlist(){
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
        $uid = Yii::$app->session->get('user_id');
        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
		$type = Yii::$app->request->post('type');
		if($type == 1 ){
			$data = (new \yii\db\Query())
                        ->select('id,status,number,account,fayuan_name,fayuan_address,address,create_time,type,qisu,caichan,zhengju,anjian')
                        ->from('zcb_protectright')
                        ->where(['create_user'=>$uid,'status'=>[1,10,20,30]])
						->offset(($page-1)*$limit)
                        ->limit($limit)
						->orderBy(['create_time'=>SORT_DESC])
                        ->all();
		}else{
			$data = (new \yii\db\Query())
                        ->select('id,status,number,account,fayuan_name,fayuan_address,address,create_time,type,qisu,caichan,zhengju,anjian')
                        ->from('zcb_protectright')
                        ->where(['create_user'=>$uid,'status'=>40])
						->offset(($page-1)*$limit)
                        ->limit($limit)
						->orderBy(['create_time'=>SORT_DESC])
                        ->all();
		}
		$this->success("",['result'=>$data]);
     }
	 
	 
	 public function actionBaoquanlists(){
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):0;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
		$type = Yii::$app->request->post('type');
        $uid = Yii::$app->session->get('user_id');
        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
		if($type == 1){
			 $sql = "select id,status,number,account,fayuan_name,fayuan_address,address,create_time,type,qisu,caichan,zhengju,anjian from zcb_protectright where create_user = {$uid} and status in(1,10,20,30) order by create_time desc";
		}else{
			 $sql = "select id,status,number,account,fayuan_name,fayuan_address,address,create_time,type,qisu,caichan,zhengju,anjian from zcb_protectright where create_user = {$uid} and status = 40 order by create_time desc";
		}
       
        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
		foreach($rows as $k=>$v){
			$a = ['qisu'=>$v['qisu'],'caichan'=>$v['caichan'],'zhengju'=>$v['zhengju'],'anjian'=>$v['anjian']];
			foreach ($a as $key=>$value){
				$data = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$value)])
                        ->limit(5)
                        ->all();
						if($data){
							$rows[$k][$key] = $data;
						}
			}
			
		}
		$this->success("",['data'=>$rows]);
     }
	 
	 
	 
     
     //保全数据查询
     public function actionAudit(){
        $id = Yii::$app->request->post('id');
        //$model = \app\models\Protectright::findOne(['id'=>$id])->all();
		$messageId = Yii::$app->request->post('messageid');
		// var_dump($messageId);exit;
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			$MessageQuery->setRead($messageId);
		}
		
		
		$data = (new \yii\db\Query())
                        ->select('*')
                        ->from('zcb_protectright')
                        ->where(["id"=>$id])
                        ->limit(1)
                        ->all();
		foreach($data as $k=>$v){
			$a = ['qisus'=>$v['qisu'],'caichans'=>$v['caichan'],'zhengjus'=>$v['zhengju'],'anjians'=>$v['anjian']];
			foreach ($a as $key=>$value){
				$datas = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$value)])
                        ->limit(5)
                        ->all();
						if($datas){
							$data[$k][$key] = $datas;
						}
			}
			
		}
        if($data){
           echo json_encode(['code'=>'0000','result'=>$data[0]]);
        }else{
             $this->errorMsg("Userphone",''); 
        }
        
     }
     
     //图片保存
     public function actionPicturedatas(){
        if(Yii::$app->request->post()){
 
            $pictureId = [
                'id'=>Yii::$app->request->post('id'),
                'qisu'=>Yii::$app->request->post('qisu'),
                'caichan'=>Yii::$app->request->post('caichan'),
                'zhengju'=>Yii::$app->request->post('zhengju'),
                'anjian'=>Yii::$app->request->post('anjian'),
            ];
 
            $model = \app\models\Protectright::findOne(['id'=>$pictureId['id']]);
            $model->setAttributes($pictureId);
             $modelDdata = $model->attributes; 
            if($modelDdata){
                $modelDdata['qisu'] = $pictureId['qisu'];
                $modelDdata['caichan'] = $pictureId['caichan'];
                $modelDdata['zhengju'] = $pictureId['zhengju'];
                $modelDdata['anjian'] = $pictureId['anjian'];
                if($model->save()){
                    $this->success("提交成功",[]); 
                }else{
                    $this->errorMsg("ModelDataSave",$model->formatErrors());
                }
            }
            
            
        }
     }
     
     //图片获取
        public function actionTupian(){
            $id = Yii::$app->request->post('id');
                $model = (new \yii\db\Query())
                        ->select('id , qisu , caichan , zhengju , anjian')
                        ->from('zcb_protectright')
                        ->where('id=:id',[':id'=>$id])
                        ->limit(1)
                        ->one();
            if(!$model)$this->errorMsg("ParamsCheck");
            
            //$number = $model[0]['qisu'].','.$model[0]['caichan'].','.$model[0]['zhengju'].','.$model[0]['anjian'];
            $qisu = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$model['qisu'])])
                        ->limit(5)
                        ->all();
            $caichan = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files') 
                        ->where(["id"=>explode(",",$model['caichan'])])
                        ->limit(5)
                        ->all();
            $zhengju = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$model['zhengju'])])
                        ->limit(5)
                        ->all();
            $anjian = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$model['anjian'])])
                        ->limit(5)
                        ->all();
                        
                        //var_dump($qisu);die;
             $this->success("获取成功",['model'=>$model,'qisu'=>$qisu,'caichan'=>$caichan,'zhengju'=>$zhengju,'anjian'=>$anjian]);  
        }
        
        
     //单组图片获取
     public function actionPicturecategory(){
        $id = Yii::$app->request->post('id');
        $url = yii\helpers\Url::toRoute(['/'],true);
        $data = (new \yii\db\Query())
                        ->select(['concat("'.$url.'",file) as file','id'])
                        ->from('zcb_files')
                        ->where("id in({$id})")
                        ->limit(5)
                        ->all();
        $this->success("获取成功",['data'=>array_values(yii\helpers\ArrayHelper::map($data, 'id','file'))]);            
     }
     
     //用户手机号
     public function actionUser(){
        $id = Yii::$app->request->post('id');
        if($id){
           $model = \app\models\Protectright::findOne(['id'=>$id]);
        $userModel = new \app\models\User;
	    $model->phone = $userModel->getAttr(Yii::$app->session->get('user_id'),'mobile');
        if($model){
           echo json_encode(['code'=>'0000','result'=>$model->attributes]); 
        }else{
           $this->errorMsg("Userphone",''); 
        }  
     }else{
        $userModel = new \app\models\User;
	    $phone = $userModel->getAttr(Yii::$app->session->get('user_id'),'mobile');
        if($phone){
           echo json_encode(['code'=>'0000','result'=>$phone]); 
        }else{
           $this->errorMsg("Userphone",''); 
        }  
     }
        
     }
     /**
     * 房产评估
     * 
     */
     public function actionEstate(){
        if(Yii::$app->request->post()){
            $model = new \frontend\models\HousingPrice;
            $result = $model->getTotalPrice(Yii::$app->request->post(),Yii::$app->session->get('user_id'));
            switch($result['errorCode']){
			case 'ok':
	            echo json_encode(['code'=>'0000','result'=>$result]);
				break;
			case 'UserLogin':
				echo $this->errorMsg("UserLogin",'');
				break;
			case 'TotalpriceCheck':
                 $msg = explode(' ',$model->formatErrors());
                 echo json_encode(['code'=>'8001','msg'=>$msg[0]]);
                 //echo $this->errorMsg("TotalpriceCheck",$model->formatErrors());
				break;
			case 'TotalpriceSave':
				echo $this->errorMsg("TotalpriceSave",'');
				break;
			case 'TotalpriceAPI':
				echo $this->errorMsg("TotalpriceAPI",'');
				break;
			case 'FangjiaToken':
				echo $this->errorMsg("TotalpriceAPI",'');
				break;
			case 'TotalpriceLimit':
				echo $this->errorMsg("TotalpriceLimit",'每天只能评估10次');
				break;
		}
        }
        
     }
	public function actionEstatelist()
    {
        $uid =Yii::$app->session->get('user_id');
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
		
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }else{
			$this->errorMsg("ParamsCheck");
		}
        $data = (new \yii\db\Query())
                     ->select('*')
                     ->from('zcb_housing_price')
                     ->where(['userid'=>$uid,'code'=>200])
                     ->andWhere(['>',"userid",0])
					 ->offset(($page-1)*$limit)
                     ->limit($limit)
					 ->orderby('create_time desc')
                     ->all();
        $this->success("",["data"=>$data]);
    }
	 
     
     //上海固定区域
     public function actionDiqu(){
        $citySelect = [
			"浦东"=>"浦东新区",
			"黄浦"=>"黄浦区",
			"静安"=>"静安区",
			"徐汇"=>"徐汇区",
			"长宁"=>"长宁区",
			"杨浦"=>"杨浦区",
			"虹口"=>"虹口区",
			"普陀"=>"普陀区",
			"宝山"=>"宝山区",
			"嘉定"=>"嘉定区",
			"闵行"=>"闵行区",
			"松江"=>"松江区",
			"青浦"=>"青浦区",
			"奉贤"=>"奉贤区",
			"金山"=>"金山区",
			"崇明"=>"崇明县",
		];
        $this->success("获取成功",['data'=>$citySelect]); 
     }
     
}