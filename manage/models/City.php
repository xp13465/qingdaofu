<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_city".
 *
 * @property integer $id
 * @property integer $cityID
 * @property string $city
 * @property integer $fatherID
 */
class City extends \yii\db\ActiveRecord
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
        return $dbArr[count($dbArr)-1].'.zcb_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cityID', 'city', 'fatherID'], 'required'],
            [['cityID', 'fatherID'], 'integer'],
            [['city'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cityID' => 'City ID',
            'city' => 'City',
            'fatherID' => 'Father ID',
        ];
    }
}
