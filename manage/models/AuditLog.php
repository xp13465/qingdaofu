<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_audit_log".
 *
 * @property integer $id
 * @property integer $relatype
 * @property integer $relaid
 * @property integer $afterstatus
 * @property integer $beforestatus
 * @property integer $action_by
 * @property integer $action_at
 * @property string $memo
 * @property string $params
 */
class AuditLog extends \yii\db\ActiveRecord
{
	public static $soluActionLabels = [
		// '10'=>'提交',
		'11'=>'提交面谈',
		'12'=>'面谈失败',
		'20'=>'面谈通过',
		'30'=>'合同确认',
		'40'=>'订金支付',
		'50'=>'中止',
		'60'=>'回款',
		'70'=>'归档',
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
		return '`manage`.{{%audit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relatype', 'relaid', 'afterstatus', 'beforestatus', 'action_by', 'action_at'], 'integer'],
            [['action_at'], 'required'],
            [['memo', 'params'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relatype' => '1 销售进件  2请假单 3外出单 4加班单',
            'relaid' => '记录id',
            'afterstatus' => '新状态',
            'beforestatus' => '源状态',
            'action_by' => '操作人',
            'action_at' => '操作时间',
            'memo' => '审批备注',
            'params' => '审批备注',
        ];
    }

    /**
     * @inheritdoc
     * @return AuditLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdministrationQuery(get_called_class());
    }
	
	//审核流程日志保存
	public function create($data=[]){
		if($data){
			$this->setAttributes($data);
			if(!$this->validate())return MODELDATACHECK;
			if($this->save()){
				return OK ;
			}else{
				return $this->errors;
			}
		}
		
	}
	
	public function getAdmin(){
		return $this->hasMany(\manage\models\Admin::className(),['id'=>'action_by'])->alias('admin')->select('admin.id,admin.personnelid')->joinWith(['personnels']);
	}
}
