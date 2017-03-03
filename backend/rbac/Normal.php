<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/2/26
 * Time: 14:19
 */

namespace backend\rbac;


class Normal extends \yii\rbac\Rule
{
    public function execute($user, $item, $params){
		// var_dump($user);
		// var_dump($item);
		// var_dump($params);
		// return false;
		return true;
    }
}