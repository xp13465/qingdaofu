<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_province".
 *
 * @property integer $id
 * @property integer $provinceID
 * @property string $province
 */
class Province extends \yii\db\ActiveRecord
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
        return $dbArr[count($dbArr)-1].'.zcb_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provinceID', 'province'], 'required'],
            [['provinceID'], 'integer'],
            [['province'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provinceID' => 'Province ID',
            'province' => 'Province',
        ];
    }
}
