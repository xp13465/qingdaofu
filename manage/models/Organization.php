<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_organization".
 *
 * @property string $organization_id
 * @property string $organization_name
 * @property string $organization_full_name
 * @property integer $status
 * @property string $validflag
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property string $modifyd_by
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_organization';
    }
	
    public static $validflag = [
	     '0'=>'是',
		 '1'=>'否',
	];

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [
			[['created_at'], 'default', 'value' => time()],
			[['created_by'], 'default', 'value' => Yii::$app->user->getId()],
			[['organization_name', 'organization_full_name','office'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'modifyd_by'], 'integer'],
            [['validflag'], 'string'],
            [['organization_name', 'organization_full_name'], 'string', 'max' => 255],
            [['office'], 'string', 'max' => 50],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'organization_id' => 'Organization ID',
            'organization_name' => '机构简称',
            'organization_full_name' => '机构全称',
            'office' => '办公地点',
            'status' => '1:启用 2：禁用 3:注销',
            'validflag' => '回收状态',
            'created_at' => '创建时间',
            'updated_at' => '删除时间',
            'created_by' => '创建人',
            'modifyd_by' => '修改人',
        ]; 
    } 
	public function getAdmin()
    {
        return $this->hasOne(\manage\models\Admin::className(),['id' => 'created_by'])->alias('admin')->select('username');
    }
}
