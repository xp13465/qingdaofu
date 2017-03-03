<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%creditor_product}}".
 *
 * @property integer $id
 * @property integer $category
 * @property string $code
 * @property double $money
 * @property double $term
 * @property double $rate
 * @property integer $rate_cat
 * @property integer $repaymethod
 * @property integer $loan_type
 * @property string $guaranteemethod
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $mortorage_has
 * @property string $mortorage_community
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
 * @property integer $browsenumber
 * @property integer $applyclose
 * @property integer $applyclosefrom
 * @property integer $rebate
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
        return '{{%creditor_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'code', 'money', 'agencycommissiontype', 'progress_status', 'create_time', 'modify_time', 'uid', 'is_del'], 'required'],
            [['category', 'rate_cat', 'repaymethod', 'loan_type', 'province_id', 'city_id', 'district_id', 'mortorage_has', 'judicialstatusB', 'obligor', 'commitment', 'commissionperiod', 'agencycommissiontype', 'paymethod', 'progress_status', 'create_time', 'modify_time', 'uid', 'is_del', 'browsenumber', 'applyclose', 'applyclosefrom', 'rebate', 'carbrand', 'audi', 'licenseplate'], 'integer'],
            [['money', 'term', 'rate', 'agencycommission', 'paidmoney', 'interestpaid'], 'number'],
            [['creditorfile'], 'string'],
            [['code'], 'string', 'max' => 20],
            [['guaranteemethod', 'creditorinfo', 'borrowinginfo'], 'string', 'max' => 1000],
            [['mortorage_community', 'judicialstatusA'], 'string', 'max' => 100],
            [['seatmortgage'], 'string', 'max' => 300],
            [['performancecontract', 'accountr'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'code' => 'Code',
            'money' => 'Money',
            'term' => 'Term',
            'rate' => 'Rate',
            'rate_cat' => 'Rate Cat',
            'repaymethod' => 'Repaymethod',
            'loan_type' => 'Loan Type',
            'guaranteemethod' => 'Guaranteemethod',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'mortorage_has' => 'Mortorage Has',
            'mortorage_community' => 'Mortorage Community',
            'seatmortgage' => 'Seatmortgage',
            'judicialstatusA' => 'Judicialstatus A',
            'judicialstatusB' => 'Judicialstatus B',
            'obligor' => 'Obligor',
            'commitment' => 'Commitment',
            'commissionperiod' => 'Commissionperiod',
            'agencycommissiontype' => 'Agencycommissiontype',
            'agencycommission' => 'Agencycommission',
            'paymethod' => 'Paymethod',
            'paidmoney' => 'Paidmoney',
            'interestpaid' => 'Interestpaid',
            'performancecontract' => 'Performancecontract',
            'creditorfile' => 'Creditorfile',
            'creditorinfo' => 'Creditorinfo',
            'borrowinginfo' => 'Borrowinginfo',
            'progress_status' => 'Progress Status',
            'create_time' => 'Create Time',
            'modify_time' => 'Modify Time',
            'uid' => 'Uid',
            'is_del' => 'Is Del',
            'browsenumber' => 'Browsenumber',
            'applyclose' => 'Applyclose',
            'applyclosefrom' => 'Applyclosefrom',
            'rebate' => 'Rebate',
            'carbrand' => 'Carbrand',
            'audi' => 'Audi',
            'licenseplate' => 'Licenseplate',
            'accountr' => 'Accountr',
        ];
    }
	/** 查询数据源方法
	*
	*/
	public function getAll($select='*',$where='',$order='',$page=1,$limit=10){
		$page = intval($page)?intval($page):1;
		$limit = intval($limit)?intval($limit):1;
		$data = self::find()->asArray()
		->select($select)
		->where($where)
		->offset(($page-1)*$limit)
		->limit($limit)
		->orderby($order)
		->all();
		return $data;
	}
	/** 查询所有清收诉讼产品
	*
	*/
	public function getAllProduct($where='',$page=1,$limit=10,$filter='filterlocal'){
		$select = "id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber,province_id ,uid,agencycommissiontype,agencycommission,loan_type,rebate,rate,carbrand,audi,licenseplate,rate_cat,accountr,mortorage_community";
		$order = 'progress_status asc ,modify_time desc';
		$data = $this->getAll($select,$where,$order,$page,$limit);
		if($filter==true){
			$data= $this->$filter($data);
		}
		return $data;
	}
	
	/** 查询推荐清收诉讼产品
	*
	*/
	public function getAllTjProduct($where='',$page=1,$limit=10,$filter='filterlocal'){
		$select = "id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber,province_id ,uid,agencycommissiontype,agencycommission,loan_type,rebate,rate,carbrand,audi,licenseplate,rate_cat,accountr,mortorage_community";
		$order = 'create_time desc ';
		$data = $this->getAll($select,$where,$order,$page,$limit);
		if($filter==true){
			$data= $this->$filter($data);
		}
		return $data;
	}
	 
	/** 过滤规则
	*
	*/
	public function filterlocal($data=[]){
		foreach ($data as $key => $value){
			$location = $value['seatmortgage'].$value['mortorage_community'];
            $data[$key]['location'] = isset($location)?\frontend\services\Func::getSubstrs($location):'';
        }
		return $data;
	}
	
}
