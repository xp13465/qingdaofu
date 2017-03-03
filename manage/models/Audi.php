<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_audi".
 *
 * @property integer $id
 * @property string $name
 * @property integer $pid
 */
class Audi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public static function getDb()  
    {  
        return \Yii::$app->qdfdb;  
    } 
    public static function tableName()
    {
        return 'zcb_audi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '车系',
            'pid' => '品牌ID',
        ];
    }
}
