<?php
namespace common\models;
use Yii; 
/** 
 * This is the model class for table "{{%express}}". 
 * 
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $orderId
 * @property integer $time
 * @property integer $uptime
 * @property integer $jid
 */ 
class Express extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%express}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['phone'], 'required'],
            [['time', 'uptime', 'jid'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 100],
            [['orderId'], 'string', 'max' => 32],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'name' => '收件人',
            'phone' => '联系方式',
            'address' => '地址',
            'orderId' => '快递单号',
            'time' => '申请时间',
            'uptime' => '发件时间',
            'jid' => '产调查询ID',
        ]; 
    } 
} 
