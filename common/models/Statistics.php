<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_statistics".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $category
 * @property integer $uid
 * @property integer $status
 * @property integer $readCount
 */
class Statistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_statistics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'category', 'uid', 'status'], 'required'],
            [['cid', 'category', 'uid', 'status', 'readCount'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'category' => 'Category',
            'uid' => 'Uid',
            'status' => 'Status',
            'readCount' => 'Read Count',
        ];
    }
}
