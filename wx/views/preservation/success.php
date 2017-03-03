<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;

?>
<link rel="stylesheet" href="/css/more.css">
<?=wxHeaderWidget::widget(['title'=>'&nbsp;','gohtml'=>'<a href="javascript:void(0);" class="wancheng">完成</a>'])?>
<section>        
     	<div class="relation">     		
        <ul>
        	<li class="hm-01">          
             <div class="detail">                                   
                 <p><img src="/images/image_success.png"></p>
                 <span style="color:#0da3f8;font-size:25px;">申请成功</span>
            </div>
            </li>
        	 <li  style="height:180px;">
                <div class="lianxi">
                    <div class="kefu">
                        <span class="hot_line" style="font-size:18px;color: #000;">保全信息</span>
                        <p>1.保全申请成功,我们将在24小时内与您取得联系</p>
                        <p>2.材料越完整进度越快,请完善资料</p>

                        <button onclick="location.href='<?= yii\helpers\Url::toRoute(['/preservation/baoquanlist','type'=>1])?>';">我的保全</button>
                        <button onclick="location.href='<?= yii\helpers\Url::toRoute(['/preservation/picture',"id"=>$id])?>';">完善资料</button>
                    </div>                    
                </div>
            </li>
           
        </ul>
    </div>     	
     </section>  
     <script type="text/javascript">
      $(document).ready(function(){
        $('.wancheng').click(function(){
            location.href = "<?php echo yii\helpers\Url::toRoute('/')?>";
        })
      })
     
     </script>	
