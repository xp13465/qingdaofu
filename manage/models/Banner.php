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
	public static $type=[
		"WEB"=>"WEB",
		"APP"=>"APP",
		"AppStart"=>"AppStart",
	];
	public static $target=[
		"0"=>"本页",
		"1"=>"新窗口打开",
	];
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
			[['create_at'], 'default', 'value' => time()],
            [['create_by'], 'default', 'value' => Yii::$app->user->getId()],
			[['validflag'], 'default', 'value' => '1' ],
			[['sort'], 'default', 'value' => '0' ],
			[['fileid','type'], 'required'],
			[['fileid', 'sort', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
			[['starttime','endtime'], 'default', 'value' => '0' ],
			[['starttime'], 'date', 'when' => function ($model) {
				return $model->starttime;
			},'format'=>'php:Y-m-d H:i' ],
			
			[['endtime'], 'date', 'when' => function ($model) {
				return $model->endtime;
			},'format'=>'php:Y-m-d H:i' ],
			['sort','compare', 'when' => function ($model) {
				return $model->type == '2'; 
			},'compareValue' => 127, 'operator' => '<=','message'=>'排序不能超过127'],
			
            [['type','target', 'validflag'], 'string'],
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
            'fileid' => '图片',
			'title' => '标题提示',
            'target' => '是否新窗口打开',
            'url' => '超链接地址',
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
	public function beforeSave($insert)  
	{  
		if(parent::beforeSave($insert)){  
			if($this->starttime&&strtotime($this->starttime)){
				$this->starttime = strtotime($this->starttime);
			}
			if($this->endtime&&strtotime($this->endtime)){
				$this->endtime = strtotime($this->endtime);
			}
			if($this->isNewRecord){  
				$this->create_at = time();  
				$this->create_by = Yii::$app->user->id;  
				$this->validflag = '1';  
			}else{  
				$this->modify_at = time();  
				$this->modify_by = Yii::$app->user->id;  
			}  
			return true;  
		}else{  
			return false;  
		}  
	}  

	public function afterSave($insert,$changedAttributes)  
	{ 	
		$backFile = "./".$this->file;
		$wwwFile = "../../frontend/web".$this->file;
		if(!file_exists($wwwFile)){
			$savePath = dirname($wwwFile);
			if (!file_exists($savePath)) {
				mkdir($savePath);
			}
			copy($backFile,$wwwFile);
		}
		if(parent::afterSave($insert,$changedAttributes)){
			return true;  
		}else{  
			return false;  
		}  
	}
}
