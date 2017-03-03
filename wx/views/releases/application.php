<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'申请延期','gohtml'=>'<a href="javascript:void(0)" class="application">保存</a>'])?>
<style>
.xiang{float:right;width: 100%;min-height: 50px;max-height: 500px;margin-left: auto;margin-right: auto;outline: 0;font-size: 16px;word-wrap: break-word;overflow-x: hidden;overflow-y: auto;}
.jxiang{width: 100%;max-width:640px; min-height: 50px; max-height: 500px;margin-left: auto; margin-right: auto;outline: 0;font-size: 16px; word-wrap: break-word; overflow-x: hidden;overflow-y: auto;}
</style>
<section>
<?= Html::hiddenInput('id',Yii::$app->request->get('id'))?>
<?= Html::hiddenInput('category',Yii::$app->request->get('category'))?>
  <ul class="add_xi">
    <li> 
    <span>延期天数</span>
     <input type="text" name="day" value="" placeholder="请输入希望延期天数">
      <i style="color:#0065b3;">天</i>
    </li>
    <li class="jxiang"> 
       <div class="xiang" contenteditable="true"></div> 
    </li>
  </ul>

</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.application').click(function(){
            var dalay_reason = $('.xiang').html();
            var day = $('input[name=day]').val();
            var id  = $('input[name=id]:hidden').val();
            var category = $('input[name=category]:hidden').val();
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/releases/delayapply')?>",
                type:'post',
                data:{dalay_reason:dalay_reason,day:day,id:id,category:category},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute(['/releases/index','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>";
                    }else{
                        alert(json.msg);
                    }
                }
            })

        })
    })
</script>
