<?php
use yii\helpers\Url;
$isGuest = Yii::$app->user->isGuest;
?>
<div class="grayDiv">
</div>
<div class="header" style='border-bottom: 1px solid #eee;'>
    <div class="menus">
        <div class="header1 clearfix">
            <div class="n_nav">
                <div class="m_nav">
                    <div class="kefu">
                        <img src="/images/icon.png" height="22" width="22" alt="" />
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
                                <li><a href="<?php echo Url::toRoute('/message/list')?>" class="current"><span><?php  echo $user->mobile;?></span></a></li>
                                <li>|</li>
                                <li><a href="<?php echo Url::toRoute('/site/logout')?>"><span style="margin-right:0;">退出</span></a></li>
                            <?php }?>
                            <li>|</li>
                            <li><a href="<?php echo Url::toRoute('/site/help')?>">新手帮助</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header">
    <div class="menus">
        <div class="header1 clearfix">
            <?php
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            ?>
            <div class="head">
                <div class="m_head">
                    <div class="l_logo">
                        <a href="<?php echo yii\helpers\Url::toRoute('/')?>"><img src="/images/login/logo.png" height="60" width="200" alt="" /></a>
                    </div>
                    <div class="r_logo">
                        <ul class="demo">
                            <li class="hover"><a href="<?php echo Url::toRoute('/')?>"<?php if(strtolower($controller.$action) == 'homepagehomepages'){echo "class='current'";}?>>首页</a></li>
                            <li class="hover">
							<a href="<?php echo Url::toRoute('/services/index')?>" <?php if(strtolower($controller) == 'services'){echo "class='current'";}?>>法律服务</a>
							<i style="z-index: 9;position:absolute;top:-5px;left:75px;color:#ff0100;border:1px solid #ff0100;border-radius:3px;height:15px;line-height:15px;">NEW</i>
							</li>
							<?php /*        <li class="hover">
                                <a href="<?php echo Url::toRoute('/capital/list')?>"<?php if(strtolower($controller) == 'capital'){echo "class='current'";}?>>产品列表</a>
                                <ol>
                                 <li><a href="<?php echo yii\helpers\Url::toRoute('/capital/list')?>?cat=1">融资</a></li>
									<li><a href="<?php echo yii\helpers\Url::toRoute('/capital/list')?>?cat=2">清收</a></li> 
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/capital/list')?>?cat=3">诉讼</a></li>
                                </ol>
                            </li>*/?>
                            <li class="hover"><a href="<?php echo Url::toRoute('/products/products')?>" <?php if(strtolower($controller) == 'products'){echo "class='current'";}?>>产品服务</a>
                                <ol>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/products/products')?>#2">产品释义</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/products/products')?>#3">产品优势</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/products/products')?>#4">产品服务</a></li>
                                </ol>
                            </li>
                            <li class="hover"><a href="<?php echo \yii\helpers\Url::toRoute('/aboutus/intro')?>" <?php if(strtolower($controller) == 'aboutus'){echo "class='current'";}?>>关于我们</a>

                                <ol>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>">公司简介</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/serviceclause')?>">服务协议</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/helpcenter')?>">帮助中心</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/feedback')?>">意见反馈</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/cooperation')?>">商务合作</a></li>
                                    <li><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/contactus')?>">联系我们</a></li>
                                </ol>
                            </li>
                            <li class="hover"><a href="<?php echo Url::toRoute('/message/list')?>"<?php if(in_array(strtolower($controller),['user','message','publish','list','order','certification'])){echo "class='current'";}?>>用户中心</a>
                                <?php if(Yii::$app->user->getId()){?>
                                    <ol>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/message/list')?>">系统消息</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/publish/publish')?>">发布管理</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/order/index')?>">我的接单</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/certification/lawyer')?>">认证管理</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/user/security')?>">安全中心</a></li>
                                    </ol>
                                <?php } else { ?>
                                    <ol>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">系统消息</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">发布管理</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">我的接单</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">认证管理</a></li>
                                        <li><a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">安全中心</a></li>
                                    </ol>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>

					<div class="r_logo" style="float:right;">
					<ul class="demo">
					   <?php /*<li id="over1"><a style='display: block;' href="<?php echo \yii\helpers\Url::toRoute('/publish/publish')?>">&nbsp;</a>
						<ol style="display: none; height: 198px; padding-top: 0px; margin-top: 0px; margin-left:-25px; padding-bottom: 0px; margin-bottom: 0px;">
						  <li><a href="<?php echo yii\helpers\Url::toRoute('/publish/collection')?>">发布清收</a></li> 
						  <li><a href="<?php echo yii\helpers\Url::toRoute('/publish/litigation')?>">发布诉讼</a></li>
						</ol>
						*/?>
					  </li>
						<?php 
						if(!$isGuest){
							$msgCount = Yii::$app->db->createCommand('select count(*) from zcb_message where isRead = 0 and type !=24 and belonguid = '.\Yii::$app->user->getId())->queryScalar();	
						}
						?>
						<li id="over2"><?php if(!$isGuest&&$msgCount) {?><a class="mynews"  href="<?php echo \yii\helpers\Url::toRoute('/message/list')?>"><?=!$isGuest?$msgCount:''?></a><?php }?></li>
					</ul>
				  </div>
                </div>
            </div>
        </div>
        </div>
</div>

<script>
    $(function(){
        $('.demo>li').hover(function(e) {
			$(this).children('ol').stop().slideDown('fast');
        },function(){
			$(this).children('ol').stop().slideUp('fast');
		});
    })
</script>