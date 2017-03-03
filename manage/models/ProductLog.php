<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_log}}".
 *
 * @property integer $logid
 * @property integer $productid
 * @property string $level
 * @property string $memo
 * @property string $validflag
 * @property integer $afterstatus
 * @property integer $beforestatus
 * @property integer $action_by
 * @property integer $action_at
 *
 * @property Product $product
 */
class ProductLog extends \yii\db\ActiveRecord
{
	public static function getDb()  
    {  
        return \Yii::$app->qdfdb;  
    } 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_product_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		    [['action_at'], 'default', 'value' => time()],
            [['action_by'], 'default', 'value' => Yii::$app->user->getId()],
            [['productid', 'afterstatus', 'beforestatus'], 'integer'],
            [['level', 'validflag'], 'string'],
            [['memo'], 'string', 'max' => 2000],
            [['productid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['productid' => 'productid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logid' => 'Logid',
            'productid' => '产品ID',
            'level' => '日志级别 1进度日志，2子表日志',
            'memo' => '审批备注',
            'validflag' => '回收状态',
            'afterstatus' => '新状态',
            'beforestatus' => '源状态',
            'action_by' => '操作人',
            'action_at' => '操作时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productid' => 'productid']);
    }
	
	public function create($productid,$level='1',$memo,$afterstatus,$beforestatus,$action_by){
		if($action_by) $this->action_by = $action_by;
		$this->action_at = time();
		$this->productid = $productid;
		$this->level = $level;
		$this->memo = $memo;
		$this->afterstatus = $afterstatus;
		$this->beforestatus = $beforestatus;
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
}
