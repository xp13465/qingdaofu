<?php
$apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);
if(!isset($apply->id)) $this->context->redirect(\yii\helpers\Url::to(['/']));
$certification = \common\models\Certification::findOne(['uid'=>$apply->uid]);
$username = \common\models\User::findOne(['id'=>$apply->uid]);
$trustee = \common\models\Certification::findOne(['uid'=>$username['pid']]);
if(!isset($certification->id)) $user = \common\models\User::findOne(['id'=>$apply->uid]);
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{
    die;
}


?>
<!-- 处置方详情 -->
<div class="apply">
    <span>处置方详情</span>
    <i></i>
    <div class="mytable">
        <table cellspacing="1" cellpadding="0">
            <thead>
            <tr>
                <th>区域</th>
                <th>类型</th>
                <th>申请日期</th>
                <th>到期日期</th>
                <th>剩余时间(天)</th>
                <th>受托方</th>
                <th>代理人</th>
                <th>联系方式</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>上海</td>
                <td><?php $arr = ['1'=>'融资','2'=>'清收','3'=>'诉讼',];echo $arr[$category];?></td>
                <td><?php echo date('Y',$apply->create_time).'-'.date('m',$apply->create_time).'-'.date('d',$apply->create_time).'-'.date('H',$apply->create_time).':'.date('i',$apply->create_time)?></td>
                <td><?php echo date("Y-m-d H:i",strtotime(date("Y-m-d 23:59:59",$apply->agree_time).' + '.$desc->term." days "))?></td>
                <td><?php echo floor((strtotime(date("Y-m-d 23:59:59",$apply->agree_time).' + '.$desc->term." days ") - time())/86400); ?></td>
                <td><?php echo isset($certification['name'])?$certification['name']:(isset($trustee['name'])?$trustee['name']:'');?></td>
                <td><?php echo isset($certification['contact'])?$certification['contact']:(isset($user['username'])?$user['username']:'');?></td>
                <td><?php if(isset($user['id'])&&$user['id']){echo $user->mobile;}else{echo $certification['mobile'];}?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>