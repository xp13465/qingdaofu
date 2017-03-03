<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_tag_data".
 *
 * @property string $tag_id
 * @property string $content_id
 * @property integer $type
 * @property string $status
 */
class TagData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_tag_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'content_id'], 'required'],
            [['tag_id', 'content_id', 'type'], 'integer'],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'content_id' => 'Content ID',
            'type' => '栏目类型',
            'status' => '是否显示',
        ];
    }
}
