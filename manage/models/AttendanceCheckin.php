<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attendance_checkin}}".
 *
 * @property integer $id
 * @property integer $personnel_id
 * @property string $employeeid
 * @property string $number
 * @property string $username
 * @property string $signtype
 * @property integer $signtime
 * @property string $signdate
 * @property string $modify_at
 * @property string $gengzheng
 * @property string $yichang
 */
class AttendanceCheckin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attendance_checkin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personnel_id', 'signtime'], 'integer'],
            [['signdate', 'modify_at'], 'safe'],
            [['employeeid', 'number'], 'string', 'max' => 20],
            [['username'], 'string', 'max' => 50],
            [['signtype', 'gengzheng', 'yichang'], 'string', 'max' => 255],
            [['signtime'], 'unique', 'targetAttribute' => ['username', 'signtype', 'signtime'], 'message' => '员工姓名在相同出勤时间已有相同的出勤状态记录存在（一分钟内多次打卡）'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personnel_id' => '员工ID',
            'employeeid' => '工号',
            'number' => '考勤机号码',
            'username' => '员工姓名',
            'signtype' => '签到类型',
            'signtime' => '签到时间',
            'signdate' => '签到日期',
            'modify_at' => 'Modify At',
            'gengzheng' => '更正',
            'yichang' => '异常',
        ];
    }
}
