<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%audit_log}}".
 *
 * @property integer $id
 * @property integer $beforestatus
 * @property integer $afterstatus
 * @property integer $relaid
 * @property integer $relatype
 * @property integer $action_by
 * @property integer $action_at
 * @property string $memo
 */
class AuditLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['beforestatus', 'afterstatus', 'relaid', 'relatype', 'action_by', 'action_at'], 'integer'],
            [['action_at'], 'required'],
            [['memo'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'beforestatus' => 'Beforestatus',
            'afterstatus' => 'Afterstatus',
            'relaid' => 'Relaid',
            'relatype' => 'Relatype',
            'action_by' => 'Action By',
            'action_at' => 'Action At',
            'memo' => 'Memo',
        ];
    }
}
