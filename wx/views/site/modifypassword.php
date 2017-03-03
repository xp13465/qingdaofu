<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>$type==2?"设置密码":'修改密码','gohtml'=>''])?>
<section>
    <?php \yii\widgets\ActiveForm::begin(['id'=>'modify'])?>
	<?= Html::input('hidden','type',$type)?>
    <div class="phone_num">
		<?php if($type!=2){?>
        <div class="yz">
            <?= Html::input('password','original_pass','',['placeholder'=>'请输入原始密码','class'=>'input01'])?>
        </div>
		<?php }?>
        <div class="password">
            <?= Html::input('password','new_pass','',['placeholder'=>$type==2?"输入密码":'输入新密码'])?>
            <?= Html::tag('a','显示密码',['href'=>'javascript:void(0);','class'=>'display'])?>
        </div>
    </div>
    <div class="login">
        <?= Html::tag('a',$type==2?"保存":'修改',['href'=>'javascript:void(0)','class'=>'denglu change'])?>
    </div>
    <?php \yii\widgets\ActiveForm::end() ?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        new_pass = 0 ;
        asss = 0;
        $('.display').click(function () {
           if($(this).prev('input:password').attr('type') == 'password'){
                $('input[name="new_pass"]').attr('type','text');
                $(this).html('隐藏密码');
            }else{
                $('input[name="new_pass"]').attr('type','password');
                $(this).html('显示密码');
            }
        });
        $('.change').click(function(){
			var index = layer.load(1)
            var url ="<?php yii\helpers\Url::toRoute('/site/modify')?>";
            $.post(url,$('#modify').serialize(),function(json){
				layer.close(index)
                if(json.code == '0000'){
                    layer.msg(json.msg,{},function(){ window.location = "<?php echo yii\helpers\Url::toRoute('/user/info');?>";});
                }else{
                    layer.msg(json.msg);
                }
            },'json');
        })

    })
</script>
