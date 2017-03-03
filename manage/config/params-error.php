<?php
$errorMsg =[
	'ParamsCheck'=>['code' => '0001', 'msg' => '参数错误'], //请求参数不合法
	'NotFound'=>['code' => '0404', 'msg' => '没有数据'], //请求参数不合法
	'ModelDataCheck'=>['code' => '0002', 'msg' => '格式错误'], //数据格式不正确
	'PageTimeOut'=>['code' => '0003', 'msg' => '页面超时'], //页面超时
	'ModelDataSave'=>['code' => '0010', 'msg' => '页面已过期，请刷新！'], //数据保存失败
	'EXISTED'=>['code' => '0101', 'msg' => '重复申请！'], //重复申请！
	'ORDERSCLOSED'=>['code' => '0102', 'msg' => '已结案'], //已结案
	'ORDERSTERMINATION'=>['code' => '0103', 'msg' => '已中止'], //已中止
	'ORDERSPROCESS'=>['code' => '0104', 'msg' => '处置中'], //处置中
	'ORDERSCONFIRM'=>['code' => '0104', 'msg' => '接单待确认'], //处置中
	'ORDERSPACT'=>['code' => '0104', 'msg' => '接单协议待上传'], //处置中
	
	
	'UserLogin'=>['code' => '3001', 'msg' => '请先登录。'], //未登录
	'UserAuth'=>['code' => '3002', 'msg' => '无权限！'], //权限失败
	'UserAuthenticate'=>['code' => '3015', 'msg' => '请先认证！'], //权限失败
	 
	'phoneFormat'=>['code' => '1001', 'msg' => '手机号格式错误。'],
	'FangjiaToken'=>['code' => '8000', 'msg' => '评估价格失败,请实地评估。'],    //房价网TOKEN 获取错误
	'TotalpriceCheck'=>['code' => '8001', 'msg' => '评估价格失败,请实地评估。'],  //评估总价表单格式错误
	'TotalpriceSave'=>['code' => '8002', 'msg' => '评估价格失败,请实地评估。'],		//评估总价更新错误
	'TotalpriceAPI'=>['code' => '8003', 'msg' => '评估价格失败,请实地评估。'],	//评估总价借口返回错误
	'TotalpriceLimit'=>['code' => '8004', 'msg' => '评估价格失败,请实地评估。'],  //评估次数
	
	'WXPayConn'=>['code' => '7000', 'msg' => '支付失败。'],  //微信支付通讯失败
	'WXPaySignCheck'=>['code' => '7001', 'msg' => '支付失败。'],  //微信支付签名失败
	'WXPayResult'=>['code' => '7002', 'msg' => '支付失败。'],		//微信支付业务失败
	'WXPayResultSignCheck'=>['code' => '7003', 'msg' => '支付失败。'],  //微信支付签名失败
    
    'Userphone'=>['code'=>'4001','msg'=>'查询失败，没有数据'],//用户手机号码查询
	
	//保全,保函报错信息
	'BaoquanAddress'=>['code'=>'1001','msg'=>'自提地址不能为空'],
	'BaoquanAddresss'=>['code'=>'1001','msg'=>'快递地址不能为空'],
	'BaoquanAccount'=>['code'=>'1001','msg'=>'金额不能为空'],
	'BaoquanAccounts'=>['code'=>'1001','msg'=>'请填写正确的金额'],
	'BaoquanPhone'=>['code'=>'1001','msg'=>'联系方式不能为空'],
	'BaoquanPhones'=>['code'=>'1001','msg'=>'请填写正确的手机号'],
	'BaoquanCategory'=>['code'=>'1001','msg'=>'请选择案件类型'],
	'BaoquanFayuan_id'=>['code'=>'1001','msg'=>'请选择法院'],
	'BaoquanArea_id'=>['code'=>'1001','msg'=>'请选择区域'],
	'BaoquanAnhao'=>['code'=>'1001','msg'=>'请输入案号'],
	'BaoquanQisu'=>['code'=>'1001','msg'=>'请上传起诉书的图片'],
	'BaoquanCaichan'=>['code'=>'1001','msg'=>'请上传财产保全申请书的图片'],
	'BaoquanZhengju'=>['code'=>'1001','msg'=>'请上传相关证据资料的图片'],
	'BaoquanAnjian'=>['code'=>'1001','msg'=>'请上传案件受理通知书的图片'],
	
	
	
	
	'CANTSGHOUCANG'=>['code'=>'4001','msg'=>''],
	'CANTSGHOUCANG1'=>['code'=>'4001','msg'=>'个人用户只能用于发布数据'],
	'CANTSGHOUCANG2'=>['code'=>'4002','msg'=>'请不要重复收藏'],
	'CANTSGHOUCANG3'=>['code'=>'4003','msg'=>'公司用户只能清收'],
	'CANTSGHOUCANG4'=>['code'=>'4003','msg'=>'您已经申请对该产品接单'],
	'CANTSGHOUCANG5'=>['code'=>'4004','msg'=>'自己不能申请自己发布的数据'],
	'CANTSGHOUCANG6'=>['code'=>'4004','msg'=>'您对该产品已经申请成功'],
	'CANTSGHOUCANG7'=>['code'=>'4005','msg'=>'律所用户只能收藏诉讼、清收'],
	'CANTSGHOUCANG8'=>['code'=>'4005','msg'=>'面谈中，请联系发布方面谈。'],
	'CANTSGHOUCANG9'=>['code'=>'4002','msg'=>'请不要收藏自己发布的'],
	'CANTSGHOUCANG10'=>['code'=>'4002','msg'=>'请不要输入大于亿的金额'],
	'CANTSGHOUCANG11'=>['code'=>'4002','msg'=>'固定费用不能大于委托金额'],
	'CANTSGHOUCANG12'=>['code'=>'4002','msg'=>'请不要大于36个月'],
	'CANTSHENQING13'=>['code'=>'4002','msg'=>'请不要保存空数据'],
	'CANTSHENQING14'=>['code'=>'4002','msg'=>'申请成功！您还未认证请尽快认证'],
	'CANTSHENQING15'=>['code'=>'4002','msg'=>'接单方还未认证请联系对方先认证'],
	'CANTSHENQING16'=>['code'=>'4002','msg'=>'请选择面谈的用户'],
	'CANTSHENQING17'=>['code'=>'4002','msg'=>'您已选择了面谈客户，请先取消面谈再重新选择'],
	'CANTSHENQING'=>['code'=>'4011','msg'=>''],
	
	'CERTIFICATION0'=>['code'=>'4001','msg'=>'您的申请已提交，请耐心等待客服审核！'],
	'CERTIFICATION1'=>['code'=>'4001','msg'=>'您已认证请不要重复认证'],
	
	'FLOWSTATE1'=>['code'=>'0001','msg'=>'草稿流转出错，请联系开发人员'],
	'FLOWSTATE2'=>['code'=>'0001','msg'=>'待审核流转出错，请联系开发人员'],
	'FLOWSTATE3'=>['code'=>'0001','msg'=>'同意流转出错，请联系开发人员'],
	'FLOWSTATE4'=>['code'=>'0001','msg'=>'人事审批流转出错，请联系开发人员'],
	'FLOWSTATE5'=>['code'=>'0001','msg'=>'审批失败流转出错，请联系开发人员'],
	
	"NOAUDITUID"=>['code'=>'1011','msg'=>'未匹配到审核人员，请联系管理员'],
	];
	defined('OK') or define('OK', 'ok');
	defined('ON') or define('ON', 'on');
	
	foreach($errorMsg as $key=>$val){
		$label = strtoupper($key);
		defined($label) or define($label, $key);
	}
return [
    'errorMsg' => $errorMsg
];
