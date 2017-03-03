<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_classic_case".
 *
 * @property integer $id
 * @property string $name
 * @property string $occupation
 * @property string $picture
 * @property string $casetext
 * @property integer $create_time
 * @property integer $update_time
 */
class ClassicCase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_classic_case';
    }

    public static $occupation = [
        ''=>'请选择',
        1=>'律师',
        2=>'律所',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'occupation', 'picture', 'casetext', 'create_time', 'update_time'], 'required'],
            [['create_time', 'update_time'], 'integer'],
            [['casetext'], 'string'],
            [['name', 'occupation', 'picture'], 'string', 'max' => 255],
            [['abstract'],'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '个人或律师姓名',
            'occupation' => '职业',
            'picture' => '图片',
            'casetext' => '案例',
            'create_time' => '发布时间',
            'update_time' => '更新时间',
            'abstract'=>'摘要',
        ];
    }
}
