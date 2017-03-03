<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "zcb_policy".
 *
 * @property integer $id
 * @property integer $orderid
 * //@property string $name
 * @property string $phone
 * @property string $fayuan
 * @property string $money
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Policy extends \frontend\components\ActiveRecord
{
    const STATUS_YI = 1;
    const STATUS_ER = 2;
    const STATUS_SAN = 3;
    const STATUS_SI = 4;
    const STATUS_WU = 5;
    const STATUS_LIU =6;
    const STATUS_QI = 7;
    const STATUS_BA = 8;
    const STATUS_JIU =9;
    const STATUS_SHI = 10;

    const SHENHE_STATUS_OFF = 0;
    const SHENHE_STATUS_ST = 1;
    const SHENHE_STATUS_ON = 2;
    const SHENHE_STATUS_OK = 3;
	
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
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_policy';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			// [['type'], 'default', 'value' => 2],
			[[ 'area_pid', 'area_id'], 'required','message'=>"请选择..."],
            [['fayuan_id'], 'required','message'=>'法院不能为空'],
			[['category','anhao','phone','money'], 'required'],
			['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'请输入正确的手机号码'],
			[['money'], 'number'],
			[['orderid'], 'default', 'value' => \frontend\services\Func::createCatCode(5)],
            [['created_by'], 'default', 'value' => Yii::$app->user->getId() ],
            [['status', 'shenhe_status', 'fayuan_id','area_pid', 'area_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [[ 'qisu','caichan','zhengju','anjian'], 'required','message'=>"请上传{attribute}"],
            [[ 'area_pid','area_id', 'fayuan_id','type'], 'integer'],
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
            //[['name', 'phone'], 'unique'],
            [['phone', 'fayuan_name', 'area_name','address','fayuan_address'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => '保单编号',
            'area_pid' => '省',
            'area_id' => '市、区',
            'area_name' => '地区名字',
            'address' => '收货地址',
            'fayuan_address' => '取函地址',
            'qisu' => '起诉书',
            'caichan' => '财产保全申请书',
            'zhengju' => '相关证据资料',
            'anjian' => '案件受理通知书',
            'fayuan_id' => '法院',
            'fayuan_name' => '法院',
            'money' => '保函金额',
            'status' => '状态',
            'phone' => '联系方式',
            'shenhe_status' => '状态',
            'created_at' => '申请时间',
            'updated_at' => '更新时间',
			'type'=>'1为自提。2为快递',
			'anhao'=>'案号',
			'category'=>'案件类型'
        ];
    }

    public static function getStatus($status)
    {
        $data = [
            self::STATUS_YI => [
                'name' => '等待竞价',
            ],
            self::STATUS_ER => [
                'name' => '筛选竞价',
            ],
            self::STATUS_SAN => [
                'name' => '退款',
            ],
            self::STATUS_SI => [
                'name' => '上传资料',
            ],
            self::STATUS_WU => [
                'name' => '等待机构申请',
            ],
            self::STATUS_LIU => [
                'name' => '机构不通过',
            ],
            self::STATUS_QI => [
                'name' => '待收保函',
            ],
            self::STATUS_BA => [
                'name' => '订单完成',
            ],
            self::STATUS_QI => [
                'name' => '机构通过',
            ],
            self::STATUS_BA => [
                'name' => '法院不认可',
            ],

        ];

        return $data[$status];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_YI => '等待竞价',
            self::STATUS_ER => '筛选竞价',
            self::STATUS_SAN => '退款',
            self::STATUS_SI => '上传资料',
            self::STATUS_WU => '等待机构申请',
            self::STATUS_LIU => '机构不通过',
            self::STATUS_QI => '待收保函',
            self::STATUS_BA => '订单完成',
            self::STATUS_JIU => '机构通过',
            self::STATUS_SHI => '法院不认可',

        ];
    }


    public static function getShenheStatus()
    {
        return [
            self::SHENHE_STATUS_OFF => '已取消',
            self::SHENHE_STATUS_ST => '审核中',
            self::SHENHE_STATUS_OK => '审核通过',
            self::SHENHE_STATUS_ON => '审核未通过',
        ];
    }


    public function sendMobile($mobile, $msg)
    {
        return Yii::$app->smser->sendMsgByMobile($mobile, $msg);
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
