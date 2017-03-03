<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\components\BackController;
use backend\models\LoginForm;
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['upload','logout','changepwd', 'index'],
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
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload($filetype=1)
    {
        $model = new \common\models\UploadForm();
        if (Yii::$app->request->isPost) { 
            $model->imageFile = \frontend\components\UploadedFile::getInstance($model, 'Filedata');
			$data = $return = $model->upload($filetype,true);
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
		$this->title = '产调列表';
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
	
	public function actionChangepwd()
    {
        // $this->layout = 'main-login';

        // if (!\Yii::$app->user->isGuest) {
            // return $this->goHome();
        // }
		$model = \backend\models\Admin::find()->select(["username","password_hash"])->where(["id"=>Yii::$app->user->getId()])->one();
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

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
