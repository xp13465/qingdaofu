<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PolicyForm extends Model
{
    public $court;
    public $name;
    public $phone;
    public $money;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['court', 'name', 'phone', 'money'], 'required'],
            ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}必须为1开头的11位纯数字'],
            ['money', 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'court' => '法院',
            'name' => '申请人',
            'phone' => '手机号码',
            'money' => '保全金额',

        ];
    }

}
