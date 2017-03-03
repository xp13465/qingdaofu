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
        $url = \yii\helpers\Url::to(['/protocol/mediacyfinancing','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::to(['/protocol/mediacyinvestment','category'=>$category,'id'=>$id]);
    }
}elseif($category == 2){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::to(['/protocol/mediacyentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::to(['/protocol/mediacycollection','category'=>$category,'id'=>$id]);
    }
}elseif($category == 3){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::to(['/protocol/mediacylawentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::to(['/protocol/mediacylawer','category'=>$category,'id'=>$id]);
    }
}
?>
<div class="xieyi">
    <span>服务协议</span>
    <i></i>
    <p>
        <a><?php echo $desc->code?>协议：</a>
        <a href="<?php echo $url;?>" target="_blank"><?php echo $desc->code?>协议</a>
    </p>
</div>