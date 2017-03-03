<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\modules\settings\models\Workflow;


/**
 * This is the model class for table "zcb_form".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $code
 * @property string $name
 * @property string $department
 * @property string $unit
 * @property string $bank
 * @property string $bank_account
 * @property string $item
 * @property string $item_money
 * @property string $item_type
 * @property string $pay_type
 * @property string $cny
 * @property integer $pay_time
 * @property string $pay_name
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Form extends \yii\db\ActiveRecord
{

    //const STATUS_OFF = 0;
    //const STATUS_ON = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_form';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(FormCategory::className(), ['id' => 'category_id']);
    }


    static public function formCategory()
    {
        return ArrayHelper::map(FormCategory::find()->all(), 'id', 'name');
    }

    static public function getStatus()
    {
        //print_r(ArrayHelper::map(Workflow::find()->all(), 'steps', 'workname'));
        return ArrayHelper::map(Workflow::find()->all(), 'steps', 'workname');
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_OFF => Yii::t('zcb', 'OFF'),
            self::STATUS_ON => Yii::t('zcb', 'ON'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'pay_time', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code', 'name', 'department', 'unit', 'bank', 'bank_account', 'item', 'item_type', 'pay_type', 'pay_name'], 'required'],
            [['item_money'], 'number'],
            [['code', 'name', 'department', 'unit', 'bank', 'bank_account', 'item', 'item_type', 'pay_type', 'cny', 'pay_name'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 255],
        ];
    }



    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if(!$this->data || (!is_object($this->data) && !is_array($this->data))){
                $this->data = new \stdClass();
            }

            $this->data = json_encode($this->data);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => '所属类目',
            'code' => '表单编号',
            'name' => '申请人',
            'department' => '部门',
            'unit' => '收款单位',
            'bank' => '开户银行',
            'bank_account' => '银行账号',
            'item' => '付款项目',
            'item_money' => '项目金额',
            'item_type' => '项目类型',
            'pay_type' => '付款方式',
            'cny' => '大写人民币',
            'pay_time' => '付款日期',
            'pay_name' => '付款人',
            'remark' => '备注',
            'status' => '状态',
            'created_at' => '申请日期',
            'updated_at' => '更新日期',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
