<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "zcb_shopping_address".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $nickname
 * @property string $tel
 * @property string $address
 * @property integer $isdefault
 * @property integer $createtime
 * @property integer $modifytime
 */
class ShoppingAddress extends \frontend\components\ActiveRecord
{
    /**
     * @表名
     */
    public static function tableName()
    {
        return 'zcb_shopping_address';
    }

    /**
     * 字段规则
     */
    public function rules()
    {
        return [
			[['isdefault'], 'default', 'value' =>0],
			[['validflag'], 'default', 'value' =>1],
			[['uid'], 'default', 'value' => Yii::$app->user->getId()?Yii::$app->user->getId():Yii::$app->session->get('user_id')],
            [['uid', 'province', 'city', 'area', 'nickname' , 'address', 'tel', 'isdefault'], 'required'],
            [['uid', 'province', 'city', 'area', 'isdefault', 'createtime', 'modifytime'], 'integer'],
            [['isdefault'], 'in', 'range' => [0,1]],
            [['nickname'], 'string', 'max' => 20],
			[['tel'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>"请输入正确的手机号码！"],
            [['address'], 'string', 'max' => 200],
			
        ];
    }

    /**
     * @字段描述
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户ID',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'nickname' => '联系人',
            'tel' => '联系方式',
            'address' => '地址',
            'isdefault' => '设为默认',
            'createtime' => '新增时间',
            'modifytime' => '修改时间',
            'validflag' => '回收状态',
        ];
    }
	/**
	*  新增/编辑地址
	*
	*/
	public function change($data=array()){
		
		if($this->isNewRecord){
			if(isset($data['id']))unset($data['id']);
			$data['createtime'] = time();  
		}else{
			$data['modifytime'] = time(); 
		}
		 
		if(isset($data['isdefault'])){
			$setDefault = $data['isdefault'] ;
			unset($data['isdefault']);
		}else{
			$setDefault = 0;
		}
		$this->setAttributes($data);  
		if($this->validate()){
			if($this->save()){
				if($setDefault){
					$this->setDefault();
				}
				return "ok";
			}else{
				return "ModelDataSave";
			}
		}else{
			return 'ModelDataCheck';
		}
	}
	/**
	* 回收地址
	* 1为回收
	* 2为恢复
	*/
	public function recy($type=1){
		// echo $type;exit;
		$beforeValidflag = $type==2?"0":'1';
		$afterValidflag = $type==2?"1":'0';
		
		return $this->updateAll(['validflag'=>$afterValidflag],['validflag'=>$beforeValidflag,'id'=>$this->id]);
		 
	}
	
	/**
	* 设为默认地址
	* 
	*/
	public function setDefault(){
		
		// var_dump($this->isdefault);
		if($this->isdefault==0){
			$this->updateAll(['isdefault'=>0],['uid'=>$this->uid]);
			return  $this->updateAll(['isdefault'=>1],['validflag'=>1,'id'=>$this->id]);
		}else if($this->isdefault==1){
			return 2;
		}else{
			return 0;
		}
		
		 
	}
	
	public function getProvincename(){
		return $this->hasOne(\common\models\Province::className(), ['provinceID'=>'province'])->select('province as province_name,provinceID');
	}
	public function getCityname(){
		return $this->hasOne(\common\models\City::className(), ['cityID'=>'city'])->select('city as city_name,cityID');
	}
	public function getAreaname(){
		return $this->hasOne(\common\models\Area::className(), ['areaID'=>'area'])->select('area as area_name,areaID');
	}
	
	
}
