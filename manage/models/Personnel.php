<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_personnel".
 *
 * @property integer $id
 * @property integer $organization_id
 * @property integer $department_id
 * @property integer $post_id
 * @property string $validflag
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property string $modifyd_by
 */
class Personnel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_personnel';
    }
	public static $validflag = [
	     '0'=>'是',
		 '1'=>'否',
	];
	public $username = '';
	 /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
			[['created_at'], 'default', 'value' => time()],
			[['created_by'], 'default', 'value' => Yii::$app->user->getId()],
			[['name', 'mobile'], 'required'],
			[['parentid'],'required','message'=>'请选择正确的上级领导或联系开发人员'],
			[['mobile'],'match','pattern'=>'/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/','message'=>'请输入正确的手机号'],
            [['headimg','parentid', 'organization_id', 'department_pid', 'department_id', 'post_id', 'created_at', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['validflag'], 'string'],
            [['name', 'job', 'email', 'office'], 'string', 'max' => 50],
			// [['email'],'match','pattern'=>'/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/','message'=>'请输入正确的邮箱'],
            [['employeeid'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 30],
            [['tel'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 300],
			
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'name' => '姓名',
			'headimg' => '头像',
            'job' => '职业描述',
            'employeeid' => 'Employeeid',
            'mobile' => '联系方式',
            'tel' => '固话',
            'email' => '邮箱',
            'address' => '联系地址',
            'parentid' => '直属上级领导ID',
            'organization_id' => '机构ID',
            'department_pid' => '一部门ID',
            'department_id' => '二级部门',
            'post_id' => '岗位ID',
            'office' => '办公地点',
            'checkin' => '是否统计考勤',
            'validflag' => '回收状态',
            'created_at' => '创建时间',
            'updated_at' => '删除时间',
            'created_by' => '创建人',
            'modifyd_by' => '修改人',
        ]; 
    } 
	
    public function getFiles()
    {
        return $this->hasOne(Files::className(),['id' => 'headimg'])->alias('files');
    }
	public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['organization_id' => 'organization_id'])->alias('organization')->select('organization_name');
    }
	public function getDepartmentPid()
    {
        return $this->hasOne(Department::className(),['id' =>'department_pid'])->alias('departmentPid')->select('departmentPid.name');
    }
	public function getDepartmentId()
    {
        return $this->hasOne(Department::className(),['id' =>'department_id'])->alias('departmentId')->select('departmentId.name');
    }
	public function getPost()
    {
        return $this->hasOne(Post::className(),['id' => 'post_id'])->alias('post')->select('id,department_id,name');
    }
	public function getAdmin()
    {
        return $this->hasOne(\manage\models\Admin::className(),['id' => 'created_by'])->alias('admin')->select('username');
    }
	
	public function getAdmins()
    {
        return $this->hasOne(\manage\models\Admin::className(),['personnelid' =>'parentid'])->alias('admins')->select('admins.id,admins.personnelid')->onCondition(['and','admins.personnelid != 0']);
    }
	
	public function getParent()
    {
        return $this->hasOne(Personnel::className(),['id' => 'parentid'])->alias('parent')->select('name');
    }
	public function getChild()
    {
        return $this->hasMany(Personnel::className(),['parentid' => 'id'])->alias('child')->select('name');
    }
	
	public function getUserid(){
		return $this->hasOne(User::className(),['mobile' =>'mobile'])->alias('user')->select('user.id,user.mobile,user.username');
	}
	
	public function getOperationUser($mobile=''){
		return $this->hasOne(User::className(),['mobile' =>'mobile'])->andFilterWhere(['mobile'=>$mobile])->select('user.id,user.mobile,user.username')->asArray();
	}
	public function getDepartments(){
		return $this->hasOne(Department::className(),['id' =>'department_pid'])->alias('department')->select('department.id,department.name');
	}
	
	public function autoComplete($term,$limit = 10){
		$Personneldata = self::find()->alias('personnel')->select(["personnel_id"=>"personnel.id","personnel.name","personnel.job","personnel.employeeid","personnel.mobile","personnel.email","personnel.organization_id","personnel.department_pid","personnel.department_id","personnel.post_id","personnel.parentid"])->where(["or",["like","personnel.name",$term],["like","employeeid",$term],["like","mobile",$term]])->joinWith(['departments'])->asArray()->limit($limit)->all();
		$return = [];
		foreach($Personneldata as $data){
			$item = $data;
			$item["label"]="姓名:".$data["name"]."；工号:".$data["employeeid"]."；手机:".$data["mobile"];
			$item["value"]=$data["name"];
			$return[]=$item;
		}
		return $return ;
	}
}
