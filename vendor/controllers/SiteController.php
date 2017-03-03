<?php
namespace frontend\controllers;

use common\models\User;
use Yii;
use common\models\LoginForm;
use frontend\services\Func;
use common\models\Sms;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\captcha\Captcha;
use yii\web\BadRequestHttpException;
use frontend\components\FrontController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers;
use yii\helpers\BaseJson;

/**
 * Site controller
 */
class SiteController extends FrontController
{
    public $layout = 'logins';
    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */
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
        $this->redirect('/homepage/homepages');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->redirect('/user/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $this->redirect('/user/index');
        } else {
            return $this->render('login', [
                'model' => $model,
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
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $this->redirect('/user/index');;
                }
            }
        }
        return $this->render('signup', [
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
            $verifyCode = \Yii::$app->request->post('verifyCode');
            if (!Func::isMobile($mobile)) {
                echo json_encode(['code' => '1001', 'msg' => '手机号格式错误']);
                die;
            }

            if ($verifyCode != Yii::$app->session->get('captcha_verify_code')) {
                echo json_encode(['code' => '1003', 'msg' => '验证码错误']);
                die;
            }
            if (!YII_DEBUG && Yii::$app->smser->sendValidateCode($mobile)) {
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
                echo 'false';
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
        $this->title = '清道夫债管家';
        return $this->render('/site/forgetpassword');
    }


    public function actionMobilesms()
    {
        if (\Yii::$app->request->isAjax) {
            $mobile = \Yii::$app->request->post('mobile');
            if (!Func::isMobile($mobile)) {
                echo json_encode(['code' => '1001', 'msg' => '手机号格式错误']);
                die;
            }
            if (!YII_DEBUG && Yii::$app->smser->sendValidateCode($mobile)) {
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

    public function actionModify(){
        if (Yii::$app->request->post()) {
            $user = User::findOne(['mobile' => \Yii::$app->request->post('mobile')]);
            $sms = new Sms();
            if (!$user) {
                exit(json_encode(1));
            } else if (!$sms->isVildateCode(\Yii::$app->request->post('verifyCode'),\Yii::$app->request->post('mobile'))) {
                exit(json_encode(2));
            } else {
                $user->password_hash = Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('passwords'));
                if ($user->save()) exit(json_encode(3));
            }
        }
    }

}
