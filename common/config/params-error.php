<?php
return [
    'errorMsg' => [
		'ParamsCheck'=>['code' => '0001', 'msg' => '参数错误'], //请求参数不合法
		'ModelDataCheck'=>['code' => '0002', 'msg' => '格式错误'], //数据格式不正确
		'PageTimeOut'=>['code' => '0003', 'msg' => '页面超时'], //数据保存失败
		'ModelDataSave'=>['code' => '0010', 'msg' => '保存失败'], //数据保存失败
		
		
		
		'UserLogin'=>['code' => '3001', 'msg' => '请先登录。'], //未登录
		'UserAuth'=>['code' => '3002', 'msg' => '无权限！'], //权限失败
		
		
	],
];
