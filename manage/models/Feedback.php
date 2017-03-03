<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property string $opinion
 * @property string $phone
 * @property string $picture
 * @property integer $uid
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opinion', 'phone'], 'required'],
            [['uid'], 'integer'],
			[['createtime'], 'safe'],
            [['opinion', 'picture'], 'string', 'max' => 1000],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'opinion' => '意见',
            'phone' => '联系方式',
            'picture' => '截图',
            'uid' => '用户',
            'createtime' => '创建日期',
        ];
    }
	
	public function getUser(){
		return $this->hasOne(User::className(), ['id'=>'uid']);
	}
}
