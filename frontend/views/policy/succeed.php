<?php 
use yii\helpers\Url;
?>
<div class="write-top">
    <p>申请保函-提交成功</p>
</div>
<form id="financing" method="post" enctype="multipart/form-data" novalidate="">
      <div class="buchong pl" style="display: block;">
         <div class="qh"> <img src="/images/ssc.png">
          <p id="p1">恭喜您!保函申请提交成功，您可以在我的保函查看保函处理状态</p>
          <div class="bottom">
           <a href="<?=Url::toRoute(['index'])?>">我的保函</a>
          </div>
          
        </div>
        </div>
 </form>
 