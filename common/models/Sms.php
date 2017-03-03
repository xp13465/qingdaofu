<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zcb_sms".
 *
 * @property integer $id
 * @property string $code
 * @property integer $create_time
 * @property integer $is_use
 * @property string $mobile
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'create_time', 'mobile'], 'required'],
            [['create_time', 'is_use', 'type'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'code' => '验证码',
            'create_time' => '创建时间',
            'is_use' => '是否使用',
            'type' => '验证码类型',
            'mobile' => '接收验证码手机号码',
        ];
    }

    public function isVildateCode($code,$mobile,$type=0,$doUse = true){
        //超时未验证超过30分钟自动失效
        $this->updateAll(['is_use' => '-1'],"is_use = 0 and create_time< ".(time()-10*60));

        //查找为过时的验证码
        $res = $this->find()->where("is_use = 0 and create_time >= ".(time()-10*60)." and code = '{$code}' and mobile = '{$mobile}' ".($type?"and type = '{$type}'":""))->one();

        if(isset($res)&&$res->code){
			if($doUse){
				$this->updateAll(['is_use' => 1],"is_use = 0 and id = '{$res->id}'");
			}
            // $this->updateAll(['is_use' => 1],"is_use = 0 and create_time >= ".(time()-40*60)." and code = '{$code}' and mobile = '{$mobile}'");
            return true;
        }else{
            return false;
        }
    }
}
