<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="details">
    <div class="look">
        <h3>查看信息</h3>
    </div>
    <!-- 融资详情 -->
    <div class="finance">
        <span>融资详情</span>
        <i></i>
        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>$dt['category'],'id'=>$dt['id']])?>
    </div>
    </div>


