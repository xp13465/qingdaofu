<?php
namespace manage\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;
	public $department_pid;
	

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['username','personnelid'], 'required'],
			[['headimg'], 'default', 'value' => 0],
			[['group'], 'default', 'value' => 1],
			[['group'], 'default', 'value' => 1],
			[['post_id'], 'default', 'value' => 1],
            [['username'], 'unique'],
            ['password_hash', 'required', 'on' => 'create'],
            [['password_hash', 'postName'],'safe'],
            ['password_reset_token', 'default', 'value' => null],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['group', 'post_id', 'created_at', 'updated_at', 'headimg','personnelid'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /*public function scenarios()
    {
        return [
            'create' => ['username', 'password_hash', 'status'],
        ];
        }*/

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                //$this->auth_key = $this->generateAuthKey();
                //$this->password_hash = $this->setPassword($this->password_hash);
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
            } else {
                $this->password_hash = $this->password_hash != '' ? Yii::$app->security->generatePasswordHash($this->password_hash) : $this->oldAttributes['password_hash'];
            }
            return true;
        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes)
    {
		if ($insert) {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('游客');
            $auth->assign($authorRole, $this->id);
        }
			
        if (parent::afterSave($insert, $changedAttributes)) {
            // if ($insert) {
                // $auth = Yii::$app->authManager;
                // $authorRole = $auth->getRole('普通管理员'); 
                // $auth->assign($authorRole, $this->id);
            // }
        }
    }


    public function attributeLabels()
    {
        return [
            'id' =>'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' =>'Password Reset Token',
            'status' => Yii::t('zcb', 'Status'),
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'group' => '部门',
            'post_id' => '岗位',
            'postName' => '岗位名称',
			'personnelid' => '员工ID',
            'headimg' => '头像',
            'personnelName' => '关联员工',
        ];
    }

    public function getRoleName()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }

    /*public function getRole()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
        }*/

    public function getPost()
    {
        return $this->hasOne(\app\models\Post::className(), ['id' => 'post_id']);
    }

    public function getPostName()
    {
        return "";//$this->post['name'];
    }

    static public function getGroup()
    {
        //return ArrayHelper::map(AuthItem::find()->all(), 'name', '');

    }


    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('zcb', 'Active'),
            self::STATUS_INACTIVE => Yii::t('zcb', 'INActive'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    private function hashPassword($password)
    {
        return sha1($password . $this->getAuthKey() . Setting::get('password_salt'));
        //return sha1($password . $this->getAuthKey(). Yii::$app->security->generateRandomString());
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	public static function queue($data,$id='0',$lev =' '){
		$classA = array();
		foreach($data as $value){
			if($value['pid'] == $id){
				$value['lev'] = $lev;
				$classA[] = $value;
				$classA = array_merge($classA,self::queue($data,$value['id'],$lev.'　'));
			}
		}	
		return $classA;
	}
	
	public function getFiles()
    {
        return $this->hasOne(\app\models\Files::className(),['id' => 'headimg'])->alias('files');
    }
	
	public function getPersonnel()
    {
        return $this->hasOne(\app\models\Personnel::className(), ['id' => 'personnelid']);
    }
	
	public function getLeave(){
		return $this->hasMany(\app\models\AttendanceLeave::className(), ['parentid' => 'personnelid']);
	}
	
	public function getPersonnelName()
    {
        return $this->personnel['name'];
    }
	
	public function getPersonnels()
    {
        return $this->hasOne(\app\models\Personnel::className(), ['id' => 'personnelid'])->alias('personnels')->select('personnels.id,personnels.name,personnels.department_pid,personnels.mobile,personnels.employeeid,personnels.job,personnels.parentid');
    }
	
	public function autoComplete($term){
		$adminUser = self::find()->alias('admin')->select(["admin.id","admin.username","admin.personnelid"])->where(['admin.id'=>$term])->joinWith(['personnels'=>function($query){
			$query->joinWith(['departments','admins']);
		}])->asArray()->one();
		return $adminUser;
	}
}
