<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_protectright".
 *
 * @property integer $id
 * @property string $area_pid
 * @property string $area_id
 * @property string $fayuan_id
 * @property string $account
 * @property string $jietiao
 * @property string $yinhang
 * @property string $danbao
 * @property string $caichan
 * @property string $other
 */
class Protectright extends \yii\db\ActiveRecord
{	
	/**
	*	保全步骤
	*
	*/
	
	public static $step = [ 
		'0'=>'',//"",
		'1'=>'',//"材料收集",
		'2'=>'',//"起诉",
		'3'=>'',//"立案",
		'4'=>'',//"分配法官",
		'5'=>'',//"申请保函",
		'6'=>'',//"移交保全",
		'7'=>'',//"处理成功",
	];
	/**
	*	保全步骤状态
	*
	*/
	public static $status = [
		'1'=>"等待审核",
		'10'=>"审核通过",
		'20'=>"协议已签订",
		'30'=>"保函已出",
		'40'=>"完成/退保", 
	];
	public static $type	= [
		'1'=>"快递",
		'2'=>"自取", 
	];
	
	public static $category = [
          ''=>'请选择',
          1=>'借贷纠纷',
          2=>'房产土地',
          3=>'劳动纠纷',
          4=>'婚姻家庭',
          5=>'合同纠纷',
          6=>'公司治理',
          7=>'知识产权',
          8=>'其他民事纠纷',
    ];
	
	public static $uploadFiled = ['jietiao'=>"借条",'yinhang'=>"银行转款凭证",'danbao'=>'担保合同','caichan'=>"财产线索",'other'=>"其他证据"];
	public static $uploadFiledType = ['jietiao'=>"11",'yinhang'=>"12",'danbao'=>'13','caichan'=>"14",'other'=>"15"];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_protectright';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		   [[ 'area_pid', 'area_id'], 'required','message'=>"请选择..."],
           [['fayuan_id'], 'required','message'=>'法院不能为空'],
		   [['category','phone','account'], 'required'],
		   ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'请输入正确的手机号码'],
		   [['account'], 'number'],
           [['step', 'status', 'create_user', 'create_time', 'area_id','area_pid','fayuan_id','modify_user', 'modify_time','type'], 'integer'],
           [['number'], 'string', 'max' => 20],
           ['type', 'required' ,'message'=>"请选择取函方式"],
			['type', 'in', 'range' => [1,2],'message'=>"请选择取函方式"],
			['fayuan_address', 'required', 'when' => function ($model) {
				return $model->type == '1';
			}, 'whenClient' => "function (attribute, value) {
				return $('#policy-type').val() == '1';
			}"
			],
			['address', 'required', 'when' => function ($model) {
				return $model->type==2;
			},'whenClient' => "function (attribute, value) {
				return $('#policy-type').val() == '2';
			}"],
           [['qisu', 'caichan', 'zhengju', 'anjian', 'other','fayuan_address','address','fayuan_name'], 'string', 'max' => 100],
        ];
    }
	
	public function validateCardNo()
    {	
		$preg = [
			"15"=>"/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/",
			"18"=>"/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/"];
			
		$len= strlen($this->cardNo);
		if(in_array($len,[15,18])&&preg_match($preg[$len], $this->cardNo)){
			 
		}else{
			$this->addError('cardNo', '请输入正确的身份证号！');
		}
        
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
           'number' => 'Number', 
           'area_pid' => '省份',
           'area_id' => '城市',
           'fayuan_id' => '法院',
           'fayuan_name'=>'法院名',
           'category' => '案件类型',
           'phone' => '联系方式',
           'account' => '债权金额',
           'address' => '快递地址',
           'fayuan_address' => '自提法院名',
           'type' => '1.为自提，2.为快递',
           'step' => 'Step', 
           'status' => 'Status', 
           'qisu' => 'qisu',
           'caichan' => 'caichan',
           'zhengju' => 'zhengju',
           'anjian' => 'anjian',
           'other' => 'Other',
           'create_user' => 'Create User', 
           'create_time' => 'Create Time', 
           'modify_user' => 'Modify User', 
           'modify_time' => 'Modify Time', 
        ];
    }
	/**
	*	获取保全当前状态
	*
	*/
	public static function getStatus($step,$status){
		$return = "";
		$return .=self::$step[$step];
		$return .=self::$status[$status];
		return $return;
		
	}
	
	public function change($data=array(),$isnew=false){
		//$addPolicyArray['isSuccess']='demo';
		$attributes=[
				'area_pid'=>isset($data['area_pid'])?$data['area_pid']:'',
				'area_id'=>isset($data['area_id'])?$data['area_id']:'',
                'fayuan_id'=>isset($data['fayuan_id'])?$data['fayuan_id']:'',
                'fayuan_name'=>isset($data['fayuan_name'])?$data['fayuan_name']:'',
                'category'=>isset($data['category'])?$data['category']:'',
                'address'=>isset($data['address'])?$data['address']:'',
                'fayuan_address'=>isset($data['fayuan_address'])?$data['fayuan_address']:'',
                'type'=>isset($data['type'])?$data['type']:'',
				'phone'=>isset($data['phone'])?$data['phone']:'',
				'account'=>isset($data['account'])?(int)$data['account']*10000:0,    
				'qisu'=>isset($data['qisu'])?$data['qisu']:'',    
				'caichan'=>isset($data['caichan'])?$data['caichan']:'',    
				'zhengju'=>isset($data['zhengju'])?$data['zhengju']:'',    
				'anjian'=>isset($data['anjian'])?$data['anjian']:'',    
				'other'=>isset($data['other'])?$data['other']:'',    
				'modify_user'=>Yii::$app->user->getId()?Yii::$app->user->getId():Yii::$app->session->get('user_id'),
				'modify_time'=>time()
		];
		if($isnew){
			$attributes['create_user'] = Yii::$app->user->getId()?Yii::$app->user->getId():Yii::$app->session->get('user_id');
			$attributes['create_time'] = time();
			$attributes['step'] = 1;
			$attributes['status'] = 1;
			$attributes['number'] = \frontend\services\Func::createCatCode(4);
		}
		$this->setAttributes($attributes); 
		// var_dump($this);exit;
		return $this->save();
		
	}
	public function  getJietiaos(){
		return $this->hasMany(Files::className(), ['id' => 'jietiao']);
	}
    
    public function formatErrors()
    {
        $result = '';
        foreach($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
        }
        return $result;
    }
	
	public function getQisufiles()
    {
		if($this->qisu){
			return \app\models\Files::getFiles(explode(",",$this->qisu));
		}else{
			return [];
		}
    }
	public function getCaichanfiles()
    {
		if($this->caichan){
			return \app\models\Files::getFiles(explode(",",$this->caichan));
		}else{
			return [];
		}
    }
	
	public function getZhengjufiles()
    {
		if($this->zhengju){
			return \app\models\Files::getFiles(explode(",",$this->zhengju));
		}else{
			return [];
		}
    }
	public function getAnjianfiles()
    {
		if($this->anjian){
			return \app\models\Files::getFiles(explode(",",$this->anjian));
		}else{
			return [];
		}
    }
	
	
	
}
