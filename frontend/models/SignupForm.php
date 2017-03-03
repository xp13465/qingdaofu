<?php
namespace frontend\models;

use frontend\services\Func;
use common\models\User;
use common\models\Sms;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $mobile;
    public $username = "zcb_";
    public $cardno = '123';
    public $validateCode;
    public $verifyCode = '1111';
    public $passwordf;
    public $passwords;
    public $password_reset_token;
    public $tjmobile;
    public $isAgree = true;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'passwordf','username','cardno', 'passwords','validateCode','verifyCode'], 'required'],
            [['password_reset_token'], 'string', 'max' => 300],
            // rememberMe must be a boolean value
            ['isAgree', 'boolean'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $sms = new Sms();
        if ($this->validate() && !User::findByUsername($this->mobile) && Func::isMobile($this->mobile) && ($this->passwordf == $this->passwords)  &&$sms->isVildateCode($this->validateCode,$this->mobile)){
            $user = new User();
            $user->username = 'qdf_'.Func::random(4,1).substr(md5($this->mobile),8,8);
            $user->mobile = $this->mobile;
            $user->setPassword($this->passwordf);
            $user->generateAuthKey();
            if ($user->save()){
                return $user;
            }
        }
        return null;
    }

    public function registerCorperationUser(){
        if(!User::findByUsername($this->mobile)&&Func::isMobile($this->mobile)){
            $user = new User();
            $user->username =$this->username;
            $user->mobile = $this->mobile;
            $user->cardno = $this->cardno;
            $user->password_reset_token = $this->password_reset_token;
            $user->pid = Yii::$app->user->getId();
            $user->isstop = 0;
            $user->setPassword($this->passwordf );
            $user->generateAuthKey();
		    if($user->save()){
			  return true;
		   };
        }
    }
}
