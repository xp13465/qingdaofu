<?php

namespace app\models;
use Yii;
/**
 * This is the ActiveQuery class for [[ServicesAgreement]].
 *
 * @see ServicesAgreement
 */
class ServicesAgreementQuery extends \yii\db\ActiveQuery
{
	public $errors = [];
	public $id = '';
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ServicesAgreement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ServicesAgreement|array|null
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
			$model = new ServicesAgreement;
		}
		$params = ["desc"=>"","contacts"=>"","tel"=>"","type"=>""];
		foreach($params as $field=>$val)$params[$field]=isset($data[$field])?$data[$field]:$val;
		$status = $model->change($params,$action_by);
		if($status==OK){
			$this->id = $model->id;
			Yii::$app->smser->sendMsgByMobile("13918500509", "[{$model->contacts}:{$model->tel}]申请了法律服务-合同起草 ID#{$model->id};描述：".$model->desc."...");
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
