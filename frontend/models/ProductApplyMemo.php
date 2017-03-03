<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_apply_memo}}".
 *
 * @property integer $memoid
 * @property integer $applyid
 * @property string $memo
 * @property string $files
 * @property string $status
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property integer $modify_by
 *
 * @property ProductApply $apply
 */
class ProductApplyMemo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_apply_memo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applyid', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['status', 'validflag'], 'string'],
            [['memo'], 'string', 'max' => 2000],
            [['files'], 'string', 'max' => 100],
            [['applyid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductApply::className(), 'targetAttribute' => ['applyid' => 'applyid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'memoid' => '申请ID',
            'applyid' => '申请ID',
            'memo' => '备注',
            'files' => '附件',
            'status' => '状态（0普通备注，10成功备注，20失败备注）',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApply()
    {
        return $this->hasOne(ProductApply::className(), ['applyid' => 'applyid']);
    }
}
