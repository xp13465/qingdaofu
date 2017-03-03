<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_files".
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property integer $datetime
 * @property string $ip
 * @property integer $type
 * @property string $addr
 */
class QdfFiles extends \yii\db\ActiveRecord
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
		$dbArr = explode("=",\Yii::$app->qdfdb->dsn);
        return $dbArr[count($dbArr)-1].'.zcb_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime', 'type'], 'integer'],
			[['uuid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 50],
            [['file', 'addr'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'file' => 'File',
            'datetime' => 'Datetime',
            'ip' => 'Ip',
            'type' => 'Type',
            'addr' => 'Addr',
        ];
    }
	
	public static function getFiles($ids=[],$fields=false){
		$query = self::find()->where(['id'=>$ids])->asArray();
		if($fields)$query->select($fields);
		$data = $query->all();
		return $data;
	}

    public function afterSave($insert,$changedAttributes)  
	{ 	
		$backFile = "./".$this->addr;
		$wwwFile = "../../frontend/web".$this->addr;
		if(!file_exists($wwwFile)){
			$savePath = dirname($wwwFile);
			if (!file_exists($savePath)) {
				mkdir($savePath,0777,true);
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
