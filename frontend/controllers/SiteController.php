<?php
namespace frontend\controllers;
use kartik\mpdf\Pdf;
use common\models\User;
use Yii;
use common\models\LoginForm;
use frontend\services\Func;
use common\models\Sms;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
// use frontend\models\ContactForm;
use yii\base\InvalidParamException;
// use yii\captcha\Captcha;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
// use yii\filters\VerbFilter;
// use yii\filters\AccessControl;
use yii\helpers;
// use yii\data\Pagination;
use yii\helpers\BaseJson;
// use yii\web\CookieCollection;
use yii\helpers\Json; 

/**
 * Site controller
 */
class SiteController extends FrontController
{

    public $layout = 'logins';
    public $modelClass = 'frontend\models\Policy';
	

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $model = new $this->modelClass;
		$bannerModels = new \app\models\Banner;
		$Banners = $bannerModels->getBanners("WEB");

        $this->title = '清道夫债管家-不良资产处置_上海清债公司';
        $this->keywords = '清道夫债管家,不良资产处置,资产处置,清欠公司,上海要债公司,上海清债公司,借钱不还怎么办';
        $this->description = '清道夫债管家平台倾力打造互联网金融资产创新服务，主要提供上海地区诉讼清收等服务，以及为客户完成不良资产处置。借钱不还怎么办？清道夫债管家为客户提供清收及法律为一体的服务。';
		
		// $rows = \app\models\News::find()->orderBy('create_time desc')->limit(10)->all();
		// $categoryNews["2"] = \app\models\News::find()->where(["category"=>"2"])->orderBy('create_time desc')->limit(7)->all();
		// $categoryNews["3"] = \app\models\News::find()->where(["category"=>"3"])->orderBy('create_time desc')->limit(7)->all();
		// $categoryNews["4"] = \app\models\News::find()->where(["category"=>"4"])->orderBy('create_time desc')->limit(7)->all();
		
		$categoryNews["2"] = \app\models\Album::find()->getList(["category"=>2]);
		$categoryNews["3"] = \app\models\Album::find()->getList(["category"=>3]);
		$categoryNews["4"] = \app\models\Album::find()->getList(["category"=>4]);
		// var_dump($rows);
		
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
		$ProductQuery=\app\models\Product::find();
		$ProductQuery->where(["validflag"=>"1","status"=>["10","20","30","40"]]);
		$PCount = $ProductQuery->count("account");
		// $PSum   = $ProductQuery->sum("account");
		
		$Total['Qingshou'] = \frontend\services\Func::getQingshouTotal();
		$Total['Susong']   = \frontend\services\Func::getSusongTotal();
		$Total['Baohan']   =  \frontend\services\Func::getBaohanTotal();
		$Total['Baoquan']  =  \frontend\services\Func::getBaoquanTotal();
		$PSum = array_sum($Total);
		
		
		
		$organization = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 3")->queryScalar();
		$lawyer = Yii::$app->db->createCommand("select count(category) from zcb_certification where category = 2")->queryScalar();
		
		
		
		$ProductQuery=\app\models\Product::find();
		$post = Yii::$app->request->post();
		//$productList = $ProductQuery->searchLists($post,'',false);
		$selfApply = true;
		$productList = $ProductQuery->searchLists([],$selfApply);
        
		$Tjdata = $ProductQuery->filterAll($productList->getModels());
		// var_dump($data);exit;
        return $this->render('homepages',[
			'Banners'=>$Banners,
			// 'newsdata'=>$rows,
			'categoryNews'=>$categoryNews,
			'organization'=>$organization,
			'lawyer'=>$lawyer,
			'PCount'=>$PCount,
			'PSum'=>$PSum, 
			'citySelect' => $citySelect, 
			'Tjdata' => $Tjdata
		]);

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin($backurl="")
    {
		 
		 
		$curUrl = \yii\helpers\Url::to('',true);
		$backurl = $backurl?:Yii::$app->request->getReferrer();
		if(!$backurl||$backurl==$curUrl){
			$backurl = "/userinfo/info";
		}
		// echo $backurl ;exit;
        $this->title = '用户登录-清道夫债管家';
        $this->keywords = '用户登录';
        $this->description = '用户登录';
        $this->layout = 'login_new';
        if (!\Yii::$app->user->isGuest) {
            // $this->redirect($backurl);
			return $this->goBack();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
			return $this->goBack();
            // return $this->redirect($backurl);
			
        } else{
			
            return $this->render('login',[
                'model' => $model,
                'backurl' => $backurl,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $this->title = '用户注销-清道夫债管家';
        $this->keywords = '用户注销';
        $this->description = '用户注销';
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->title = '用户注册-清道夫债管家';
        $this->keywords = '用户注册';
        $this->description = '用户注册';
		$this->layout = 'login_new';
        $model = new SignupForm();
        $model->load(Yii::$app->request->post(),'');
            if($user=$model->signup()){
                    if (Yii::$app->getUser()->login($user)){
                        $cookies = Yii::$app->response->cookies;
                        $duration = time()+5;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => "name",
                            'value'=> '2',
                            'expire'=>$duration
                        ]));
						\frontend\services\Func::addMessage(26,\yii\helpers\Url::to('/site/index'),[]);
                        $this->redirect('/user/index');
                }
            }
        return $this->render('signup',[
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->title = '重置密码-清道夫债管家';
        $this->keywords = '重置密码';
        $this->description = '重置密码';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSms()
    {
        if (\Yii::$app->request->isAjax) {
            $mobile = \Yii::$app->request->post('mobile');
            $type = \Yii::$app->request->post('type');
			$type = $type?$type:1;
            $verifyCode = \Yii::$app->request->post('verifyCode');
            if (!Func::isMobile($mobile)) {
                echo json_encode(['code' => '1001', 'msg' => '手机号格式错误']);
                die;
            }
            if ($type==1 && $verifyCode != Yii::$app->session->get('captcha_verify_code')) {
                echo json_encode(['code' => '1003', 'msg' => '验证码错误']);
                die;
            }
            if (Yii::$app->smser->sendValidateCode($mobile,$type)) {
                echo json_encode([
                    'code' => '0000',
                    'msg' => '发送验证码成功',
                ]);
                die;
            } else {
                echo json_encode(['code' => '1002', 'msg' => '发送验证码失败']);
                die;
            }
        } else {

        }
    }

    public function actionCheckmobile()
    {
        if (\Yii::$app->request->post()) {
            $mobile = \Yii::$app->request->post('mobile');
            if (User::findByUsername($mobile)) {
                echo 'false';
                die;
            }
            echo 'true';
            die;
        }
    }

    public function actionCheckmobilepass()
    {
        if (\Yii::$app->request->post()) {
            $model = new LoginForm();
            $model->mobile = Yii::$app->request->post('mobile');
            $model->password = Yii::$app->request->post('mobilepassword');
            $model->rememberMe = true;
            if ($model->login()) {
                echo 'true';
                die;
            } else {
                echo '密码或手机号输入有误';
                die;
            }
        }
    }

    public function actionRegisterprotocal()
    {
        $this->layout = false;

        return $this->render('registerprotocal');
    }

    public function actionForgetpassword()
    {
		$this->layout = 'login_new';
        $this->title = '忘记密码-清道夫债管家';
        $this->keywords = '忘记密码';
        $this->description = '忘记密码';
        if (!Yii::$app->user->isGuest) {
            $this->redirect('/site/index');
        } else{
            return $this->render('/site/forgetpassword');
        }
    }


    public function actionMobilesms()
    {

        if (\Yii::$app->request->isAjax) {
            $mobile = \Yii::$app->request->post('mobile');
            $user = User::findOne(['mobile'=>$mobile]);
            if (!Func::isMobile($mobile)) {
                echo json_encode(['code' => '1001', 'msg' => '手机号格式错误']);
                die;
            }

            if($user['mobile']){
                if (!YII_DEBUG && Yii::$app->smser->sendValidateCode($mobile,2)){
                    echo json_encode([
                        'code' => '0000',
                        'msg' => '发送验证码成功',
                    ]);
                    die;
                } else {
                    echo json_encode(['code' => '1002', 'msg' => '发送验证码失败']);
                    die;
                }
            }else{
                echo json_encode(['code' => '1002', 'msg' => '该手机未注册']);
                die;
            }
        } else {

        }
    }
    public function actionResets(){
		$this->layout = 'login_new';
        $this->title = '清道夫债管家';
        return $this->render('/site/resetPassword');
    }
    public function actionModifys(){
            if (Yii::$app->request->post()) {
                $user = User::findOne(['mobile' => \Yii::$app->request->post('mobile')]);
                $sms = new Sms();
                if (!$user) {
                    exit(json_encode(1));
                } else if (!$sms->isVildateCode(\Yii::$app->request->post('verifyCode'),\Yii::$app->request->post('mobile'))) {
                    exit(json_encode(2));
                } else {
                    $bool = Yii::$app->user->login($user, 3600 * 24 * 30);
                    if ($bool) {
                        exit(json_encode(3));
                    } else {
                        echo 'false';
                        die;
                    }
                }
            }
        }

    public function actionModify()
    {
        if (Yii::$app->request->post()) {
            $user = User::findOne(['id' => \Yii::$app->user->getId()]);
            if($user){
            $user->password_hash = Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('passwords'));
            if ($user->save()) exit(json_encode(4));
            }else{
                exit(json_encode(5));
            };
        }
    }

    public function actionError(){
        return $this->render('error');
    }

    //连接
    public function actionSitemap(){
        return $this->renderPartial('sitemap');
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
            //var_dump($model->imageFile);
			$data = $return = $model->upload($filetype,true);
			unset($return['tempName']);
			echo Json::encode($return);
        }
    }
	
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionMa($url='http://www.zcb2016.com'){
		return \dosamigos\qrcode\QrCode::png($url,false,3,10); 
	}
	public function actionMalogo($url='http://www.zcb2016.com'){
		
		$logo = './images/ewmlogo.png';//需要显示在二维码中的Logo图像
		$QR = '/site/ma'; 
		if ($logo !== FALSE) {
			$QR = imagecreatefromstring ( file_get_contents ( \yii\helpers\Url::toRoute([$QR,'url'=>$url],true) ) );
			$logo = imagecreatefromstring ( file_get_contents ( $logo ) );
			$QR_width = imagesx ( $QR );
			$QR_height = imagesy ( $QR );
			$logo_width = imagesx ( $logo );
			$logo_height = imagesy ( $logo );
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width / $logo_qr_width;
			$logo_qr_height = $logo_height / $scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
		}
		imagepng ( $QR);//带Logo二维码的文件名
		
		
	}
	
	public function actionDemo($type=2)
    {
		$WxMsg = new \frontend\services\WxMsg("WXMSG");
		$openid = 'oNZOTszvLxTU9zaLKyvfXu--ynqQ';
		// $openid = 'oNZOTs-OesEpM5DiovEI1zKOaQ30';
		$openid = 'oNZOTs9ivfNuDbt3wsLHf6PFbRx8';
		$template1 = array(
            'touser' => $openid,
            'template_id' => '-WZphstKbZt3XoWu001cQk0zqHzPUgf7zranCGiwPFc',
			"url"=> urlencode("http://wx.zcb2016.com"),
			"topcolor"=> "#FF0000",
            'data' => array(
				'first' => ["value" => "尊敬的狗剩","color" => "#ff0000"],
				'OrderSn' => ["value" => "X00201512126810","color" => "#173177"],
				'OrderStatus' => ["value" => "已消灭","color" => "#555555"],
				'remark' => ["value" => "快递单号: 8031971890\n点击“详情”查看完整消灭信息","color" => "#cccccc"],
			)
        ); 
		$time = time();
		$ordersid = date("Ymd",$time).str_pad(1047,6,"0",STR_PAD_LEFT);
		$WXurl ="http://wx.zcb2016.com/property/view?id=MCwyMjQ0";
		$template = array(
			'touser' => $openid,
			'template_id' => '-WZphstKbZt3XoWu001cQk0zqHzPUgf7zranCGiwPFc',
			"url"=> urlencode($WXurl),
			"topcolor"=> "#FF0000",
			'data' => array(
				'first' => ["value" => "报告尊上：您查阅的产调已处理~","color" => "#0065b3"],
				'OrderSn' => ["value" => $ordersid,"color" => "#0065b3"],
				'OrderStatus' => ["value" => "已处理","color" => "#0065b3"],
				'remark' => ["value" => "","color" => "#0065b3"],
			)
		); 
		var_dump($template);
		$status = $WxMsg->sendMB($template);
		var_dump($status);
		exit;
		$user = \common\models\User::findUserData(1);
		// Yii::$app->cache->delete("UserData_1_username");
		var_dump($user);
		exit;
		
		
		
		$transaction=\Yii::$app->db->beginTransaction();
		$model=\frontend\models\Property::findOne(1);

		$model->orderId='洋12'; 
		$a= $model->update(false);
var_dump($a);
exit;
		if(!$model->save()){
			$transaction->rollback();
		}else{ //save表示不校验数据
			$transaction->commit();
		}
		
		// $code=new QRcode();
		// $code::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
		exit;
		/*
		$data = (new \yii\db\Query())
			->from('zcb_message')
			->where(['type' => '24'])
			// ->limit(1)
			->all();
		
		print_r(count($data));	
		$i=0;		
		foreach($data as $v){
			// var_dump($v);
			$params = @unserialize($v['params']);
			
			
			if($params&&isset($params['id'])&&is_numeric($params['id'])){
				$one = (new \yii\db\Query())
				->from('zcb_property')
				->where(['id' => $params['id']])
				->limit(1)
				->one();
				if($one){
					$i++;
					$newID = urlencode(base64_encode($one['cid'] .','.$one['id'])); 
					$params['id']=$newID;
					\common\models\Message::updateAll(['params'=>serialize($params)],["id"=>$v["id"]]);
					// exit;
					
				}else{
					echo $v['id'];
					echo "<hr/>";
					continue;
				}
				var_dump($one);
				var_dump($params);
			}else{
				continue;
			}
		}
		echo $i;
		exit;*/
		
		if($type=='1'){
			echo '发给陈';
			$openid = 'oNZOTszvLxTU9zaLKyvfXu--ynqQ';
		}else{
			echo '发给吴';
			$openid = 'oNZOTs-OesEpM5DiovEI1zKOaQ30';
			
		}
		
		
		$con='测试';
		$template = array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array(
				'content' => $con
			)
        );
		$template1 = array(
            'touser' => $openid,
            'msgtype' => 'news',
            'news' => array(
				'articles' => array(
					[
						"title"=>"Happy Day我我",
						"description"=>"Is Really A Happy Day",
						"url"=>"http://www.zcb2016.com/",
						"picurl"=>"http://www.zcb2016.com/images/banner/banner1.png"
					],
					[
						"title"=>"Happy Day",
						"description"=>"Is Really A Happy Day",
						"url"=>"http://www.zcb2016.com/",
						"picurl"=>"http://www.zcb2016.com/images/banner/banner1.png"
					]
				)
			)
        ); 
		$template2 = array(
            'touser' => $openid,
            'msgtype' => 'news',
            'news' => array(
				'articles' => array(
					[
						"title"=>"Happy Day我我",
						"description"=>"Is Really A Happy Day",
						"url"=>"http://www.zcb2016.com/",
						"picurl"=>"http://www.zcb2016.com/images/banner/banner1.png"
					]
				)
			)
        );
		
		
		
		
		for($i=1;$i<=21;$i++){
			$WxMsg = new \frontend\services\WxMsg("WXMSG");
			echo $template['text']['content'] = "第{$i}次发送".$con;
			$status = $WxMsg->sendM($template);
			var_dump($status);
			echo "<hr/>";
			// $status = $WxMsg->sendM($template1);
			// var_dump($status);
			// $status = $WxMsg->sendM($template2);
			// var_dump($status);
		}
		
		
		exit;
		$openid = 'oC2RrwHbLPYQsYXBR2Ozc2pwQCI4';
        $status = $WxMsg->sendM($openid,$con,'WXMSG2');
		var_dump($status);
    }
	public function actionQrcode()
    {
		echo "<img style='width:200px' src = '/site/ma?url=".urlencode('http://www.zcb2016.com/link.php')."'>";    //调用二维码生成方法
		echo "<img style='width:200px' src = '/site/malogo?url=".urlencode('http://www.zcb2016.com/link.php')."'>";    //调用二维码生成方法
	}
	
	public function actionPdf($type="",$types=''){
		$mpdf=new \mPDF('zh-CN','A4','','ccourierB','15','15','24','24','5','5');
		$mpdf->useAdobeCJK = true;
		$header='<table width="100%" style="">
		<tr>
		<td width="20%"></td>
		<td width="60%" align="center" style="font-size:px;color:#AAA">页眉</td>
		<td width="20%" style="text-align: right;"></td>
		</tr> 	
		</table>';
		//设置PDF页脚内容
		$footer='<table width="100%" style=" vertical-align: bottom; font-family:黑体; font-size:12pt; color: #;"><tr style="height:px"></tr><tr>
		<td width="20%"></td>
		<td width="60%" align="center" style="font-size:10px;">{PAGENO}/{nb}</td>
		<td width="20%" style="text-align: left;"></td>
		</tr></table>';
		//添加页眉和页脚到pdf中
		$mpdf->SetHTMLHeader($this->renderPartial('pdf_header'));
		$mpdf->SetHTMLFooter($this->renderPartial('pdf_footer'));
		//$mpdf->AddPage();
		switch($types){
			case 1;
			$mpdf->WriteHTML($this->renderPartial('fangwu'));
			break;
			case 2:
			$mpdf->WriteHTML($this->renderPartial('jietiao'));
			break;
			case 3:
			 $mpdf->WriteHTML($this->renderPartial('shouquan'));
			break;
			case 4:
			$mpdf->WriteHTML($this->renderPartial('weituo'));
			break;
			case 5:
			$mpdf->WriteHTML($this->renderPartial('zhaiwu'));
			break;
			case 6:
			$mpdf->WriteHTML($this->renderPartial('jujian'));
			break;
			case 7:
			$mpdf->WriteHTML($this->renderPartial('jujian01'));
			break;
		}
		
        // $mpdf->WriteHTML($this->renderPartial('myview'));
		//$mpdf->AddPage();
		$mpdf->Output('MyPDF.pdf', $type);//I查看D下载
	}
	
	public function actionHelp(){
		$this->layout = false;
		return $this->render("help");
	}
	 //连接
    public function actionUpimages($file){
        $filedata = \app\models\Files::find()->where(["uuid"=>$file])->one();
		if($filedata){
			$img = @file_get_contents($filedata["addr"],true);
			//使用图片头输出浏览器
			header("Content-Type: image/jpeg;text/html; charset=utf-8");
			echo $img;
			exit;
		}
    }
}
