<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $mobile
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'created_at', 'updated_at', 'mobile'], 'required'],
            [['status', 'created_at', 'updated_at','pid','isstop'], 'integer'],
            [['username', 'password_hash', 'password_reset_token','cardno'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 11],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'mobile' => '唯一手机号',
            'cardno'=>'公司经办人的身份证',
            'pid'=>'公司认证人的ID',
            'isstop'=>'是否停用',
        ];
    }
	
	
	 /**
	 * 获取所有评价
     * @return \yii\db\ActiveQuery
     */
    public function getComments($isArray = false,$orderBy='comments.productid asc ,comments.action_at asc')
    {
		if($isArray){
			return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')
			->select(["commentid","ordersid","type","touid","tocommentid","truth_score","assort_score","response_score","files","memo","(case  when comments.type= 2 and comments.tocommentid !=0  then comments.tocommentid else comments.commentid end) as owner"])
			->onCondition(['comments.validflag'=>1])
			->groupBy("(case  when comments.type= 2 and comments.tocommentid !=0  then comments.tocommentid else comments.commentid end) asc ,".$orderBy)
			->orderBy($orderBy)
			->asArray($isArray)->all();
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')->onCondition(['comments.validflag'=>1])->orderBy($orderBy);
		}	
	}
	
    /**
	 * 获取所有普通评价
     * @return \yii\db\ActiveQuery
     */
    public function getComments1($isArray = false,$orderBy='comments.action_at asc',$limit=100,$page=1)
    {//->andWhere(['comments.type'=>'1'])
		if($isArray){
			$return = $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')
			->onCondition(['comments.validflag'=>1])
			->select(['comments.*','count(comments.touid)-1 as zuiping'])
			->joinWith(['userinfo'])
			->orderBy($orderBy)
			->limit($limit)
			->offset(($page-1)*$limit)
			->asArray($isArray)
			->groupBy("comments.commentid")
			->all();
			
			if(count($return)==1&&$return[0]['zuiping']=='-1'){
				return [];
			}else{
				return $return;
			}
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'1'])->orderBy($orderBy);
		}	
	}
	
	/**
	 * 获取所有普通评价
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsScore()
    {
		return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')
		->where(['comments.type'=>1,'comments.validflag'=>1])
		->select(['truth_score'=>'AVG(comments.truth_score)','assort_score'=>'AVG(comments.assort_score) ','response_score'=>'AVG(comments.response_score)']) 
		->asArray()
		->one();
       
	}
	

	
	 /**
	 * 获取所有追加评价
     * @return \yii\db\ActiveQuery
     */
    public function getComments2($isArray = false,$orderBy='comments.action_at asc',$limit=100,$page=1)
    {
		if($isArray){
			return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')
			->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'2'])
			->orderBy($orderBy)
			->limit($limit)
			->offset(($page-1)*$limit)
			->asArray($isArray)
			->all();
        }else{
			return $this->hasMany(ProductOrdersComment::className(), ['touid' => 'id'])->alias('comments')->onCondition(['comments.validflag'=>1])->andWhere(['comments.type'=>'2'])->orderBy($orderBy);
		}	
	}
	
	public function getAttr($id,$field=''){
		$data = $this->findOne($id)	;
		
		return $field?$data[$field]:$data->attributes;
		
	}
	
	public static function getCertifications($userid,$fillter = true,$fields=['id','username','realname','mobile','picture']){
		$User = \common\models\User::findUserData($userid,$fields,false);
		if(!$User){
			return PARAMSCHECK;
		}
		$certification = \frontend\services\Func::getCertifications($User['id']);
		if(!$certification){
			// return PARAMSCHECK;
		}
		if($fillter&&$certification){
			$certification =	\frontend\services\Func::fillterCertification($certification);
		}
		$User['certification'] = $certification;
		$User['pictureimg'] = (new \yii\db\Query())
									->select('id,file')
									->from('zcb_files')
									->where(["id"=>$User['picture']])
									->limit(1)
									->one();
		if($User['pictureimg']){
			$User['pictureurl']	=\yii\helpers\Url::toRoute("/",true).$User['pictureimg']['file'];		
		}else{
			$User['pictureurl']= \yii\helpers\Url::toRoute("/",true).'/images/defaulthead.png';
		}
		return $User;
	}
	

	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadimg()
    { 
        return $this->hasOne(Files::className(), ['id' => 'picture'])->alias("headimg")->select(['headimg.file','headimg.id']);
    }
	
	/**
	*  被评人接单评价列表
	*
	*/
	public function commentList($userid,$isobj = true,$limit=10,$page=1){
		$query = self::find();
		$query->where(['id'=>$userid]);
		$isArray = $isobj?false:true;
		$User = $query->one();
		if(!$User)return PARAMSCHECK;
		
		$commentsScore = $User->commentsScore;
		
		$productOrdersOperators ["Comments1"]= $User->getComments1($isArray,'comments.action_at asc',$limit,$page);
		$productOrdersOperators ["commentsScore"]= $productOrdersOperators ["Comments1"]?array_sum($commentsScore)/count($commentsScore):5;
		$productOrdersOperators ["commentsScore"]= sprintf("%.1f", $productOrdersOperators ["commentsScore"]);
		$productOrdersOperators ["commentsScoredetail"]= $commentsScore;
		
		return $productOrdersOperators;
		 
	}
	
	/**
	*  接单评价列表
	*
	*/
	public function commentDetail($commentid){
		$comment = ProductOrdersComment::find()->where(["commentid"=>$commentid])->one();
		if(!$comment)return PARAMSCHECK;
		return $comment->detail;
		 
	}
	
	/**
	*  区分主用户或者代理人
	*
	*/
	public function userid($uid){
		$userid = User::findOne(['id'=>$uid]);
		if($userid){
			if(!$userid->pid){
				return $userid->id;
			}else{
				return $userid->pid;
			}
		}else{
			return false;
		}
		
	}
	
	
	
}
