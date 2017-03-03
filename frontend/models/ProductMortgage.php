<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_mortgage}}".
 *
 * @property integer $mortgageid
 * @property integer $productid
 * @property string $validflag
 *
 * @property Product $product
 */
class ProductMortgage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_mortgage}}';
    }
	
	public static $contract = [
		'1'=>'合同纠纷',
		'2'=>'民事诉讼',
		'3'=>'房产纠纷',
		'4'=>'劳动合同',
		'5'=>'其他',
	];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'relation_1', 'relation_2', 'relation_3', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['type'], 'required'],
			['type','in','range'=>['1','2','3'],'message'=>'请选择抵押物类型'],
			[['relation_1','relation_2','relation_3'],'required','when'=>function($productMortgages){
				return $productMortgages->type == in_array($productMortgages->type,['1','2']);
			}],
			[['relation_desc'],'required','when'=>function($productMortgages){
				return $productMortgages->type == in_array($productMortgages->type,['1']);
			}],
			[['relation_1'],'required','when'=>function($productMortgages){
				return $productMortgages->type == '3';
			}],
            [['type', 'validflag'], 'string'],
            [['relation_desc'], 'string', 'max' => 255],
            [['productid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['productid' => 'productid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mortgageid' => '抵押物ID',
            'productid' => '产品ID',
            'type' => '抵押物类型（1房产，2机动车，3合同纠纷）',
            'relation_1' => '请选择省份|请选择机动车品牌|纠纷类型',
            'relation_2' => '请选择城市|请选择机动车车型|无',
            'relation_3' => '请选择区域|请选择机动车牌照|无',
            'relation_desc' => '详细地址',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid']);
    }
	public function getProvincename(){
		return $this->hasOne(\common\models\Province::className(), ['provinceID'=>'relation_1'])->select('province,provinceID');
	}
	public function getCityname(){
		return $this->hasOne(\common\models\City::className(), ['cityID'=>'relation_2'])->select('city,cityID');
	}
	public function getAreaname(){
		return $this->hasOne(\common\models\Area::className(), ['areaID'=>'relation_3'])->select('area,areaID');
	}
	
	public function getBrand(){
		return $this->hasOne(\common\models\Brand::className(), ['id'=>'relation_1'])->select('id,name');
	}
	
	public function getAudi(){
		return $this->hasOne(\common\models\Audi::className(), ['id'=>'relation_2'])->select('id,name');
	}
	
	
	/**
	* 债权类型查询
	*/
	
	public function category($type,$relation_1,$relation_2='',$relation_3=''){
		 switch($type){
			 case '1':
				$provinceID = Yii::$app->db->createCommand("select province from zcb_province where provinceID = '{$relation_1}'")->queryScalar();
				$cityID     = Yii::$app->db->createCommand("select city from zcb_city where cityID = '{$relation_2}'")->queryScalar();
				$districtID = Yii::$app->db->createCommand("select area from zcb_area where areaID = '{$relation_3}'")->queryScalar();
				$data = ['provinceID'=>$provinceID,'cityID'=>$cityID,'districtID'=>$districtID];
				return $data;
			 break;
			 case '2':
				$brand = Yii::$app->db->createCommand("select name from zcb_brand where id = '{$relation_1}'")->queryScalar();
				$audi  = Yii::$app->db->createCommand("select name from zcb_audi where id = '{$relation_2}'")->queryScalar();
				$paizhao = $relation_3;
				$data = ['brand'=>$brand,'audi'=>$audi,'paizhao'=>$paizhao];
				return $data;
			 break;
		 }
	}
	
	/**
	* 抵押物地址新增
	*/
	public function mortgageAdd($params){
		$this->setAttributes($params);
		if(!$this->validate($params)) return MODELDATACHECK;
		if($this->isNewRecord){
			$this->create_at = time();
			$this->create_by = Yii::$app->user->getId();
			//var_dump($params);die;
			if($this->save()){
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}else{
			$this->modify_at = time();
			$this->modify_by = Yii::$app->user->getId();
			if($this->update()){
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}
	}
	
	/**
	* 抵押物地址删除
	*/
	public function mortgageDel($mortgageid){
		$status = $this->updateAll(['validflag'=>'0'],['mortgageid'=>$mortgageid,'validflag'=>'1','create_by'=>Yii::$app->user->getId()]);
		if($status) return OK;
	}
	
	/**
	* 抵押物地址报错转换
	*/
	public function transformation($params,$type){
		if($type){
			foreach($params as $key=>$value){
			$data = explode('|',$value[0]);
			
			if($type == '1'){
				$params[$key][0]=$data[0];
			}else if($type == '2'){
				$params[$key][0]=$data[1];
			}else{
				$params[$key][0]= $data[2];
			}
			}
			return $params;
		}else{
			return PARAMSCHECK;
		}
		
	}
	

	
}
