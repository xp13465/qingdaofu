<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_department".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $organization_id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $validflag
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property string $modifyd_by
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_department';
    }
    public $second;
	public $secondName;
    /**
     * @inheritdoc
     */
   public function rules() 
    { 
        return [
			[['created_at'], 'default', 'value' => time()],
			[['created_by'], 'default', 'value' => Yii::$app->user->getId()],
			[['organization_id'], 'required',"message"=>"请选择机构"],
			[['name'], 'required',"message"=>"请输入部门名称"],
            [['pid', 'organization_id', 'status', 'created_at', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['validflag'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 128],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'pid' => '上级部门id 0为顶级部门',
            'organization_id' => '机构ID 默认1 为资邦财富',
            'name' => '部门名称',
            'description' => '部门描述',
            'status' => '0离职 1在职 2无效 ',
            'validflag' => '回收状态',
            'created_at' => '创建时间',
            'updated_at' => '删除时间',
            'created_by' => '创建人',
            'modifyd_by' => '修改人',
        ]; 
    } 
	public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['organization_id' => 'organization_id'])->alias('organization')->select('organization_id,organization_name');
    }
	public function getAdmin()
    {
        return $this->hasOne(\manage\models\Admin::className(),['id' => 'created_by'])->alias('admin')->select('username');
    }
}
