<?php 
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
if(Yii::$app->request->get("type")=='detail'){
	echo wxHeaderWidget::widget(['title'=>'评估结果','gohtml'=>'']);
}else{
	

?>
  <div class="cm-header">           
        <i>&nbsp;</i>  
        <a href="<?= yii\helpers\Url::toRoute('/site/index')?>">完成</a>
	</div> 
<?php }?>
 <section>   
 	<ul class="con11">
    <li class="hm-01">
	
        <div class="hm-01_shuzi" style="border-bottom:1px solid #eee;">
		 
            <span style="font-size:20px;color:#333;">评估结果</span>                    
            <span style='float:right;padding-right:10px;color:#999'><?=date("Y-m-d",$result['create_time'])?></span>                    
			
			
        </div>

         <div class="detail-m" style='padding:15px 0;'>                                   
             <p style="font-size:35px;text-align:center;color:#f63a5b;font-weight: bold"><?= round($result['totalPrice']/10000).'<font style="font-size:16px">万</font>';?></p>
             <span style="text-align:center;display:block;color:#999;">房产初步评估结果</span>
        </div>
    </li>       
</ul>
 	<div class="relation">     		
    <ul style="margin-top:12px;">
    	 <li>
            <div class="lianxi">
                <div class="kefu">
                    <span class="" style="font-size:20px;color:#333;">房源信息</span>                        
                </div>                    
            </div>
        </li>
        <li>
            <div class="lianxi">
                <div class="kefu">
                    <span class="">房源地址：</span>
                    <span class="shuzi"><?= isset($result['district'])?$result['city'].$result['district']:''?></span>
                </div>                 
            </div>
        </li>        
        <li>
            <div class="lianxi">
                <div class="kefu">
                  <span class="">小区地址：</span>
                  <span class="shuzi"><?= isset($result['address'])?$result['address']:''?></span>
                </div>              
            </div>
        </li>           
        <li>
        	<div class="lianxi">
           	<div class="kefu">
	                <span class="">小区均价：</span>
	                <span class="shuzi"><?= round($result['totalPrice']/$result['size'])?>元/平米</span>
               </div>
          </div>
        </li>
        <li>
        	<div class="lianxi">
           	<div class="kefu">
	                <span class="">房源面积：</span>
	                <span class="shuzi"><?= isset($result['size'])?$result['size']:''?>平方米</span>
               </div>
          </div>
        </li>
        <li>
            <div class="lianxi">
                <div class="kefu">
                    <span class="">房源楼层：</span>
                    <span class="shuzi">第<?= isset($result['floor'])?$result['floor']:''?>层，共<?= isset($result['maxFloor'])?$result['maxFloor']:''?>层</span>
                </div>
            </div>
        </li>
    </ul>
    </div>
     	
     </section>
   
    <footer>
	<?php if(Yii::$app->request->get("type")!='detail'){?>
    <div class="zhen">
        <a href="<?= yii\helpers\Url::toRoute('/estate/index')?>" class="renz">继续评估</a>
    </div>
	<?php }?>
</footer>