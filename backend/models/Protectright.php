<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%protectright}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $area_pid
 * @property integer $area_id
 * @property integer $fayuan_id
 * @property string $fayuan_name
 * @property integer $category
 * @property string $address
 * @property string $fayuan_address
 * @property integer $type
 * @property string $name
 * @property string $cardNo
 * @property string $phone
 * @property string $account
 * @property integer $step
 * @property integer $status
 * @property string $qisu
 * @property string $caichan
 * @property string $zhengju
 * @property string $anjian
 * @property string $jietiao
 * @property string $yinhang
 * @property string $danbao
 * @property string $other
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $modify_user
 * @property integer $modify_time
 */
class Protectright extends \yii\db\ActiveRecord
{
	public static $status = [
		'1'=>"待审核",
		'10'=>"审核通过",
		'20'=>"协议已签订",
		'30'=>"保函已出",
		'40'=>"完成/退保", 
	];
	public static $type	= [
		'1'=>"快递",
		'2'=>"自取", 
	];
	
	public static $category = [
          ''=>'请选择',
          1=>'借贷纠纷',
          2=>'房产土地',
          3=>'劳动纠纷',
          4=>'婚姻家庭',
          5=>'合同纠纷',
          6=>'公司治理',
          7=>'知识产权',
          8=>'其他民事纠纷',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%protectright}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_pid', 'area_id', 'fayuan_id', 'category', 'type', 'step', 'status', 'create_user', 'create_time', 'modify_user', 'modify_time'], 'integer'],
            [['fayuan_id', 'fayuan_name', 'category', 'address', 'fayuan_address'], 'required'],
            [['account'], 'number'],
            [['number', 'name'], 'string', 'max' => 20],
            [['fayuan_name', 'fayuan_address'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 255],
            [['cardNo'], 'string', 'max' => 18],
            [['phone'], 'string', 'max' => 11],
            [['qisu', 'caichan', 'zhengju', 'anjian', 'jietiao', 'yinhang', 'danbao', 'other'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '保全编号',
            'area_pid' => '省',
            'area_id' => '市',
            'fayuan_id' => '法院ID',
            'fayuan_name' => '法院名称',
            'category' => '案件类型',
            'address' => '快递地址',
            'fayuan_address' => '自取地址',
            'type' => '取函方式',
            'name' => '姓名',
            'cardNo' => '身份证号',
            'phone' => '联系方式',
            'account' => '保全金额',
            'step' => 'Step',
            'status' => '状态',
            'qisu' => '起诉书',
            'caichan' => '财产保全申请书',
            'zhengju' => '相关证据资料',
            'anjian' => '案件受理通知书',
            'jietiao' => 'Jietiao',
            'yinhang' => 'Yinhang',
            'danbao' => 'Danbao',
            'other' => 'Other',
            'create_user' => '申请人',
            'create_time' => '申请时间',
            'modify_user' => '操作人',
            'modify_time' => '操作时间',
        ];
    }
	
	public function getCreateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'create_user'])->select(['id','username']);
	}
	
	public function getUpdateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'modify_user'])->select(['id','username']);
	}
	
	public function getImgHtml($fileIds){
		$fileData = Files::find()->where(['id'=>explode(",",$fileIds)])->all(); 
		$string = '';
		foreach($fileData as $file){ 
			$string .= \yii\helpers\Html::img(Yii::$app->params['www'].$file->file, ['title' => $file->name,'alt' => $file->name,'class' => 'photoShow']);
		}
		return $string?$string:'无图片';
	}
}
