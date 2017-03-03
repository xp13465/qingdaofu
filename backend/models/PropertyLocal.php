<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_property_local".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $file
 */
class PropertyLocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_property_local';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid'], 'integer'],
            [['file'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'file' => 'File',
        ];
    }
}
