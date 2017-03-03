<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_orders_log}}".
 *
 * @property integer $logid
 * @property integer $ordersid
 * @property string $action
 * @property string $level
 * @property integer $relaid 
 * @property string $memo
 * @property string $validflag
 * @property integer $afterstatus
 * @property integer $beforestatus
 * @property integer $action_by
 * @property integer $action_at
 *
 * @property ProductOrders $orders
 */
class ProductOrdersLog extends \yii\db\ActiveRecord
{
	public static $actionLabels = [
		'10'=>'系统', 
		'20'=>'留言',
		'21'=>'风控尽调',
		'22'=>'风控报告',
		'23'=>'理财配资',
		'24'=>'运作公正',
		'25'=>'运作合同',
		'26'=>'运作产调',
		'27'=>'财务出账',
		'28'=>'财务回款',
		'30'=>'评价', 
		'31'=>'追评', 
		'40'=>'申请结案',
		'41'=>'同意结案',
		'42'=>'否决结案',
		'50'=>'申请终止',
		'51'=>'同意终止',
		'52'=>'否决终止',
	];
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['action_at'], 'default', 'value' => time()],
            [['action_by'], 'default', 'value' => Yii::$app->user->getId() ],
            [['validflag'], 'default', 'value' => '1' ],
            [['ordersid', 'relaid', 'afterstatus', 'beforestatus', 'action_by', 'action_at'], 'integer'],
            [['action'], 'required'],
            // [['action', 'level', 'validflag'], 'string'],
            [['memo'], 'string', 'max' => 2000],
            [['files'], 'string', 'max' => 255],
            [['ordersid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrders::className(), 'targetAttribute' => ['ordersid' => 'ordersid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logid' => 'Logid',
            'ordersid' => '接单ID',
            'action' => '接单动作日志 1调查 2处置 3结案 4进度，5评论',
            'level' => '日志级别 1进度日志，2子表日志',
			'relaid' => '关联ID', 
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
    public function getOrders()
    {
        return $this->hasOne(ProductOrders::className(), ['ordersid' => 'ordersid']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getActionUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'action_by'])
		->alias("actionuser")
		// ->joinWith(["headimg"=>function($query){
			// $query->alias("actionuserheadimg")->select(["actionuserheadimg.file","actionuserheadimg.id"]);
		// }])
		->select(['actionuser.id',"actionuser.username","actionuser.realname","actionuser.mobile","actionuser.pid","actionuser.picture"]);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getFileImg()
    {
        return $this->hasMany(Files::className(), ["id"=>"files"])->alias("fileImg");
    }
	
	
	/**
	*	日志生成
	*	
	*/
	public function create($ordersid,$relaid,$memo,$beforeStatus,$afterStatus,$action='4',$level='1', $action_by = '',$files=''){
		if($action_by)$this->action_by = $action_by;
		$this->ordersid = $ordersid;
		$this->relaid = $relaid;
		$this->memo = $memo;
		$this->action = $action;
		$this->files = $files;
		$this->level = $level;
		$this->beforestatus = $beforeStatus;
		$this->afterstatus =$afterStatus; 
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
	
	
	/**
	 * 过滤等处理
	 *
	 */
	 
	public static function filterAll($Logs,$accessTerminationAUTH,$accessClosedAUTH,$checkStatus,$fabuid,$jiedanid,$operatorsData=[],&$data=[]){
		
		
		$Operators = [];
		foreach($operatorsData as $v)$Operators[$v['operatorid']]=$v;
		
		$actionLabels = self::$actionLabels;
		$userid = Yii::$app->user->getId();
		foreach($Logs as $key => $productOrdersLogs){
			
			$Logs[$key]['actionLabel']=$actionLabels[$productOrdersLogs['action']];
			
			if($productOrdersLogs["files"]){
				$ids=explode(",",$productOrdersLogs["files"]);
				$Logs[$key]["filesImg"]=\app\models\Files::getFiles($ids);
			}else{
				$Logs[$key]["filesImg"]=[];
			}
			$Logs[$key]['trigger']=false;
			$Logs[$key]['triggerLabel']='';
			$Logs[$key]['memoLabel'] = $Logs[$key]['memo'];
			$Logs[$key]['memoTel'] = '';
			if(in_array($productOrdersLogs['action'],[40,50])){
				if($productOrdersLogs['action']==50&&$productOrdersLogs['action_by']!=$userid&&$accessTerminationAUTH==true&&$checkStatus=='ORDERSPROCESS'&&$productOrdersLogs['relatrigger']=='0'){
					$Logs[$key]['trigger']=true;
					$Logs[$key]['triggerLabel']="点击处理";
					$data['Prompt'] = 50;
					$data['relaid'] = $productOrdersLogs['relaid'];
				}else if($productOrdersLogs['action']==40&&$productOrdersLogs['action_by']!=$userid&&$accessClosedAUTH==true&&$checkStatus=='ORDERSPROCESS'&&$productOrdersLogs['relatrigger']=='0'){
					$Logs[$key]['trigger']=true;
					$Logs[$key]['triggerLabel']="点击处理";
					$data['Prompt'] = 40;
					$data['relaid'] = $productOrdersLogs['relaid'];
				}
			}
			$label = "系";
			$class = "system";
			if($productOrdersLogs['action']!=10){
				
				if($productOrdersLogs['action_by']==$userid){
					if($userid ==$fabuid||$userid ==$jiedanid){
						$label = "我";
						$class = "me";
					}else if($productOrdersLogs['action_by']==$jiedanid){
						$label = "接";
						$class = "me";
					}else if($productOrdersLogs['action_by']==$fabuid){
						$label = "发";
						$class = "";
					}else{
						$label = "经";
						$class = "jing";
					}
				}else{
					if(isset($Operators[$productOrdersLogs['action_by']])){
						$operator = \common\models\User::findUserData($Operators[$productOrdersLogs['action_by']]["operatorid"],['mobile','realname']);
						$label = "经";
						$class = "jing";
						$Logs[$key]['memo'].=$Logs[$key]['memo']?"。":"";
						$Logs[$key]['memo'].="经办人：".$operator['realname']." <a href='tel:{$operator['mobile']}'>".$operator['mobile']."</a>";
						$Logs[$key]['memoTel'].="经办人：".$operator['realname']." ".$operator['mobile']."";
					}else if($productOrdersLogs['action_by']==$jiedanid){
						$label = "接";
						$class = "me";
					}else if($productOrdersLogs['action_by']==$fabuid){
						$label = "发";
						$class = "";
					}
				}
			}
			$Logs[$key]['class']=$class;
			$Logs[$key]['label']=$label;
			
		}
		return $Logs;
	}
	
	public function filterAlls($data=[],$fabuid,$jiedanid,$operatorsData){
		$userid = Yii::$app->user->getId();
		$Operators = [];
		foreach($operatorsData as $v)$Operators[$v['operatorid']]=$v;
		foreach($data as $key=>$value){
			$actionLabels = self::$actionLabels;
			$data[$key]['actionLabel']=$actionLabels[$value['action']];
			if($value["files"]){
				$ids=explode(",",$value["files"]);
				$data[$key]["filesImg"]=\app\models\Files::getFiles($ids);
			}else{
				$data[$key]["filesImg"]=[];
			}
			$data[$key]['trigger']=false;
			$data[$key]['triggerLabel']='';
			$data[$key]['memoLabel'] = $Logs[$key]['memo'];
			$data[$key]['memoTel'] = '';
			if(in_array($value['action'],[40,50])){
				if($value['action']==50&&$value['action_by']!=$userid&&$accessTerminationAUTH==true&&$checkStatus=='ORDERSPROCESS'&&$value['relatrigger']=='0'){
					$data[$key]['trigger']=true;
					$data[$key]['triggerLabel']="点击处理";
					//$data['Prompt'] = 50;
					//$data['relaid'] = $productOrdersLogs['relaid'];
				}else if($value['action']==40&&$value['action_by']!=$userid&&$accessClosedAUTH==true&&$checkStatus=='ORDERSPROCESS'&&$value['relatrigger']=='0'){
					$data[$key]['trigger']=true;
					$data[$key]['triggerLabel']="点击处理";
					//$data['Prompt'] = 40;
					//$data['relaid'] = $productOrdersLogs['relaid'];
				}
			}
			$label = "系";
			$class = "system";
			if($value['action']!=10){
				
				if($value['action_by']==$userid){
					if($userid ==$fabuid||$userid ==$jiedanid){
						$label = "我";
						$class = "me";
					}else if($value['action_by']==$jiedanid){
						$label = "接";
						$class = "me";
					}else if($value['action_by']==$fabuid){
						$label = "发";
						$class = "";
					}else{
						$label = "经";
						$class = "jing";
					}
				}else{
					if(isset($Operators[$value['action_by']])){
						$operator = \common\models\User::findUserData($Operators[$value['action_by']]["operatorid"],['mobile','realname']);
						$label = "经";
						$class = "jing";
						$data[$key]['memo'].=$data[$key]['memo']?"。":"";
						$data[$key]['memo'].="经办人：".$operator['realname']." <a href='tel:{$operator['mobile']}'>".$operator['mobile']."</a>";
						$data[$key]['memoTel'].="经办人：".$operator['realname']." ".$operator['mobile']."";
					}else if($value['action_by']==$jiedanid){
						$label = "接";
						$class = "me";
					}else if($value['action_by']==$fabuid){
						$label = "发";
						$class = "";
					}
				}
			}
			$data[$key]['class']=$class;
			$data[$key]['label']=$label;
		}
		return $data;
	}
}
