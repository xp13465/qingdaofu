<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_creditor_product".
 *
 * @property integer $id
 * @property integer $category
 * @property varchar $code
 * @property double $money
 * @property double $term
 * @property double $rate
 * @property double $rate_cat
 * @property integer $repaymethod
 * @property string $guaranteemethod
 * @property integer $city_id
 * @property integer $province_id
 * @property integer $district_id
 * @property string $seatmortgage
 * @property string $judicialstatusA
 * @property integer $judicialstatusB
 * @property integer $obligor
 * @property integer $commitment
 * @property integer $commissionperiod
 * @property integer $agencycommissiontype
 * @property double $agencycommission
 * @property integer $paymethod
 * @property double $paidmoney
 * @property double $interestpaid
 * @property string $performancecontract
 * @property string $creditorfile
 * @property string $creditorinfo
 * @property string $borrowinginfo
 * @property integer $progress_status
 * @property integer $create_time
 * @property integer $modify_time
 * @property integer $uid
 * @property integer $is_del
* @property integer $carbrand
* @property integer $audi
* @property integer $licenseplate
 * @property string $accountr
 */
class CreditorProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_creditor_product';
    }

    public static $guaranteemethod = [
        1=>'住房',
        2=>'别墅',
        3=>'写字楼',
        4=>'商铺',
        5=>'其他',
    ];

    public static $categorys = [
         // 1=>'融资',
         2=>'清收',
         3=>'诉讼'
    ];
    public static $loan_types = [
        1=>'房产抵押',
        2=>'应收账款',
        3=>'机动车抵押',
        4=>'无抵押',
    ];

    public static $progressstatus =[
        1 => '发布中',
        2 => '已处置',
        3 => '已处置',
        4=>'已处置',
    ];


    public static $ratedatecategory = [
        ''=>'请选择',
        1=>'天',
        2=>'月',
    ];

    public static $judicialstatusA = [
        0=>'违约未诉讼',
        1=>'诉讼',
        2=>'仲裁',
        3=>'强制执行公证',
        4=>'强制执行',
    ];

    public static $agencycommissiontype = [
        1=>'固定费用',
        2=>'风险费率',
    ];

    public static $judicialstatusB = [
        1=>'可联',
        2=>'失联',
    ];
	
    public static $obligor = [
        1=>'自然人',
        2=>'法人',
        3=>'其他(未成年,外籍等)',
    ];

    public static $repaymethod = [
        ''=>'请选择',
        1=>'一次性到期还本付息',
        2=>'按月付息,到期还本',
        3=>'其他',
    ];

    public static $commitment = [
        ''=>'请选择',
        1=>'代理诉讼',
        2=>'代理仲裁',
        3=>'代理执行',
    ];


    public static $paymethod = [
        ''=>'请选择',
        1=>'分期',
        2=>'一次性付清',
    ];


    public static $commissionperiod = [
        ''=>'请选择',
        1=>'1',
        2=>'2',
        3=>'3',
        4=>'4',
        5=>'5',
        6=>'6',
        7=>'7',
        8=>'8',
        9=>'9',
        10=>'10',
        11=>'11',
        12=>'12',
    ];
    public static $overdue =[
        ''=>'请选择',
        1=>'资金周转困难',
        2=>'无力偿还',
        3=>'债务人失联',
        4=>'逃避债务',
        5=>'其他',
    ];
    public static $licenseplate = [
        1=>'沪牌',
        2=>'非沪牌',
    ];

    public static $finance = [
        '' => '请选择',
        1 => '尽职调查',
        2 => '公证',
        3 => '抵押',
        4 => '放款',
        5 => '返点',
        6 => '其他',
    ];
    public static $collection = [
        '' => '请选择',
        1 => '电话',
        2 => '上门',
        3 => '面谈',
    ];

    public static $litigations = [
        '' => '请选择',
        1 => '债权人上传处置资产',
        2 => '律师接单',
        3 => '双方洽谈',
        4 => '向法院起诉(财产保全)',
        5 => '整理诉讼材料',
        6 => '法院立案',
        7 => '向当事人发出开庭传票',
        8 => '开庭前调解',
        9 => '开庭',
        10 => '判决',
        11 => '二次开庭',
        12 => '二次判决',
        13 => '移交执行局申请执行',
        14 => '执行中提供借款人的财产线索',
        15 => '调查(公告)',
        16 => '拍卖',
        17 => '流拍',
        18 => '拍卖成功',
        19 => '付费',
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'money','is_del','loan_type', 'agencycommissiontype','progress_status', 'create_time', 'modify_time', 'uid'], 'required'],
            [['category', 'repaymethod', 'province_id', 'city_id','place_province_id','place_city_id','place_district_id', 'district_id', 'judicialstatusB', 'obligor', 'commitment', 'commissionperiod', 'agencycommissiontype', 'paymethod', 'progress_status', 'create_time', 'modify_time', 'uid','commitment','paymethod','rate_cat','rebate','licenseplate','audi','carbrand','start','browsenumber'], 'integer'],
            [['money', 'term', 'rate', 'agencycommission', 'paidmoney', 'interestpaid'], 'number'],
            [['guaranteemethod'], 'string', 'max' => 1000],
            [['code'], 'string', 'max' => 20],
            [['seatmortgage'], 'string', 'max' => 300],
            [['judicialstatusA'], 'string', 'max' => 100],
            [['performancecontract','accountr'], 'string', 'max' => 200],
            [['creditorfile', 'creditorinfo', 'borrowinginfo'], 'string', 'max' => 10000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'category' => '产品类型',
            'code' => '编码',
            'money' => '产品金额',
            'term' => '期限',
            'rate' => '利率',
            'rate_cat' => '利率单位',
            'repaymethod' => '还款方式',
            'loan_type' => '借款类型',
            'guaranteemethod' => '担保方式',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
			'place_province_id'=>'合同履行地省份ID',
			'place_city_id'=>'合同履行地市ID',
			'place_district_id'=>'合同履行地区域ID',
            'seatmortgage' => '抵押物所在地',
            'judicialstatusA' => '司法现状,诉讼情况',
            'judicialstatusB' => '司法现状，是否可联系',
            'obligor' => '债务人主体',
            'commitment' => '委托事项',
            'commissionperiod' => '委托期限',
            'agencycommissiontype' => '代理费用类型',
            'agencycommission' => '代理费用',
            'paymethod' => '付款方式',
            'paidmoney' => '已付本金',
            'interestpaid' => '已付利息',
            'performancecontract' => '合同履行地',
            'creditorfile' => '债权文件',
            'creditorinfo' => '债权方信息',
            'borrowinginfo' => '借款放信息',
            'progress_status' => '进展状态',
            'create_time' => '创建时间',
            'modify_time' => '修改时间',
            'uid' => '发布人ID',
            'is_del' => '是否删除',
            'rebate' => '返点',
            'carbrand'=>'机动车品牌',
            'audi'=>'车系',
            'licenseplate'=>'车牌类型',
            'accountr'=>'应收账款',
            'start' => '逾期日期开始时间',
			'browsenumber'=>'浏览次数',
        ];
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            if($this->progress_status>1){
                $this->progress_status = 0;
            }
        }else{
            $nowData = self::findOne(['id',$this->id]);
            if($nowData->progress_status&&$this->progress_status<$nowData->progress_status){
                $this->progress_status = $nowData->progress_status;
            }
        }

        if(!in_array($this->category,[2,3])){
            $this->category = 2;
        }

        return parent::beforeSave($this);
    }
	
	public function getUser(){
		return $this->hasOne(User::className(), ['id'=>'uid']);
	}
	
	public static function getStart($time,$day){
		$starts = [];
		
		if(date('Y',$time) == date('Y',time())){

			for($i=0;$i<=$day;$i++){
				$a = date("Y",strtotime("-{$i} year"));
				$starts[$i]= $a;
				}
				$b=1;
			for($j=0;$j<=$day;$j++){
				$a = date("Y",strtotime("+".$b++." year"));
				$starts[] = $a;
			}

		}
		asort($starts);
		return $starts;
	}
	
	public static function getMonth(){
		$Month = [];
		for($i=1;$i<=12;$i++){
			$Month[]=$i;
		}
		return $Month;
	}
	
	public static function getDay($month){
		$Day = [];
		$b=1;
			for($j=1;$j<=31;$j++){
				$Day[$b++] = $j;
			}
		if($month == 2){
			unset($Day[31]);
			unset($Day[30]);
			unset($Day[29]);
			return $Day; 
	    }else if($month%2 == 0){
			array_pop($Day);
			return $Day;
		}else{
			return $Day;
		}
	}
	public function getCertificationdata(){
		return $this->hasOne(\common\models\Certification::className(), ['uid'=>'uid'])->asArray();
	}
	public function getCertification(){
		return $this->hasOne(\common\models\Certification::className(), ['uid'=>'uid']);
	}
	
	public function getAppCount(){
		$data = $this->hasMany(\common\models\Apply::className(),['product_id'=>'id'])->alias("apply")->select("product_id,app_id,count(id) as id")->groupBy("app_id")->asArray();
		return $data;
	}
	
}
