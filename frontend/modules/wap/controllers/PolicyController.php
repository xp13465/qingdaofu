<?php

namespace frontend\modules\wap\controllers;

use frontend\modules\wap\components\WapController;
use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use frontend\services\Func;
use frontend\models\PolicyForm;
use frontend\models\Policy;
use frontend\components\FrontController;


/**
 * 用户操作控制器
 */
class PolicyController extends WapController
{
	public $modelClass = 'frontend\models\Policy';
	
	public function actionIndex()
    {
        $uid = Yii::$app->session->get('user_id');
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
            $limitstr= "";
            if(is_numeric($page)&&is_numeric($limit)){
                $page = $page<=1?1:$page;
                $limit = $limit<=0?10:$limit;
                $limitstr = " limit ".($page-1)*$limit.",".$limit;
            }
            $type = Yii::$app->request->post('type');
            if($type == 1 ){
			$data = (new \yii\db\Query())
                        ->select('*')
                        ->from('zcb_policy')
                        ->where(['created_by'=>$uid,'status'=>[1,10,20,30]])
						->offset(($page-1)*$limit)
                        ->limit($limit)
						->orderBy(['created_at'=>SORT_DESC])
                        ->all();
		   }else{
			$data = (new \yii\db\Query())
                        ->select('*')
                        ->from('zcb_policy')
                        ->where(['created_by'=>$uid,'status'=>40])
						->offset(($page-1)*$limit)
                        ->limit($limit)
						->orderBy(['created_at'=>SORT_DESC])
                        ->all();
		}
		
          echo Json::encode(['code'=>'0000','result'=>$data]);die;
    }
	
	
    //保涵单条数据查询
    public function actionBaohan(){
        $id = Yii::$app->request->post('id');
		$messageId = Yii::$app->request->post('messageid');
		// var_dump($messageId);exit;
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			$MessageQuery->setRead($messageId);
		}
		
        if(is_numeric($id)){
            $model = (new \yii\db\Query())
                        ->select('*')
                        ->from('zcb_policy')
                        ->where('id=:id',[':id'=>$id])
                        ->limit(10)
                        ->all();
		foreach($model as $k=>$v){
			$a = ['qisus'=>$v['qisu'],'caichans'=>$v['caichan'],'zhengjus'=>$v['zhengju'],'anjians'=>$v['anjian']];
			foreach ($a as $key=>$value){
				$datas = (new \yii\db\Query())
                        ->select('id,file')
                        ->from('zcb_files')
                        ->where(["id"=>explode(",",$value)])
                        ->limit(5)
                        ->all();
						if($model){
							$model[$k][$key] = $datas;
						}
			}
		}			
            if($model){ 
                //echo Json::encode(['code'=>'0000','result'=>$model]);die;
                $this->success("",['model'=>$model[0]]);
            }else{
                $this->errorMsg("ParamsCheck",'');die;
            }
        }
    }
    
    public function actionCreate()
    {
        $model = new $this->modelClass;
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
		if(Yii::$app->request->post()){
			  $model->setAttributes(Yii::$app->request->post());
			  $post = Yii::$app->request->post();
			  $money =(int)$post['money']*10000;
			if($model->validate()){
				$model->created_by = $uid;
				$model->status="1";	
				$model->money = $money;
				$addPolicy = Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/addPolicy",[
				'domain'=>'wx.zcb2016.com',
				'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
				'app_fayuan'=>$model->fayuan_name,
				'cid'=>$model->fayuan_id,
				'uid'=> $uid,
				'app_phone'=>$model->phone,
				'app_money'=>$model->money/10000,
				]);
				$addPolicyArray = Json::decode($addPolicy);
				
				if($model->save()) {
					if($addPolicyArray['isSuccess'] == 'true' ){
						$model->orderid = $addPolicyArray['orderid'];
						$model->save();
					}
					
					if(!YII_ENV_TEST){
						$smsbak = array( 
								'mobile' => '13918500509',
								// 'mobile' => '15316602556',
								'msg' => $model->phone . '已申请保函,请进入后台处理...'
							);
						\frontend\services\Func::curl(2,$smsbak);
						$smsbak = array( 
									'mobile' => '15658635519',
									'msg' => $model->phone . '已申请保函,请进入后台处理...'
								);
						\frontend\services\Func::curl(2,$smsbak);
					}
					$this->success("申请成功！");
					$message = \app\models\Message::find();
					$data = $message->addMessage(400,['code'=>$model->orderid],Yii::$app->user->getId(),$model->id,20,$model->created_by);
				}else{
					$this->errorMsg("ModelDataSave","申请失败！");
				}
			}else { 
				$this->errorMsg("ModelDataCheck",$model->formatErrors());  
			}    
		}
	}
	
	public function actionAreaProvince(){
			$province =  Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getArea",[
                'domain'=>'wx.zcb2016.com',
                'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
                'type'=>1,
                'pid'=>0,
            ]);

            $provinceArray = Json::decode($province);
			

            if ($provinceArray['errCode']) {
                $provinceData = $provinceArray['data'];
            }
			echo Json::encode(['code'=>'0000','result'=>['data'=>$provinceData]]);die;

    }
	
	
	public function actionAreaCity()
    {
        $out = [];
	if (isset($_POST['depdrop_parents'])) {
		    if(is_array($_POST['depdrop_parents'])){
				$parents = $_POST['depdrop_parents']; 
			}else{
				$parents = [$_POST['depdrop_parents']];
			}
            
            if ($parents != null) {
                $cat_id = $parents[0];
                $zhixiashi = [1,22,868,2479,];
                if (in_array($cat_id, $zhixiashi)) {

                    $city_zxs =  Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getArea",[
                        'domain'=>'wx.zcb2016.com',
                        'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
                        'type'=>4,
                        'pid'=>$cat_id,
                    ]);

                    $city_zxs_array = Json::decode($city_zxs);

                    if ($city_zxs_array['errCode'] == 000) {
                        $city_zxs_Data = $city_zxs_array['data'];
                    }

                    $cat_zxs_id = $city_zxs_Data[0]['id'];

                    $city =  Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getArea",[
                        'domain'=>'wx.zcb2016.com',
                        'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
                        'type'=>3,
                        'pid'=>$cat_zxs_id,
                    ]);
                }else{
                    $city =  Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getArea",[
                        'domain'=>'wx.zcb2016.com',
                        'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
                        'type'=>2,
                        'pid'=>$cat_id,
                    ]);
                } 
                $cityArray = Json::decode($city);

                if ($cityArray['errCode'] == 000) {
                    $cityData = $cityArray['data'];
                }
				echo Json::encode(['code'=>'0000','result'=>['data'=>$cityData]]);die;
                return;
            }
        }
        echo Json::encode(['code'=>'4001','msg'=>'没有数据,请查询参数是否正确']);die;

    }

    public function actionFayuan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])  || isset($_POST['area_pid'])&& isset($_POST['area_id'])) {
			if(isset($_POST['depdrop_parents'])){
				$ids = $_POST['depdrop_parents']; 
			}else if(isset($_POST['area_pid'])&& isset($_POST['area_id'])){
				$ids = [$_POST['area_pid'],$_POST['area_id']];
			}
            $cat_id = empty($ids[0]) ? null : $ids[0];
            $subcat_id = empty($ids[1]) ? null : $ids[1];

            if ($subcat_id != null) {

                $fayuan = Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getCourt",[
                    'domain'=>'wx.zcb2016.com',
                    'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
                    'id'=>$subcat_id,
                    'pid' => $cat_id,

                ]);

                $fayuanArray = Json::decode($fayuan);

                if ($fayuanArray['errCode'] == 000) {
                    $fayuanData = $fayuanArray['data'];
                }

                foreach ($fayuanData as $i => $account) {
                    $out[] = ['id' => $account['id'], 'name' => $account['name']];
                }

				echo Json::encode(['code'=>'0000','result'=>['data'=>$out]]);die;
                return;
            }
        }

       echo Json::encode(['code'=>'4001','msg'=>'没有数据,请查询参数是否正确']);die;

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
            $model = \frontend\models\Policy::findOne(['id'=>$pictureId['id']]);
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
                        ->from('zcb_policy')
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

}
