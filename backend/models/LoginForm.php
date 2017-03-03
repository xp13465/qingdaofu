<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\validators\EscapeValidator;

/**
 * Login form
 */
class LoginForm extends \yii\db\ActiveRecord
{
    const CACHE_KEY = 'LOGIN_TRIES';
    //public $username;
    //public $password;
    //public $rememberMe = true;

    private $_user;

    public static function tableName()
    {
        return '{{%loginform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            [['username', 'password'], EscapeValidator::className()],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('zcb', 'Username'),
            'password' => Yii::t('zcb', 'Password'),
            'rememberMe' => Yii::t('zcb', 'Remember Me'),
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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户密码错误');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */

    public function login()
    {
        $cache = Yii::$app->cache;

        if (($tries = (int)$cache->get(self::CACHE_KEY)) > 3) {
            $this->addError('username', Yii::t('zcb', 'Please wait.'));
            return false;
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->time = time();

        if ($this->validate()) {
            $this->password = '******';
            $this->success = 1;
        } else {
            $this->success = 0;
            $cache->set(self::CACHE_KEY, ++$tries, 300);
        }

        $this->insert(false);
        // return $this->success ? Yii::$app->user->login($this->getUser(), Setting::get('auth_time') ?: null ) : false;
        return $this->success ? Yii::$app->user->login($this->getUser(), 60*60*4 ) : false;
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}
