<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use Yii;
/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
	public $errors =[];
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	public  function search($params=[],$uid='',$isobj=true,$pageload = false)
    {
		$uid = $uid?:Yii::$app->user->getId();
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		if($pageload){
			$this->offset(($page-1)*$limit);
			$this->limit($limit);
		}
		$this->where(["validflag"=>'1',"belonguid"=>$uid]);
		$this->orderBy("create_time desc");
		return $this;
	}
	
	
	/* 分类消息 */
	public function groupList($params=[],$uid=''){
		$uid = $uid?:Yii::$app->user->getId();
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		
		$query=$this->search($params,$uid,true,false);
		$query->alias("groupMessage");
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$subQuery = Message::find()->orderBy("create_time desc")
		->where(["validflag"=>'1',"belonguid"=>$uid])
		->select(["aid"=>"SUBSTRING_INDEX(group_concat(id order by `create_time` desc),',',1)","isRead"=>"count(isRead)-sum(isRead)"])
		->groupBy("relatype,relaid,params")
		->andWhere(["in","relatype",["10","20","30","40","50"]]);
		
		$this->select("message.*,tmpA.isRead");
		$this->from(["tmpA"=>$subQuery])->join("inner join","zcb_message as message","message.id = tmpA.aid");
		$dataProvider = new ActiveDataProvider([
			'query' => $this,
			'sort' => [
				'defaultOrder' => [
					'create_time' => SORT_DESC,            
				]
			],
			'pagination' => [
                'pagesize' => $limit,
                'page' => $page-1,
			]
        ]);


        return $dataProvider;
		
	}
	
	//阅读消息
	public function setRead($messageId="",$updateType="GROUP",$uid=""){
		$uid = $uid?:Yii::$app->user->getId();
		if($messageId){
			$data = Message::find()->where(["validflag"=>"1","id"=>$messageId,"belonguid"=>$uid,"isRead"=>'0'])->one();
			if(!$data)return PARAMSCHECK;
			$relatype =$data->relatype;
			$belonguid =$data->belonguid;
			$relaid =$data->relaid;
		}else{
			$relatype ="1";
			$belonguid =$uid;
			$relaid ="0";
		}
		if($updateType=='GROUP'){
			$num= \app\models\Message::updateAll(["isRead"=>"1"],["validflag"=>"1","isRead"=>"0","relatype"=>$relatype,"relaid"=>$relaid,"belonguid"=>$belonguid]);
		}
		
		
	}
	/* 系统消息 */
	public function systemList($params=[],$uid='',$isCount=false,$isUpdate=false){
		$uid = $uid?:Yii::$app->user->getId();
		$page = isset($params["page"])?$params["page"]:1;
		$limit = isset($params["limit"])?$params["limit"]:10;
		$query=$this->search($params,$uid,true,false);
		$query->alias("systemMessage");
		$query->andWhere(["relatype"=>'1']);
		if($isCount){
			$query->andWhere(["isRead"=>'0']);
			return $query->count();
		}
		if($isUpdate){
			$this->setRead("","GROUP",$uid);
		}
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'create_time' => SORT_DESC,            
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
	 * 消息添加
	 * Messagetypeid 消息排列号
	 * params 编号参数
	 * toUid 属于谁的Uid
	 * relaid 是否是系统发送不是就填关联id
	 * relatype 消息类型
	 */
	public function addMessage($Messagetypeid ='',$params =[],$toUid,$relaid=0,$relatype=1,$uid=0,$WxMsg=true){
		$MessageModel =new  Message;
		$MessageTemplate = \frontend\configs\MessageConfig::$Message;
		if(!isset($MessageTemplate[$Messagetypeid]))return PARAMSCHECK;
		
		$replaceParams['{1}'] = isset($params['code'])?$params['code']:"";
		$replaceParams['{2}'] = isset($params['action'])?$params['action']:"";
		$title = $MessageTemplate[$Messagetypeid]['title'];
		
        $content = isset($MessageTemplate[$Messagetypeid]['content'])?$MessageTemplate[$Messagetypeid]['content']:'';
        $relatitle = isset($MessageTemplate[$Messagetypeid]['relatitle'])?$MessageTemplate[$Messagetypeid]['relatitle']:'';
        $relatitle = \frontend\services\Func::replaceMessage($relatitle,$replaceParams);
        $content = \frontend\services\Func::replaceMessage($content,$replaceParams);
		
		$status = $MessageModel->create($relatitle,$title,$content,$toUid,$relaid,$relatype,$uid);
		if($status==OK && $WxMsg){
			$url ="http://localhost/script/messagepush";
			\frontend\services\Func::asyncExecute($url);
		}
		$this->errors= $MessageModel->errors;
		return $status;
	}
	
	/** 
	 * 消息推送
	 * Messagetypeid 消息排列号
	 * params 编号参数
	 * toUid 属于谁的Uid
	 * relaid 是否是系统发送不是就填关联id
	 * relatype 消息类型
	 */
	public function MessagePush($Wx=true,$Email=false){
		$time = time()-3600;
		$relatype = ["10","20","40","50"];
		$data = $this->where(["validflag"=>"1","wxpush"=>"0","relatype"=>$relatype])->andWhere("create_time >= '{$time}'")->one();
		$scriptNum = 0;
		while($data&& $scriptNum<10 ){
			$scriptNum ++;
			if($data->belonguserOpenid&&$data->belonguserOpenid->openid){
				$openid = $data->belonguserOpenid->openid;
				// $openid = 'oNZOTszvLxTU9zaLKyvfXu--ynqQ';
				$data->wxpush = "-1";
				$num = $data->update();
				switch($data->relatype){
					case "10":
						$ordersid ="";
						$WXurl = "http://wx.zcb2016.com/preservation/audit?push=wx&messageid={$data->id}&id=".$data->relaid;
						break;
					case "20":
						$ordersid ="";
						$WXurl = "http://wx.zcb2016.com/policy/baohan?push=wx&messageid={$data->id}&id=".$data->relaid;
						break;
					case "40":
						$ordersid ="";
						$WXurl = "http://wx.zcb2016.com/wrelease/details?push=wx&messageid={$data->id}&productid=".$data->relaid;
						break;
					case "50":
						$ordersid ="";
						$WXurl = "http://wx.zcb2016.com/productorders/detail?push=wx&messageid={$data->id}&applyid=".$data->relaid;
						break;
					default:
						exit;
						break;
				}
				if($num==1){
					// $openid = $Property->userOpenid->openid;
					
					$WxMsg = new \frontend\services\WxMsg("WXMSG");
					$template = array(
						'touser' => $openid,
						'template_id' => '-WZphstKbZt3XoWu001cQk0zqHzPUgf7zranCGiwPFc',
						"url"=> $WXurl,
						"topcolor"=> "#FF0000",
						'data' => array(
							'first' => ["value" => "报告尊上：".$data->content,"color" => "#0065b3"],
							'OrderSn' => ["value" => str_replace("编号：","",$data->relatitle),"color" => "#0065b3"],
							'OrderStatus' => ["value" => $data->title,"color" => "#0065b3"],
							'remark' => ["value" => "","color" => "#0065b3"],
						)
					); 
					$status = $WxMsg->sendMB($template);
					// var_dump($status);
					if($status&&isset($status["errmsg"])&&$status["errmsg"]=="ok"){
						$data->wxpush = "1";
					}else{
						$data->wxpush = "2";
					}
					$data->update();
				}
			}else{
				$data->wxpush = "3";
				$data->update();
			}
			// var_dump($num);
			// var_dump($data);
			if($scriptNum<10 ){
				$data = $this->where(["validflag"=>"1","wxpush"=>"0","relatype"=>$relatype])->andWhere("create_time >= '{$time}'")->one();
			}else{
				$data = NULL;
			}
		}
	}
	
	/**
	 * 返回数据处理
	 */
	public function filterOne($data){
		if($data['uid']){
			$User = User::findOne($data['uid']);
			$data['username'] = $User?($User->realname?:$User->username):'';
			$data['headimg'] = $User&&$User->headimg?$User->headimg->toArray():[];
		}else{
			$data['username'] ='';
			$data['headimg'] =[];
		}
		$timeLabel="";
		if($time = $data['create_time']){
			$diff=(time()-$time);
			if($diff<=60*10){
				$timeLabel ="刚刚";
			}else if($diff<=60*60){
				$timeLabel =floor($diff/60)."分钟前";
			}else if($diff<=60*60*24){
				$timeLabel =floor($diff/60/60)."小时前";
			}else if($diff<=60*60*24*30){
				$timeLabel =floor($diff/60/60/24)."天前";
			}else{
				$timeLabel =floor($diff/60/60/24/30)."月前";
			}
		}
		$data['timeLabel'] = $timeLabel;
		return $data;
	}
	
	/**
	 * 返回数据处理
	 */
	public function filterAll($list){
		foreach($list as $item=>$data){
			$list[$item]= $this->filterOne($data->toArray());
		}
		return $list;
	}
	
}
