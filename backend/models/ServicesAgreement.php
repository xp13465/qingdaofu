<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%services_agreement}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $contacts
 * @property string $tel
 * @property string $desc
 * @property string $status
 * @property string $validflag
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $modify_user
 * @property integer $modify_time
 */
class ServicesAgreement extends \yii\db\ActiveRecord
{
	public static $type=[
		"1"=>"买卖合同",
		"2"=>"赠与合同",
		"3"=>"借款合同",
		"4"=>"租赁合同",
		"5"=>"融资租赁合同",
		"6"=>"承揽合同",
		"7"=>"建设工程合同",
		"8"=>"运输合同",
		"9"=>"技术合同",
		"10"=>"仓储合同",
		"11"=>"委托合同",
		"12"=>"行纪合同",
		"13"=>"居间合同",
	];
	public static $status = [
		"10"=>"待处理",
		"20"=>"已处理",
		"30"=>"忽略",
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%services_agreement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'status', 'validflag'], 'string'],
            [['create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['contacts', 'tel'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '合同类型',// ,1买卖合同,2赠与合同,3借款合同,4租赁合同,5融资租赁合同,6承揽合同,7建设工程合同,8运输合同,9技术合同,10仓储合同,11委托合同,12行纪合同,13居间合同
            'contacts' => '联系人',
            'tel' => '联系方式',
            'desc' => '合同描述',
            'auditmemo' => '处理备注',
            'status' => '状态',// 10提交中  20已处理  30忽略
            'validflag' => '回收标志',
            'create_user' => '创建用户',
            'create_time' => '创建时间',
            'modify_user' => '修改用户',
            'modify_time' => '修改时间',
        ];
    }
	public function getCreateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'create_user']);
	}
	public function getModifyuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'modify_user']);
	}
}
