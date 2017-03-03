<?php

namespace backend\modules\settings\models;

use Yii;

/**
 * This is the model class for table "zcb_workflow".
 *
 * @property integer $id
 * @property integer $steps
 * @property string $workname
 * @property string $description
 * @property string $settings
 * @property integer $flag
 */
class Workflow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_workflow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['steps', 'flag'], 'integer'],
            [['workname', 'description'], 'required'],
            [['settings'], 'string'],
            [['workname'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'steps' => '审核级别',
            'workname' => '工作流名称',
            'description' => '工作流描述',
            'settings' => '工作流设置',
            'flag' => 'Flag',
        ];
    }
}
