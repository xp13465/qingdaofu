<?php

namespace app\models;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
	public $errors = [];
	public $productid = '';
	public $uid = '';
	public $number = '';
	public $applyid = '';
	public $ordersid = '';
	public $listtype = '';
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    } 
	
	/**
	* 删除产品
	*
	*/
	public function productDelete($productid){
		$product = Product::findOne(['productid'=>$productid,'validflag'=>'1']);
		if($product){
			$status = $product->singleDelete($productid);
			if($status != 'ok'){
				$this->errors = $product->errors;
				return $status;
			}
			$this->number = $product->number;
			return OK;
		}else{
			return ParamsCheck;
		}
	}
	
	/**
	* 删除接单
	*/
	public function actionApplyDel(){
		$applyid = Yii::$app->request->post('applyid');
		$ProductOrdersQuery = ProductOrders::find();
		$status = $ProductOrdersQuery->applyDel($applyid);
		switch($status){
			case 'ok':
				$this->success("删除成功",["commentid" => $ProductOrdersQuery->commentid]);
				break;
			default:
				$this->errorMsg($status,$ProductOrdersQuery->formatErrors());
				break ;
		}
	}
}
