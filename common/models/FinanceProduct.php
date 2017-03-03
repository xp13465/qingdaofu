<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_finance_product".
 *
 * @property integer $id
 * @property integer $category
 * @property varchar $code
 * @property double $money
 * @property double $term
 * @property double $rate
 * @property double $rate_cat
 * @property double $rebate
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property double $mortgagearea
 * @property integer $fundstime
 * @property string $seatmortgage
 * @property integer $mortgagecategory
 * @property double $rentmoney
 * @property integer $status
 * @property integer $mortgagestatus
 * @property double $mortgagemoney
 * @property double $loanyear
 * @property double $obligeeyear
 * @property integer $progress_status
 * @property integer $create_time
 * @property integer $modify_time
 * @property integer $uid
 * @property integer $is_del
 */
class FinanceProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_finance_product';
    }

    public static $categorys = [
         1=>'融资',
         2=>'清收',
         3=>'诉讼'
    ];

    public static $mortgagecategory = [
        ''=>'请选择',
        1=>'住宅',
        2=>'商户',
        3=>'办公楼',
    ];


    public static $ratedatecategory = [
        ''=>'请选择',
        1=>'天',
        2=>'月',
    ];

    public static $status = [
        ''=>'请选择',
        1=>'自住',
        2=>'出租',
    ];

    public static $mortgagestatus = [
        ''=>'请选择',
        1=>'清房',
        2=>'二抵',
        3=>'民间转单',
    ];

    public static $obligeeyear = [
        ''=>'请选择',
        1=>'65岁以下',
        2=>'65岁以上',
    ];


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'money','rate','rate_cat', 'is_del', 'rebate', 'seatmortgage', 'progress_status', 'create_time', 'modify_time', 'uid'], 'required'],
            [['category', 'province_id', 'city_id', 'district_id', 'fundstime', 'mortgagecategory', 'status', 'mortgagestatus', 'progress_status', 'create_time', 'modify_time', 'uid','loan_type'], 'integer'],
            [['money', 'term', 'rate', 'rebate', 'mortgagearea', 'rentmoney', 'mortgagemoney', 'loanyear', 'obligeeyear'], 'number'],
            [['seatmortgage'], 'string', 'max' => 300],
            [['code'], 'string', 'max' => 20]
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
            'rebate' => '返点',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'district_id' => '区域ID',
            'mortgagearea' => '抵押物面积',
            'fundstime' => '资金到账日',
            'seatmortgage' => '抵押物地址',
            'mortgagecategory' => '抵押物类型',
            'rentmoney' => '租金',
            'status' => '状态',
            'mortgagestatus' => '抵押物状态',
            'mortgagemoney' => '银行抵押金额',
            'loanyear' => '借款年龄',
            'obligeeyear' => '权利人年龄',
            'progress_status' => '进展状态',
            'create_time' => '创建时间',
            'modify_time' => '修改时间',
            'uid' => '发布人ID',
            'is_del' => '是否删除',
            'loan_type' => '借款类型'
        ];
    }



    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
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
        $this->category = 1;

        return parent::beforeSave($this);
    }
}
