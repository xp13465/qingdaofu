<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .renz{color: #fff;background-color: #337ab7;   border: 1px solid transparent;border-radius: 4px;padding: 2px 6px;}
</style>
<!-- 引入百度编辑器 -->
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <?php if($certifi['category'] ==1){ ?>
                <form class="cerzero">
                    <thead class="personal">
                    <td colspan="2" style="text-align: center;">
                        <span>个人</span>
                    </td>
                    <tr>
                        <td align="right">姓名：</td>
                        <td><?php echo html::input('text','name',$certifi->name)?><i name="name"></i></td>
                    </tr>
                    <tr>
                        <td align="right">身份证：</td>
                        <td><?php echo html::input('text','cardno',$certifi->cardno)?><i name="cardno"></i></td>

                    </tr>
                    <tr>
                        <td align="right">图片：</td>
                        <td><span class="schuan uploadSpan">上传</span>
                            <?php echo \yii\helpers\Html::hiddenInput('cardimg[1]');?>
                            <span ><img width="60px;" height="60px;" src="<?php echo substr($certifi->cardimg,1,-1)?>"/> </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">邮箱：</td>
                        <td><?php echo html::input('text','email',$certifi->email)?></td>
                    </tr>
                    <tr>
                        <td align="right">经典案例：</td>
                        <td><?php echo html::textarea('casedesc',$certifi->casedesc)?><i name="casedesc"></i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <?php echo \yii\helpers\Html::hiddenInput('types',0);?>
                            <?php echo \yii\helpers\Html::hiddenInput('userId',yii::$app->request->get('id'));?>
                            <a href="javascript:void(0)" class="renz">认证</a>
                        </td>
                    </tr>
                    </thead>
                </form>
                <?php }else if($certifi['category'] == 2){?>

                <form class="cerone">
                    <thead class="personal">
                    <td colspan="2" style="text-align: center;">
                        <span>律所</span>
                    </td>
                    <tr>
                        <td align="right">律所名称：</td>
                        <td><?php echo html::input('text','name',$certifi->name)?><i name="name"></i></td>
                    </tr>
                    <tr>
                        <td align="right">执业证号：</td>
                        <td><?php echo html::input('text','cardno',$certifi->cardno)?><i name="cardno"></i></td>
                    </tr>
                    <tr>
                        <td align="right">图片：</td>
                        <td><span class="schuan uploadSpan">上传</span>
                            <?php echo \yii\helpers\Html::hiddenInput('cardimg[1]');?>
                            <span ><img width="60px;" height="60px;" src="<?php echo substr($certifi->cardimg,1,-1)?>"/> </span>
                            <i></i>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">联系人：</td>
                        <td><?php echo html::input('text','contact',$certifi->contact)?><i name="contact"></i></td>
                    </tr>
                    <tr>
                        <td align="right">联系方式：</td>
                        <td><?php echo html::input('text','mobile',$certifi->mobile)?><i name="mobile"></i></td>
                    </tr>
                    <tr>
                        <td align="right">邮箱：</td>
                        <td><?php echo html::input('text','email',$certifi->email)?></td>
                    </tr>
                    <tr>
                        <td align="right">经典案例：</td>
                        <td><?php echo html::textarea('casedesc',$certifi->casedesc)?><i name="casedesc"></i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <?php echo \yii\helpers\Html::hiddenInput('types',1);?>
                            <?php echo \yii\helpers\Html::hiddenInput('userId',yii::$app->request->get('id'));?>
                            <a href="javascript:void(0)" class="renz">认证</a>
                        </td>
                    </tr>
                    </thead>
                </form>
                <?php }else if($certifi['category'] == 3){?>
                <form class="certwo">
                    <thead class="personal">
                    <td colspan="2" style="text-align: center;">
                        <span>公司</span>
                    </td>
                    <tr>
                        <td align="right">企业名称：</td>
                        <td><?php echo html::input('text','name',$certifi->name)?><i name="name"></i></td>
                    </tr>
                    <tr>
                        <td align="right">营业执照号：</td>
                        <td><?php echo html::input('text','cardno',$certifi->cardno)?><i name="cardno"></i></td>
                    </tr>
                    <tr>
                        <td align="right">图片：</td>
                        <td><span class="schuan uploadSpan">上传</span>
                            <?php echo \yii\helpers\Html::hiddenInput('cardimg[1]');?>
                            <span ><img width="60px;" height="60px;" src="<?php echo substr($certifi->cardimg,1,-1) ?>"/> </span>
                        </td><i></i>
                    </tr>
                    <tr>
                        <td align="right">联系人：</td>
                        <td><?php echo html::input('text','contact',$certifi->contact)?><i name="contact"></i></td>
                    </tr>
                    <tr>
                        <td align="right">联系方式：</td>
                        <td><?php echo html::input('text','mobile',$certifi->mobile)?><i name="mobile"></i></td>
                    </tr>
                    <tr>
                        <td align="right">企业邮箱：</td>
                        <td><?php echo html::input('text','email',$certifi->email)?></td>
                    </tr>
                    <tr>
                        <td align="right">企业经营地址：</td>
                        <td><?php echo html::input('text','address',$certifi->address)?></td>
                    </tr>
                    <tr>
                        <td align="right">公司网站：</td>
                        <td><?php echo html::input('text','enterprisewebsite',$certifi->enterprisewebsite)?></td>
                    </tr>
                    <tr>
                        <td align="right">经典案例：</td>
                        <td><?php echo html::textarea('casedesc',$certifi->casedesc)?><i name="casedesc"></i></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <?php echo \yii\helpers\Html::hiddenInput('types',2);?>
                            <?php echo \yii\helpers\Html::hiddenInput('userId',yii::$app->request->get('id'));?>
                            <a href="javascript:void(0)" class="renz">认证</a>
                        </td>
                    </tr>
                    </thead>

                </form>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function printError(types){
        var validateCheck = {
            isEmail:function(email){
                var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                return myreg.test(email);
            },
            isMobile:function(mobile){
                var myreg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
                return myreg.test(mobile);
            },
            isTel:function(mobile){
                var myreg = /^\d{1,}$/;
                return myreg.test(mobile);
            },
            isNet:function(net){
                var myreg = /^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/;
                return !net||myreg.test(net);
            },
            isZhiyezhenghao:function(zhiyezhenghao){
                var myreg = /^\d{17}$/;
                return myreg.test(zhiyezhenghao);
            },
            isZhizhao:function(zhizhao){
                var myreg = /^\d{15}$/;
                return myreg.test(zhizhao);
            },
        };
        var name = $('input[name=name]').val();
        var cardno = $('input[name=cardno]').val();
        var casedesc = $('textarea[name=casedesc]').val();
        var mobile = $('input[name=mobile]').val();
        var contact = $('input[name=contact]').val();
        if(types == 1){
            var vildateFlag = true;
            if(!name){$('i[name=name1]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name1]').html('')};
           // if(!casedesc){$('i[name=casedesc1]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc1]').html('')};
            if(!idCardNoUtil.checkIdCardNo(cardno)){$('i[name=cardno1]').html('身份证号码错误');vildateFlag = false;}else{$('i[name=cardno1]').html('')};
            if (!vildateFlag) {return;}
            var url = "<?php echo yii\helpers\Url::to(['/certification/modify','id'=>$certifi->uid])?>";
            $.post(url,$('.cerzero').serialize(),function(v){
                if(v == 1){
                    window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                }else{
                    alert('修改失败');
                }
            },'json');
        }else if(types == 2){
            var vildateFlag = true;
            if(!name){$('i[name=name]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name]').html('')};
            if(!contact){$('i[name=contact]').html('联系人必填');vildateFlag = false;}else{$('i[name=contact]').html('');}
            if(!validateCheck.isTel(mobile)){$('i[name=mobile]').html('联系人手机必填');vildateFlag = false;}else{$('i[name=mobile]').html('');}
           // if(!casedesc){$('i[name=casedesc]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc]').html('')};
            if(!validateCheck.isZhiyezhenghao(cardno)){$('i[name=cardno]').html('执照号错误');vildateFlag = false;}else{$('i[name=cardno]').html('')};
            if (!vildateFlag) {return;}
            var url = "<?php echo yii\helpers\Url::to(['/certification/modify','id'=>$certifi->uid])?>";
            $.post(url,$('.cerone').serialize(),function(v){
                if(v == 1){
                    window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                }else{
                    alert('修改失败');
                }
            },'json');
        }else if(types == 3){
            var vildateFlag = true;
            if(!name){$('i[name=name]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name]').html('')};
            if(!contact){$('i[name=contact]').html('联系人必填');vildateFlag = false;}else{$('i[name=contact]').html('');}
            if(!validateCheck.isTel(mobile)){$('i[name=mobile]').html('联系人手机必填');vildateFlag = false;}else{$('i[name=mobile]').html('');}
           // if(!casedesc){$('i[name=casedesc]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc3]').html('')};
            if(!validateCheck.isZhizhao(cardno)){$('i[name=cardno]').html('执照号错误');vildateFlag = false;}else{$('i[name=cardno]').html('')};
            if (!vildateFlag) {return;}
            var url = "<?php echo yii\helpers\Url::to(['/certification/modify','id'=>$certifi->uid])?>";
            $.post(url,$('.certwo').serialize(),function(v){
                if(v == 1){
                    window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                }else{
                   alert('修改失败');
                }
            },'json');
        }
    }


    $('.renz').click(function(){
        var types = "<?php echo $certifi->category ?>";
        if(!printError(types))return false;
    })

    $(document).delegate('.uploadSpan','click',function(){
        var name = $(this).next('input:hidden').attr("name");
        $.msgbox({
            closeImg: '/images/close.png',
            async: false,
            height:530,
            width:630,
            title:'请选择图片',
            content:"<?php echo \yii\helpers\Url::to(["/certification/uploadsimgsingle"])?>/?type="+name,
            type:'ajax'
        });
    });

</script>
