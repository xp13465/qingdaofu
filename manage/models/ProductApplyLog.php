<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_apply_log}}".
 *
 * @property integer $logid
 * @property integer $applyid
 * @property string $level
 * @property string $memo
 * @property string $validflag
 * @property integer $afterstatus
 * @property integer $beforestatus
 * @property integer $action_by
 * @property integer $action_at
 *
 * @property ProductApply $apply
 */
class ProductApplyLog extends \yii\db\ActiveRecord
{
	public static function getDb()  
    {  
        return \Yii::$app->qdfdb;  
    } 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_product_apply_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applyid', 'afterstatus', 'beforestatus', 'action_by', 'action_at'], 'integer'],
            [['level', 'validflag'], 'string'],
            [['memo'], 'string', 'max' => 2000],
            [['applyid'], 'exist', 'skipOnError' => true, 'targetClass' => ProductApply::className(), 'targetAttribute' => ['applyid' => 'applyid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logid' => 'Logid',
            'applyid' => '申请ID',
            'level' => '日志级别 1进度日志，2子表日志',
            'memo' => '审批备注',
            'validflag' => '回收状态',
            'afterstatus' => '新状态',
            'beforestatus' => '源状态',
            'action_by' => '操作人',
            'action_at' => '操作时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApply()
    {
        return $this->hasOne(ProductApply::className(), ['applyid' => 'applyid']);
    }
	
	public function create($applyid,$memo='',$afterstatus,$beforestatus,$action_by){
		$this->applyid = $applyid;
		$this->memo = $memo;
		$this->afterstatus = $afterstatus;
		$this->beforestatus = $beforestatus;
		$this->action_at = time();
		$this->action_by = $action_by;
		if($this->save()){
			return OK;
		}else{
			return MODELDATASAVE;
		}
	}
}
