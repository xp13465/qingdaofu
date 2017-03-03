<style>

.fo10{ background: url(/images/fo10.png) no-repeat;width:28px; height: 28px;}
.menu_center li:hover .fo10{ background:url(/images/fo10s.png) no-repeat;}
</style>
<div class="content_menu">
    <!--<div class="menu_top">
        <div class="m_left">
            <span></span>
        </div>
    </div>-->
    <?php
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
	$controller = strtolower($controller);
	$controllerAction =$controller.strtolower($action);
    ?>
    <div class="menu_center">
        <ul>
		<?php /*
        <!--<li class="xiaoxi">
            <a href="<?php echo \yii\helpers\Url::toRoute('/message/list')?>"  <?php  if(strtolower($controller) == 'message') echo "class='current'";?>>系统消息<i class="display"><em><?php echo Yii::$app->db->createCommand('select count(*) from zcb_message where isRead = 0 and belonguid = "'.\Yii::$app->user->getId().'"')->queryScalar()?></em></i>
            </a> <b class="fo2"></b>
        </li>

        <li style="border-top: 1px solid #e5e5e5;">
            <a href="<?php echo \Yii\helpers\Url::toRoute('/publish/publish')?>" <?php  if(strtolower($controller) == 'publish') echo "class='current'";?>>发布信息</a><b class="fo3"></b>
        </li>-->
		  <li >
            <a href="<?php echo \Yii\helpers\Url::toRoute('/list/index')?>" <?php  if(strtolower($controller) == 'list') echo "class='current'";?>>我的发布</a><b class="fo1"></b></li>
        <li>
            <a href="<?php echo \Yii\helpers\Url::toRoute('/order/index')?>" <?php  if(strtolower($controller) == 'order') echo "class='current'";?>>我的接单</a><b class="fo2"></b>
        </li>
*/?>
		<li >
            <a href="<?php echo \Yii\helpers\Url::toRoute('/certification/lawyer')?>" <?php  if(strtolower($controller.$action) == 'certificationlawyer') echo "class='current'";?>>认证管理</a><b class="fo3"></b>
        </li>
        <?php $certi = \frontend\services\Func::getCertification();
           if(isset($certi['category'])&&in_array($certi['category'],[2,3])){
        ?>
        <li style="border-top: 1px solid #e5e5e5;">
            <a href="<?php echo \Yii\helpers\Url::toRoute('/certification/agent')?>" <?php  if(strtolower($controller.$action) == 'certificationagent') echo "class='current'";?>>我的代理</a><b class="fo4"></b>
        </li>
        <?php }?>

        <li style="border-top: 1px solid #e5e5e5;">
            <a href="<?php echo \Yii\helpers\Url::toRoute('/policy/index')?>" <?php  if(strtolower($controller) == 'policy'  && $action == 'index') echo "class='current'";?>>我的保函</a><b class="fo6"></b>
        </li>
		
		 
        <li>
            <a href="<?php echo \Yii\helpers\Url::toRoute('/protectright/index')?>" <?php  echo $controllerAction=='protectrightindex'?"class='current'": "";?>>我的保全</a><b class="fo7"></b>
        </li>
		<li>
            <a href="<?php echo \Yii\helpers\Url::toRoute('/fangjia/index')?>" <?php  echo $controllerAction=='fangjiaindex'?"class='current'": "";?>>房产评估</a><b class="fo9"></b>
        </li>

      

        
        <li style="border-top: 1px solid #e5e5e5;">
			<a href="<?php echo \Yii\helpers\Url::toRoute('/user/security')?>" <?php  if(strtolower($controller) == 'user') echo "class='current'";?>>安全中心</a><b class="fo7"></b>
		</li>
    </ul>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        <?php if(strtolower($controller) == 'publish'||strtolower($controller) == 'list'){?>
        $("#hover").css("display","block");
        <?php }?>
    });
</script>