<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_disposing_process".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $category
 * @property integer $status
 * @property string $content
 * @property integer $create_time
 */
class DisposingProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_disposing_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category', 'status','create_time'], 'required'],
            [['product_id', 'category', 'status', 'create_time','audit','case'], 'integer'],
            [['content'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'product_id' => '对应产品ID',
            'category' => '产品类型',
            'status' => '状态',
            'content' => '进度内容',
            'create_time' => '创建时间',
            'audit'=>'审核号',
            'case'=>'案号',
        ];
    }
}
