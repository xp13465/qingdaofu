<?php
use yii\helpers\Url;
$isGuest = Yii::$app->user->isGuest;

?>
<div class="grayDiv"></div>
<div class="header" style='border-bottom: 1px solid #eee;'>
    <div class="menus">
        <div class="header1 clearfix">
            <div class="n_nav">
                <div class="m_nav">
                    <div class="kefu">
                        <span class="kefu_hot">客服热线:400-855-7022</span>
                    </div>
                    <div class="denglu">
                        <ul>
                            <?php if(\Yii::$app->user->isGuest){?>
                                <li><a href="<?php echo Url::toRoute('/site/login')?>" class="current">登录</a></li>
                                <li>|</li>
                                <li><a href="<?php echo Url::toRoute('/site/signup')?>">注册</a></li>
                            <?php }else{
                                $user = \common\models\User::findOne(['id'=>Yii::$app->user->getId()]); ?>
                                <li><a href="<?php echo Url::toRoute('/userinfo/info')?>" class="current"><span><?php  echo $user->mobile;?></span></a></li>
                                <li>|</li>
                                <li><a href="<?php echo Url::toRoute('/site/logout')?>"><span style="margin-right:0;">退出</span></a></li>
                            <?php }?>
                            <li>|</li>
                            <li><a target="_blank" href="<?php echo Url::toRoute('/site/help')?>">新手帮助</a></li>
                            <li style="width:20px;height:25px;background: url(/bate2.0/images/home.png) left -27px top -485px no-repeat;" class="code"><a href="#" ></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            ?>
            <div class="head">
                <div class="m_head">
                    <div class="l_logo">
                        <a href="<?php echo Url::toRoute('/')?>"><img src="/images/login/logo.png" height="60" width="200" alt="" /></a>
                    </div>
                    <div class="r_logo">
                        <ul class="menunav">
                            <li class="hover noChild"><a href="<?php echo Url::toRoute('/')?>"<?php if(strtolower($controller.$action) == 'homepagehomepages'){echo "class='current'";}?>>首页</a></li>
                            
							<li  class="hover noChild">
								<a href="<?php echo Url::toRoute('/product/index')?>" <?php if(strtolower($controller.$action) == 'productindex'){echo "class='current'";}?>>债权超市</a>
							</li>
							
							<li  class="hover noChild">
								<a href="<?php echo Url::toRoute('/services/index')?>" <?php if(strtolower($controller) == 'services'){echo "class='current'";}?>>法律服务</a>
								<i style="z-index: 9;position:absolute;top:-8px;left:75px;color:#ff0100;border:1px solid #ff0100;border-radius:3px;height:15px;line-height:14px;font-size:12px;padding:0 2px;">new</i>
							</li>
							
						   
						   <?php /*        <li class="hover">
                                <a href="<?php echo Url::toRoute('/capital/list')?>"<?php if(strtolower($controller) == 'capital'){echo "class='current'";}?>>产品列表</a>
                                <ol>
                                 <li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=1">融资</a></li>
									<li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=2">清收</a></li> 
                                    <li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=3">诉讼</a></li>
                                </ol>
                            </li>*/?>
                            <li class="hover"><a href="<?php echo Url::toRoute('/products/products')?>" <?php if(strtolower($controller) == 'products'){echo "class='current'";}?>>产品服务</a>
                                <ol>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#2">产品释义</a></li>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#3">产品优势</a></li>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#4">产品服务</a></li>
                                </ol>
                            </li>
                            <li class="hover"><a href="<?php echo Url::toRoute('/aboutus/intro')?>" <?php if(strtolower($controller) == 'aboutus'){echo "class='current'";}?>>关于我们</a>

                                <ol>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/intro')?>">公司简介</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/serviceclause')?>">服务协议</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/helpcenter')?>">帮助中心</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/feedback')?>">意见反馈</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/cooperation')?>">商务合作</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/contactus')?>">联系我们</a></li>
                                </ol>
                            </li> 
                        </ul>
                    </div>
                  
					
					<?php 
					if(!$isGuest){
						$msgCount = \app\models\Message::find()->where(["validflag"=>"1","isRead"=>"0","belonguid"=>Yii::$app->user->getId()])->count();
						
					?>
					<div class="r_logo" style="float:right;">
						<ul class="menunav">
							<li id="over1" ><a style='display: block;' href="<?php echo \yii\helpers\Url::toRoute('/product/create')?>">发布债权</a></li>
							<li id="over2" >
							<img src="<?=Yii::$app->user->identity->headimg?Yii::$app->user->identity->headimg->file:'/images/defaulthead.png'?>">
							<?php if($msgCount){?>
								<i style="border:1px solid #ff0100;width:5px; height:5px;position:absolute;top:5px;margin-left:-10px;border-radius:20px;background:#ff0100;"></i>
							<?php }?>
								<a href="<?php echo Url::toRoute('/userinfo/info')?>"><?=Yii::$app->user->identity->realname?:Yii::$app->user->identity->username?></a>
								<span class='oljt'></span>
								<ol class="center">
									<li>
									<a href="<?=Url::toRoute('/message')?>">消息
									<?php if($msgCount){?>
									<i style="padding:0 5px;background:#F79D25;color:#fff;border-radius:60px;font-size:12px;position:relative;left:50px;z-index:9;"><?=$msgCount?></i>
									<?php }?>
									</a>
									</li>
									<li><a href="<?=Url::toRoute(['/certifications/index'])?>">认证</a></li> 
									<li><a href="<?=Url::toRoute(['/site/logout'])?>">退出</a></li>
								</ol>
							</li>
						</ul>
					</div>
					<?php }else{?>
					<div class="r_logo" style="float:right;border:1px solid #ddd;border-radius:3px;height:35px;margin-top:20px;margin-right: 20px;">
						<ul class="demo">
						  <li id="over3" style="color:#333;line-height:35px;"><a href="<?php echo Url::toRoute('/site/login')?>" style="color:#333;float:right;margin-right:15px;">个人中心</a></li>
						</ul>
					  </div>
					<?php }?>
					
                </div>
            </div>
        </div>
        </div>
</div>

<div class="header header-animation" style="display:none;position: fixed;top: 0;opacity:0.95;z-index:9;box-shadow: 0px 0px 5px #999;">
    <div class="menus"  style="height:50px;">
        <div class="header1 clearfix">
            <?php
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            ?>
            <div class="head">
                <div class="m_head">
                    <div class="l_logo">
                        <a href="<?php echo Url::toRoute('/')?>"><img src="/bate2.0/images/logo_1.png" alt="" /></a>
                    </div>
                    <div class="r_logo">
                        <ul class="menunav">
                            <li class="hover noChild"><a href="<?php echo Url::toRoute('/')?>"<?php if(strtolower($controller.$action) == 'homepagehomepages'){echo "class='current'";}?>>首页</a></li>
                            <li  class="hover noChild">
								<a href="<?php echo Url::toRoute('/product/index')?>" <?php if(strtolower($controller.$action) == 'productindex'){echo "class='current'";}?>>债权超市</a>
							</li>
							<li  class="hover noChild">
								<a href="<?php echo Url::toRoute('/services/index')?>" <?php if(strtolower($controller) == 'services'){echo "class='current'";}?>>法律服务</a>
								<i style="z-index: 9;position:absolute;top:-8px;left:75px;color:#ff0100;border:1px solid #ff0100;border-radius:3px;height:15px;line-height:14px;font-size:12px;padding:0 2px;">new</i>
							</li>
              
						   
						   <?php /*        <li class="hover">
                                <a href="<?php echo Url::toRoute('/capital/list')?>"<?php if(strtolower($controller) == 'capital'){echo "class='current'";}?>>产品列表</a>
                                <ol>
                                 <li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=1">融资</a></li>
									<li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=2">清收</a></li> 
                                    <li><a href="<?php echo Url::toRoute('/capital/list')?>?cat=3">诉讼</a></li>
                                </ol>
                            </li>*/?>
                            <li class="hover"><a href="<?php echo Url::toRoute('/products/products')?>" <?php if(strtolower($controller) == 'products'){echo "class='current'";}?>>产品服务</a>
                                <ol>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#2">产品释义</a></li>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#3">产品优势</a></li>
                                    <li><a href="<?php echo Url::toRoute('/products/products')?>#4">产品服务</a></li>
                                </ol>
                            </li>
                            <li class="hover"><a href="<?php echo Url::toRoute('/aboutus/intro')?>" <?php if(strtolower($controller) == 'aboutus'){echo "class='current'";}?>>关于我们</a>

                                <ol>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/intro')?>">公司简介</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/serviceclause')?>">服务协议</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/helpcenter')?>">帮助中心</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/feedback')?>">意见反馈</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/cooperation')?>">商务合作</a></li>
                                    <li><a href="<?php echo Url::toRoute('/aboutus/contactus')?>">联系我们</a></li>
                                </ol>
                            </li>
							<?php /*?>
                            <li class="hover"><a href="<?php echo Url::toRoute('/message')?>"<?php if(in_array(strtolower($controller),['user','message','publish','list','order','certification'])){echo "class='current'";}?>>用户中心</a>
                                <?php if(Yii::$app->user->getId()){?>
                                    <ol>
                                        <li><a href="<?php echo Url::toRoute('/message')?>">系统消息</a></li>
                                        <li><a href="<?php echo Url::toRoute('/publish/publish')?>">发布管理</a></li>
                                        <li><a href="<?php echo Url::toRoute('/order/index')?>">我的接单</a></li>
                                        <li><a href="<?php echo Url::toRoute('/certification/lawyer')?>">认证管理</a></li>
                                        <li><a href="<?php echo Url::toRoute('/user/security')?>">安全中心</a></li>
                                    </ol>
                                <?php } else { ?>
                                    <ol>
                                        <li><a href="<?php echo Url::toRoute('/site/login')?>">系统消息</a></li>
                                        <li><a href="<?php echo Url::toRoute('/site/login')?>">发布管理</a></li>
                                        <li><a href="<?php echo Url::toRoute('/site/login')?>">我的接单</a></li>
                                        <li><a href="<?php echo Url::toRoute('/site/login')?>">认证管理</a></li>
                                        <li><a href="<?php echo Url::toRoute('/site/login')?>">安全中心</a></li>
                                    </ol>
                                <?php } ?>
                            </li>
							<?php  */?>
                        </ul>
                    </div>
<?php 
					if(!$isGuest){
						// $msgCount = Yii::$app->db->createCommand('select count(*) from zcb_message where isRead = 0 and type !=24 and belonguid = '.\Yii::$app->user->getId())->queryScalar();	
						
					?>
					<div class="r_logo" style="float:right;">
						<ul class="menunav">
							<li id="over1" ><a style='display: block;' href="<?php echo \yii\helpers\Url::toRoute('/product/create')?>">发布债权</a></li>
							<li id="over2" >
							<img src="<?=Yii::$app->user->identity->headimg?Yii::$app->user->identity->headimg->file:'/images/defaulthead.png'?>">
							<?php if($msgCount){?>
								<i style="border:1px solid #ff0100;width:5px; height:5px;position:absolute;top:5px;margin-left:-10px;border-radius:20px;background:#ff0100;"></i>
							<?php }?>
								<a href="<?php echo Url::toRoute('/userinfo/info')?>"><?=Yii::$app->user->identity->realname?:Yii::$app->user->identity->username?></a>
								<span class='oljt'></span>
								<ol class="center">
									<li>
									<a href="<?=Url::toRoute('/message')?>">消息
									<?php if($msgCount){?>
									<i style="padding:0 5px;background:#F79D25;color:#fff;border-radius:60px;font-size:12px;position:relative;left:50px;z-index:9;"><?=$msgCount?></i>
									<?php }?>
									</a>
									</li>
									<li><a href="<?=Url::toRoute(['/certifications/index'])?>">认证</a></li> 
									<li><a href="<?=Url::toRoute(['/site/logout'])?>">退出</a></li>
								</ol>
							</li>
						</ul>
					</div>
					<?php }else{?>
					<div class="r_logo" style="float:right;border:1px solid #ddd;border-radius:3px;height:35px;margin-right: 20px;">
						<ul class="demo">
						  <li id="over3" style="color:#333;line-height:35px;"><a href="<?php echo Url::toRoute('/site/login')?>" style="color:#333;float:right;margin-right:15px;">个人中心</a></li>
						</ul>
					  </div>
					<?php }?>
					
                </div>
            </div>
        </div>
        </div>
</div>

<script>
$(document).ready(function(){
	var tipindex='';
	$(".code").mouseover(function(){
		tipindex = layer.tips('<img src="/bate2.0/images/dipian.png" class="tu1" width="120" height="120">', $(this), {
		  tips: [1, '#33b3f5'],
		  area: ["140px !important","140px !important"],
		  scrollbar :true,
		  time: 0
		});
		console.log(tipindex)
	$(".code").mouseout(function(){
		if(tipindex){
			layer.close(tipindex)
		}
		// $(".layui-layer,.layui-layer-tips,.layer-anim,.tu1").hide();
		});
	})
})

/*
    $(function(){
        $('.menunav>li').hover(function(e) {
			if($(this).children("ol").size()){
				$(this).children('ol').stop().slideDown(100);
			}else{
				$(this).addClass("noChild")	
			}
        },function(){
			$(this).children('ol').stop().slideUp(0);
		});
    })
	*/
	
	$(document).ready(function(){
		$(window).on("scroll",function(){
			if($(window).scrollTop()>92){
				$(".header-animation").slideDown('fast');
			}else{
				$(".header-animation").slideUp('fast');
			}
		})	
		$(window).trigger("scroll")
		//$(".header-animation").show();
        // $(".header-animation").animate({top: '+92px'}, "slow");
     });

</script>
