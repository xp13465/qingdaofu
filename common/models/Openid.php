<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%openid}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $openid
 * @property string $openid_2
 */
class Openid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%openid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['openid', 'openid_2'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'openid' => 'Openid',
            'openid_2' => 'Openid 2',
        ];
    }
}
