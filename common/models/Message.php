<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_message".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $uid
 * @property integer $belonguid
 * @property string $uri
 * @property integer $isRead
 * @property integer $create_time
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'uid', 'belonguid', 'isRead', 'create_time'], 'required'],
            [['uid', 'belonguid', 'isRead', 'create_time', 'type'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 2000],
            [['params'], 'string', 'max' => 200],
            [['uri'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'title' => '标题',
            'content' => '内容',
            'uid' => '操作人ID',
            'type' => 'url类型',
            'params' => '参数',
            'belonguid' => '属于用户IDS',
            'uri' => '查看链接',
            'isRead' => '0为未读，1为已读',
            'create_time' => '操作时间',
        ];
    }
}
