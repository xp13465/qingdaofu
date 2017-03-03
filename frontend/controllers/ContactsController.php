<?php

namespace frontend\controllers;
use frontend\components\FrontController;
use yii\filters\AccessControl;
 
use yii;
use app\models\Contacts;
/**
 * 用户操作控制器
 */
class ContactsController extends FrontController
{
    public $layout = 'main';
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Contacts';
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
	/**
	*  我通讯录列表
	*  
	*/
	
	public function actionIndex($type=1)
    {  
		$uid = Yii::$app->user->getId();
        $ContactsQuery = Contacts::find(); 
		$params ['page'] = Yii::$app->request->get("page",1);
		$params ['limit'] = Yii::$app->request->get("limit",10);
		$params ['ordersid'] = Yii::$app->request->get("ordersid");
		$provider = $ContactsQuery->searchList($params,$uid,false);
		
		
		
		$data = $provider->getModels(); 
		if($type==2&&$params ['ordersid']){
			$orders=\app\models\ProductOrders::find()->asArray()->joinWith(["createuser"])->where(["ordersid"=>$params ['ordersid']])->one();
		}else{
			$orders = [];
		}
		
		
		// if($ordersid){
			// $ProductOrdersQuery = \app\models\ProductOrders::find();
			// $operatorList = $ProductOrdersQuery->ordersOperatorList($ordersid,false);
			// var_dump($operatorList);exit;
			// foreach($data as $key=>$val){
				// $data[$key]["aaa"]="213";
			// }
		// }
		// var_dump($orders);
		return $this->render("index",
			[
			"type"=>$type,
			"ordersid"=>$params ['ordersid'],
			"orders"=>$orders,
			"data"=>$data,
			"provider"=>$provider,
			"totalCount"=>$provider->getTotalCount(),
			"curCount"=>$provider->getCount(),
			"pageSize"=>$provider->pagination->getPageSize(),
			"curpage"=>$provider->pagination->getPage()+1
			]
		);
		 
    }
	
	/**
	*  搜索用户
	*  
	*/
	
	public function actionSearch()
    {
        $uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $ContactsQuery = Contacts::find(); 
		$mobile = Yii::$app->request->post("mobile");
		if(Yii::$app->user->identity->mobile==$mobile){
			$this->errorMsg('NotFound','不可添加自己到通讯录');
		}
		$userData = $ContactsQuery->searchUser($mobile);
		if($userData){
			$this->success("",['userData'=>$userData]);
		}else{
			$this->errorMsg('NotFound');
		}
    }
	
	
	/**
	*  添加联系人
	*  
	*/
	public function actionApply()
    {
		$userid = Yii::$app->request->post('userid');
        $ContactsQuery = Contacts::find();  
		$status = $ContactsQuery->applyUser($userid);
		
		switch($status){
			case 'ok':
				$this->success("联系人添加成功",['contactsid'=>$ContactsQuery->contactsid]);
				break;
			default:
				$this->errorMsg($status,$ContactsQuery->formatErrors());
				break ;
		}
    }
	/**
	*  删除联系人
	*  
	*/
	
	public function actionDel()
    {	
		 
		$contactsid = Yii::$app->request->post('contactsid');
        $ContactsQuery = Contacts::find(); 
		$status = $ContactsQuery->recyUser($contactsid);
		switch($status){
			case 'ok':
				$this->success("联系人移除成功",['contactsid'=>$ContactsQuery->contactsid]);
				break;
			default:
				$this->errorMsg($status,$ContactsQuery->formatErrors());
				break ;
		}
    }
	 
    
}
