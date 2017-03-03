<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
//if($completionRate < 1){}
?>

<div class="box"></div>
<header>
   <div class="cm-header">
       <span class="icon-back"></span>
       <i>个人信息</i>
	   <?php if($certification['state']==2){?>
                <div class="write">
                    <a href="<?php echo yii\helpers\Url::to(['/certification/add','status'=>1])?>"><span class="">编辑</span></a>
                </div>
            <?php }else{ echo '';}?>
   </div>
    <!--<div class="ck-header cp-header"><span class="clock"></span></div>-->
</header>

    <style>
.box{width:100%;height:100%;background:gray;opacity:0.7;position:fixed;z-index:9;display:none;}
.hint{width:300px;height:280px;margin:0 auto;background:#fff;position:fixed;top:50%;left:70%;margin-left:-250px;margin-top:-166px;z-index:111;display:none;}
.hint-in1{width:300px;height:50px;position:relative;background:#f3f3f3;}
.hint2{width:300px;height:100px;padding-top:50px;}
.hint3{width:38px;height:37px;background:url(/images/hint33.png) no-repeat;-webkit-background-size:38px 37px;background-size:38px 37px; position:absolute;top:7px;right:7px;}
.r-top{width:100%;max-width:640px;height:220px;background:#1ec4f4;}
.r-top span{display:block;text-align:center;padding-top:20px;}
.r-top p{font-size:16px;color:#fff;display:block;text-align:center;}
</style>
<?php if($certification['state']==1){ ?>
<section>
    <div class="person_xi">
    <div class="r-top">
    <span><img src="/images/rto.png"></span>
    <p>信息已经通过认证</p>
    </div>
        <div class="rz_name" >
            <ul>
                <li>
                    <span class="x_name">姓名:</span>
                    <span class="y_name"><?php echo isset($certification['name'])?$certification['name']:'暂无';?></span>
                </li>
                <li>
                    <span class="x_name">身份证号码:</span>
                    <span class="y_name"><?php echo isset($certification['cardno'])?$certification['cardno']:'暂无';?></span>
                </li>
                <!--<li>
                    <span class="x_name x_name_top">身份照片:</span>
                    <?php /*$cardimg = explode(',',$certification['cardimg']);*/?>
                    <span class="rim btn1"><img src="<?php /*echo isset($cardimg)?Yii::$app->params['wx'].substr(array_pop($cardimg),1,-1):'暂无'*/?>" style="height:50px;width:50px; display:inline-block;"/></span>
                </li>-->
                <li>
                    <span class="x_name">邮箱:</span>
                    <span class="y_name"><?php echo isset($certification['email'])?$certification['email']:'暂无';?></span>
                </li>
                <!--<li>
                    <span class="x_name x_name_top">经典案例:</span>
                    <textarea name="" id="" class="y_name" style="overflow-y:hidden;"><?php /*echo isset($certification['casedesc'])?$certification['casedesc']:'暂无';*/?></textarea>
                </li>-->
            </ul>
        </div>
    </div>
    <!--<div class="no_fb">
        <p>在您未发布及未接单前,您可以根据实际需要,修改您的身份认证</p>
    </div>-->
<?php }else if($certification['state']==0){ echo "<h1 style=' font-size: 20px; font-weight: 400;margin:32% auto;padding:12px;text-align:center;'>请耐心等待客服审核</h1>";}else if($certification['state']==2){echo "<h1 style=' font-size: 20px; font-weight: 400;margin:32% auto;padding:12px;text-align:center;'>认证失败<br/>原因：".$certification['resultmemo']."</h1>";} ?>
</section>
<div class="hint">
	<div class="hint-in1">
    <?php $cardimg = explode(',',$certification['cardimg']);?>
		<div class="hint2"><img src="<?php echo isset($cardimg)?Yii::$app->params['wx'].substr(array_pop($cardimg),1,-1):'暂无'?>" style="height:230px;width:300px; display:inline-block;"/></div>
		<div class="hint3"></div>
	</div>
</div>
<?php if($certification['canModify'] == 1){?>
<!--<footer>
    <div class="zhen">
        <a href="<?php echo yii\helpers\Url::toRoute(['/certification/index','modify'=> 1])?>">修改认证</a>
    </div>
</footer>-->
<?php } ?>
<script type="text/javascript">
    $('.icon-back').bind('touchstart click',function () {
        var type = "<?php echo Yii::$app->request->get('type')?>";
        if(type == 4){
            history.go(-1);
        }else{
            window.location = "<?php echo yii\helpers\Url::toRoute('/user/info')?>";
        }
    });
	
	$(document).ready(function($){

	$(".btn1").click(function(event){
		$(".hint").css({"display":"block"});
		$(".box").css({"display":"block"});
	});
	$(".hint3").click(function(event) {
		$(this).parent().parent().css({"display":"none"});
		$(".box").css({"display":"none"});
	});
});
</script>
