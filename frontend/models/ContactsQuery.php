<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the ActiveQuery class for [[Contacts]].
 *
 * @see Contacts
 */
class ContactsQuery extends \yii\db\ActiveQuery
{
	public $contactsid='';
	public $ordersid='';
	public $errors=[];
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Contacts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Contacts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	/**
	 *  通讯录查找条件模块
	 *
	 *
	 */
	public  function search($params=[],$uid='',$isobj=true,$pageload = true)
    {
		$query = Contacts::find();
		
		$orderby = isset($params["orderby"])?$params["orderby"]:'';
		$query->alias("contacts")->select(['contacts.contactsid','contacts.userid',/*'contacts.mobile','contacts.name',*/'contacts.modify_at','contacts.validflag','contacts.status','contacts.modify_by','contacts.create_by','contacts.create_at']);
		if(!$isobj){
			$query->asArray();
		}
		if($pageload){
			$page = isset($params["page"])?$params["page"]:1;
			$limit = isset($params["limit"])?$params["limit"]:10;
			$query->offset(($page-1)*$limit);
			$query->limit($limit);
		}
		if($orderby){
			$this->orderby($orderby);
		}
		$query->where(['contacts.validflag' => '1','contacts.status' => '1']);
		$query->joinWith( 
		[
			'userinfo', 
		]);
		if($uid){
			$query->andFilterWhere(['contacts.create_by' => $uid]);
		}
        return $query;
    }
	/**
	 *  通讯录列表查找
	 *
	 *
	 */
	public  function searchList($params=[],$uid='',$isobj=true)
    {
		
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$orderby = isset($params["orderby"])?$params["orderby"]:'';
		$this->ordersid = isset($params["ordersid"])?$params["ordersid"]:'';
		$query = $this->search($params,$uid,$isobj,false);
		if($this->ordersid){
			$query->joinWith(
				['ordersOperator'=>function($query){
						$query->onCondition(["ordersOperator.ordersid"=>$this->ordersid,"ordersOperator.validflag"=>"1"]);
				}]
			);
		}
		// $select = ["ordersOperator.operatorid",'contacts.contactsid','contacts.userid','contacts.mobile','contacts.modify_at','contacts.validflag','contacts.name','contacts.status'];
		// $query->join('LEFT OUTER JOIN','zcb_product_orders_operator as ordersOperator',"ordersOperator.operatorid= contacts.userid and ordersOperator.validflag = 1 and ordersOperator.ordersid = '{$this->ordersid}'");
		// $query->select($select);
		// var_dump($query->all());
		// exit;
		
        $dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'modify_at' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pageSize' => $limit,
                'page' => $page-1,
			]
        ]);

        return $dataProvider;
		
    }
	/**
	*  查找用户
	*
	*/
	public function searchUser($mobile,$uid=''){
		if(!$mobile)return PARAMSCHECK;
		$uid = $uid?:Yii::$app->user->getId();
		return User::find()->select(['user.mobile','user.id','user.username','user.realname','contacts.contactsid'])->alias("user")
		->join('LEFT OUTER JOIN','zcb_contacts as contacts','contacts.userid = user.id and contacts.validflag = 1 and contacts.create_by = '.$uid)
		->where(["user.mobile"=>$mobile])->andWhere(["!=","user.id",$uid])->asArray()->one();
		
	}
	/**
	*  添加用户至通讯录
	*
	*/
	public function applyUser($userid,$uid=''){
		if(!$userid)return PARAMSCHECK;
		$User = \common\models\User::findUserData($userid,["id","username","realname",'mobile']);
		if(!$User)return PARAMSCHECK;
		
		$data['userid'] = $User['id'];
		$data['mobile'] = $User['mobile'];
		$data['name'] = $User['username'];
		$data['status'] = '1';
		$Contacts = new Contacts;
		$status = $Contacts->apply($data, $uid );
		$this->contactsid=$Contacts->contactsid;
		$this->errors=$Contacts->errors;
		
		return $status;
	}
	
	/**
	*  删除用户从通讯录
	*
	*/
	public function recyUser($contactsid, $uid = ''){
		$Contacts = new Contacts;
		$status = $Contacts->recy($contactsid, $uid );
		$this->errors = $Contacts->errors;
		return $status;
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
}
