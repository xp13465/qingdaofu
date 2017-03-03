<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<style>
.xiang{width: 70%;min-height: 50px;max-height: 500px;margin-left: auto;margin-right: auto;outline: 0;font-size: 16px;word-wrap: break-word;overflow-x: hidden;overflow-y: auto;}
.jxiang{width: 100%;max-width:640px; min-height: 50px; max-height: 500px;margin-left: auto; margin-right: auto;outline: 0;font-size: 16px; word-wrap: break-word; overflow-x: hidden;overflow-y: auto;}
</style>
 <?=wxHeaderWidget::widget(['title'=>'处理进度','gohtml'=>''])?>

	<section>
<?php foreach($disposing as $value){ ?>
  <div style="width:100%;max-width:640px;background:#ddd;height:50px;text-align:center;font-size:16px;line-height:50px;color:#666;"><?php echo isset($value['create_time'])?date('Y-m-d H:i',$value['create_time']):'暂无';?></div>
  <div class="show">
    <ul class="revert">
	<?php if(Yii::$app->request->get('category') == 3){ ?>
      <li>
        <div class="revert_l"> <span>案号类型</span> </div>
        <div class="revert_r"> <span><?php echo isset($value['audit'])?frontend\services\Func::$case[$value['audit']]:'暂无';?></span> </div>
      </li>
      <li>
        <div class="revert_l"> <span>案号</span> </div>
        <div class="revert_r"><?php echo isset($value['case'])?$value['case']:'暂无';?></div>
      </li>
	<?php } ?>
      <li>
        <div class="revert_l"> <span>处置类型</span> </div>
        <div class="revert_r"> <span><?php echo isset($value['status'])? \frontend\services\Func::$Status[$value['category']][$value['status']]:'暂无';?></span> </div>
      </li>
      <li class="jxiang">
        <div class="revert_l"> <span>详情</span> </div>
        <div class="revert_r xiang"> <span><?php echo isset($value['content'])?$value['content']:'暂无';?></span> </div>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if(Yii::$app->request->get('type') == is_numeric(1)){ ?>
<footer>
<div class="apply">
<div class="apply_sq">
      <?php if($progress_status == 3 || $progress_status == 4){?>
             <a href="javascript:void(0);" style="background:#0065b3;">新增进度</a>
	  <?php }else{ ?>
			 <a href="<?php echo yii\helpers\Url::toRoute(['/releases/speedo','category'=>$category,'id'=>$id]);?>" style="background:#0065b3;">新增进度</a>
	   <?php } ?>
</div>
</div>
</footer>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.select-area .select-value').each(function(){
            if( $(this).next("select").find("option:selected").length != 0 ){
                $(this).text( $(this).next("select").find("option:selected").text() );
            }
        })
        $(".select-area select").change(function(){
            var value = $(this).find("option:selected").text();
            $(this).parent(".select-area").find(".select-value").text(value);
        });

    $('.codes').change(function(){
        var audit = $(this).val();
        var id    = $(this).prev().attr('data-id');
        var category = $(this).prev().attr('data-category');
        $.ajax({
            url:"<?php echo yii\helpers\Url::toRoute('/usercenter/code')?>",
            type:'post',
            data:{id:id,audit:audit,category:category},
            dataType:'json',
            success:function(html) {
                if(html == 1){
                    $('input[name=case]').attr('value','');
                }else{
                    $('input[name=case]').attr('value',html);
                }
            }
        })
    })
    })
</script>


