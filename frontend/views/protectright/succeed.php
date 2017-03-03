<?php 
use yii\helpers\Url;
?>
<div class="write-top">
   <p>申请保全-提交成功</p>
</div>
<form id="financing" method="post" enctype="multipart/form-data" novalidate="">
<div class="buchong pl" style="display: block;">
   <div class="qh"> <img src="/images/ssc.png">
      <p id="p1">恭喜您!保全提交成功，您可以选择上传证据材料</p>
      <div class="bottom">
      <a href="<?php echo Url::toRoute(['/protectright/modify','id'=>Yii::$app->request->get('id')]);?>">点击上传</a>
      <p><a href="<?php echo Url::toRoute(['/protectright/index']);?>" style="color:#0da3f8;font-size:14px;">不，先查看我的保全</a></p>
      </div>
   </div>
</div>
</form>
