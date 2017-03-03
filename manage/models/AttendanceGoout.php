<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attendance_goout}}".
 *
 * @property integer $id
 * @property integer $personnel_id
 * @property string $employeeid
 * @property string $username
 * @property string $department
 * @property string $job
 * @property string $writedate
 * @property string $gooutdate
 * @property integer $gooutstart
 * @property integer $gooutend
 * @property string $gooutend_valid
 * @property string $totalhour
 * @property string $description
 * @property string $overtimefile
 * @property string $supervisormemo
 * @property string $supervisorsignature
 * @property string $supervisorsignaturefile
 * @property string $supervisordate
 * @property string $administrationmemo
 * @property string $administrationsignature
 * @property string $administrationsignaturefile
 * @property string $administrationdate
 * @property string $generalmanagermemo
 * @property string $generalmanagersignature
 * @property string $generalmanagersignaturefile
 * @property string $generalmanagerdate
 * @property string $validflag
 * @property integer $create_at
 * @property integer $create_by
 * @property integer $modify_at
 * @property string $modify_by
 */
class AttendanceGoout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attendance_goout}}';
    }
	
		public static $statusLabel = [
		   '0'=>'草稿',
		   '10'=>'提交审核',
		   '20'=>'部门领导审核中',
		   '21'=>'部门领导审核失败',
		   '30'=>'总经理审核中',
		   '31'=>'总经理审核失败',
		   '40'=>'流转人事核中',
		   '50'=>'审核完成',
		];
		
	public static $statusData = [
	   '1'=>'草稿',
	   '20'=>'提交审核',
	   '20,30,40'=>'审核中',
	   '21,31'=>'审核失败',
	   '50'=>'审核完成',
	];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['create_at'], 'default', 'value' => time()],
			[['create_by'], 'default', 'value' => Yii::$app->user->getId()],
			[['username','gooutdate','description','gooutstart','gooutend','gooutend_valid'], 'required'],
			[['personnel_id'],'required','message'=>'请检查该员工信息是否有录入'],
			[['toexamineid'], 'required',"message"=>"由于你还没有添加上级领导，请联系后台管理人员"],
            [['personnel_id', 'create_at', 'create_by', 'modify_at', 'modify_by'], 'integer'],
            [['writedate', 'gooutdate', 'supervisordate', 'administrationdate', 'generalmanagerdate'], 'safe'],
            [['totalhour'], 'number'],
            [['validflag','status'], 'string'],
            [['employeeid'], 'string', 'max' => 20],
            [['username','toexamineid'], 'string', 'max' => 50],
            [['department', 'job'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 1000],
            [['overtimefile', 'supervisormemo', 'supervisorsignature', 'supervisorsignaturefile', 'administrationmemo', 'administrationsignature', 'administrationsignaturefile', 'generalmanagermemo', 'generalmanagersignature', 'generalmanagersignaturefile'], 'string', 'max' => 255],
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
            'department' => '部门',
            'job' => '职务',
            'writedate' => '填写日期',
            'gooutdate' => '外出日期',
            'gooutstart' => '外出起始时间',
            'gooutend' => '外出结束时间 预计',
            'gooutend_valid' => '外出结束时间 实际',
            'totalhour' => '合计时长',
            'description' => '外出事由',
            'overtimefile' => '加班单附件',
            'supervisormemo' => '部门主管意见',
            'supervisorsignature' => '部门主管签名',
            'supervisorsignaturefile' => '部门主管签名图',
            'supervisordate' => '部门主管日期',
            'administrationmemo' => '人事行政意见',
            'administrationsignature' => '人事行政签名',
            'administrationsignaturefile' => '人事行政签名图',
            'administrationdate' => '人事行政日期',
            'generalmanagermemo' => '总经理意见',
            'generalmanagersignature' => '总经理签名',
            'generalmanagersignaturefile' => '总经理签名图',
            'generalmanagerdate' => '总经理日期',
			'toexamineid' => '直属上级领导ID',
			'status'=>'(0为草稿|10为提交审核|20为上级领导审核成功|30为流转人事|40为审核失败)',
            'validflag' => '回收状态',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'modify_at' => '修改时间',
            'modify_by' => '修改人',
        ];
    }
	
	   public static function find()
    {
        return new AdministrationQuery(get_called_class());
    }
	public function getAuditLog(){
		  return $this->hasMany(AuditLog::className(),['relaid'=>'id'])->alias('auditLog');
	}
	
	public function getLeavefileAttr(){
		return $this->leavefile?Files::getFiles(explode(",",$this->leavefile)):[];
	}
	public function getSupervisorsignaturefileAttr(){
		return $this->supervisorsignaturefile?Files::getFiles(explode(",",$this->supervisorsignaturefile)):[];
	}
	public function getAdministrationsignaturefileAttr(){
		return $this->administrationsignaturefile?Files::getFiles(explode(",",$this->administrationsignaturefile)):[];
	}
	public function getGeneralmanagersignaturefileAttr(){
		return $this->generalmanagersignaturefile?Files::getFiles(explode(",",$this->generalmanagersignaturefile)):[];
	}
	
	public function getStatusLabel($status=''){
		$status = $status?:$this->status;
		return static::$statusLabel[$status];
	}
	public function getOperationUser($uid){
		$uid = explode(',',$uid);
		$operationUser =  \manage\models\Admin::find()->alias('admin')->where(['admin.id'=>$uid])->joinWith(['personnels'])->asArray()->one();
		return $operationUser['personnels']['name'];
	}
	public function auditBtns($uid)
    {
		$btnHtml="";
		switch($this->status){
			case '20':
			    if($this->totalhour >= 24 ){
					$btnHtml.= $this->auditBtn('通过审核','30','20');
				}else{
					$btnHtml.= $this->auditBtn('通过审核','40','20');
				}
				$btnHtml.= $this->auditBtn('否决审核','21','20');
				break;
			case '30':
				$btnHtml.= $this->auditBtn('通过审核','40','30');
				$btnHtml.= $this->auditBtn('否决审核','31','30');
				break;
			case '40':
				$btnHtml.= $this->auditBtn('确定','50','40');
				break;
		}
		
		return $btnHtml;
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function auditBtn($title,$afterStatus,$beforestatus)
    {
		return \yii\helpers\Html::a($title,'javascript:void(0);', ['title' =>'','class'=>'deletes yangshi','data-afterstatus'=>$afterStatus,'data-beforestatus'=>$beforestatus,'data-id'=>$this->id,'data-status'=>$this->status]);
    }
}
