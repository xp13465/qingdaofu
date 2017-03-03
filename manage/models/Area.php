<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_area".
 *
 * @property integer $id
 * @property integer $areaID
 * @property string $area
 * @property integer $fatherID
 */
class Area extends \yii\db\ActiveRecord
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
		$dbArr = explode("=",\Yii::$app->qdfdb->dsn);
        return $dbArr[count($dbArr)-1].'.zcb_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['areaID', 'area', 'fatherID'], 'required'],
            [['areaID', 'fatherID'], 'integer'],
            [['area'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'areaID' => 'Area ID',
            'area' => 'Area',
            'fatherID' => 'Father ID',
        ];
    }
}
