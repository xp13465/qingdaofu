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
     * 新保存产品数据
     * @param data
	 * @param create_by  用户id;
	 * @param draft 判断用户是保存还是发布，1为保存,2为发布；
     */
	 public function draft($data,$uid=''){
		 $Product = new Product;
		 if(empty($data)) return CANTSHENQING13;
		 $status = $Product->change($data,$uid,$draft='1');
		 $this->productid = $Product->productid;
		 $this->number = $Product->number;
		 return $status;
	} 
	
	/**
	*  新发布产品数据
	*  
	*/
	public function release($data,$uid=''){
		 $Product = new Product;
		 $status = $Product->change($data,$uid,$draft='2');
		 if($status !='ok'){
			 $this->errors = $Product->errors;
			 return $status;
		 }
		 $status = $Product->release();
		 if(!$status){
			 $Product->delete();
			 return MODELDATASAVE;
		 }
		 $this->productid = $Product->productid;
		 $message = \app\models\Message::find();
		 $data = $message->addMessage(101,['code'=>$Product->number],Yii::$app->user->getId(),$Product->productid,40,$Product->create_by);
		 return OK;
	}
	
	/**
	* 单条产品数据
	*/
	public function View($productid){
		$model = Product::find()->where(['productid'=>$productid,'validflag'=>'1','create_by'=>Yii::$app->user->getId()])->joinWith(['provincename','cityname','areaname'])->asArray()->one();
        return $model;		
	}
	
	/**
	*  编辑产品数据
	* @param data
	* @param productid  产品id;
	* @param draft 判断用户是保存还是发布，1为保存,2为发布； 
	*/
	public function edit($data,$productid,$uid='',$draft){
		if(is_numeric($productid)){
			$model = Product::find()->where(['productid'=>$productid,'validflag'=>'1'])->one();			
			if(!$model) return NOTFOUND;
			$models = $model->checkStatus();
			if(!in_array($models,['ORDERSCONFIRM','ORDERSPACT'])){
				return $models;
			}
			if(!$model->accessProduct('MULTIPLE','',['0','10'])) return USERAUTH;
			$status = $model->edit($data,$productid,$draft);
            if($status != 'ok'){
				$this->errors = $model->errors;	
			    return $status;
			}	
			$this->number = $model->number;
			return OK;
		}
	}
	
	/**
	*  产品列表数据分页处理
	* 
	*/
	public function productList($params=[],$uid='',$isObj=true,$pageload=true){
		$query  = product::find();
		$query->alias('product');
		//产品列表
		if(!$isObj){
			$query->asArray();
			$query->where([
			'product.validflag'=>'1',
			'product.status' => ['10','20','30','40'],
			])->orderBy(['product.status'=>SORT_ASC]);
		}
		//分页
		if($pageload){
			$page = isset($params["page"])?$params["page"]:1;
			$limit = isset($params["limit"])?$params["limit"]:10;
			$query->offset(($page-1)*$limit);
			$query->limit($limit);
		}
		// province省 city市 District区 account金 status状态
        if(isset($params['province'])&&$params['province']!=='0'){
			$query->andFilterWhere(['product.province_id'=>$params['province']]);
		}
        if(isset($params['city'])&&$params['city']!=='0'){
			$query->andFilterWhere(['product.city_id'=>$params['city']]);
		}
        if(isset($params['district'])&&$params['district']!=='0'){
			$query->andFilterWhere(['product.district_id'=>$params['district']]);
		}
        if(isset($params['account'])&&$params['account']!=='0'){
			switch($params['account']){
				case '2':
					$query->andFilterWhere(['<=','product.account','300000']);
				break;
				case '3':
					$query->andFilterWhere(['between','product.account','300000','1000000']);
				break;
				case '4':
					$query->andFilterWhere(['between','product.account','1000000','5000000']);
				break;
				case '5':
					$query->andFilterWhere(['>','product.account','5000000']);
				break;
			}
		}
        if(isset($params['status'])&&$params['status']!=='0'){
	        switch($params['status']){
				case '2':
				$query->andFilterWhere(['product.status'=>'10']);
                break;
                case '3':
				$query->andFilterWhere(['product.status'=>['20','30','40']]);
				break;
			}
		}
		
		if(isset($params['create_by'])&&$params['create_by']!=='0'){
			$query->andFilterWhere(['product.create_by'=>$params['create_by']]);
			
		}
		$query->joinWith(['cityname']);
		$query->joinWith(['provincename','areaname']);
		$query->joinWith(['fabuuser']);
       return $query;		
	}
	
	
	
	/**
	*  产品列表数据分页处理
	* 
	*/
	public function searchLists($params=[],$selfApply=false){
		 $page = isset($params["page"])?$params["page"]:1;
		 $limit = isset($params["limit"])?$params["limit"]:10;
		 $query = $this->productList($params,false,false); 
		 if($selfApply){
		 	$query->joinWith(['applySelf']);
			$query->joinWith(['collectSelf']);
			$query->joinWith(['homeUsesrHead']);
			$query->orderBy(['product.status'=>SORT_ASC]);
		 }
		 $dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_at' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);
		return $dataProvider;
	}
	
	
	
	/**
	*  产品数据查询
	* 
	*/
	public function searchOrder($params=[],$uid='',$isobj=true,$pageload=true){
		$query  = Product::find();
		$query->alias('product');
		$this->listtype = isset($params["listtype"])?$params["listtype"]:'';
		//产品列表
		if(!$isobj){
			$query->asArray();
			$query->where([
			'product.validflag'=>'1',
			'product.status' => ['10','20','30','40'],
			])->orderBy(['product.create_at'=>SORT_DESC]);
		}
		//分页
		if($pageload){
			$page = isset($params["page"])?$params["page"]:1;
			$limit = isset($params["limit"])?$params["limit"]:10;
			// $query->offset(($page-1)*$limit);
			// $query->limit($limit);
		}
		
		switch($this->listtype){
			case 'processing':
				$query->andFilterWhere(['product.status'=>['10','20']]);
				break;
			case 'aborted':
				$query->andFilterWhere(['product.status'=>"30"]);
				break;
			case 'completed':
				$query->andFilterWhere(['product.status'=>"40"]);
				break;
		}
		$query->joinWith(['cityname']);
		$query->joinWith(['provincename','areaname']);
		$query->joinWith(['fabuuser']);
		$query->joinWith(['curapply']);
		$query->joinWith(['orders']);
		if($uid){
			$query->andFilterWhere(['product.create_by' => $uid]);
		}
		return $query;die;
		
	}
	
	/**
	* 草稿保存
	*/
	public function Preservation($params=[]){
		$query = Product::find()->where(['validflag'=>'1','create_by'=>Yii::$app->user->getId(),'status'=>'0'])->asArray();
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$Preservation = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_at' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);
		 
		return $Preservation;
	}
	
	/**
	* 我的发布数据详情
	*/
	public function ProductDeta($params=[],$uid='',$isobj=true){
		$query  = Product::find();
		$query->alias('product');
		// $query->select(['product.productid','product.status','product.create_by','product.number']);
		if(!$isobj){
			$query->asArray();
		}
		$query->andFilterWhere(['product.productid'=>$params['productid']]);
		$query->joinWith(['productApply']);
		$query->joinWith(['fabuuser']);
		$query->joinWith(['cityname','provincename','areaname']);
        if($uid){
			$query->andFilterWhere(['product.create_by' => $uid]);
		}	
       return $query;		
	}
	
	
	
	
	public function Statistics($productid){
		$query = product::findOne(['productid'=>$productid]);
		$data = $query->getStatistics($query->productid);
		return $data['applyCount'];
	}
	
	public function Pingjia($productid){
		$query = product::findOne(['productid'=>$productid]);
		$data = $query->getStatistics($query->productid,false);
		return $data;
	}
	
	/**
	* 查询面谈中的数据
	*/
	public function getInterview($productid){
		$apply = ProductApply::find()->where(['productid'=>$productid,'validflag'=>'1','status'=>'20'])->one();
		if($apply){
			return $apply->status;
		}
		
	}
	
	/**
	*  收藏列表
	* 
	*/
	
	public function collectList($params=[],$uid='',$isobj=true,$pageload=true){
		$query = ProductCollect::find();
		$query->alias('collect');
		//收藏列表
		if(!$isobj){
			$query->asArray();
			$query->joinWith(['product'=>function($query){
				$query->joinWith(['provincename','cityname','areaname']);
			}])->where([
			'collect.validflag'=>'1',
			])->orderBy(['collect.create_at'=>SORT_DESC]);
		}
		//分页
		if($pageload){
			$page = isset($params["page"])?$params["page"]:1;
			$limit = isset($params["limit"])?$params["limit"]:10;
			$query->offset(($page-1)*$limit);
			$query->limit($limit);
		}
		if($uid){
			$query->andFilterWhere(['collect.create_by' => $uid]);
		}
		return $query;
	}
	
		
	/**
	*  产品列表数据分页处理
	* 
	*/
	public function searchList($params=[],$uid='',$collectList=true){
		 $page = isset($params["page"])?$params["page"]:1;
		 $limit = isset($params["limit"])?$params["limit"]:10;
		 if($collectList){
			 $query = $this->collectList($params,$uid,false);
		 }else{
			 $query = $this->searchOrder($params,$uid,false,false); 
		 }
		 
		 $dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_at' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);
		return $dataProvider;
	}
	
	public function addressAll($data,$status){
		//var_dump($data);die;
		foreach ($data as $key=>$value){
			if(isset($value['type'])&&$value['type'] == '1'){
				$data[$key]['addressLabel']= '';
				$data[$key]['addressLabel'].= isset($value['provincename'])&&$value['provincename']?$value['provincename']['province']:"";
				$data[$key]['addressLabel'].= isset($value['cityname'])&&$value['cityname']&&!in_array($value['cityname']['city'],["市辖区","县","崇明县"])?$value['cityname']['city']:"";
				$data[$key]['addressLabel'].= isset($value['areaname'])&&$value['areaname']?$value['areaname']['area']:"";
				$data[$key]['addressLabel'].= isset($value['relation_desc'])&&$value['relation_desc']?$value['relation_desc']:"";
				$data[$key]['addressLabel'] = trim(str_replace("　","",$data[$key]['addressLabel']));
				if($value['create_by'] != Yii::$app->user->getId() && in_array($status,['0','10'])){
					$data[$key]['addressLabel'] = \frontend\services\Func::getSubstrs($data[$key]['addressLabel']);
				}
			}
			if(isset($value['type'])&&$value['type'] == '2'){
				$data[$key]['brandLabel'] = '';
				$data[$key]['brandLabel'].=isset($value['brand'])&&$value['brand']?$value['brand']['name'].'-':'';
				$data[$key]['brandLabel'].=isset($value['audi'])&&$value['audi']?$value['audi']['name'].'-':'';
				$data[$key]['brandLabel'].= isset($value['relation_3'])&&$value['relation_3']=='1'?'沪牌':'非沪牌';
			}
			if(isset($value['type'])&&$value['type'] == '3'){
				$data[$key]['contractLabel'] = isset($value['relation_1'])&&$value['relation_1']?ProductMortgage::$contract[$value['relation_1']]:'';
			}
		}
		return $data;
	}
	
	
	/**
	 * 返回数据处理
	 *
	 *
	 */
	public function filterAll($list){
		foreach($list as $item=>$data){
			$list[$item]= $this->filterOne($data);
		}
		return $list;
	}
	/**
	* 收藏数据处理
	*/
	public function collectAll($list){
		foreach($list as $item => $data){
			$list[$item]['product']= $this->filterOne($data['product']);
		}
		return $list;
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
			if(isset($data['productApply'])){
				$data['ApplystatusLabel'] = $data['productApply']['status']=='20'?'面谈中':$data['productApply']['status'];
			}
			
			//省市区
			$data['addressLabel']='';
			$data['addressLabel'] .=isset($data['provincename'])&&$data['provincename']?$data['provincename']['province']:"";
			$data['addressLabel'] .=isset($data['cityname'])&&$data['cityname']&&!in_array($data['cityname']['city'],["市辖区","县","崇明县"])?$data['cityname']['city']:"";
			$data['addressLabel'] .=isset($data['areaname'])&&$data['areaname']?$data['areaname']['area']:"";
			$data['addressLabel'] = trim(str_replace("　","",$data['addressLabel']));
		
		return $data;
	}
	
	/**
	* 收藏产品
	*
	*/
	
	public function collect($productid){
		$parduct = Product::findOne(['productid'=>$productid]);
		if($parduct){
			if($parduct->create_by == Yii::$app->user->getId()){
				return CANTSGHOUCANG9;
			}else{
				$productId = ProductCollect::findOne(['productid'=>$productid,'validflag'=>['0','1'],'create_by'=>Yii::$app->user->getId()]);
				if($productId){
						if($productId->validflag == '1'){
							return CANTSGHOUCANG2;
						}else{
							//if(!$productId->product->accessProduct('COLLECTION',$productId->create_by)) return USERAUTH;
							$status = $productId->collect($productid);
							if($status != 'ok'){
								$this->errors = $productId->errors;
								return $status;
							}
						}
					}else{
						$query = new ProductCollect;
						$status = $query->collect($productid);
						if($status != 'ok'){
							$this->errors = $query->errors;
							return $status;
						}	
					}
				$this->number = $parduct->number;
				return OK;
			}
			
		}else{
			return NOTFOUND;
		}	
	}
	
	/**
	* 删除产品
	*
	*/
	public function productDelete($productid){
		$product = Product::findOne(['productid'=>$productid,'validflag'=>'1','create_by'=>Yii::$app->user->getId()]);
		if($product){
			if(!$product->accessProduct('MULTIPLE')) return USERAUTH;
			$status = $product->singleDelete($productid);
			if($status != 'ok'){
				$this->errors = $product->errors;
				return $status;
			}
			$this->number = $product->number;
			return OK;
		}else{
			return NOTFOUND;
		}
	}
	
	/**
	* 取消收藏产品
	*
	*/
	public function collectCancel($productid){
		   $productId = ProductCollect::findOne(['productid'=>$productid,'validflag'=>'1','create_by'=>Yii::$app->user->getId()]);
		   if($productId){
			  if(!$productId->product->accessProduct('COLLECTION',$productId->create_by)) return USERAUTH;
			  $status = $productId->cancel($productid);
			  if($status != 'ok'){
				$this->errors = $productId->errors;
				return $status;
			  }
		   }else{
			   return NOTFOUND;
		   }
		    return OK;	
	}
	
	/**
	* 抵押物地址新增
	*/
	public function mortgageAdd($params){
		$productMortgages = new ProductMortgage;
		$status = $productMortgages->mortgageAdd($params);
		if($status !='ok'){
			$this->errors = $productMortgages->transformation($productMortgages->errors,$params['type']);
			 return $status;
		 }
		if(!$status){
			 $productMortgages->delete();
			 return MODELDATASAVE;
		}
			// $this->productid = $productMortgages->productid;
			 return OK;
	}
	
	/**
	* 抵押物地址编辑
	*/
	public function mortgageEdit($params){
		if(is_numeric($params['mortgageid'])){
			$productMortgages = ProductMortgage::findOne(['mortgageid'=>$params['mortgageid'],'validflag'=>'1']);
			if($productMortgages){
				$Mortgages = $productMortgages->product->checkStatus();
				if(!in_array($Mortgages,['ORDERSCONFIRM','ORDERSPACT'])){
					return $Mortgages;
				}
				if(!$productMortgages->product->accessProduct('MULTIPLE')) return USERAUTH;
				$status = $productMortgages->mortgageAdd($params);
				if($status != 'ok'){
					$this->errors = $productMortgages->transformation($productMortgages->errors,$params['type']);	
					return $status;
				}	
					return OK;
			}else{
				return NOTFOUND;
			}
		}else{
			return PARAMSCHECK;
		}
		
		
	}
	
	/**
	* 抵押物地址删除
	*/
    public function mortgageDel($mortgageid){
		$productMortgages = ProductMortgage::findOne(['mortgageid'=>$mortgageid,'validflag'=>'1']);
		if($productMortgages){
			if(!$productMortgages->product->accessProduct('MULTIPLE')) return USERAUTH;
			$status = $productMortgages->mortgageDel($mortgageid);
			if($status != 'ok'){
				$this->errors = $productMortgages->errors;	
				return $status;
			}			
			return OK;
		}else{
			return NOTFOUND;
		}
	} 
    
	/**
	* 抵押物地址详情
	*/
	public function mortgageDetail($mortgageid){
		 $productMortgages = ProductMortgage::findOne(['mortgageid'=>$mortgageid,'validflag'=>'1']);
	    if($productMortgages){
			if(!$productMortgages->product->accessProduct('MULTIPLE')) return USERAUTH;
			$mortgage = new ProductMortgage;
			$productMortgages = $productMortgages->toArray();
			$productMortgages['productMortgages'] = $mortgage->category($productMortgages['type'],$productMortgages['relation_1'],$productMortgages['relation_2'],$productMortgages['relation_3']);
			return $productMortgages;
		}else{
			return NOTFOUND;
		}
	}
	
	
	/**
	* 接单方申请接单
	*/
	
	public function apply($productid){
		    $this->where(['productid'=>$productid]);
			$productData = $this->one();
			if(!$productData)return PARAMSCHECK;
			$model = $productData->checkStatus();
			if(!in_array($model,['ORDERSPACT'])){
				return $model;
			}
			if($productData->accessProduct('MULTIPLE',['10'])) return CANTSGHOUCANG5;
			
			
			$apply = ProductApply::find()->where(['productid'=>$productid,'create_by'=>Yii::$app->user->getId(),'validflag'=>'1','status'=>['10','20','40']])->one();
			if(!$apply){
				$applyAdd = new ProductApply;
				$status = $applyAdd->change($productid);
				if($status != 'ok'){
					$this->errors = $applyAdd->errors;
					return $status;
				}
				$this->applyid = $applyAdd->applyid;
				$message = \app\models\Message::find();
			    $data = $message->addMessage(100,['code'=>$productData->number],$productData->create_by,$productid,40,$productData->applyOne->create_by);
				if(!$productData->certificationUser()){
					return ON;
				}else{
					return OK;	
				}
				
			}else{
				switch($apply->status){
					case '10':
						return CANTSGHOUCANG4;
						break;
					case '20':
					    return CANTSGHOUCANG8;
						break;
					case '40':
					    return ORDERSPROCESS;
						break;
				}
			}
	}
	
	/**
	* 接单方取消申请
	*/
	public function applyCancel($applyid){
          $applyCancel = ProductApply::findOne(['applyid'=>$applyid,'validflag'=>'1','status'=>'10']);
          if($applyCancel){
			  if(!$applyCancel->product->accessProduct('COLLECTION',$applyCancel->create_by)) return USERAUTH;
			  $status = $applyCancel->applyCancel($applyid);
			  if($status != 'ok'){
				  $this->errors = $applyCancel->errors;
				  return $status;
			  }
			  return OK;
		  }else{
			  return NOTFOUND;
		  }
	}
	
	/**
	*  申请接单人列表
	*/
	public function applicantList($productid,$isobj=true){
		$this->joinWith(['certi'])->where(['productid'=>$productid,'validflag'=>'1']);
		$isArray = $isobj?false:true;
		$product = $this->one();
		if(!$product)return PARAMSCHECK;
		if(!$product->accessProduct('MULTIPLE')) return USERAUTH;
		$param = $product->getProductApplies(true,['createuser']);
		$data['apply'] = isset($param)?$param:'';
		$data['state'] = isset($product->certi->state)?$product->certi->state:'';
	    return $data;
	}
	
	/**
	*  申请接单人列表
	*/
	public function applyPeople($applyid){
		$query = ProductApply::find()->alias('apply')->where(['apply.applyid'=>$applyid,'apply.validflag'=>'1','apply.status'=>'10'])->joinWith('createuser');
		return $query;
	}
	
	/**
	*  接单详情
	*/
	
	public function applyDetails($userid){
		//$username = new User;
		$limit = Yii::$app->request->post('limit',10);
		$page = Yii::$app->request->post('page',1);
		$certification = User::getCertifications($userid);
		$username = new User;
		$comment = $username->commentList($certification['id'],false,$limit,$page);
		$data = array_merge($certification,$comment);
		return $data;
	}

	/**
	* 发布方选择接单方面谈
	*/
	public function applyChat($applyid){
          $applyChat = ProductApply::findOne(['applyid'=>$applyid,'validflag'=>'1']);
		  $applys = productApply::find()->where(['productid'=>$applyChat->productid,'status'=>'20','validflag'=>'1'])->one();
		   if($applys){
			 return CANTSHENQING17; 
		   }
          if($applyChat){
			  $model = $applyChat->product->checkStatus();
			  if(!in_array($model,['ORDERSPACT'])){
					return $model;
				}
			  if(!$applyChat->product->accessProduct('MULTIPLE',['10'])) return USERAUTH;
			  //if(!$applyChat->product->CertificationUser($applyChat->create_by)) return CANTSHENQING15;
			  $data = $applyChat->applyChat($applyid);
			  if($data != 'ok'){
				  $this->errors = $applyChat->errors;
				  return $data;
			  }
			  $message = \app\models\Message::find();
			  $data = $message->addMessage(107,['code'=>$applyChat->product->number],$applyChat->create_by,$applyid,50,$applyChat->product->create_by);
			  return OK;
		  }else{
			  return CANTSHENQING16;
		  }  		  
	}
	
	/**
	* 发布方取消接单方面谈
	*/
	public function applyVeto($applyid){
		$applyChat = ProductApply::findOne(['applyid'=>$applyid,'validflag'=>'1']);
          if($applyChat){
			  $model = $applyChat->product->checkStatus();
			  if(!in_array($model,['ORDERSPACT'])){
					return $model;
				}
			  if(!$applyChat->product->accessProduct('MULTIPLE',['10'])) return USERAUTH;
			  $data = $applyChat->applyVeto($applyid);
			  if($data != 'ok'){
				  $this->errors = $applyChat->errors;
				  return $data;
			  }
			  $message = \app\models\Message::find();
			  $data = $message->addMessage(108,['code'=>$applyChat->product->number],$applyChat->create_by,$applyid,50,$applyChat->product->create_by);
			  return OK;
		  }else{
			  return NOTFOUND;
		  }
	}
	/**
	* 发布方同意接单方处理
	*/
	public function applyAgree($applyid){
		$applyChat= ProductApply::findOne(['applyid'=>$applyid,'validflag'=>'1']);
        if($applyChat){
			  $model = $applyChat->product->checkStatus();
			  if(!in_array($model,['ORDERSPACT'])){
					return $model;
				}
			  if(!$applyChat->product->accessProduct('MULTIPLE',['10'])) return USERAUTH;
			  if(!$applyChat->product->CertificationUser($applyChat->create_by)){
				  $message = Message::find();
				  $message->addMessage(119,['code'=>$applyChat->product->number],$applyChat->create_by,$applyid,50,$applyChat->product->create_by);
				  return CANTSHENQING15; 
			  }
			  $data = $applyChat->product->productAgree($applyChat->productid);
			  $data = $applyChat->applyAgree($applyid,$applyChat->productid);
			  if($data != 'ok'){
				  $this->errors = $applyChat->errors;
				  return $data;
			  }
			  $message = Message::find();
			  $message->addMessage(109,['code'=>$applyChat->product->number],$applyChat->create_by,$applyid,50,$applyChat->product->create_by);
			  return OK;
		  }else{
			  return NOTFOUND;
		  }
	}
	
	
	
	
	public function formatErrors($isAll=false)
    {
        $result = '';
        foreach($this->errors as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
			if(!$isAll)break;
        }
        return $result;
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductApplies()
    {
        return $this->hasMany(ProductApply::className(),['productid' => 'productid'])->alias('apply')->select(['productid','status','applyid','create_by']);
    }
	
	/**
	* 接单协议
	*/
	public function Agreement($params=[],$uid='',$isobj=true){
		$query  = Product::find();
		$query->alias('product');
		$query->select(['product.productid','product.status','product.create_by','product.number','product.account','product.overdue']);
		if(!$isobj){
			$query->asArray();
		}
		$query->andFilterWhere(['product.productid'=>$params['productid'],'product.validflag'=>'1']);
		$query->joinWith(['agreementApply']);
		$query->joinWith(['releaseUser','fabuuser']);
        if($uid){
			$query->andFilterWhere(['product.create_by' => $uid]);
		}	
       return $query;		
	}
}
