<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_orders_comment}}".
 *
 * @property integer $commentid
 * @property integer $productid
 * @property integer $ordersid
 * @property string $type
 * @property integer $touid
 * @property integer $tocommentid
 * @property integer $truth_score
 * @property integer $assort_score
 * @property integer $response_score
 * @property string $files
 * @property string $memo
 * @property string $validflag
 * @property integer $action_by
 * @property integer $action_at
 *
 * @property ProductOrders $orders
 */
class ProductOrdersComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_orders_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['type'], 'default', 'value' => '1'],
			[['action_by'], 'default', 'value' => Yii::$app->user->getId()],
            [['action_at'], 'default', 'value' =>time()],
            [['productid', 'ordersid', 'touid', 'tocommentid', 'truth_score', 'assort_score', 'response_score', 'action_by', 'action_at'], 'integer'],
            [['type', 'validflag'], 'string'],
            [['productid','ordersid','memo'], 'required'],
			[['truth_score','assort_score','response_score'], 'required', 'when' => function ($model) {
				return $model->type==1;
			},'message'=>"请对{attribute}打分！"],
            [['files'], 'string', 'max' => 255],
            [['memo'], 'string', 'max' => 2000,'min'=>5],
            [['ordersid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrders::className(), 'targetAttribute' => ['ordersid' => 'ordersid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commentid' => 'Commentid',
            'productid' => '被评价产品ID',
            'ordersid' => '被评价接单ID',
            'type' => '评论类型（1普通评价，2追加评价）',
            'touid' => '被评价人',
            'tocommentid' => '被评论评价（追加评价有此值）',
            'truth_score' => '真实性',
            'assort_score' => '配合度',
            'response_score' => '响应度',
            'files' => '评论图片',
            'memo' => '评价内容',
            'validflag' => '回收状态',
            'action_by' => '评价人',
            'action_at' => '评价时间',
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
    public function getUserinfo()
    {
        return $this->hasOne(User::className(), ['id' => 'action_by'])->alias("userinfo")
		->joinWith('headimg')
		->select(['userinfo.id','userinfo.username','userinfo.realname','userinfo.mobile','userinfo.picture']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasMany(ProductOrdersComment::className(), ['ordersid' => 'ordersid'])->alias("commentdetail")->asArray();
    }

	
	/**
	*	添加评论
	*	
	*/
	public function add($params=[] , $action_by = ''){
		$action_by = $action_by?:Yii::$app->user->getId();
		$Orders = ProductOrders::find()->where(['ordersid'=>$params['ordersid'],'validflag'=>1])->one(); 
		if(!$Orders)return PARAMSCHECK;
		$statusLabel = $Orders->checkStatus();
		if($statusLabel!='ORDERSCLOSED'){
			return $statusLabel;
		}
		if(!$Orders->accessOrders('ADDCOMMENT',$action_by)){
			return USERAUTH;
		}
		
		$this->setAttributes($params);
		$this->action_by = $action_by;
		if($this->type==1){
			$num = $Orders->getProductOrdersCommentsNum(['type'=>'1','action_by'=>$this->action_by]);
			if($num>0){
				$this->addError("action_by","您已评价过");
				return MODELDATACHECK;
			}
		}
		$this->ordersid = $Orders->ordersid;
		$this->productid = $Orders->productid;
		$this->memo = htmlspecialchars($params['memo']);
		$productUid = $Orders->product->create_by;//发布方
		$orderUid = $Orders->create_by;//接单方
		if($action_by == $productUid){
			$this->touid = $orderUid;
		}else if($action_by == $orderUid){
			$this->touid = $productUid;
		}
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){ 
			$ProductOrdersLog = new ProductOrdersLog;
			$beforeStatus = $Orders->status;
			$afterStatus = $Orders->status;
			$ProductOrdersLog->create($this->ordersid,$this->commentid,$this->memo,$beforeStatus,$afterStatus,($this->type==2?31:30),2,"",$this->files);
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
}
