<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<style>
    .photo_11 .phc :hover{background:#4A4A4A;opacity:0.5;z-index:12}
	.photo_11 span{margin-top:0px;}
	.add span {
		background: url(/images/icon.png) no-repeat -288px -188px;
		    background-size: 400px 315px;
			-webkit-background-size: 400px 315px
	}
</style>
<?=wxHeaderWidget::widget(['title'=>'意见反馈','gohtml'=>''])?>
<section>
    <div class="feedback">
        <div class="fank">
            <textarea name="casedesc" id="" placeholder="请详细描述您的问题或建议,您的反馈是我们前进最大的动力"></textarea>
        </div>
        <div class="add">
            <span class="picture"></span>
            <div class="file">
                <?php echo \yii\helpers\Html::hiddenInput('picture');?>
            </div>
        </div>
        <div class="img-box" style="margin: -65px 0px 87px 48px;"></div>
    </div>
    <div class="mail">
        <input type="text" name="mobile" placeholder="手机号码或邮箱 (方便我们联系您)"/>
    </div>
    <div class="submit">
        <a href="javascript:void(0)" class="submits">提交</a>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        $('.submits').click(function(){
            var casedesc = $('textarea[name=casedesc]').val();
            var mobile   = $('input[name=mobile]').val();
            var picture  = $('input[name=picture]:hidden').val();
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/aboutus/opiniondata')?>",
                type:'post',
                data:{casedesc:casedesc,mobile:mobile,picture:picture},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert('提交成功');
                        window.location = "<?php echo yii\helpers\Url::toRoute('/usercenter/index')?>";
                    }else{
                        alert(json.msg);
                    }
                }
            })
        })
        var w = document.body.clientWidth;
        var h = document.body.clientHeight;
        // $(document).delegate('.picture', 'click', function () {
        $('.picture').on( 'click', function () {
            var name = $(this).next().children('input:hidden').attr("name");
            $.msgbox({
                closeImg: '/images/close.png',
                async: false,
                width: w * 0.7,
                height: h * 0.9,
                title: '请选择图片',
                content: "<?php echo \yii\helpers\Url::toRoute(["/common/uploadimage"])?>/?type=" + name+'&i='+3,
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
    })
</script>