<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .current{background-color: #0065b3;color:#fff;}
    .renz{color: #fff;background-color: #337ab7;   border: 1px solid transparent;border-radius: 4px;padding: 2px 6px;}
</style>
<!-- 引入百度编辑器 -->
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <span onclick='categorys(this,0);' style="margin-right:40px;" class="current" id="0">个人</span><em style="margin-right:40px;">|</em>
                        <span onclick='categorys(this,1);' id="1">律所</span><em style="margin-left:40px;">|</em>
                        <span onclick='categorys(this,2);' style="margin-left:40px;" id="2">公司</span>
                    </td>
                </tr>
                </thead>
            <form class="cerzero">
                <thead class="personal">
                    <tr>
                        <td align="right">姓名：</td>
                        <td><?php echo html::input('text','name','')?><i name="name1"></i></td>
                    </tr>
                    <tr>
                        <td align="right">身份证：</td>
                        <td><?php echo html::input('text','cardno','')?><i name="cardno1"></i></td>

                    </tr>
                    <tr>
                        <td align="right">图片：</td>
                        <td><span class="schuan uploadSpan">上传</span>
                            <?php echo \yii\helpers\Html::hiddenInput('cardimg[1]');?>
                            <span style="display: none"><img width="60px;" height="60px;" src=""/> </span>
                            <i></i>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">邮箱：</td>
                        <td><?php echo html::input('text','email','')?></td>
                    </tr>
                    <tr>
                        <td align="right">经典案例：</td>
                        <td><?php echo html::textarea('casedesc')?><i name="casedesc1"></i></td>
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

            <form class="cerone">
                   <thead class="personal">
                   <tr>
                       <td align="right">律所名称：</td>
                       <td><?php echo html::input('text','name2','')?><i name="name2"></i></td>
                   </tr>
                   <tr>
                       <td align="right">执业证号：</td>
                       <td><?php echo html::input('text','cardno2','')?><i name="cardno2"></i></td>
                   </tr>
                   <tr>
                       <td align="right">图片：</td>
                       <td><span class="schuan uploadSpan">上传</span>
                           <?php echo \yii\helpers\Html::hiddenInput('cardimg[2]');?>
                           <span style="display: none"><img width="60px;" height="60px;" src=""/></span>
                           <i></i>
                       </td>
                   </tr>
                   <tr>
                       <td align="right">联系人：</td>
                       <td><?php echo html::input('text','contact2','')?><i name="contact2"></i></td>
                   </tr>
                   <tr>
                       <td align="right">联系方式：</td>
                       <td><?php echo html::input('text','mobile2','')?><i name="mobile2"></i></td>
                   </tr>
                   <tr>
                       <td align="right">邮箱：</td>
                       <td><?php echo html::input('text','email2','')?></td>
                   </tr>
                   <tr>
                       <td align="right">经典案例：</td>
                       <td><?php echo html::textarea('casedesc2')?><i name="casedesc2"></i></td>
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

            <form class="certwo">
               <thead class="personal">
                   <tr>
                       <td align="right">企业名称：</td>
                       <td><?php echo html::input('text','name3','')?><i name="name3"></i></td>
                   </tr>
                   <tr>
                       <td align="right">营业执照号：</td>
                       <td><?php echo html::input('text','cardno3','')?><i name="cardno3"></i></td>
                   </tr>
                   <tr>
                       <td align="right">图片：</td>
                       <td><span class="schuan uploadSpan">上传</span>
                           <?php echo \yii\helpers\Html::hiddenInput('cardimg[3]');?>
                           <span style="display: none"><img width="60px;" height="60px;" src=""/> </span>
                       </td><i></i>
                   </tr>
                   <tr>
                       <td align="right">联系人：</td>
                       <td><?php echo html::input('text','contact3','')?><i name="contact3"></i></td>
                   </tr>
                   <tr>
                       <td align="right">联系方式：</td>
                       <td><?php echo html::input('text','mobile3','')?><i name="mobile3"></i></td>
                   </tr>
                   <tr>
                       <td align="right">企业邮箱：</td>
                       <td><?php echo html::input('text','email3','')?></td>
                   </tr>
                   <tr>
                       <td align="right">企业经营地址：</td>
                       <td><?php echo html::input('text','address3','')?></td>
                   </tr>
                   <tr>
                       <td align="right">公司网站：</td>
                       <td><?php echo html::input('text','enterprisewebsite3','')?></td>
                   </tr>
                   <tr>
                       <td align="right">经典案例：</td>
                       <td><?php echo html::textarea('casedesc3')?><i name="casedesc3"></i></td>
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
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.personal').hide();
        categorys('',0);
    });
    function categorys(ob,num){
        $('.personal').hide();
        $('.personal').eq(num).show();
       $(ob).addClass('current').siblings().removeClass('current');
    }

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

               if(types == 0){
                   var name = $('input[name=name]').val();
                   var cardno = $('input[name=cardno]').val();
                   var casedesc = $('textarea[name=casedesc]').val();
                   var vildateFlag = true;
                   if(!name){$('i[name=name1]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name1]').html('')};
                  // if(!casedesc){$('i[name=casedesc1]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc1]').html('')};
                   if(!idCardNoUtil.checkIdCardNo(cardno)){$('i[name=cardno1]').html('身份证号码错误');vildateFlag = false;}else{$('i[name=cardno1]').html('')};
                   if (!vildateFlag) {return;}
                   var url = "<?php echo yii\helpers\Url::to('/certification/personal')?>";
                   $.post(url,$('.cerzero').serialize(),function(json){
                       if(json.code != '0000'){
                           alert(json.msg);
                       }else{
                           window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                       }
                   },'json');
               }else if(types == 1){
                   var name = $('input[name=name2]').val();
                   var cardno = $('input[name=cardno2]').val();
                   var mobile = $('input[name=mobile2]').val();
                   var casedesc = $('textarea[name=casedesc2]').val();
                   var contact = $('input[name=contact2]').val();
                   var vildateFlag = true;
                   if(!name){$('i[name=name2]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name2]').html('')};
                   if(!contact){$('i[name=contact2]').html('联系人必填');vildateFlag = false;}else{$('i[name=contact2]').html('');}
                   if(!validateCheck.isTel(mobile)){$('i[name=mobile2]').html('联系人手机必填');vildateFlag = false;}else{$('i[name=mobile2]').html('');}
                  // if(!casedesc){$('i[name=casedesc2]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc2]').html('')};
                   if(!validateCheck.isZhiyezhenghao(cardno)){$('i[name=cardno2]').html('执照号错误');vildateFlag = false;}else{$('i[name=cardno2]').html('')};
                   if (!vildateFlag) {return;}
                   var url = "<?php echo yii\helpers\Url::to('/certification/lawyer')?>";
                   $.post(url,$('.cerone').serialize(),function(json){
                       if(json.code != '0000'){
                           alert(json.msg);
                       }else{
                           window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                       }
                   },'json');
               }else if(types == 2){
                   var name = $('input[name=name3]').val();
                   var cardno = $('input[name=cardno3]').val();
                   var mobile = $('input[name=mobile3]').val();
                   var casedesc = $('textarea[name=casedesc3]').val();
                   var contact = $('input[name=contact3]').val();
                   var vildateFlag = true;
                   if(!name){$('i[name=name3]').html('用户姓名必填');vildateFlag = false;}else{$('i[name=name3]').html('')};
                   if(!contact){$('i[name=contact3]').html('联系人必填');vildateFlag = false;}else{$('i[name=contact3]').html('');}
                   if(!validateCheck.isTel(mobile)){$('i[name=mobile3]').html('联系人手机必填');vildateFlag = false;}else{$('i[name=mobile3]').html('');}
                  // if(!casedesc){$('i[name=casedesc3]').html('案例必填');vildateFlag = false;}else{$('i[name=casedesc3]').html('')};
                   if(!validateCheck.isZhizhao(cardno)){$('i[name=cardno3]').html('执照号错误');vildateFlag = false;}else{$('i[name=cardno3]').html('')};
                   if (!vildateFlag) {return;}
                   var url = "<?php echo yii\helpers\Url::to('/certification/orgnization')?>";
                   $.post(url,$('.certwo').serialize(),function(json){
                       if(json.code != '0000'){
                           alert(json.msg);
                       }else{
                           window.location = "<?php echo \yii\helpers\Url::to('/user/index')?>";
                       }
                   },'json');
               }
        }


    $('.renz').click(function(){
        var types = $('.current').attr('id');
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
