<?php
namespace frontend\controllers;

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

class PolicyController extends FrontController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $layout = 'info';

    public function init(){
        if (\Yii::$app->user->isGuest) {
            $this->redirect('/site/login/')->send();
			exit;
        }
    }

    public $modelClass = 'frontend\models\Policy';

    public function actionIndex()
    {
		$messageId = Yii::$app->request->get('messageid');
		if($messageId){
			$MessageQuery = \app\models\Message::find();
			if(!$messageId) $updateType = '';
			$MessageQuery->setRead($messageId,$updateType='GROUP');
		}
        $model = new $this->modelClass;
        $data = new ActiveDataProvider([
            'query' => Policy::find()->where(['and',['created_by' => Yii::$app->user->identity->id], ['>', 'shenhe_status', 0]])->sortDate(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->renderIsAjax('index', compact('data', 'model', 'messageId'));
    }

    public function actionShenhe($id)
    {
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }else{
                $post = Yii::$app->request->post();
                $shenhe_status = $post['Policy']['shenhe_status'];
                $model->update();
            }
        }

    }


    public function actionCreate()
    {
        $model = new $this->modelClass;
        if ($model->load(Yii::$app->request->post())){ 
			$post = Yii::$app->request->post(); 
			$model->money = $model->money*10000;
			if($model->validate()){ 
				$model->status="1";	 
				
				 /*
				$addPolicy = Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/addPolicy",[
				'domain'=>'wx.zcb2016.com',
				'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
				'app_fayuan'=>$model->fayuan_name,
				'cid'=>$model->fayuan_id,
				'uid'=> $model->created_by,
				'app_phone'=>$model->phone,
				//'app_name'=>$name,
				'app_money'=>$model->money/10000,
				]);
				$addPolicyArray = Json::decode($addPolicy);
				 */
				 $addPolicyArray['isSuccess']='demo';
				if($model->save()) {
					if($addPolicyArray['isSuccess'] == 'true' ){
						$model->orderid = $addPolicyArray['orderid'];
						$model->save();
					}
					
					/*if(!YII_ENV_TEST){
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
					}*/
					Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->phone.']已申请保函,请进入后台处理...');
					$message = \app\models\Message::find();
					$data = $message->addMessage(400,['code'=>$model->orderid],Yii::$app->user->getId(),$model->id,20,$model->created_by);
					
					\frontend\services\Func::addMessage(31,\yii\helpers\Url::to(['/policy/index','id' => $model->id]),['{1}'=>$model->orderid]);
					$this->success("申请成功！",['id'=>$model->id]);
				}else{
					$this->errorMsg("ModelDataSave","申请失败！");
				}
			}else { 
				$this->errorMsg("ModelDataCheck",$model->formatErrors());  
			}    
		}else{

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

            return $this->renderIsAjax('create', compact('model', 'provinceData'));
        }
    }

    private function actionUpdate($id) {

        $model = $this->findModel($id);


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

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('crudMessage', '表单更新成功.');
            return $this->redirect('/policy/succeed');
        }

        return $this->renderIsAjax('update', compact('model', 'provinceData'));
    }
	public function actionModify($id) {
		$model = $this->findModel($id);
		
		if(Yii::$app->request->isPost){
			
			$formparams=Yii::$app->request->post('Policy');
			$post['qisu']=isset($formparams['qisu'])?$formparams['qisu']:'';
			$post['anjian']=isset($formparams['anjian'])?$formparams['anjian']:'';
			$post['caichan']=isset($formparams['caichan'])?$formparams['caichan']:'';
			$post['zhengju']=isset($formparams['zhengju'])?$formparams['zhengju']:'';
			
			$model->setAttributes($post);
			if($model->validate()){
				if($model->update()){
					$this->success("资料完善成功！",["id"=>$id]);
				}else{
					$this->errorMsg("ModelDataSave","无资料更新！");
				}
			}else { 
				$this->errorMsg("ModelDataCheck",$model->formatErrors()); 
			}
			
		}
		
		return $this->renderIsAjax('modify',['model'=>$model]);
		
	}

    private function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(($model = $this->findModel($id))){
            $model->shenhe_status = 0;
            if($model->update()) {
                $this->flash('success', '取消成功');
                return $this->redirect(Url::to(['index']));
            }
        }
        return $this->redirect(Url::to(['/policy/index']));
    }

    public function actionSucceed()
    {
        return $this->renderIsAjax('succeed');
    }

    public function actionAreaCity()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
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
				$cityData = [];
                if ($cityArray['errCode'] == 000) {
                    $cityData = $cityArray['data'];
                }

                echo Json::encode(['output'=>$cityData, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

    public function actionFayuan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
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
				$out = [];
                if ($fayuanArray['errCode'] == 000) {
                    $fayuanData = $fayuanArray['data'];
                }

                foreach ($fayuanData as $i => $account) {
                    $out[] = ['id' => $account['id'], 'name' => $account['name']];
                }

                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }

        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
	//单组图片获取
    public function actionPicturecategory(){
        $id = Yii::$app->request->post('id');
        $url = Url::toRoute(['/'],true);
        $data = (new \yii\db\Query())
                        ->select(['concat("'.$url.'",file) as file','id'])
                        ->from('zcb_files')
                        ->where("id in({$id})")
                        ->limit(5)
                        ->all();
        $this->success("获取成功",['data'=>array_values(yii\helpers\ArrayHelper::map($data, 'id','file'))]);            
     }



}