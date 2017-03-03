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
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'default', 'value' => "`UUID()`"],
            [['datetime', 'type'], 'integer'],
			[['uuid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 50],
            [['file','file_old', 'addr'], 'string', 'max' => 200],
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
}
