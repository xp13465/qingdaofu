<!-- 处置方详情 -->
<?php
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}else{

}

$url = '';
if($category == 1){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyfinancing','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyinvestment','category'=>$category,'id'=>$id]);
    }
}elseif($category == 2){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacycollection','category'=>$category,'id'=>$id]);
    }
}elseif($category == 3){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacylawentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacylawer','category'=>$category,'id'=>$id]);
    }
}
?>

<div class="xy">
    <p class="border_l color">服务协议<span><?php echo $desc->code?>协议</span></p>
    <div>
        <a href="<?php echo $url;?>" target="_blank">点击查看协议</a>
    </div>

</div>