<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $mobile;
    public $password;
    public $logintype;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            [['logintype'], 'integer'],
			[['logintype'], 'default', 'value' => 1],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobile' =>"手机号码",
            'password' =>$this->logintype==2?"验证码":'密码',
        ];
    }
	
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
			if($this->logintype == 2){
				
				$sms = new \common\models\Sms();
				if(!$sms->isVildateCode($this->password,$this->mobile)){
					$this->addError($attribute, '验证码错误');
				}else{
				
					if(!$user){
						$user = new User();
						$user->username = 'qdf_'.(\frontend\services\Func::random(4,1)).substr(md5($this->mobile),8,8);
						$user->mobile = $this->mobile;
						$user->setPassword('998997996995');
						$user->generateAuthKey();
						if($user->save()){
							
						}else{
							$this->addError($attribute, '该手机号已存在！');
						}
					}
				}
			}else{
				if (!$user) {
					$this->addError($attribute, '手机号码不存在');
				}else if(!$user->validatePassword($this->password)){
					$this->addError($attribute, '登录密码错误');
				}
			}
           
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login($isvalidate=true)
    {
		if($isvalidate==false){
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		}else{
			if ($this->validate()) {
				return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
			} else {
				return false;
			}
		}
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->mobile);
        }

        return $this->_user;
    }
}
