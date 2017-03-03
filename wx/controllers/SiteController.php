<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class SiteController extends yii\web\Controller
{
    public $enableCsrfValidation = false;
    public $title;
    public $keywords;
    public $description;

    public function actionLogout()
    {
		$client = 'wx';
        Func::CurlPost(yii\helpers\Url::toRoute("/wap/user/logout"),['token'=>Yii::$app->session->get('user_token'),"client"=>$client]);
        Yii::$app->session->destroySession('user_token');
		Yii::$app->user->logout();
		$this->redirect('login');
    }

    public function actionError()
    {
        return $this->render('error');
    }

    public function actionRegistery()
    {
        $this->title = "清道夫债管家-注册";
        $this->keywords = "清道夫债管家-注册";
        $this->description = "清道夫债管家-注册";

        if(Yii::$app->request->isAjax){
            $mobile = Yii::$app->request->post('mobile');
            $validatecode = Yii::$app->request->post('validatecode');
            $password = Yii::$app->request->post('password');
            echo Func::CurlPost(yii\helpers\Url::toRoute("/wap/user/registery"),['mobile'=>$mobile,'password'=>$password,'validatecode'=>$validatecode]);
            die;
        }

        return $this->render('registery');
    }

    public function actionGetsms(){
        if(Yii::$app->request->isAjax){
            $mobile = Yii::$app->request->post('mobile');
            $str = Func::CurlPost(yii\helpers\Url::toRoute("/wap/user/smscode"),['mobile'=>$mobile]);
            echo $str;
            die;
        }
    }


    public function actionLogin()
    {
        $this->title = "清道夫债管家-登录";
        $this->keywords = "清道夫债管家-登录";
        $this->description = "清道夫债管家-登录";
		$openid = '';
		if(!Yii::$app->request->isAjax){
			$cookies = Yii::$app->response->cookies;
			$prevpage = Yii::$app->session->get('loginReferrer');
			if(!$prevpage){
				$Referrer = Yii::$app->request->getReferrer();
				
				if(strpos($Referrer,$_SERVER['HTTP_HOST'])!==false){
					 Yii::$app->session->set('loginReferrer',$Referrer);
					 $prevpage = $Referrer;
				}
				
			}
			
			$islogin = false;
			$token = Yii::$app->session->get('user_token');
			if($token){
				$json = Func::CurlPost(\yii\helpers\Url::toRoute("/wap/user/islogin"),['token$'=>$token]);
				// $json_arr = $this->json2array($json);
				$array=json_decode($json);
				$json_arr = !is_null($array)?\yii\helpers\Json::decode($json):['code'=>'-1'];
				if($json_arr['code']!='0000'){
					Yii::$app->session->destroySession('user_token');
				}else{
					$islogin = true;
					$this->redirect($prevpage);
				}
			}
			$uagent = $_SERVER['HTTP_USER_AGENT'];
			if (strpos($uagent, 'MicroMessenger') === false){
				$true=false;
			}else{
				$true=true;
			}
			
			
			if($true&&$islogin==false&&strpos($uagent, 'MicroMessenger') > -1 ){
				Yii::$app->user->logout();
				$callbackUrl=\yii\helpers\Url::toRoute(['/site/login'],true);
				Yii::$app->request->getReferrer();
				$openid = YII_ENV_TEST?'oNZOTszvLxTU9zaLKyvfXu--ynqQ':$this->getOpen1($callbackUrl);
				if($openid){
					$userOpenIdModel = \common\models\Openid::find()->where(["openid"=>$openid,"autologin"=>"1"])->one();
					if($userOpenIdModel){
						$userModel = \common\models\User::find()->where(['id'=>$userOpenIdModel->uid])->one(); 
						if($userModel){
							if(!$userModel->token){
								$userModel->token = md5(time() . $userModel->id);
								$userModel->save();
							}
							
							$model = new \common\models\LoginForm();
							$model->mobile = $userModel->mobile;
							$model->logintype = 1;
							$model->password = '';
							$model->login(false);
							// echo Yii::$app->user->getId();
							// echo $userModel->token;
							Yii::$app->session->set('user_token',$userModel->token);
							
							// $this->redirect($prevpage);
							header('Location:'.$prevpage);
							// echo 44;
							// var_dump($prevpage);
							// echo yii\helpers\Url::toRoute($prevpage);
							exit;
							// $status = $this->redirect($prevpage)->send();
							// $this->redirect(Yii::$app->request->getReferrer());
						}
					}
				}
			} 
		}
        if(Yii::$app->request->isAjax){
            $mobile = Yii::$app->request->post('mobile');
            $password = Yii::$app->request->post('password');
            $logintype = Yii::$app->request->post('logintype');
            $openid = Yii::$app->request->post('openid');
			$client = 'wx';
			$str = Func::CurlPost(yii\helpers\Url::toRoute("/wap/user/login"),['mobile'=>$mobile,'password'=>$password,'logintype'=>$logintype,'client'=>$client]);
			$strArr = yii\helpers\Json::decode($str);

            if($strArr['code'] == '0000'){
                $model = new \common\models\LoginForm();
                $model->load(Yii::$app->request->post(), '') ;
				$model->logintype = 1;
				$model->password = '';
                $model->login(false);
				if($openid){
					$userid = Yii::$app->user->getId();
					$userOpenIdModel = \common\models\Openid::find()->where(["uid"=>$userid])->one();
					
					if($userid&&!$userOpenIdModel){
						$userOpenIdModel = new \common\models\Openid;
						$userOpenIdModel->uid = $userid;
					}
					if($userid&&$userOpenIdModel->openid!=$openid){
						$userOpenIdModel->updateAll(["openid"=>""],["openid"=>$openid]);
						$userOpenIdModel->openid = $openid;
						$userOpenIdModel->save();
					}
				}
				
				
				// echo Yii::$app->user->getId();
                Yii::$app->session->set('user_token',$strArr['token']);
                unset($strArr['token']);
            }
            echo yii\helpers\Json::encode($strArr);
            die;
        }
        return $this->render('login',['openid'=>$openid]);
    }

    //忘记密码
    public function actionForget(){
        $this->title = "清道夫债管家-忘记密码";
        $this->keywords = "清道夫债管家-忘记密码";
        $this->description = "清道夫债管家-忘记密码";
        if(Yii::$app->request->isAjax){
            $mobile = Yii::$app->request->post('mobile');
            $new_password = Yii::$app->request->post('password');
            $validatecode = Yii::$app->request->post('Verification');
            echo Func::CurlPost(yii\helpers\Url::toRoute("/wap/user/resetpassword"),['mobile'=>$mobile,'new_password'=>$new_password,'validatecode'=>$validatecode]);
            die;
        }
        return $this->render('forgetPassword');
    }
    //修改密码
    public function actionModify($type=1){
        $this->title = "清道夫债管家-修改密码";
        $this->keywords = "清道夫债管家-修改密码";
        $this->description = "清道夫债管家-修改密码";
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->post()){
            $old_password = Yii::$app->request->post('original_pass');
            $new_password = Yii::$app->request->post('new_pass');
			if($type==2){
				echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/userinfo/setpassword'),['password'=>$new_password,'token'=>$token]);
			}else{
				echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/user/modifypassword'),['old_password'=>$old_password,'new_password'=>$new_password,'token'=>$token]);
            }
			die;
        }
        return $this->render('modifypassword',['type'=>$type]);
    }


    public function actionTest(){
        echo Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/addPolicy",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
            'app_fayuan'=>Yii::$app->request->get('app_fayuan'),
            'app_phone'=>Yii::$app->request->get('app_phone'),
            'app_name'=>Yii::$app->request->get('app_name'),
            'app_money'=>Yii::$app->request->get('app_money'),
        ]);
    }

    public function actionTestdistrict(){
        echo Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getArea",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
            'type'=>Yii::$app->request->get('type'),
            'pid'=>Yii::$app->request->get('pid'),
        ]);
    }


    public function actionTestfayuan(){
        echo Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getCourt",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
            'id'=>Yii::$app->request->get('id'),
        ]);
    }

    public function actionTestlist(){
        echo Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getPolicyList",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
        ]);
    }

    public function actionTestdetail(){
        echo Func::CurlPostForAPI("http://api.ssbq.cn/api/Index/getPolicyDetail",[
            'domain'=>'wx.zcb2016.com',
            'secret'=>'mi-xndy-mdc1odc5lxd4lnpjyjiwmtyuy29tleebtowqkei1hos6p+euoeeqhuaciemzkowfrowpuc3pg5hluizlm7-tmtgxntu2mje2oty=',
            'orderid'=>Yii::$app->request->get('orderid'),
        ]);
    }
    public function actionJudge(){
        $token = Yii::$app->session->get('user_token');
        if(Yii::$app->request->isAjax){
            $id       = Yii::$app->request->post('id');
            $category = Yii::$app->request->post('category');
            echo  Func::CurlPost(yii\helpers\Url::toRoute('/wap/common/islogin'), ['token' => $token,'category'=>$category,'id'=>$id]);die;
        }
    }

    // public function actionIndexs()
    // {
		
        // $this->title = "清道夫债管家-精选";
        // $this->keywords = "清道夫债管家-精选";
        // $this->description = "清道夫债管家-精选";
		// $cats = Func::CurlPost(yii\helpers\Url::toRoute("/wap/product/index"),['page'=>0,'limit'=>6]);
		// $strArr = yii\helpers\Json::decode($cats);
		// $Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		// $Total['Susong']   = \frontend\services\Func::getSusongTotal();
		// $Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		// $Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		
		 // if($strArr['code'] == '0000'){
            // $data = $strArr['result']['data'];
			// return $this->render('indexs',['data'=>$data,'sum'=>array_sum($Total)]);
        // }else{
			//$this->notFound();
		// }   
    // }
	
	public function actionIndex(){
		$this->title = "清道夫债管家-精选";
        $this->keywords = "清道夫债管家-精选";
        $this->description = "清道夫债管家-精选";
		$token = Yii::$app->session->get('user_token');
		/*微信等于额外验证首页*/
		$uagent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($uagent, 'MicroMessenger') === false){
			$wxtrue=false;
		}else{
			$wxtrue=true;
		}
		$cats = Func::CurlPost(yii\helpers\Url::toRoute(["/wap/product/index",'showtype'=>"1"]),['token'=>$token,'page'=>0,'limit'=>10]);
		$strArr = yii\helpers\Json::decode($cats);
		if($wxtrue&&$strArr["userid"]==""){
			Yii::$app->user->logout();
			$callbackUrl=\yii\helpers\Url::toRoute(['/site/index'],true);
			$openid = YII_ENV_TEST?'oNZOTszvLxTU9zaLKyvfXu--ynqQ':$this->getOpen1($callbackUrl);
			if($openid){
				$userOpenIdModel = \common\models\Openid::find()->where(["openid"=>$openid,"autologin"=>"1"])->one();
				if($userOpenIdModel){
					$userModel = \common\models\User::find()->where(['id'=>$userOpenIdModel->uid])->one(); 
					if($userModel){
						if(!$userModel->token){
							$userModel->token = md5(time() . $userModel->id);
							$userModel->save();
						}
						$model = new \common\models\LoginForm();
						$model->mobile = $userModel->mobile;
						$model->logintype = 1;
						$model->password = '';
						$model->login(false);
						
						Yii::$app->session->set('user_token',$userModel->token);
					}
				}
			}
		}
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		$bannersStr = Func::CurlPost(yii\helpers\Url::toRoute(["/wap/capital/banner",'type'=>"APP"]),['token'=>$token,'page'=>0,'limit'=>10]);
		$bannersArr = (yii\helpers\Json::decode($bannersStr));
		$banners = $bannersArr["result"]["banner"];
		
		if($strArr['code'] == '0000'){
            $data = $strArr['result']['data'];
			return $this->render('index',['data'=>$data,'banners'=>$banners,'sum'=>array_sum($Total)]);
        }else{
			// $this->notFound();
		}  
	}

    public function actionAgreement(){
        return $this->render('agreement');
    }
	
	public function actionTotalClaims(){
		
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		
		echo json_encode(['code'=>'0000','msg'=>"",'result'=>['sum'=>array_sum($Total),'total'=>$Total]]);
		
	}
	
	public function actionTotalDetail($type=""){
		$this->layout='nomain';
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		
		
		
		return $this->render("total-detail",['total'=>$Total,'sum'=>array_sum($Total),'type'=>$type]);
	}
	public function getOpen1($callbackUrl){
		
		$wxapiConfig = [];
		$wxapiConfig['savedb'] = false;
		$wxapiConfig['appid'] = 'wxb31437c7395f1399';
		$wxapiConfig['mchid'] = '1328619001';
		$wxapiConfig['key'] = '8c777e91602c930f443015952db70d16';
		$WxpayService = new \frontend\services\WxpayService($wxapiConfig); 
		
		
		$code = Yii::$app->request->get('code'); 
		$url = $WxpayService->createOauthUrlForCode(urlencode($callbackUrl));
		$WxpayService->setCode($code);
		
		$openid = $WxpayService->getOpenId('wxb31437c7395f1399','8c777e91602c930f443015952db70d16');
		
		if(!$openid){
			$this->redirect($url); 
		}else{ 
			$userid = Yii::$app->user->getId();
			$userOpenIdModel = \common\models\Openid::find()->where(["uid"=>$userid])->one();
			
			if($userid&&!$userOpenIdModel){
				$userOpenIdModel = new \common\models\Openid;
				$userOpenIdModel->uid = $userid;
			}
			if($userid&&$userOpenIdModel->openid!=$openid){
				$userOpenIdModel->updateAll(["openid"=>""],["openid"=>$openid]);
				$userOpenIdModel->openid = $openid;
				$userOpenIdModel->save();
			}
			return $openid;
		}
	}
	public function actionDemo(){
		$token =   Yii::$app->session->get('user_token');
		var_dump($token);
	}
	 
}
