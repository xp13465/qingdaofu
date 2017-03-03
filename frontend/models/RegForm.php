<?php
namespace frontend\models;

use  yii\widgets\ActiveForm;

/**
 * Signup form
 */
class RegForm extends ActiveForm
{
    public $mobile;
    public $validateCode;
    public $verifyCode;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['mobile', 'required'],
            ['validateCode', 'required'],
            ['verifyCode', 'required'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'validateCode' => '手机验证码',
            'verifyCode' => '验证码',
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
        if ($this->validate() && Func::isMobile($this->mobile) && $this->passwordf == $this->passwords &&$sms->isVildateCode($this->validateCode,$this->mobile)){
            $user = new User();
            $user->username = 'qdf_'.Func::random(4,1).substr(md5($this->mobile),8,8);
            $user->mobile = $this->mobile;
            $user->setPassword($this->passwordf);
            $user->generateAuthKey();

            if ($user->save()) {
                return $user;
            }
        }
        return null;
    }
}
