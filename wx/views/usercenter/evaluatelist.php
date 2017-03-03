<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'评价消息','gohtml'=>''])?>


<section>
  <div class="apply_yy">
    <div class="pinjia_m">
        <ul>
            <li>
                <span>收到的评价(2)</span>
            </li>
        </ul>
    </div>
<?php if(!empty($eva) && $eva!=''){ ?>
    <?php foreach($eva as $value){ ?>
    <ul class="apply_num">
       <li>
         <p style="color:#666;"><?php echo isset($value['mobiles'])?\frontend\services\Func::HideStrRepalceByChar($value['mobiles'],'*',3,4):''?><i style="float:right;"><?php echo isset($value['create_time'])?date('Y-m-d H:i',$value['create_time']):''?></i></p>
         <div>
           <span><?php echo \frontend\services\Func::evaluateNumber(round($creditors))?></span>
         </div>
         <p><?php echo isset($value['content'])?$value['content']:''?></p>
		 <?php $picture =explode(',',$value['picture']);?>
         <div class="square">
            <?php foreach($picture as $vc => $k){ ?>
             <?php if($k){ ?>
               <span><img style="height:50px;width:50px; display:inline-block;" src="<?php echo Yii::$app->params['wx'].str_replace("'",'',$k)?>"/></span>
            <?php } else {echo '';}?>
           <?php } ?>
        </div>
       </li>
     </ul>
	 <?php } ?>
   <?php }else{echo '';} ?>
  </div>
</section>

<section>
  <div class="apply_yy">
    <div class="pinjia_m">
        <ul>
            <li>
                <span>发出的评价(1)</span>
            </li>
        </ul>
    </div>
<?php if(!empty($launchevaluation) && $launchevaluation != ''){ ?>
    <?php foreach($launchevaluation as $valu){ ?>
    <ul class="apply_num">
       <li>
         <p style="color:#666;"><?php echo isset($valu['mobiles'])?\frontend\services\Func::HideStrRepalceByChar($valu['mobiles'],'*',3,4):''?><i style="float:right;"><?php echo isset($valu['create_time'])?date('Y-m-d H:i',$valu['create_time']):''?></i></p>
         <div>
           <span><?php echo \frontend\services\Func::evaluateNumber(round($creditor))?></span>
         </div>
         <p><?php echo isset($valu['content'])?$valu['content']:''?></p>
         <?php $picture =explode(',',$valu['picture']);?>
         <div class="square">
         <?php foreach($picture as $vc => $k){ ?>
            <?php if($k){ ?>
               <span><img style="height:50px;width:50px; display:inline-block;" src="<?php echo Yii::$app->params['wx'].str_replace("'",'',$k)?>"/></span>
            <?php } else {echo '';}?>
            <?php } ?>
         </div>
       </li>
     </ul>
	 <?php } ?>
<?php }else{echo"";} ?>
  </div>
</section>

<footer>
  <div class="foot"> 
  <?php if(count($launchevaluation) < 3){ ?>
       <a href="<?php echo yii\helpers\Url::toRoute(['/releases/addevaluation','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>" style="color:#fff;background:#0065b3;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;">追加评价</a>
  <?php }else{?>
       <a href="#" style="color:#fff;background:#979797;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;">追加评价</a>
  <?php }?>
   </div>
</footer> 



<script type="text/javascript">
    $(document).ready(function(){
         $('.pinjia_m ul li ').click(function(){
         var index = $(this).index();
         $(this).children().addClass('current').parent().siblings().children().removeClass('current').parents().parent().next().children().eq(index).show().siblings().hide();
         })
    })
    $('.delete').click(function(){
        var id = $(this).attr('data-id');
        var sid = $(this).attr('data-sid');
        $.post('<?php echo yii\helpers\Url::toRoute('/usercenter/deleteeva')?>',{id:id,sid:sid},function(json){
            if(json.code = '0000'){
                alert(json.msg);
                location.reload();
            }else{
                alert(json.msg);
            }
        },'json')
    })
</script>
