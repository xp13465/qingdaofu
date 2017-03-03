<?php
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{
    die;
}

$apply = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);
if(!isset($apply->id)) $this->context->redirect(\yii\helpers\Url::toRoute(['/']));
if(Yii::$app->user->getId() != $desc->uid){
	$certification = \common\models\Certification::findOne(['uid'=>$desc->uid]);
	
}else{
	$certification = \common\models\Certification::findOne(['uid'=>$apply->uid]);
    $username = \common\models\User::findOne(['id'=>$apply->uid]);
    $trustee = \common\models\Certification::findOne(['uid'=>$username['pid']]);
}

if(!isset($certification->id)) $user = \common\models\User::findOne(['id'=>$apply->uid]);

$pubUser = \common\models\User::findOne(['id'=>$desc->uid]);
$pubCertification = \common\models\Certification::findOne(['uid'=>$desc->uid]);
if($pubUser->pid)$pubCertificationPar = \common\models\Certification::findOne(['uid'=>$pubUser->pid]);

if($category == 1 && $desc['rate_cat'] == 1){
    $a = isset($desc['term'])&&$desc['term']!==''?$desc['term']:0;
    $delays = floor((strtotime(date("Y-m-d 23:59:59",$apply['agree_time']).' + '.$a." days ")-time())/3600/24) ;
}else if($category == 1 && $desc['rate_cat'] == 2){
    $a = isset($desc['term'])&&$desc['term']!==''?$desc['term']:0;
    $delays = floor((strtotime('+'.$a."months",$apply['agree_time'])-time())/3600/24) ;
}else{
    $a = isset($desc['commissionperiod'])&&$desc['commissionperiod']!==''?$desc['commissionperiod']:0;
    $delays = floor((strtotime('+'.$a."months",$apply['agree_time'])-time())/3600/24) ;
}
?>
<?php if(Yii::$app->user->getId() != $desc->uid){?>
<!-- 处置方详情 -->
<div class="apply">
    <span class="bord_l">发布方详情</span>
    <i></i>
    <div class="mytable">
        <table cellspacing="1" cellpadding="0">
            <thead>
            <tr>
				<th>区域</th>
                <th>类型</th>
                <th>发布日期</th>
                <th>到期日期</th>
                <th>剩余时间(天)</th>
                <th>发布方</th>
                <th>联系人</th>
                <th>联系方式</th>
				 
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo \frontend\services\Func::getCityNameById($desc['city_id']);?></td>
                <td><?php $arr = ['1'=>'融资','2'=>'清收','3'=>'诉讼',];echo $arr[$category];?></td>
				
				<td><?php echo date('Y-m-d H:i',$desc->create_time);?></td>
                <td><?php echo date("Y-m-d H:i",strtotime(date("Y-m-d 23:59:59",$apply->agree_time).' + '.$a." days "))?></td>
				<td><?php echo isset($delays)&&$delays>0?$delays:0 ; ?></td>
				<td><a href="<?php echo yii\helpers\Url::toRoute(['/publish/information','id'=>$certification['id']])?>"><?php echo isset($pubCertificationPar['name'])?$pubCertificationPar['name']:(isset($pubCertification['name'])?$pubCertification['name']:'');?></a></td>
                <td><?php echo isset($certification['contact'])?$certification['contact']:$pubUser['username'];?></td>
				<td><?php if(isset($pubUser['id'])&&$pubUser['id']){echo $pubUser->mobile;}else{ echo isset($pubCertificationPar['mobile'])?$pubCertificationPar['mobile']:(isset($pubCertification['mobile'])?$pubCertification['mobile']:'');}?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php }
if(Yii::$app->user->getId() != $apply->uid){
?>
<!-- 处置方详情 -->
<div class="apply" id="cd1">
    <span class="bord_l">接单方详情</span>
    <!-- <i></i> -->
    <div class="mytable">
        <table cellspacing="1" cellpadding="0">
            <thead>
            <tr>
                <th>区域</th>
                <th>类型</th>
                <th>申请日期</th>
                <th>到期日期</th>
                <th>剩余时间(天)</th>
                <th>接单方</th>
                <th>代理人</th>
                <th>联系方式</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo \frontend\services\Func::getCityNameById($desc['city_id']);?></td>
                <td><?php $arr = ['1'=>'融资','2'=>'清收','3'=>'诉讼',];echo $arr[$category];?></td>
                <td><?php echo date('Y',$apply->create_time).'-'.date('m',$apply->create_time).'-'.date('d',$apply->create_time).'-'.date('H',$apply->create_time).':'.date('i',$apply->create_time)?></td>
                <td><?php echo date("Y-m-d H:i",strtotime(date("Y-m-d 23:59:59",$apply->agree_time).' + '.$a." days "))?></td>
                <td><?php echo isset($delays)&&$delays>0?$delays:0 ; ?></td>
                <td><a href="<?php echo yii\helpers\Url::toRoute(['/publish/information','id'=>$certification['id']])?>"><?php echo isset($certification['name'])?$certification['name']:(isset($trustee['name'])?$trustee['name']:'');?></a></td>
                <td><?php echo isset($certification['contact'])?$certification['contact']:(isset($user['username'])?$user['username']:'');?></td>
                <td><?php if(isset($user['id'])&&$user['id']){echo $user->mobile;}else{echo $certification['mobile'];}?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php }?>