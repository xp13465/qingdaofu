<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attendance_checkin_view}}".
 *
 * @property integer $personnel_id
 * @property string $employeeid
 * @property string $username
 * @property string $signdate
 * @property string $dayofweek
 * @property string $timediff
 * @property string $daytype
 * @property string $uplate
 * @property string $downlate
 * @property integer $upid
 * @property string $upsigntype
 * @property integer $upsigntime
 * @property integer $downid
 * @property string $downsigntype
 * @property integer $downsigntime
 */
class AttendanceCheckinView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attendance_checkin_view}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personnel_id', 'upid', 'upsigntime', 'downid', 'downsigntime'], 'integer'],
            [['signdate'], 'safe'],
            [['timediff', 'uplate', 'downlate'], 'number'],
            [['employeeid'], 'string', 'max' => 20],
            [['username'], 'string', 'max' => 50],
            [['dayofweek'], 'string', 'max' => 3],
            [['daytype'], 'string', 'max' => 4],
            [['upsigntype', 'downsigntype'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personnel_id' => '员工ID',
            'employeeid' => '工号',
            'username' => '员工姓名',
            'signdate' => '签到日期',
            'dayofweek' => 'Dayofweek',
            'timediff' => 'Timediff',
            'daytype' => 'Daytype',
            'uplate' => 'Uplate',
            'downlate' => 'Downlate',
            'upid' => 'Upid',
            'upsigntype' => '签到类型',
            'upsigntime' => '签到时间',
            'downid' => 'Downid',
            'downsigntype' => '签到类型',
            'downsigntime' => '签到时间',
        ];
    }
}
