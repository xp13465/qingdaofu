<?php
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>
<style>
.foot a{display:block}
</style>

<footer>
    <div class="foot">
        <ul>
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/site/index')?>">
                    <span <?php if(strtolower($controller) == 'site'){echo 'class="ig-01"';}else{echo 'class="ig01"';}?>></span>
                    <p <?php if(strtolower($controller) == 'site'){echo 'style="color:#0065b3;"';}else{echo 'style="color:gray;"';}?>>首页</p>
                </a>
            </li>
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/list/index')?>">
                    <span <?php if(strtolower($controller) == 'list'){echo 'class="ig02"';}else{echo 'class="ig-02"';}?>></span>
                    <p <?php if(strtolower($controller) == 'list'){echo 'style="color:#0065b3;"';}else{echo 'style="color:gray;"';}?>>产品</p>
                </a>
            </li>
            <li>
				<a href="<?php echo yii\helpers\Url::toRoute('/product/create')?>">
                <span class="ig03" id="btnLogin" style="position: relative;bottom: 15px;"></span>
                 <p style="position:relative;bottom:20px;">发布</p>
				  </a>
            </li>
			
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/message/index')?>">
                <span <?php if($number == 0){if(strtolower($controller) == 'message'){echo 'class="ig-04"';}else{echo 'class="ig04"';}}else{if(strtolower($controller) == 'message'){echo 'class="ig-04  ui-reddot-s "';}else{echo 'class="ig04 ui-reddot-s"';}}?>></span>
                <p <?php if(strtolower($controller) == 'message'){echo 'style="color:#0065b3;"';}else{echo 'style="color:gray;"';}?>>消息</p>
                </a>
            </li>
            <li>

                <a href="<?php echo yii\helpers\Url::toRoute('/user/index');?>">
                    <span <?php if(strtolower($controller) == 'user'){echo 'class="ig-05"';}else{echo 'class="ig05"';}?>></span>
                    <p <?php if(strtolower($controller) == 'usercenter'){echo 'style="color:#0065b3;"';}else{echo 'style="color:gray;"';}?>>用户</p>
                </a>
             </li>

        </ul>
        <!-- <div class="fly" style="position:absolute;left:45%;bottom:-15px;">
     <a href="#wheel5" class="wheel-button"></a>
		<ul id="wheel5" data-angle="N" class="wheel">
			<li class="item" style="border-top:0px solid gray;background:none;height:60px;"><a href="#"></a></li>
            <li class="item" style="border-top:0px solid gray;background:none;height:60px;"><a href="<?php /*echo \yii\helpers\Url::toRoute('/publish/financing')*/?>"><img src="/images/money.png"></a></li>
			<li class="item" style="border-top:0px solid gray;background:none;height:60px;"><a href="<?php /*echo \yii\helpers\Url::toRoute('/publish/collection')*/?>"><img src="/images/laba.png"></a></li>
            <li class="item" style="border-top:0px solid gray;background:none;height:60px;"><a href="<?php /*echo \yii\helpers\Url::toRoute('/publish/ligitation')*/?>"><img src="/images/chui.png"></a></li>
			<li class="item" style="border-top:0px solid gray;background:none;height:60px;"><a href="#"></a></li>
		</ul>
    </div> -->
    </div>
</footer>