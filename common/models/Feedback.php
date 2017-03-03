<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_apply".
 *
 * @property integer $id
 * @property integer $opinion
 * @property integer $phone
 * @property integer $uid
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opinion','phone'], 'required'],
            [[ 'uid'], 'integer'],
            [['picture'],'string','max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'opinion' => '用户反馈意见',
            'phone' => '用户联系方式',
            'picture'=>'图片上传',
            'uid' => '用户',
        ];
    }
}
