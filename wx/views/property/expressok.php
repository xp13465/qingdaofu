<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;

?>
<link rel="stylesheet" href="/css/more.css">
<?=wxHeaderWidget::widget(['title'=>'提交成功','gohtml'=>''])?>
<section>        
     	<div class="relation">     		
        <ul>
        	<li class="hm-01">          
             <div class="detail">                                   
                 <p><img src="/images/image_success.png"></p>
                 <span style="color:#0da3f8;font-size:25px;">提交成功</span>
            </div>
            </li>
        	 <li  style="height:180px;">
                <div class="lianxi">
                    <div class="kefu">
                        <span class="hot_line" style="font-size:18px;color: #000;">快递信息</span>
                        <p>我们将尽快把产调原件邮寄给您</p>
                        <button onclick="location.href='<?php echo yii\helpers\Url::toRoute('/')?>';">回首页</button>
                        <button onclick="location.href='<?php echo yii\helpers\Url::toRoute('/property/add')?>';">申请产调</button>
                    </div>                    
                </div>
            </li>
        </ul>
    </div>     	
     </section> 