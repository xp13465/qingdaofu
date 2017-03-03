<?php
namespace wx\components;

use wx\services\Func;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
/**
 * Site controller
 */
class FrontController extends Controller
{
    public $title = '';
    public $keywords = '';
    public $description = '';
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }
		// $form_key = Yii::$app->session->get('form_key');
		// if(!Yii::$app->request->post()){
			// if(!$form_key){
				// Yii::$app->session->set('form_key',time().mt_rand(1000,9999));
			// }
		// }else{
			// if(!$form_key){
				// echo json_encode(['code' => '0003', 'msg' => '页面超时']);
			// }
		// }
		
		
        $token = Yii::$app->session->get('user_token');
        $json = Func::CurlPost(Url::toRoute("/wap/user/islogin"),['token'=>$token]);

        $json_arr = $this->json2array($json);
        if($json_arr['code']!='0000')
        {
			Yii::$app->session->destroySession('user_token');
			
			$controller = Yii::$app->controller->id;
			$action = Yii::$app->controller->action->id;
			$controllerAction ='/'.$controller."/".$action;
			
			Yii::$app->session->set('loginReferrer',Yii::$app->request->url);
			
            if(!Yii::$app->request->isPost){
				header("location:".Url::toRoute('/site/login'));die;
			}else{
				echo json_encode(['code' => '3001', 'msg' => '请先登录']);exit;
			}
            
        }else{
			
        }

        // other custom code here

        return true; // or false to not run the action
    }
	public function json2array($string){
		$array=json_decode($string);
		if(YII_ENV_TEST&&is_null($array))exit($string);
		
		return !is_null($array)?Json::decode($string):['code'=>'-1'];
		
	}
	
	public function notFound(){
		
		throw new NotFoundHttpException('此页面不存在',404);
		
	}
	
	public function delFormKey(){
		Yii::$app->session->set('form_key','');
		// Yii::$app->session->destroySession('form_key');
		
	}
	
}
