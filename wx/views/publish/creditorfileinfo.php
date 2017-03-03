<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<header>
    <div class="cm-header">
        <span class="icon-back" ></span>
        <i>上传债权文件</i>
        <a href="javascript:void(0)" class="save">保存</a>
    </div>
</header>

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
                <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgnotarization'],['id'=>'imgnotarization']);?>
                <span class="file_look">查看</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt02"></span>
                    <span>借款合同</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgcontract');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgcontract'],['id'=>'imgcontract']);?>
                    <span class="file_look">查看</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt03"></span>
                    <span>他项权证</span>

                </div>

                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgcreditor');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgcreditor'],['id'=>'imgcreditor']);?>
                    <span class="file_look">查看</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt04"></span>
                    <span>付款凭证</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgpick');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgpick'],['id'=>'imgpick']);?>
                    <span class="file_look">查看</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt05"></span>
                    <span>收据</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgshouju');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgshouju'],['id'=>'imgshouju']);?>
                    <span class="file_look">查看</span>
                </form>
            </li>
            <li>
                <div class="file_l">
                    <span class="debt06"></span>
                    <span>备注</span>
                </div>
                <form style="float: right">
                    <?php echo \yii\helpers\Html::hiddenInput('name','imgbenjin');?>
                    <?php echo \yii\helpers\Html::hiddenInput('str',$data['imgbenjin'],['id'=>'imgbenjin']);?>
                    <span class="file_look">查看</span>
                </form>
            </li>
        </ul>
    </div>
</section>

<script>
    $(function() {
        $(document).delegate('.save','click',function() {
            $.ajax({
                url:"<?php echo \yii\helpers\Url::toRoute(['/publish/creditorfile','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>",
                type:'post',
                data:getCookie(getCookie('publishCookieName')+'creditorfile'),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert("保存成功");
                        delCookie(getCookie('publishCookieName')+'creditorfile');
                        if(getCookie('fromWhere') == 'editcollection'){
                            window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/editcollection','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>";
                        }else if(getCookie('fromWhere') == 'editligitation'){
                            window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/editligitation','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>";
                        }
                    }else{
                        alert(json.msg);
                    }
                }
            });

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

        $('.icon-back').click(function () {
            window.location = "<?php echo \yii\helpers\Url::toRoute(['/publish/editcollection','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>";
        });
    });
</script>
