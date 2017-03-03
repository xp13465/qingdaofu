<?php
namespace frontend\controllers;


use Yii;
use app\models\Protectright;
use app\models\ProtectrightSearch;
use frontend\components\FrontController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\services\Func;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

/**
 * ProtectrightController implements the CRUD actions for Protectright model.
 */
class ProtectrightController extends FrontController
{
	public $layout  = 'info';
    /**
     * @inheritdoc
     */
    
	public $modelClass = 'app\models\Protectright';
	public function init(){
		
        if (\Yii::$app->user->isGuest) {
			$this->redirect('/site/login/')->send();
			exit;
        }
    }
	/**
	*  我的保全
 	*
	*/
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
				'query' => Protectright::find()->where(['and',['create_user'=>Yii::$app->user->identity->id],['>', 'status', 0]]),
                'sort' => [
					'defaultOrder' => [
						'id' => SORT_DESC,            
					]
				],
				'pagination' => [
					'pageSize' => 5,
				],	
			
		]);
        return $this->renderIsAjax('index', compact('model','data','messageId'));
    }

    /**
     * Displays a single Protectright model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->layout='info_empty';
		$model = $this->findModel($id);
		$files=[];
		$fileType=[
			"jietiao"=>"",
			"yinhang"=>"",
			"danbao"=>"",
			"caichan"=>"",
			"other"=>"",
		];
		foreach( $fileType as $key=>$val){
			if($model->$key){
				$rows = (new \yii\db\Query())
				->select(['id', 'name', 'file', 'datetime'])
				->from('zcb_files')
				->where(['id' => explode(",",$model->$key)])
				->limit(10)
				->all();
				$files[$key]=$rows;
			}else{
				$files[$key]=[];
			}
		} 
        return $this->render('view', [
            'model' => $model,
            'files' => $files,
        ]);
    }

    /**
     * Creates a new Protectright model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$next = false;
        $model = new $this->modelClass;
		//if($model->lode(Yii::$app->request->post())){
		//	$post = Yii::$app->request->post();
		//	$model->account = $model->account*10000;
		//}
		$post=Yii::$app->request->post();
		$userModel = new \app\models\User;
		$model->phone = $userModel->getAttr(Yii::$app->user->getId(),'mobile');
		if(isset($post['Protectright'])&&$post['Protectright']){
		if ($post&&$model->change($post['Protectright'],true)) {
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
					$message = \app\models\Message::find();
					$data = $message->addMessage(300,['code'=>$model->number],Yii::$app->user->getId(),$model->id,10,$model->create_user);
					Yii::$app->smser->sendMsgByMobile(13918500509,'用户['.$model->phone.']已申请保全,请进入后台处理...');
					\frontend\services\Func::addMessage(32,\yii\helpers\Url::to(['/policy/index','id' => $model->id]),['{1}'=>$model->number]);
					$this->success("申请成功！",['id'=>$model->id]);die;
			}else{
			     $this->errorMsg("ModelDataSave",$model->formatErrors());die;
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
		
		return $this->render('create',compact('model', 'provinceData'));
		}
		
    }
	
	/*
	*申请保全成功提示页面
	*/
	public function actionSucceed(){
		return $this->renderIsAjax('succeed');
	}
	
	/*
	*申请保全图片上传
	*@param integer $id
	*/
    public function actionModify($id){
		$model = $this->findModel($id);
		if(Yii::$app->request->isAjax){
			$formparams=Yii::$app->request->post('Protectright');
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
   
    /**
     * Updates an existing Protectright model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$next = false;
        $model = $this->findModel($id);
		$model->account=(int)($model->account/10000);
		$post=Yii::$app->request->post();
        if ($post&&$model->change($post,false)) {
            $next = true;
        }
        return $this->render('update', [
            'model' => $model,
			'next' => $next,
        ]);
        
    }

    /**
     * Deletes an existing Protectright model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 /*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Protectright model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Protectright the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Protectright::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	/**
	*  保全材料
 	*
	*/
    public function actionFiles(){
         $this->title .=  ' - 我的保全';
		 $this->errorMsg('phoneFormat');
    }
	
	
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload($Filetype=1)
    {  
        $model = new \app\models\UploadForm();
        return $this->render('upload', ['model' => $model]);
    }
}
