<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
?>
<div class="cm-header">
	<code>用户中心</code>
	<span class="gohtml" ></span>
</div>
<style>
.hm-01 a,.keep a,.fabu01 a{display:block}

</style>
<section>
    <ul class="con11">
        <li class="hm-01">
			<a  href="<?php echo yii\helpers\Url::toRoute('/user/info')?>">
            <div class="hm-01_shuzi">
				<div class="num_sz" style="width:65%;height:60px;margin-top:10px;float:left;"> <span style="float:left;margin:0px 10px;padding-left:0px;">
				<img src="<?=$data['pictureurl']?>" style="width:50px;height:50px;border-radius: 50%;vertical-align: middle;"></span>
					<p style="font-size:16px;line-height:30px;width：220px;overflow:hidden"><?=$data['realname']?:$data['username']?></p>
					<p><?=$data['mobile']?></p>
				</div>
				<div class="renzhen-01" style="margin-top:10px;"> 
					<span> 个人中心 </span> 
				<i class="jiantou"></i>
				</div>
            </div>
			</a>
             <div class="detail-m det">
               <ul>
					<li  class="fabus">
						<a href="<?= yii\helpers\Url::toRoute('/wrelease/index?type=1')?>">
							<p class="fb_img1" ><i></i>我的发布</p>
						</a> 
					</li>
					<li  class="fabus">
						<a href="<?= yii\helpers\Url::toRoute('/productorders/index?list=0&type=1')?>">
							<p class="fb_img2"><i></i>我的接单</p>
						</a> 
					</li>
					<li  class="jiedan">
						<a href="<?= yii\helpers\Url::toRoute('/productorders/index?list=1&type=1')?>">
							<p class="fb_img3"><i class='<?=$data['operatorDo']?"ui-reddot-s":""?>'></i>经办事项</p>
						</a>
					</li>
				</ul>
            </div>
        </li>
    </ul>
</section>
<section>
    <ul class="keep">
        <li class="keep01">
			<a href="<?php echo yii\helpers\Url::toRoute(['/preservation/baoquanlist','type'=>1])?>">
                <span class="keep_ig11"></span>
                <span class="baocun">我的保全</span>
                <i class="jiantou jiantou01"></i>
			 </a>
		</li>
        <li class="keep01">
			<a href="<?php echo yii\helpers\Url::toRoute(['/policy/index','type'=>1])?>">
                <span class="keep_ig12"></span>
                <span class="baocun">我的保函</span>
                <i class="jiantou jiantou01"></i>
			 </a>
        </li>
		<li class="keep01">
			<a href="<?php echo yii\helpers\Url::toRoute('/property/index')?>">
				<span class="keep_ig13"></span>
				<span class="baocun">我的产调</span>
				<i class="jiantou jiantou01"></i>
			</a>
		</li>
		<li>
			<a href="<?php echo yii\helpers\Url::toRoute('/estate/list')?>">
                 <span class="keep_ig14"></span>
                 <span class="baocun">我的房产评估</span>
                 <i class="jiantou jiantou01"></i>
			</a>	
		</li>
       
    </ul>
	<ul class="keep">
		<li class="keep01"> 
			<a href="<?php echo yii\helpers\Url::toRoute('/usercenter/preservation')?>">
				<span class="keep_ig15"></span> <span class="baocun">我的草稿</span> 
				<i class="jiantou jiantou01"></i> 
				<span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;">未发布的</span>
			</a>
		</li>
		
		<li>
			<a href="<?php echo yii\helpers\Url::toRoute('/usercenter/collection')?>">
				<span class="keep_ig16"></span> 
				<span class="baocun">我的收藏</span> 
				<i class="jiantou jiantou01"></i>
			</a>
		</li>
	</ul>
  
	<div class="fabu01"> 
		<a href="<?php echo yii\helpers\Url::toRoute('/contacts/index')?>">
			<span class="keep_ig7"></span>
			<span class="baocun">我的通信录</span>
			<i class="jiantou jiantou01"></i>
			<span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;">添加联系人</span>
		 </a> 
	</div>
 
 
	<div class="fabu01">
		<a href="<?php echo yii\helpers\Url::toRoute('/user/setup')?>">
			<span class="keep_ig8"></span>
			<span class="baocun">帮助中心</span>
			<i class="jiantou jiantou01"></i>
		</a> 
	</div>
 
  
</section>
<?php echo  \wx\widget\wxFooterWidget::widget()?>