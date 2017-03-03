<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<?=wxHeaderWidget::widget(['title'=>'上传债权文件','gohtml'=>'<a href="javascript:void(0)" class="save">保存</a>'])
?>


<section>
    <div class="debt_file">
        <ul>
            <li>
                <div class="file_l">
                    <span class="debt01"></span>
                    <span>公证书</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgnotarization');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgnotarization']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt02"></span>
                    <span>借款合同</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgcontract');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgcontract']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt03"></span>
                    <span>他项权证</span>

                </div>

                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgcreditor');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgcreditor']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt04"></span>
                    <span>付款凭证</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgpick');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgpick']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt05"></span>
                    <span>收据</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgshouju');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgshouju']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt06"></span>
                    <span>备注</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgbenjin');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str','',['id'=>'imgbenjin']);?>
                    <span class="file_look">上传</span>
                </form>
            </li>
        </ul>
    </div>
</section>

<script>
    $(function() {
        $(document).delegate('.save','click',function() {
            if(getCookie('fromWhere') == 'collection')
                window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/collection'])?>";
            else
                window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/ligitation'])?>";
        });
        $('.file_look').click(function(){
            $(this).parent('form').attr('action',"<?php echo \yii\helpers\Url::toRoute('/common/gallery');?>");
            $(this).parent('form').attr('method',"post");
            $(this).parent('form').submit();
        });

        function setDefaultValue(){
            if(getCookie(getCookie('publishCookieName')+'creditorfile') == null || getCookie(getCookie('publishCookieName')+'creditorfile') == '' ){
                return false;
            }
            var all = formUnserialize(getCookie(getCookie('publishCookieName')+'creditorfile'));

            for(cname in all){
                var v = decodeURIComponent(all[cname]);
                $('input[id="'+cname+'"]').val(v);
            }
        }
        setDefaultValue();
    });
</script>
