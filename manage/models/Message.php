<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $uid
 * @property integer $belonguid
 * @property string $uri
 * @property integer $type
 * @property string $params
 * @property integer $isRead
 * @property integer $create_time
 * @property string $relatype
 * @property integer $relaid
 * @property string $relatitle
 */
class Message extends \yii\db\ActiveRecord
{
	public static function getDb()  
    {  
        return \Yii::$app->qdfdb;  
    } 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_time'], 'default', 'value' => time()],
			[['uid'], 'default', 'value' => Yii::$app->user->getId() ],
			[['isRead'], 'default', 'value' => '0' ],
			[['relatype'], 'default', 'value' => '1' ],
			[['uid'], 'default', 'value' => '0' ],
			[['validflag'], 'default', 'value' => '1' ],
			
            [['title', 'content', 'uid', 'belonguid', 'isRead', 'create_time'], 'required'],
            [['uid', 'belonguid', 'type', 'isRead', 'create_time', 'relaid'], 'integer'],
            [['relatype'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 2000],
            [['uri'], 'string', 'max' => 300],
            [['params'], 'string', 'max' => 200],
            [['relatitle'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'title' => '标题',
            'content' => '内容',
            'uid' => '操作人ID',
            'belonguid' => '属于用户IDS',
            'uri' => '查看链接',
            'type' => 'url类型',
            'params' => '序列化参数',
            'isRead' => '0为未读，1为已读',
            'create_time' => '操作时间',
            'relatype' => '消息类型 1系统消息  10保全消息  20保函消息  30产调消息  40 发布消息  50接单消息',
            'relaid' => '根据relatype 关联对应表ID ',
            'relatitle' => '消息标题',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getBelonguser()
    {
        return $this->hasOne(User::className(), ['id' => 'belonguid'])->alias("belonguser")->select(['belonguser.id',"belonguser.username","belonguser.realname","belonguser.mobile","belonguser.pid","belonguser.picture"]);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getBelonguserOpenid()
    {
		return $this->hasOne(\common\models\Openid::className(), ['uid'=>'belonguid'])->alias("belonguseropenid");
	} 
	
    /**
     * @inheritdoc
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
	
	
	/**
	*	消息生成
	*	
	*/
	public function create($relatitle = "",$title = "",$content = "",$toUid,$relaid=0,$relatype=1,$uid=0){
		$this->relatype = (string)$relatype;
		$this->uid = $uid;
		$this->belonguid = $toUid;
		$this->relaid = $relaid;
		$this->relatitle = $relatitle;
		$this->title=$title;
		$this->content=$content;
		if(!$this->validate())return MODELDATACHECK;
		if($this->save()){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
}
