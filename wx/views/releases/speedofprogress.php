<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<style>
.xiang{float:right;width: 75%;min-height: 50px;max-height: 500px;margin-left: auto;margin-right: auto;outline: 0;font-size: 16px;word-wrap: break-word;overflow-x: hidden;overflow-y: auto;}
.jxiang{width: 100%;max-width:640px; min-height: 50px; max-height: 500px;margin-left: auto; margin-right: auto;outline: 0;font-size: 16px; word-wrap: break-word; overflow-x: hidden;overflow-y: auto;}
b {
	vertical-align: middle;
	margin-left: 5px;
	margin-right: 5px;
	display: inline-block;
	width: 10px;
	height: 10px;
	vertical-align: middle;
	border-left: 1px solid #A2A2A2;
	border-bottom: 1px solid #A2A2A2;
	-webkit-transform: rotate(230deg);
	transform: rotate(230deg);
	box-sizing: border-box;
}
.add_xi li{border-bottom:1px solid #0065b3;}
</style>
<?=wxHeaderWidget::widget(['title'=>'新增进度','gohtml'=>'<a href="javascript:void(0);" class="speed">保存</a>'])?>
<section>
<?php \yii\widgets\ActiveForm::begin(['id'=>'speedo']);?>
    <?php if(!empty($pro)){ ?>
    <?php echo Html::input('hidden','product_id',$pro['id']);?>
    <?php echo Html::input('hidden','category',$pro['category']);?>
    <?php } ?>
  <ul class="add_xi">
    
    <?php if(!empty($pro) && !empty($uid)) { ?>
       <?php if($pro['uid'] == $uid || in_array($pro['category'],[1,2])){?>
       <?php echo "";?>
    <?php }else{ ?>
    <li> 
    <span>案号类型</span>
	<?php echo Html::dropDownList('audit','',frontend\services\Func::$case,['style'=>'width:70%;left:35%;height:50px;-webkit-appearance:none;'])?>
      <b></b>
    </li>
    <li>	
    <span>案号</span>
	<?php echo Html::input('text','case','',['placeholder'=>"请输入案号"])?>
    </li>
	<?php } ?>
   <?php } ?>
    <li> 
    <span>处置类型</span>
	   <?php if(Yii::$app->request->get('category') == 1){ ?>
                            <!-- 融资 -->
           <?php echo html::dropDownList('finance_collection','',CreditorProduct::$finance,['style'=>'width:70%;left:35%;height:50px;-webkit-appearance:none;']);?>
       <?php }else if(Yii::$app->request->get('category') == 2){ ?>
                            <!-- 清收 -->
           <?php echo html::dropDownList('finance_collection','',CreditorProduct::$collection,['style'=>'width:70%;left:35%;height:50px;-webkit-appearance:none;']);?>
      <?php }else{ ?>
                            <!-- 诉讼 -->
           <?php echo html::dropDownList('finance_collection','',CreditorProduct::$litigations,['style'=>'width:70%;left:35%;height:50px;-webkit-appearance:none;']);?>
      <?php }?>
	
      <b></b>
    </li>
    <li class="jxiang"> 
    <span>进度详情</span>
	 <?php //echo Html::textarea('content','',['placeholder'=>'请填写进度','cols'=>'30','rows'=>'10'])?>
       <div class="xiang" contenteditable="true"></div> 
    </li>
  </ul>
<?php \yii\widgets\ActiveForm::end();?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.style').click(function(){
            $(this).next().slideToggle();
        })
        $('.speed').click(function(){
            var radio       = $('select[name=finance_collection]').val();
            var id          = $('input[name=product_id]:hidden').val();
            var category    = $('input[name=category]:hidden').val();
            var audit       = $('select[name=audit]').val();
            var cases       = $('input[name=case]').val();
			var content = $('.xiang').html();
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/releases/spee');?>",
                type:'post',
                data:{radio:radio,id:id,category:category,audit:audit,case:cases,content:content},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
						location.href = "<?php echo yii\helpers\Url::toRoute('/usercenter/speed')?>?id="+id+'&category='+category;
                    }else{
                        alert(json.msg);
                    }
                }
            })
        })
        /*$(".style .select-value").each(function(){
            if( $(this).next("select").find("option:selected").length != 0 ){
                $(this).text( $(this).next("select").find("option:selected").text() );
            }
        });
        $(".style select").change(function(){
            var value = $(this).find("option:selected").text();
            $(this).parent(".style").find(".select-value").text(value);
        });*/
		
        $(".select-area .select-value").each(function(){
            if( $(this).next("select").find("option:selected").length != 0 ){
                $(this).text( $(this).next("select").find("option:selected").text() );
            }
        });
        $(".select-area select").change(function(){
            var value = $(this).find("option:selected").text();
            $(this).parent(".select-area").find(".select-value").text(value);
        });
	


    })
</script>