<?php

namespace app\models;
use Yii;
use app\models\ProductOrdersLog;

/**
 * This is the model class for table "{{%product_orders_process}}".
 *
 * @property integer $processid
 * @property integer $productid
 * @property integer $ordersid
 * @property string $type
 * @property string $operator
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
class ProductOrdersProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders_process}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_at'], 'default', 'value' => time()],
			[['type'], 'default', 'value' => '0'],
            [['create_by','operator'], 'default', 'value' => Yii::$app->user->getId() ],
            [['validflag'], 'default', 'value' => '1' ],
            [['productid', 'ordersid', 'create_at', 'create_by', 'modify_at', 'modify_by', 'operator'], 'integer'],
            [['type', 'ordersid', 'memo', 'productid'], 'required'],
            [['type', 'validflag'], 'string'],
			['type','in','range'=>['0','1','2','3','4','5'],'message'=>'请选择正确的进度类型'],
            [['memo'], 'string', 'max' => 2000,'min' => 5 ],
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
            'processid' => '操作ID',
            'productid' => '产品ID',
            'ordersid' => '接单ID',
            'type' => '处置类型（0留言，1清收，2查封, 3材料符合， 4产调，5实地评估）',
            'operator' => '经办人',
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
	public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid']);
    }
	
	/**
	*	处置操作生成
	*	@param params 表单数据
	*   @param create_by 用户id
	*/
	public function create($params=[] , $create_by = ''){
		$create_by=$create_by?:Yii::$app->user->getId();
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return PARAMSCHECK;
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSPROCESS'){
			if($create_by==$Orders->product->create_by){
				if($statusLabel!='ORDERSCONFIRM'&&$statusLabel!='ORDERSPACT'){
					return $statusLabel;
				}
			}else{
				return $statusLabel;
			}
			
		}
		$this->setAttributes($params);
		$this->productid = $Orders->productid;
		$this->memo = htmlspecialchars($this->memo);
		if($create_by)$this->create_by = $create_by;
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			$ProductOrdersLog = new \app\models\ProductOrdersLog;
			$beforeStatus = $Orders->status;
			$afterStatus = $Orders->status;
			$ProductOrdersLog->create($this->ordersid,$this->processid,$this->memo,$beforeStatus,$afterStatus,(20+$this->type),2,"",$this->files);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
}
