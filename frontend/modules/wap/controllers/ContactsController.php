<?php

namespace frontend\modules\wap\controllers;

use frontend\modules\wap\components\WapController;
use yii;
use app\models\Contacts;
/**
 * 用户操作控制器
 */
class ContactsController extends WapController
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Contacts';
	
	/**
	*  我通讯录列表
	*  
	*/
	
	public function actionIndex()
    { 
		$uid = Yii::$app->session->get('user_id');
		$uid = $uid?$uid:Yii::$app->user->getId();
        $ContactsQuery = Contacts::find(); 
		$params ['page'] = Yii::$app->request->post("page",1);
		$params ['limit'] = Yii::$app->request->post("limit",10);
		$params ['ordersid'] = Yii::$app->request->post("ordersid");
		//var_dump($params);die;
		$provider = $ContactsQuery->searchList($params,$uid,false);
		
		 
		$data = $provider->getModels(); 
		// if($ordersid){
			// $ProductOrdersQuery = \app\models\ProductOrders::find();
			// $operatorList = $ProductOrdersQuery->ordersOperatorList($ordersid,false);
			// var_dump($operatorList);exit;
			// foreach($data as $key=>$val){
				// $data[$key]["aaa"]="213";
			// }
		// }
		// var_dump($data);
		$this->success("",
			[
			"data"=>$data,
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
