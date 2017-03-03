<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<link href="/bate2.0/css/about.css" rel="stylesheet">
<?=wxHeaderWidget::widget(['title'=>'关于清道夫','gohtml'=>''])?>
 
<section>
 <div class="about">
    <div class="logo">
      <p class="bg"></p>
      <p class="name">清道夫债管家</p>
      <p class="version">V2.0.1</p>
    </div>
    <div class="partner">
        <p class="title">商务合作</p>  
        <div class="text">
          <p class="first">
              <i></i>
              <span>公司战略合作及对外项目(平台合作、业务合作)</span>
          </p>
          <p class="second">
            <i></i>
            <span>联系电话:021-80120900-1600</span>
          </p>
          <p class="third">
             <i></i>
             <span>电子邮箱:jianglingzhi@direct-invest.com.cn</span>
          </p>
        </div>
    </div>
    <div class="partner">
        <p class="title">市场合作</p>  
        <div class="text">
          <p class="first">
              <i></i>
              <span>品牌合作、市场活动合作、广告合作、媒体采访</span>
          </p>
          <p class="second">
            <i></i>
            <span>联系电话:021-80120900-1600</span>
          </p>
          <p class="third">
             <i></i>
             <span>电子邮箱:jianglingzhi@direct-invest.com.cn</span>
          </p>
        </div>
     </div>
  </div>
 
</section>
<?php /*
<footer>
    <div class="fot">
        <span>官方网站</span>
        <p>
            Copyright2015-2016 直向资产管理有限公司　沪ICP备15055061号-1
        </p>
    </div>
</footer>*/?>
<script type="text/javascript">
    $(document).ready(function(){
        $('ul li').bind("click",function(){
            $(this).children().toggleClass('arrow_r_br').next().toggle();
            $(this).siblings().children('span').attr('class','hot_line arrow_r').siblings().hide();
        })
    })

</script><style>
    .arrow_r_br::after {
        content: "";
        width: 12px;
        height: 12px;
        border-left: 1px solid #666;
        border-bottom: 1px solid #666;
        -webkit-transform: rotate(-130deg);
        transform: rotate(-48deg);
        box-sizing: border-box;
        position: absolute;
        right: 15px;
        top: 12px;
    }
</style>
