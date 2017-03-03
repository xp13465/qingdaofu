<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attendance_statistical_view}}".
 *
 * @property integer $year
 * @property integer $month
 * @property integer $personnel_id
 * @property string $username
 * @property string $sum_overtime
 * @property string $sum_overtime_valid
 */
class AttendanceStatisticalView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attendance_statistical_view}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'month', 'personnel_id'], 'integer'],
            [['sum_overtime', 'sum_overtime_valid'], 'number'],
            [['username'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'year' => '考勤年',
            'month' => '考勤月',
            'personnel_id' => '员工ID',
            'username' => '员工姓名',
            'sum_overtime' => 'Sum Overtime',
            'sum_overtime_valid' => 'Sum Overtime Valid',
        ];
    }
}
