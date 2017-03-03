<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_orders_operator}}".
 *
 * @property integer $id
 * @property integer $productid
 * @property integer $ordersid
 * @property integer $operatorid
 * @property string $level
 * @property string $memo
 * @property string $files
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductOrders $orders
 */
class ProductOrdersOperator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders_operator}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_at'], 'default', 'value' => time()],
            [['create_by'], 'default', 'value' => Yii::$app->user->getId() ],
            [['validflag'], 'default', 'value' => '1' ],
            [['memo','files'], 'default', 'value' => '' ],
            [['modify_at','modify_by'], 'default', 'value' => '0' ],
            [['productid', 'ordersid', 'operatorid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['level'], 'required'],
            [['level', 'validflag'], 'string'],
            [['memo'], 'string', 'max' => 2000],
            [['files'], 'string', 'max' => 100],
            [['ordersid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrders::className(), 'targetAttribute' => ['ordersid' => 'ordersid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '经办ID',
            'productid' => '产品ID',
            'ordersid' => '接单ID',
            'operatorid' => '经办人id',
            'level' => '经办人级别 （1级由接单方设置，2级由经办人设置）',
            'memo' => '备注',
            'files' => '附件',
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
    public function getOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['ordersid' => 'ordersid']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOperatorUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'operatorid']);
    }
	
	public function getOperatorCertification()
    {
        return $this->hasOne(\common\models\Certification::className(), ['uid' => 'operatorid'])->alias('operatorCertification')->select(['operatorCertification.name','operatorCertification.uid']);
    }
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinfo()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'operatorid'])->alias("userinfo")->joinWith("headimg")
		->select(['userinfo.id',"userinfo.username","userinfo.mobile","userinfo.picture","userinfo.realname"]);
    }
	/**
	*	添加经办人
	*	
	*/
	public function set($params=[] , $create_by = ''){
		$create_by = $create_by?:Yii::$app->user->getId();
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return PARAMSCHECK;
		if(!$Orders->accessOrders('ADDOPERATOR',$create_by)){
			return USERAUTH;
		}
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		$operatorIds = explode(",",$params['operatorIds']);
		$datas = self::find()->where(["validflag"=>"1","ordersid"=>$Orders->ordersid,"operatorid"=>$operatorIds])->all();
		if(!$params['operatorIds']){
			$this->addError("operatorid","请选择经办人");
			return MODELDATACHECK;
		}
		if(in_array($Orders->create_by,$operatorIds)){
			$this->addError("operatorid","不可将接单方设置为经办人");
			return MODELDATACHECK;
		}
		if(in_array($Orders->product->create_by,$operatorIds)){
			$this->addError("operatorid","不可将发布方设置为经办人");
			return MODELDATACHECK;
		}
		if($datas){
			$msg = [];
			foreach($datas as $data){
				$msg[] = $data->operatorUser->mobile;
			}
			$this->addError("operatorid","经办人[".implode(",",$msg)."]已设置");
			return MODELDATACHECK;
		}
		$rows=[];
		$level = $create_by==$Orders->create_by?"1":"2";
		foreach($operatorIds as $operatorId){
			$this->level= $level;
			$this->ordersid= $Orders->ordersid;
			$this->productid= $Orders->productid;
			$this->operatorid= $operatorId;
			$this->validate();
			$rows[]=$this->attributes;
		} 
		
		$return =Yii::$app->db->createCommand()->batchInsert(ProductOrdersOperator::tableName(), array_keys($this->attributes), $rows)->execute();
		if($return){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	/**
	 * 删除经办人
	 *
	 *
	 */
	public function recy($params=[],$modify_by = ''){
		$modify_by = $modify_by?:Yii::$app->user->getId();
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return PARAMSCHECK;
		if(!$Orders->accessOrders('ADDOPERATOR',$modify_by)){
			return USERAUTH;
		}
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			return $statusLabel;
		}
		if(!$params['ids']){
			$this->addError("operatorid","请选择经办人");
			return MODELDATACHECK;
		}
		$ids = explode(",",$params['ids']);
		$datas = self::find()->where(["validflag"=>"1","id"=>$ids,"ordersid"=>$Orders->ordersid])->all();
		
		$recyIds=[];
		$recyoperatorPids=[];
		if($datas){
			$msg = [];
			foreach($datas as $data){
				if($data->create_by!=$modify_by/*&&$data->operatorid!=$modify_by*/){
					$msg[] = $data->operatorUser->mobile;
				}else{
					$recyIds[]= $data->id;
					if($data->level==1){
						$recyoperatorPids[]=$data->operatorid;
					}
				}
			}
			if($msg){
				$this->addError("operatorid","[".implode(",",$msg)."]不是你添加的，无法删除");
				return MODELDATACHECK;
			}
		}else{
			$this->addError("operatorid","经办人不存在!");
			return MODELDATACHECK;
		}
		
		$return = $this->updateAll(["validflag"=>"0","modify_at"=>time(),"modify_by"=>$modify_by],["validflag"=>"1","id"=>$recyIds]);
		if($return){
			if($recyoperatorPids){
				$this->updateAll(["validflag"=>"0","modify_at"=>time(),"modify_by"=>$modify_by],["validflag"=>"1","ordersid"=>$Orders->ordersid,"create_by"=>$recyoperatorPids]);
			}
			
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	
}
