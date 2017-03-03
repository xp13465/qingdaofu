<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attendance_checkin_reports}}".
 *
 * @property integer $id
 * @property integer $personnel_id
 * @property string $employeeid
 * @property string $username
 * @property string $signdate
 * @property string $dayofweek
 * @property string $signtime1
 * @property string $signtime2
 * @property string $timediff
 * @property string $signstatus
 * @property string $signstatus_valid
 * @property string $memo
 * @property string $latetime
 * @property integer $latetime_valid
 * @property string $overtime
 * @property string $overtime_valid
 * @property integer $status
 * @property integer $updatetime
 * @property integer $updateuser
 * @property string $modify_at
 * @property integer $dayofweekkey
 * @property integer $year
 * @property integer $month
 * @property string $gooutids
 * @property string $goouthour
 * @property string $gooutmemo
 * @property string $leaveids
 * @property string $leavehour
 * @property string $leavememo
 * @property string $overtimeids
 * @property string $overtimehour
 * @property string $overtimememo
 */
class AttendanceCheckinReports extends \yii\db\ActiveRecord
{
	public static $status = [ 
       "0"=>"脚本执行", 
       "1"=>"人为干预", 
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attendance_checkin_reports}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personnel_id', 'latetime_valid', 'status', 'updatetime', 'updateuser', 'dayofweekkey', 'year', 'month'], 'integer'],
            [['signdate', 'signtime1', 'signtime2', 'modify_at'], 'safe'],
            [['timediff', 'latetime', 'overtime', 'overtime_valid', 'goouthour', 'leavehour', 'overtimehour'], 'number'],
            [['employeeid'], 'string', 'max' => 20],
            [['username'], 'string', 'max' => 50],
            [['dayofweek', 'signstatus', 'signstatus_valid'], 'string', 'max' => 10],
            [['memo', 'gooutids', 'leaveids', 'overtimeids'], 'string', 'max' => 255],
            [[ 'gooutmemo', 'leavememo','overtimememo'], 'string', 'max' => 1255],
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
            'username' => '员工姓名',
            'signdate' => '签到日期',
            'dayofweek' => '星期几',
            'signtime1' => '上班签到',
            'signtime2' => '下班签到',
            'timediff' => '工作时长',
            'signstatus' => '签到状态',
            'signstatus_valid' => '纠正状态',
            'memo' => '备注',
            'latetime' => '迟到分钟',
            'latetime_valid' => '迟到补齐生效',
            'overtime' => '加班时长（计算）',
            'overtime_valid' => '加班时长（生效）',
            'status' => '记录状态',// 0脚本执行 1人为干预',
            'updatetime' => '纠正时间',
            'updateuser' => '纠正人',
            'modify_at' => 'Modify At',
            'dayofweekkey' => '星期几',
            'year' => '考勤年',
            'month' => '考勤月',
            'gooutids' => '外出单号',
            'goouthour' => '外出时长',
            'gooutmemo' => '外出备注',
            'leaveids' => '请假单号',
            'leavehour' => '请假时长',
            'leavememo' => '请假备注',
            'overtimeids' => '加班单号',
            'overtimehour' => '加班时长',
            'overtimememo' => '加班备注',
        ];
    }

    /**
     * @inheritdoc
     * @return AttendanceCheckinReportsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttendanceCheckinReportsQuery(get_called_class());
    }
}
