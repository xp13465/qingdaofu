<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<style>
    .photo_11 .phc :hover{background:#4A4A4A;opacity:0.5;z-index:12}
</style>

<?=wxHeaderWidget::widget(['title'=>'填写评价','gohtml'=>''])?>
<section>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'evaluation']);?>
    <?php echo \yii\helpers\Html::hiddenInput('id',Yii::$app->request->get('id'))?>
    <?php echo \yii\helpers\Html::hiddenInput('category',Yii::$app->request->get('category'))?>
    <?php echo \yii\helpers\Html::hiddenInput('type',Yii::$app->request->get('type',0))?>
    <div class="pj_text">
        <!--<span>您的融资单号<?php echo isset($product)&&$product!=''?$product:''?>已结结束,感谢您对平台的信任,请留下您的评价。</span>--->
    </div>
    <div class="pj_star">
        <ul>
            <li>
                <span>真实性</span>
                <p>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <?php echo \yii\helpers\Html::hiddenInput('serviceattitude')?>
                </p>
            </li>
            <li>
                <span>配合度</span>
                <p>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <?php echo \yii\helpers\Html::hiddenInput('professionalknowledge')?>
                </p>
            </li>
            <li>
                <span>响应度</span>
                <p>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <?php echo \yii\helpers\Html::hiddenInput('workefficiency')?>
                </p>
            </li>
        </ul>
    </div>
    <div class="delay01">
        <textarea name="content" placeholder='请输入您的真实感受,对接单方的帮助改进很大噢!不少于5个字'></textarea>
    </div>
    <div class="add_tu">
        <div class="pic">
            <p>添加图片</p> 
            <span class="add_tu_02"></span>
            <div class="file">
                <?php echo \yii\helpers\Html::hiddenInput('cardimg');?>
            </div>
        </div>
        <div class="img-box">

        </div>
    </div>
    <!---<div class="nimin">
        <span>匿名评价</span>
        <input type="checkbox" name="isHide" value="1"/>
    </div>--->
    <?php \yii\widgets\ActiveForm::end();?>
</section>
<?php if($creditor >= 3){ ?>
    <footer>
        <div class="evaluate case01">
            <a href="javascript:void(0);">提交评价</a>
        </div>
    </footer>
<?php }else{ ?>
    <footer>
        <div class="evaluate">
            <a href="javascript:void(0);" class="sbmit">提交评价</a>
        </div>
    </footer>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.pj_star ul li p span').click(function(){
            $(this).addClass('current');
            $(this).prevAll('span').addClass('current');
            $(this).siblings('input').val($(this).index()+1);
            $(this).nextAll('span').removeClass('current');
        })
        $('.pj_star ul li p span').dblclick(function(){
            $(this).parent('p').find('span').removeClass("current");
            $(this).siblings('input').val('');
        });


        var w = document.body.clientWidth;
        var h = document.body.clientHeight;
        $(document).delegate('.add_tu_02', 'click', function () {
            var name = $(this).next().children('input:hidden').attr("name");
            $.msgbox({
                closeImg: '/images/close.png',
                async: false,
                width: w * 0.7,
                height: h * 0.6,
                title: '请选择图片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/uploadimage"])?>/?type=" + name+'&i='+2,
                type: 'ajax',
                onClose: function(){
                    $('.img-box').children().each(function(){
                        $(this).hover(function(){
                            $(this).children().children().first().toggle();
                        });
                    });
                }
            });
        });
        $(document).delegate('.photo', 'click', function () {
            var typeName = $(this).prev().children().children('input:hidden').attr("name");
            var name = $(this).prev().children().children('input:hidden').val();
            $.msgbox({
                closeImg: '/images/close.png',
                async: false,
                width: w * 0.7,
                height: h * 0.6,
                title: '显示照片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/viewimages"])?>/?name=" + name + "&typeName=" + typeName,
                type: 'ajax',
            });
        });

        $('.sbmit').click(function(){
            var id = $('input[name=id]:hidden').val();
            var category = $('input[name=category]:hidden').val();
			var index = layer.load(1, {
		            shade: [0.4,'#fff'] //0.1透明度的白色背景
		         });
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/releases/addevalus')?>",
                type:'post',
                data:$('#evaluation').serialize(),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert(json.msg);
						layer.close(index);
                        window.location = "<?php echo yii\helpers\Url::toRoute(['/releases/index'])?>?id="+id+"&category="+category;
                    }else{
						layer.close(index);
                        alert(json.msg);
                    }
                }
            })


        });

    })
</script>
