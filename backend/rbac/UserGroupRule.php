<?php

namespace backend\rbac;
use Yii;

class UserGroupRule extends \yii\rbac\Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->group;
            if ($item->name === '普通管理员') {
                return $group == '普通管理员';
            } elseif ($item->name === '超级管理员') {
                return $group == '普通管理员' || $group == '超级管理员';
            }
        }
        return false;

    }

}