<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;?>
<?=wxHeaderWidget::widget(['title'=>'认证','gohtml'=>''])?>
<style>
</style>
<section>
    <!---<div class="shenfen shenfen01">
        <p>&nbsp;</p>
    </div>--->
    <div class="lb">
        <ul>
            <li>
				<a href="<?php echo yii\helpers\Url::toRoute(['/certification/add','status'=>1])?>">
					<span class="img_a"></span>
					<div class="geren">
						<p class="ziti">认证个人</p>
						<!---<span>可发布清收、诉讼</span>
						<p class="daili">暂不支持代理</p--->
					</div>
					<span  class='label'><?php echo isset($status)&&$status==1?'已认证':'未认证'?></span>
				</a>
			</li>
            <li>
				<a href="<?php echo yii\helpers\Url::toRoute(['/certification/add','status'=>2])?>">
					<span class="img_b"></span>
					<div class="geren">
						<p class="ziti">认证律所</p>
						<!--<span>可发布清收、诉讼</span>
						<p class="daili"> 可代理诉讼、清收</p--->
					</div>
					<span  class='label'><?php echo isset($status)&&$status==2?'已认证':'未认证'?></span>
				</a>
		   </li>
            <li>
				<a href="<?php echo yii\helpers\Url::toRoute(['/certification/add','status'=>3])?>">
					<span class="img_c"></span>
					<div class="geren">
						<p class="ziti">认证公司</p>
						<!--<span>可发布清收、诉讼 </span>
						<p class="daili"> 可代理清收</p-->
					</div>
					<span  class='label'><?php echo isset($status)&&$status==3?'已认证':'未认证'?></span>
				</a>
			</li>
        </ul>
    </div>
</section>
