<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_tag".
 *
 * @property integer $id
 * @property string $tag_name
 * @property string $data_count
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_name'], 'required'],
            [['data_count'], 'integer'],
            [['tag_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_name' => 'tag名称',
            'data_count' => '数据总数',
        ];
    }
}
