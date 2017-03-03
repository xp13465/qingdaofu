<?php

namespace app\models;
use Yii;
/**
 * This is the ActiveQuery class for [[ServicesReservation]].
 *
 * @see ServicesReservation
 */
class ServicesReservationQuery extends \yii\db\ActiveQuery
{
	public $errors = [];
	public $id = '';
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ServicesReservation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ServicesReservation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	  
	public function change($data=[], $action_by = ''){ 
        
       if(isset($data['id'])&&$data['id']){ 
           $model = $this->where(["id"=>$data['id']])->one(); 
           if(!$model)return NOTFOUND; 
       }else{ 
           $model = new ServicesReservation; 
       } 
       $params = ["province_id"=>"","city_id"=>"","district_id"=>"","desc"=>"","contacts"=>"","tel"=>""]; 
       foreach($params as $field=>$val)$params[$field]=isset($data[$field])?$data[$field]:$val; 
       $status = $model->change($params,$action_by); 
       if($status==OK){ 
           $this->id = $model->id; 
		   Yii::$app->smser->sendMsgByMobile("13918500509", "[{$model->contacts}:{$model->tel}]申请了法律服务-预约律师 ID#{$model->id};描述：".$model->desc."...");
       }else{ 
           $this->errors = $model->errors; 
       } 
       return $status; 
	} 
    
	public function formatErrors($isAll=false) 
	{ 
	   $result = ''; 
	   foreach($this->errors as $attribute => $errors) { 
		   $result .= implode(" ", $errors)." "; 
		   if(!$isAll)break; 
	   } 
	   return $result; 
	} 
}
