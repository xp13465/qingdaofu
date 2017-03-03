<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_delay_apply".
 *
 * @property integer $id
 * @property integer $category
 * @property integer $product_id
 * @property integer $uid
 * @property integer $create_time
 * @property integer $delay_days
 * @property string $dalay_reason
 * @property integer $is_agree
 */
class DelayApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_delay_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'product_id', 'uid', 'create_time', 'delay_days', 'dalay_reason'], 'required'],
            [['category', 'product_id', 'uid', 'create_time', 'delay_days', 'is_agree'], 'integer'],
            [['dalay_reason'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'category' => '产品类型',
            'product_id' => '产品iD',
            'uid' => '申请人ID',
            'create_time' => '创建时间',
            'delay_days' => '延迟时间',
            'dalay_reason' => '延迟原因',
            'is_agree' => '是否同意，0为申请延时中，1为同意，2为拒绝',
        ];
    }
}
