<?php

namespace common\models;

use Yii;
use frontend\modules\wap\services\Func;

/**
 * This is the model class for table "zcb_apply".
 *
 * @property integer $id
 * @property integer $category
 * @property integer $uid
 * @property integer $product_id
 * @property integer $create_time
 * @property integer $is_del
 * @property integer $app_id
 * @property integer $agree_time
 */
class Apply extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'uid', 'product_id', 'create_time', 'is_del', 'app_id', 'agree_time'], 'required'],
            [['category', 'uid', 'product_id', 'create_time', 'is_del', 'app_id', 'agree_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'category' => '申请产品类型',
            'uid' => '申请人ID',
            'product_id' => '申请产品ID',
            'create_time' => '申请时间',
            'is_del' => '是否删除',
            'app_id' => '0为已申请，1为同意申请成功',
            'agree_time' => '同意时间',
        ];
    }
	
	public function apply($product){
		if ($this->save()) {
            Func::addMessagesPerType('申请成功', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",请耐心等待发布方同意。", 8, serialize(['id' => $product->id, 'category' => $product->category]));
            Func::addMessagesPerType('申请接单', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",有人接单，请赶快去看看。", 9, serialize(['id' => $product->id, 'category' => $product->category]),$product->uid);
            // echo Json::encode(['code' => '0000', 'msg' => '恭喜您申请成功']);
			return 'ok';
        } else {
			return 'ModelDataSave';
        }
	}
    public function afterSave($insert, $attributes)
    {
        if ($this->app_id == 1) {
            $ApplyAll = $this->findAll(['category' => $this->category, 'product_id' => $this->product_id, 'app_id' => 0]);
            if ($this->category == 1) {
                $v = \common\models\FinanceProduct::findOne(['id' => $this->product_id]);
            } else {
                $v = \common\models\CreditorProduct::findOne(['id' => $this->product_id]);
            }
            foreach ($ApplyAll as $aa) {
				$aa->app_id = "-1";
				$aa->save();
                // $mobile = \common\models\User::findOne(['id' => $aa['uid']]);
                $mobile = \common\models\User::findUserData($aa['uid'],['mobile']);
                // foreach ($mobile as $cv) {
                    Yii::$app->smser->sendMsgByMobile($mobile, '申清失败【直向资产】尊敬的用户：订单号:' . $v["code"] . '申请失败，不要灰心哦!或许完善信息后，更加有助于发布方对您的青睐呢。');
                    \frontend\services\Func::addNewMessage("申请失败","您的申请已失败",$aa['uid']);
                // };
            }
            return parent::afterSave($this, $attributes);
        }
    }
	
	public function getCertificationdata(){
		return $this->hasOne(\common\models\Certification::className(), ['uid'=>'uid'])->select(['uid','name','mobile']);
	}
	
	 
}
