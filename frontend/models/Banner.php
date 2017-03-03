<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $file
 * @property string $title
 * @property string $target
 * @property string $url
 * @property integer $fileid
 * @property string $type
 * @property integer $sort
 * @property integer $starttime
 * @property integer $endtime
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['target', 'type', 'validflag'], 'string'],
            [['fileid', 'sort', 'starttime', 'endtime', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['file', 'title', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => '图片地址',
            'title' => '标题提示',
            'target' => '是否新窗口打开',
            'url' => '新地址',
            'fileid' => 'Fileid',
            'type' => '类型',
            'sort' => '排序',
            'starttime' => '有效期生效时间',
            'endtime' => '有效期过期时间',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }
	
	public function getBanners($type="WEB",$isObj = false){
		$query = self::find();
		$query->select(["id",'file'=>"concat('".Yii::$app->params["www"]."',`file`)","title","target","url","type","sort","starttime","endtime"]);
		$query->where(["validflag"=>"1","type"=>$type]);
		if(!$isObj)$query->asArray();
		$time = time();
		$query->andWhere(["or","starttime = 0 ","starttime <= {$time} "]);
		$query->andWhere(["or","endtime = 0 ","endtime >= {$time} "]);
		$query->orderBy("`sort` desc");
		
		return $query->all();
	}
}
