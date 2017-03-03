<?php
namespace app\models;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Solutionseal;

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
	
    
	
	//产品数据展示
	public function View($productid,$params=[]){
         $query = Solutionseal::find();
		 $data = $query->productView($productid);
		 return $data;
	}
	
	//组合数据
	public function ProductData($params=[],$fabuid='',$uid=''){
		$data = [];
		if(isset($params['auditLog'])&&$params['auditLog']){
			foreach($params['auditLog'] as $key=>$value){
				$data[$value['afterstatus']]['id'] = $value['afterstatus']; 
				foreach($value['admin'] as $v){
					$data[$value['afterstatus']]['name'] = $v['personnels']['name'];
				}
				$data[$value['afterstatus']]['time'] = date('Y-m-d',$value['action_at']);
				$data[$value['afterstatus']]['timeint'] = $value['action_at'];
			}
		}else if(isset($params['ordersLogs'])&&$params['ordersLogs']){
			$data = [];
		}
		return $data;
	}
	
	/**
	 * 返回数据处理
	 *
	 *
	 */
	public function filterOne($data){
			if(!$data)return $data;
			$productStatus = Product::$status;
			// 状态处理
			 if(isset($data['status'])){
				$data['statusLabel'] = isset($productStatus[$data['status']])?$productStatus[$data['status']]:$data['status'];
			 }
			//金额
			if(isset($data['account'])){
				$data['accountLabel'] = (round($data['account']/10000,1));
			}
			if(isset($data['type'])&&$data['type'] == '1'){
				$data['typenumLabel'] = round($data['typenum']/10000,1);
			}else{
				$data['typenumLabel'] =$data['typenum'] ;
			}
			// 债权类型
			if(isset($data['category'])){
				$categoryLabel="";
				$category_other=$data['category_other'];
				$ProductCategory = Product::$category;
				$categoryList = explode(",",$data['category']);
				foreach($categoryList as $k){
					if(in_array($k,[1,2,3])){
						$categoryLabel.=$categoryLabel?(",".$ProductCategory[$k]):$ProductCategory[$k];
					}else if($k == 4){
						$categoryLabel.=$category_other?($categoryLabel?",".$category_other:$category_other):'';
					}
			    }
				$data['categoryLabel']=$categoryLabel;
			}
			
			// 委托权限
			if(isset($data['entrust'])){
				$entrustLabel="";
				$entrust_other=$data['entrust_other'];
				$ProductEntrust = Product::$entrust;
				$entrustList = explode(",",$data['entrust']);
				foreach($entrustList as $k){
					if(in_array($k,[1,2,3])){
						$entrustLabel.=$entrustLabel?(",".$ProductEntrust[$k]):$ProductEntrust[$k];
					}else if($k == 4){
						$entrustLabel.=$entrust_other?($entrustLabel?",".$entrust_other:$entrust_other):'';
					}
				}
				$data['entrustLabel']=$entrustLabel;
				
			}
			
		    // 费用类型
			$ProductType = Product::$type;
			if(isset($data['type'])){
				$data['typeLabel']=$ProductType[$data['type']];
			}

		    //债权类型
			if(isset($data['productMortgages'])){
				$productMortgages = $data['productMortgages'];
				foreach ($productMortgages as $ky => $ve){
					if(in_array($ve['type'],['1','2'])){
						$mortgage = new \app\models\ProductMortgage;
						$category = $mortgage->category($ve['type'],$ve['relation_1'],$ve['relation_2'],$ve['relation_3']);
					    $data['productMortgages'][$ky]['productCategory'] = $category; 
					}			
				}
				
			}
			// if(isset($data['productApply'])){
				// $data['ApplystatusLabel'] = $data['productApply']['status']=='20'?'面谈中':$data['productApply']['status'];
			// }
			
			//省市区
			$data['addressLabel']='';
			$data['addressLabel'] .=isset($data['provincename'])&&$data['provincename']?$data['provincename']['province']:"";
			$data['addressLabel'] .=isset($data['cityname'])&&$data['cityname']&&!in_array($data['cityname']['city'],["市辖区","县","崇明县"])?$data['cityname']['city']:"";
			$data['addressLabel'] .=isset($data['areaname'])&&$data['areaname']?$data['areaname']['area']:"";
			$data['addressLabel'] = trim(str_replace("　","",$data['addressLabel']));
		
		return $data;
	}
}
