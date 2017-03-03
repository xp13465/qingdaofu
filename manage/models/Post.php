<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_post".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $validflag
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property string $modifyd_by
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_post';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [
			[['created_at'], 'default', 'value' => time()],
			[['created_by'], 'default', 'value' => Yii::$app->user->getId()],
            [['department_id', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['name'], 'required'],
            [['validflag'], 'string'],
            [['name', 'description'], 'string', 'max' => 200],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => '岗位id',
            'department_id' => '部门ID',
            'type' => '0通用岗位 1部门岗位 ',
            'name' => '岗位名称',
            'description' => '岗位描述',
            'status' => '1:启用 2：禁用 3:注销',
            'validflag' => '回收状态',
            'created_at' => '创建时间',
            'updated_at' => '删除时间',
            'created_by' => '创建人',
            'modifyd_by' => '修改人',
        ]; 
    } 
	
	public function getDepartment()
    {
        return $this->hasOne(Department::className(),['id' => 'department_id'])->alias('department')->select('id,name');
    }
	public function getAdmin()
    {
        return $this->hasOne(\manage\models\Admin::className(),['id' => 'created_by'])->alias('admin')->select('username');
    }
}
