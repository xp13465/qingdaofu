<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'联系我们','gohtml'=>''])?>
<style>
.hot_line{
	float:left;
	width:30%;
}
.shuzi{
	float:right;
	width:70%;
	color:#000;
}
.clear{clear:both}
</style>
<section>
    <div class="relation">
        <ul> 
			<li>
                <span class="hot_line">客服热线</span>
                <span class="shuzi">400-855-7022</span>
				<div class='clear'></div>
            </li>
            <li>
                <span class="hot_line">总机</span>
                <span class="shuzi">021-80120900</span>
				<div class='clear'></div>
            </li>
            <li>
                <span class="hot_line">传真</span>
                <span class="shuzi">021-80120901</span>
				<div class='clear'></div>
            </li>
            <li>
                <span class="hot_line">邮箱</span>
                <span class="shuzi">zx@direct-invest.com.cn</span>
				<div class='clear'></div>
            </li>
			<li>
                <span class="hot_line">公司地址</span>
                <span class="shuzi">上海市浦东南路855号世界广场34楼A座</span>
				<div class='clear'></div>
            </li>
            <!--<li>
                <div class="lianxi">
                    <div class="kefu">
                        <span class="hot_line">公司地址</span>
                        <span class="shuzi">上海市浦东南路855号世界广场34楼A座</span>
                    </div> 
                </div>
            </li>-->
        </ul>
    </div>
</section>
