<?php

namespace app\models;

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
 * @property string $cardimgimg
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
 * @property string $modifytime
 * @property string $resultmemo
 */
class Certification extends \yii\db\ActiveRecord
{
	public $cardimgimg1 ='';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_certification';
    }
	

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_time'], 'default', 'value' => time()],
            [['uid'], 'default', 'value' => Yii::$app->user->getId()],
			[['uid','mobile'], 'required'],
			['name','required', 'when' => function ($model) {
				return  $model->category === '1';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '1';
			}",'message'=>'请输入姓名，请与身份证上保持一致。'],
			
			['name','required', 'when' => function ($model) {
				return  $model->category === '2';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '2';
			}",'message'=>'请输入律所名称。'],
			
			['name','required', 'when' => function ($model) {
				return  $model->category === '3';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '3';
			}",'message'=>'请输入公司名称。'],
			
			['category','in','range'=>['1','2','3']],
			['cardno','required', 'when' => function ($model) {
				return $model->category == '1'; 
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '1';
			}",'message'=>'请输入身份正号码'],
			['cardno','required', 'when' => function ($model) {
				return $model->category == '2'; 
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '2';
			}",'message'=>'请输入执业证号'],
			['cardno','required', 'when' => function ($model) {
				return $model->category == '3'; 
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '3';
			}",'message'=>'请输入营业执照号'],
			
			['cardno','match', 'when' => function ($model) {
				return $model->category == '1'; 
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '1';
			}",'pattern'=>'/(^\d{15}$)|(^\d{17}([0-9]|X)$)/','message'=>'请输入正确的身份证'],
			
			['cardno','match', 'when' => function ($model) {
				return $model->category == '2'; 
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '2';
			}",'pattern'=>'/^\d{17}$/','message'=>'请输入正确执业证号'],
			
			['cardimgimg','required', 'when' => function ($model) {
				return  $model->category === '1';
			},'whenClient' => "function (attribute, value) {
				if($('#certification-category').val() == '1'){
					if(value.split(',').length==2){
						$('#cardimgimg1').val('1').trigger('change')
					}else{
						$('#cardimgimg1').val('').trigger('change')
					}
				}
				return $('#certification-category').val() == '1';
			}",'message'=>''],
			['cardimgimg','required', 'when' => function ($model) {
				return  $model->category === '2';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '2';
			}",'message'=>'请上传律师资格证'],
			
			['cardimgimg','required', 'when' => function ($model) {
				return  $model->category === '3';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '3';
			}",'message'=>'请上传公司营业执照'],
			
			['cardimgimg1','required', 'when' => function ($model) {
				return  $model->category === '1';
			},'whenClient' => "function (attribute, value) {
				return $('#certification-category').val() == '1';
			}",'message'=>'请上传身份证正反面图片'],
			
			['contact','required', 'when' => function ($model) {
				return  $model->category == ['2','3'];
			},'message'=>'请输入联系人姓名'],
			[['mobile'],'match','pattern'=>'/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/','message'=>'请输入正确的手机号'], 
            [['uid', 'category', 'state', 'create_time', 'managersnumber'], 'integer'],
            [['modifytime'], 'safe'],
            [['name', 'lang'], 'string', 'max' => 150],
            [['address', 'professional_area'], 'string', 'max' => 300],
            [['email'], 'string', 'max' => 120],
            [['mobile', 'law_cardno', 'enterprisewebsite'], 'string', 'max' => 100],
            [['cardno'], 'string', 'max' => 30],
            [['cardimgimg', 'education_level', 'contact'], 'string', 'max' => 11],
            [['cardimg'], 'string', 'max' => 200],
            [['casedesc'], 'string', 'max' => 2000],
            [['working_life'], 'string', 'max' => 20],
            [['resultmemo'], 'string', 'max' => 255],
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
            'cardimgimg' => '图片id',
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
            'modifytime' => '操作时间',
            'resultmemo' => '审核备注',
        ];
    }

	
	//添加认证
	public function Change($data=[],$isnew=false){
		$query = $this->getCertification();
		if($query){
			if($query->state == '0'){
				return CERTIFICATION0;
			}else if($query->state == '1'){
				return CERTIFICATION1;
			}else if($query->state == '2'){
				$query->setAttributes($data); MODELDATACHECK;
				if(!$query->validate()) return MODELDATACHECK;
				$query->state = '0';
				if($query->update()){
					return OK;
				}else{
					return MODELDATASAVE;
				}
			}
		}else{
			$this->setAttributes($data); MODELDATACHECK;
			if(!$this->validate()) return MODELDATACHECK ;
			//var_dump($data);die;
			$this->state = '0';
			$this->create_time = time();
			$this->uid = Yii::$app->user->getId();
			if($this->save()){
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}
		
        
	}
	
  public function formatErrors($isAll=false)
    {
        $result = '';
        foreach($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
			if(!$isAll)break;
        }
        return $result;
    }
	
  public function getCertification($uid=''){
        $uid = $uid?$uid:Yii::$app->user->getId();
        $user = \common\models\User::findOne(['id'=>$uid]);
        $certificationFromSelf = Certification::findOne(['uid'=>$uid]);
        if(!isset($certificationFromSelf->id)&&!$user->pid){
            return null;
        }else if(isset($certificationFromSelf->id)){
            return $certificationFromSelf;
        }else{
            $certificationFromParent = Certification::findOne(['uid'=>$user->pid]);
            if(isset($certificationFromParent->id))return $certificationFromParent;
            else return null;
        }
    }
	
	public function getCardimg()
    {
		if($this->cardimgimg){
			return \app\models\Files::getFiles(explode(",",$this->cardimgimg),['id','name','file']);
		}else{
			return [];
		}
    }
	
	
}
