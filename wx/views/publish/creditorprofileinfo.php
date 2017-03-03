<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<?=wxHeaderWidget::widget(['title'=>'债权人信息','gohtml'=>''])?>

<?php
if($data&&!empty($data)){
foreach($data as $k=>$valueArr){
?>
<section>
    <ul class="add_xi add_xi01">
        <li>
            <span>姓名</span>
            <span><?php echo isset($valueArr['creditorname'])?$valueArr['creditorname']:'';?></span>
            <a href="<?php echo \yii\helpers\Url::toRoute(['/publish/creditorprofile','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category'),'keynum'=>$k]);?>" class="compile">编辑</a>
        </li>
        <li>
            <span>联系方式</span>
            <span><a href="tel:4008557022"><?php echo isset($valueArr['creditormobile'])?$valueArr['creditormobile']:'';?></a></span>
        </li>
        <li class="assress_li">
            <span class="address_l">联系地址</span>
            <span class="address_r"><?php echo isset($valueArr['creditoraddress'])?$valueArr['creditoraddress']:'';?></span>
        </li>
        <li>
            <span>证件号</span>
            <span><?php echo isset($valueArr['creditorcardcode'])?$valueArr['creditorcardcode']:'';?></span>
        </li>
        <?php if($valueArr['creditorcardimage']){ ?>
        <li class="add_file01">
            <?php
                $images = isset($valueArr['creditorcardimage'])?$valueArr['creditorcardimage']:'';
                $imgArr = explode(',',$images);
                foreach($imgArr as $key=>$value){
                    echo "<span><img class='imagePreview' src = '".Yii::$app->params['wx'].trim($value,"'")."' style='height:50px;width:50px; display:inline-block;'/></span>";
                }
            ?>
        </li>
        <?php } ?>
    </ul>
</section>
<?php
}
}
?>
<script type="text/javascript">
    $('.icon-back').click(function () {
        window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/editcollection','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>";
    });
	
		$(document).on('click','.imagePreview',function(){
                var img = "<?php echo isset($valueArr['creditorcardimage'])?$valueArr['creditorcardimage']:''; ?>";
				var arr=img.split(',');
				var array = [];
				 $.each(arr,function(key,value){
                       values = value.substring(1,value.length-1);
					   var wx = "<?php echo Yii::$app->params['wx'];?>";
                        array.push(wx+values);
                    });
					var piclist = array;
                   WeixinJSBridge.invoke('imagePreview', {  
                            'current' : piclist[0],  
					        'urls' : piclist  
				       });
			
                });
</script>

<div class="continue_add">
    <a href="<?php echo \yii\helpers\Url::toRoute(['/publish/creditorprofile','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>">继续添加</a>
</div>
