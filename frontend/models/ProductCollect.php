<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_collect}}".
 *
 * @property integer $collectid
 * @property integer $productid
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property Product $product
 */
class ProductCollect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_collect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['validflag'], 'string'],
            [['productid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['productid' => 'productid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collectid' => '收藏ID',
            'productid' => '产品ID',
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
        return $this->hasOne(Product::className(), ['productid' => 'productid'])->alias('product')->onCondition(['product.validflag'=>['0','1']]);
    }
	/**
     * @inheritdoc
     * @return ProductOrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
	
	/**
	* 收藏产品
	*/
	public function collect($productid){
		$product = ['productid'=>$productid];
		$this->setAttributes($product);
		if($this->isNewRecord){
			if(!$this->validate()) return MODELDATACHECK;
			$this->create_at = time();
			$this->create_by = Yii::$app->user->getId();
			if($this->save()){
				return OK;
			}else{
				return MODELDATASAVE;
			}
		}else{
			$status = $this->updateAll(['validflag'=>'1','modify_by'=>Yii::$app->user->getId(),'modify_at'=>time()],['productid'=>$productid,'validflag'=>'0','create_by'=>Yii::$app->user->getId()]);
			if($status) return OK;
		}
		
	}
	
	/**
	* 产品更新
	*/
	public function cancel($productid){
		$status = $this->updateAll(['validflag'=>'0'],['productid'=>$productid,'validflag'=>'1']);
		if($status) return OK;
	}
	
}
