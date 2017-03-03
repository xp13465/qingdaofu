<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_certification".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $address
 * @property string $email
 * @property string $mobile
 * @property string $cardno
 * @property string $cardimg
 * @property string $casedesc
 * @property string $education_level
 * @property string $lang
 * @property string $working_life
 * @property string $law_cardno
 * @property string $professional_area
 * @property string $contact
 * @property integer $category
 * @property integer $state
 * @property string $enterprisewebsite
 * @property integer $create_time
 * @property integer $managersnumber
 */
class Certification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_certification';
    }
    public static $certifi = [
        '1' => '个人',
        '2' => '律师',
        '3' => '公司'
    ];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		    [['create_time'], 'default', 'value' => time()],
            [['uid'], 'default', 'value' => Yii::$app->user->getId()],
			[['uid','name','cardno','mobile'], 'required'],
            [['uid', 'category', 'state', 'create_time', 'managersnumber','mobile'], 'integer'],
            [['name', 'lang'], 'string', 'max' => 150],
            [['address', 'professional_area'], 'string', 'max' => 300],
            [['email'], 'string', 'max' => 120],
            [['mobile', 'law_cardno', 'enterprisewebsite'], 'string', 'max' => 100],
            [['cardno'], 'string', 'max' => 30],
            [['cardimg','cardimgimg'], 'string', 'max' => 200],
            [['casedesc'], 'string', 'max' => 2000],
            [['education_level', 'contact'], 'string', 'max' => 11],
            [['working_life'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'uid' => '用户ID',
            'name' => '用户姓名或者机构名称',
            'address' => '联系地址',
            'email' => '邮箱',
            'mobile' => '联系方式',
            'cardno' => '证件号码，身份证号或者营业执照',
            'cardimg' => '上传证件图片',
            'casedesc' => '经典案例说明',
            'education_level' => '学历',
            'lang' => '语言',
            'working_life' => '从业年限',
            'law_cardno' => '律师资格证',
            'professional_area' => '专业领域',
            'contact' => '公司联系人',
            'category' => '1:代表个人；2:代表律师；3:代表公司',
            'state' => '0:发出申请；1:为申请成功;2:为申请失败',
            'enterprisewebsite' => '公司网站',
            'create_time' => '申请时间',
            'managersnumber' => '经办人个数',
			'cardimgimg'=>'图片ID'
        ];
    }

    public function afterSave($insert,$attributes){
        if($this->isNewRecord&&$this->state == 1){
            \frontend\services\Func::addNewMessage("认证成功","您的认证已成功",Yii::$app->user->getId());
        }

        return parent::afterSave($this,$attributes);
    }

		/**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluate()
    {
        return $this->hasMany(Evaluate::className(),['buid' =>'uid'])->alias('evaluate')
		->where(['evaluate.isHide'=>'0']);
    }
	
    public static function getOwnerField()
    {
    	return 'id';
    }
		
	public function getUserinfo(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'uid'])->select(["id","username","mobile"]);
	}

}
