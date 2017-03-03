<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zcb_policy".
 *
 * @property integer $id
 * @property string $orderid
 * @property integer $area_pid
 * @property integer $area_id
 * @property string $area_name
 * @property string $phone
 * @property string $address
 * @property string $fayuan_address
 * @property string $qisu
 * @property string $caichan
 * @property string $zhengju
 * @property string $anjian
 * @property integer $fayuan_id
 * @property string $fayuan_name
 * @property string $anhao
 * @property integer $type
 * @property string $money
 * @property integer $status
 * @property integer $shenhe_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Policy extends \yii\db\ActiveRecord
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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_policy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_pid', 'area_id', 'fayuan_id', 'type', 'status', 'shenhe_status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['phone', 'fayuan_id', 'fayuan_name', 'money'], 'required'],
            [['money'], 'number'],
            [['orderid'], 'string', 'max' => 30],
            [['area_name', 'phone', 'fayuan_address', 'fayuan_name'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 255],
            [['qisu', 'caichan', 'zhengju', 'anjian', 'anhao'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => '保函编号',
            'area_pid' => '省',
            'area_id' => '市',
            'area_name' => '区域地址',
            'phone' => '联系方式',
            'address' => '快递地址',
            'fayuan_address' => '自取地址',
            'qisu' => '起诉书',
            'caichan' => '财产保全申请书',
            'zhengju' => '相关证据资料',
            'anjian' => '案件受理通知书',
            'fayuan_id' => '法院ID',
            'fayuan_name' => '法院名称',
            'anhao' => '案号',
            'type' => '取函方式',
            'money' => '保函金额',
            'status' => '状态',
            'shenhe_status' => '第三方状态',
            'created_at' => '申请时间',
            'updated_at' => '操作时间',
            'created_by' => '申请人',
            'updated_by' => '操作人',
        ];
    }
	
	public function getCreateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'created_by']);
	}
	
	public function getUpdateuser(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'updated_by']);
	}
	
	public function getImgHtml($fileIds){
		$fileData = Files::find()->where(['id'=>explode(",",$fileIds)])->all(); 
		$string = '';
		foreach($fileData as $file){ 
			$string .= \yii\helpers\Html::img("http://".Yii::$app->params['www'].$file->file, ['title' => $file->name,'alt' => $file->name,'class' => 'photoShow']);
		}
		return $string?$string:'无图片';
	}
}
