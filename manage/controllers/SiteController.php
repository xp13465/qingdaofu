<?php
namespace manage\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use manage\components\BackController;
use manage\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends BackController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','upimages','import','changepwd', 'error','province','city', 'district'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['upload','logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
	/**
	* 省份
	*/
	public function actionProvince(){
	   $province = \app\models\Province::find()->select("provinceID,province")->asArray()->all();
	   return $province;
	}
	
	/**
	* 城市
	*/
	public function actionCity(){
	   $province_id = Yii::$app->request->post('province_id');
	   $city = \app\models\City::find()->select("cityID,city")->where(['fatherID'=>$province_id])->asArray()->all();
       if($city){
		 $html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($city,'cityID','city'));
		 $html = trim(str_replace('<select name="">','',$html));
		 $html = str_replace('</select>','',$html);
		 return $this->success('',['html'=>$html]);die;  
	   }
	}
	/**
	* 市区
	*/
	public function actionDistrict(){
		$city = Yii::$app->request->post('city_id');
		$district = \app\models\Area::find()->select("areaID,area")->where(['fatherID'=>$city])->asArray()->all();
		if($district){
		 $html = \yii\helpers\Html::dropDownList('','',\yii\helpers\ArrayHelper::map($district,'areaID','area'));
		 $html = trim(str_replace('<select name="">','',$html));
		 $html = str_replace('</select>','',$html);
		 return $this->success('',['html'=>$html]);die;  
	   }
	}
	
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload($filetype=1)
    {
        $model = new \common\models\UploadForm();
        if (Yii::$app->request->isPost) {
			$dataUrl =  Yii::$app->request->post('dataUrl');	
			 if($dataUrl){
		  	   $filedata = $model->dataUrl($dataUrl);
			 }
			 $model->imageFile = \frontend\components\UploadedFile::getInstance($model, "Filedata");
			 if($dataUrl){
				 $data = $return = $model->upload($filetype,true,false);
			 }else{
				 $data = $return = $model->upload($filetype,true);
			 }
             unset($return['tempName']);
			 echo \yii\helpers\Json::encode($return);  
		   	   
        }
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
		$this->header = "首页";
		// $this->title = '产调列表'; 
		// print_r($data);
        return $this->render('index');
    } 
	
	

    public function actionLogin()
    {
        $this->layout = 'main-login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionChangepwd()
    {
        // $this->layout = 'main-login';

        // if (!\Yii::$app->user->isGuest) {
            // return $this->goHome();
        // }
		$model = \manage\models\Admin::find()->select(["username","password_hash"])->where(["id"=>Yii::$app->user->getId()])->one();
		$post = Yii::$app->request->post();
        if (isset($post["oldpassword"])&&isset($post["password_hash"])) { 
			$oldpassword = Yii::$app->request->post('oldpassword');
			$newpassword = Yii::$app->request->post('password_hash');
			if(!$oldpassword){
				Yii::$app->session->setFlash('crudMessage', '旧密码不能为空');
				return $this->redirect("changepwd");
			}
			if(!$newpassword){
				Yii::$app->session->setFlash('crudMessage', '新密码不能为空');
				return $this->redirect("changepwd");
			}
			
            if ($model->validatePassword($oldpassword)) {
                $updateStatus = $model->updateAll(["password_hash"=>Yii::$app->security->generatePasswordHash($newpassword)],["id"=>Yii::$app->user->getId()]);
				if($updateStatus){
					Yii::$app->session->setFlash('crudMessage', '密码设置成功');
					return $this->redirect("changepwd");
				}else{
					Yii::$app->session->setFlash('crudMessage', '密码设置失败，请联系管理员！');
					return $this->redirect("changepwd");
				}
            }else{
                Yii::$app->session->setFlash('crudMessage', '旧密码错误');
            }
        } 
		return $this->render('changepwd', [
                'model' => $model,
            ]);
    }
	 //连接
    public function actionUpimages($file){
        $filedata = \app\models\Files::find()->where(["uuid"=>$file])->one();
		if($filedata){
			$img = file_get_contents($filedata["addr"],true);
			//使用图片头输出浏览器
			header("Content-Type: image/jpeg;text/html; charset=utf-8");
			echo $img;
			exit;
		}
    }
}
