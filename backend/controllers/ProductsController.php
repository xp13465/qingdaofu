<?php
namespace backend\controllers;

use Yii;
use app\models\Product;
use app\models\ProductApply;
use app\models\ProductStatus;
use app\models\ProductApplySearch;
use backend\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for product model.
 */
class ProductsController extends BackController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductStatus();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionExcel(){
		$searchModel = new ProductStatus();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pagesize =0;
		$dataProvider->pagination->page =0;
		// 'pagination' => [
                // 'pagesize' => '10',
			// ]
		$data = $dataProvider->getModels();
		
		// var_dump(count($data));
		// exit;
		$arr = [];
		foreach($data as $key => $value){
			$r=[];
			$r['item'] = $key+1;
			$r['productid'] = $value['productid'];
			$r['mobile'] = $value['userMobile']['mobile'];
			$r['account'] = $value['account']/10000;
			if($value['type']=='1'){
					 $r['typernum'] = $value['typenum']/10000 .'万';
					 $r['typeLible'] = ' 固定费用';
				 }else if($value['type']=='2'){
					 $r['typernum'] = round($value['typenum']) .'%';
					 $r['typeLible'] = ' 代理费率';
			}
			$r['create_at'] = date('Y-m-d H:i:s',$value['create_at']);
			
			if(isset($value['productApplies'])&&!$value['productApplies']&&$value['status']=='10'){
				$r['status'] = '发布中';
			}else if(isset($value['productApplies'])&&$value['productApplies']){
				$r['status'] = '';
				   foreach($value['productApplies'] as $v){
					if($value['status']=='10'&&$v['status']=='20'){
						$r['status'] = '面谈中';
					}else if(in_array($value['status'],['20','30','40'])){
						$r['status'] = '已接单';
					}else{
						$r['status'] = '已申请';
					}
				}
							//return $arr['status'];
			}else if($value['status']=='0'){
				$r['status'] = '待发布';
			}
			$r['validflag'] = isset($value['validflag'])&&$value['validflag']=='1'?'未回收':'已回收';
			$r['number'] = $value['number'];
			$r['productUser'] = isset($value['userName'])&&$value['userName']['name']?$value['userName']['name']:'还未认证';
			// if(isset($value['productApplies'])&&$value['productApplies']){
				// foreach($value['productApplies'] as $key => $va){
					// if($va['status']=='40'){
						// $r['ordersUser'] = isset($va['certification'])&&$va['certification']['name']?$va['certification']['name']:'';
						// $r['ordersMobile'] = isset($va['user'])&&$va['user']['mobile']?$va['user']['mobile']:'';
					// }
				// }
			// }
			$arr[] = $r;
			
		}
		
		\moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['item','productid','mobile','productUser','account','typernum','number','create_at','validflag','status'],
            'headers'=>['item'=>'序号','productid'=>'ID','mobile'=>'手机号','productUser'=>'姓名','account'=>'金额（万）','typernum'=>'委托费用','number'=>'编码','create_at'=>'发布时间','validflag'=>'是否回收','status'=>'进度']
        ]);
	}
	
	/**
     * Lists all product models.
     * @return mixed
     */
    public function actionOrders()
    {
        $applyModel = new ProductApplySearch();
        $dataProvider = $applyModel->search(Yii::$app->request->queryParams);
        return $this->render('orders', [
            'searchModel' => $applyModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/**
     * Displays a single product model.
     * @param integer $id
     * @return mixed
     */
    public function actionOrdersList($id)
    {
        return $this->render('ordersList', [
            'model' => $this->applyFindModel($id),
        ]);
    }

    /**
     * Displays a single product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		
        return $this->render('view', [
            'model' => $this->findModel($id,$Handle=true),
        ]);
    }

    /**
     * Creates a new product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->productid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->productid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
    /**
     * Deletes an existing product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {   $productid = Yii::$app->request->post('productid');
	    $ProductQuery=Product::find();
		$status = $ProductQuery->productDelete($productid);
		switch($status){
			case "ok":
				$this->success("删除成功");
				break;
			default:
				$this->errorMsg($status,$ProductQuery->formatErrors());
				break;
		}
    }
	
	/**
	* 删除接单
	*/
	public function actionApplyDel(){
		$applyid = Yii::$app->request->post('applyid');
		$applyModel = new ProductApplySearch();
		$status = $applyModel->applyDel($applyid);
		switch($status){
			case 'ok':
				$this->success("删除成功");
				break;
			default:
				$this->errorMsg($status,$status->formatErrors());
				break ;
		}
	}

    /**
     * Finds the product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id,$Handle=false)
    {   
	    if($Handle){
			$applyModel = new ProductApplySearch();
			$query = Product::find();
			$query->alias('product');
			$query->andFilterWhere(['product.productid'=>$id]);
			$query->joinWith(['provincename','cityname','areaname','userName','userMobile','productApply','productOrders']);
			$product = $query->one();
			$data = $query->asArray()->one();
			if($product){
				$data['productMortgages1']=$applyModel->addressAll($product->productMortgages1,$data['status']);
				$data['productMortgages2']=$applyModel->addressAll($product->productMortgages2,$data['status']);
				$data['productMortgages3']=$applyModel->addressAll($product->productMortgages3,$data['status']);
			}
			if(isset($data['productOrders'])&&$data['productOrders']['productOrdersLogs']){
					$dataobj = \app\models\ProductOrders::find()->where(["ordersid"=>$data['productOrders']['ordersid']])->one();
					$data['accessTerminationAUTH'] = $dataobj->accessTermination('AUTH');
					$data['checkStatus']= $dataobj->checkStatus();
					$data['accessClosedAUTH'] = $dataobj->accessClosed('AUTH');
					$data['pacts'] = $dataobj->pacts;
					$data['productOrders']['productOrdersLogs']  = \app\models\ProductOrdersLog::filterAll($data['productOrders']['productOrdersLogs'],$data['accessTerminationAUTH'],$data['accessClosedAUTH'],$data['checkStatus'],$data['create_by'],$data['productOrders']['create_by'],$data['productOrders']['productOrdersOperators']);
			}
			if($data){
				return $data;
			}else{
				throw new NotFoundHttpException('The requested page does not exist.'); 
			}
			
		}else{
			if (($model = Product::findOne($id)) !== null) { 
				return $model; 
			} else { 
				throw new NotFoundHttpException('The requested page does not exist.'); 
			} 
		}
    }
	
	protected function applyFindModel($id)
    {   
	    $applyModel = new ProductApplySearch();
		$query = ProductApply::find();
		$query->alias('productApply');
		$query->andFilterWhere(['productApply.applyid'=>$id]);
        $query->joinWith(['product'=>function($query){             
			$query->joinWith(['provincename','cityname','areaname','userName','userMobile']);
		},'user','certification','productOrders']);
		$ProductApply = $query->one();
		$data = $query->asArray()->one();
		if($ProductApply){
			$data['productMortgages1']=$applyModel->addressAll($ProductApply->product->productMortgages1,$data['product']['status']);
			$data['productMortgages2']=$applyModel->addressAll($ProductApply->product->productMortgages2,$data['product']['status']);
			$data['productMortgages3']=$applyModel->addressAll($ProductApply->product->productMortgages3,$data['product']['status']);
		}
		if(isset($data['productOrders'])&&$data['productOrders']['productOrdersLogs']){
				$dataobj = \app\models\ProductOrders::find()->where(["ordersid"=>$data['productOrders']['ordersid']])->one();
				$data['accessTerminationAUTH'] = $dataobj->accessTermination('AUTH');
				$data['checkStatus']= $dataobj->checkStatus();
				$data['accessClosedAUTH'] = $dataobj->accessClosed('AUTH');
				$data['pacts'] = $dataobj->pacts;
				$data['productOrders']['productOrdersLogs']  = \app\models\ProductOrdersLog::filterAll($data['productOrders']['productOrdersLogs'],$data['accessTerminationAUTH'],$data['accessClosedAUTH'],$data['checkStatus'],$data['create_by'],$data['productOrders']['create_by'],$data['productOrders']['productOrdersOperators']);
		}
		if($data){
			return $data;
		}else{
			throw new NotFoundHttpException('The requested page does not exist.'); 
		}
    }
	
	/**
	* 接单协议
	*/
    public function actionAgreement($type="json"){
		$searchModel = new ProductStatus();
        $type = Yii::$app->request->get('type',$type);
        $productid = Yii::$app->request->get('productid');
		$category = Yii::$app->request->get('category');
		$agreement = $searchModel->agreement(['productid'=>$productid],'',false);
		$data  = $agreement->asArray()->one();
		if(!$data) throw new NotFoundHttpException('The requested page does not exist.');
		if($type=="json"){
			return $this->success("",['data'=>$data]);
		}
		$this->layout =false;
		if (isset($category)&&$category) {
			//居间协议（清收委托人）
			$view="jujian";
		} else {
			//居间协议 （清收人）
			$view="jujian01";
		}
       
		if($type=="pdf"){
			$mpdf=new \mPDF('zh-CN','A4','','ccourierB','15','15','24','24','5','5');
			$mpdf->useAdobeCJK = true;
			$header='<table width="100%" style="">
			<tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:px;color:#AAA">页眉</td>
			<td width="20%" style="text-align: right;"></td>
			</tr> 	
			</table>';
			//设置PDF页脚内容
			$footer='<table width="100%" style=" vertical-align: bottom; font-family:黑体; font-size:12pt; color: #;"><tr style="height:px"></tr><tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:10px;">{PAGENO}/{nb}</td>
			<td width="20%" style="text-align: left;"></td>
			</tr></table>';
			//添加页眉和页脚到pdf中
			$mpdf->SetHTMLHeader($this->renderPartial('pdf_header'));
			$mpdf->SetHTMLFooter($this->renderPartial('pdf_footer'));                                              
			$mpdf->WriteHTML($this->renderPartial($view,['data'=>$data]));
			// $mpdf->WriteHTML($this->renderPartial('myview'));
			//$mpdf->AddPage();
			$mpdf->Output('MyPDF.pdf', "I");//I查看D下载
			exit;
		}else{
			return $this->render($view,['data'=>$data]);
		}
    }
}
