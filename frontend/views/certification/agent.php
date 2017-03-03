<style>
    body{background-color: #ccc;font-family: "微软雅黑";}
    li{list-style:none;}
    *{margin:0;padding:0;}
    .agent{width:840px;background-color: #fff;}
    .t_agent{height:40px;background-color: #F2EDD0;text-align:center;line-height:40px;position:relative;}
    .t_agent img{vertical-align:middle;}
    .t_agent span{color:#E23046;vertical-align:middle;font-size:16px;}
    .t_agent .cha01{background:url(/images/cha01.png) no-repeat; width:23px;height:23px;position:absolute;top:10px;right:16px;}
    .t_agent b{font-size:18px;}
    .add a{width:138px;height:40px;display:block;text-align:center;line-height:40px;color:#fff;border-radius:2px;background-color: #0065b3;text-decoration:none;margin:35px auto 35px auto;}
    .tm_table{margin-bottom:60px;}
    .tm_table tr{height: 60px;border-top:1px solid #CACACA;font-size: 12px;color:#333;}
    .tm_table td{border-bottom: 1px solid #CACACA;border-left: 0px;border-right: 0px;}
    .tm_table th{height: 60px;background-color: #E8E9EE;font-size: 14px;border-bottom: 1px solid #CACACA;;border-left: 0px;border-right: 0px;}
    .tt_table table{border:1px solid #ccc;margin:0 auto;margin-top:30px;}

    /*弹框样式*/
    .tan{width:352px;border-radius:4px;background-color: #fff;top:30px;margin-left: -176px;}
    .t_tan{height:46px;background-color: #E3E3E3;line-height:46px;position:relative;}
    .t_tan img{vertical-align:middle;padding-left:10px;}
    .t_tan span{vertical-align:middle;}
    .xie ul{width:352px;padding:10px;}
    .xie li {margin:20px 0;}
    .xie li span{text-align:right;width:80px;display:inline-block;margin-right:16px;}
    .xie li input{width:208px;height:40px;}
    .close1{background:url(/images/close1.png) no-repeat; width:18px;height:18px;display:inline-block;position:absolute;right:10px;top:14px;}
    .yjin{position:relative;width:208px;height:40px;display:inline-block;}
    .yjin .eye_01{background:url(/images/eye_01.png) no-repeat;width:21px;height:22px;position:absolute;top:12px;right:30px;display:inline-block;}
    .yjin .eye_02{background:url(/images/eye_02.png) no-repeat;width:21px;height:22px;position:absolute;top:16px;right:0px;display:inline-block;}
    .a_niu{padding:0 57px;overflow:hidden;}
    .a_niu a{width:108px;height:36px;background-color:#CADCF0;border-radius:3px;text-align:center;line-height:36px;color:#333;display:inline-block;text-decoration:none;margin-bottom: 30px;}
    .a_niu .qd{float:right;background-color: #0065b3;color:#fff;}
</style>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<body>
<div class="agent">
    <?php if(isset($certification->uid)&&$certification->uid != Yii::$app->user->getId()){ echo ''?>
    <?php }else{?>
    <div class="t_agent">
        <img src="/images/audio.png" height="15" width="19" alt="" />
		<span>
          <?php
          $pid = Yii::$app->db->createCommand("select count(pid) from zcb_user where pid = $certification->uid")->queryScalar(); ?>
            您共可以添加
			<b class="weight"><?php echo $certification->managersnumber ?> </b>个代理商,还剩余<b class="weight"> <?php echo $certification->managersnumber-$pid ?> </b>个
		</span>
        <span class="cha01"></span>
    </div>
    <?php } ?>
    <div class="tm_table tt_table">
        <table style="width:780px;text-align:center;" cellpadding="0" cellspacing="0">
            <thead>
            <th>序号</th>
            <th>姓名</th>
            <th>联系方式</th>
            <th>身份证号码</th>
            <?php if($certification->category == 2){?><th>执业证号</th><?php }?>
            <th>密码</th>
            <?php if($certification->uid == Yii::$app->user->getId()){?><th>操作</th><?php }?>
            </thead>
            <tbody>
            <?php if(isset($certification->uid)&&$certification->uid == Yii::$app->user->getId()){?>
                <?php foreach($delegateUser as $k=>$du){?>
                    <tr>
                        <td><?php echo $k+1;?></td>
                        <td><?php echo $du['username'];?></td>
                        <td><?php echo \frontend\services\Func::HideStrRepalceByChar($du['mobile'],'*',3,4) ?></td>
                        <td><?php echo \frontend\services\Func::HideStrRepalceByChar($du['cardno'],'*',4,2) ?></td>
                        <?php if($certification->category == 2){?><td class="jin"><?php echo $du['zycardno'];?></td><?php }?>
                        <td>********</td>
                        <?php if(isset($du['isstop'])&&$du['isstop'] == 0){?>　
                            <td><a onclick="stop('<?php echo $du['id']?>')">停用</a></td>
                        <?php }else{?>
                            　<td>停用</td>
                        <?php }?>
                    </tr>
                <?php }?>
            <?php }else{?>
            <tr>
                <td><?php echo 1;?></td>
                <td><?php echo $delegateUser['username'];?></td>
                <td><?php echo \frontend\services\Func::HideStrRepalceByChar($delegateUser['mobile'],'*',3,4) ?></td>
                <td><?php echo \frontend\services\Func::HideStrRepalceByChar($delegateUser['cardno'],'*',4,2) ?></td>
                <?php if($certification->category == 2){?><td class="jin"><?php echo $delegateUser['zycardno'];?></td><?php }?>
                <td>********</td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if(isset($certification->uid)&&$certification->uid == Yii::$app->user->getId()){?>
        <div class="pages clearfix " style="top:20px">
            <div class="fenye" style="margin-left:162px;">
                <?php echo '<span class="fenyes" style="font-size:12px;margin:0px 50px -42px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条记录'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
                <?= linkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
            </div>
        </div>
    </div>
    <?php }else{echo '';} ?>
    <div class="add">
        <?php if(isset($certification->uid)&&$certification->uid != Yii::$app->user->getId()){ echo ''?>
        <?php }else{ ?>
        <a href="javascript:;" class="addAgent">添加代理人</a>
        <?php }?>
    </div>
</div>

<div class="tanchu" style="display: none">
    <form  autocomplete="off">
    <div class="tan">
        <div class="t_tan">
            <img src="/images/xie.png" height="24" width="23" alt="" />
            <span>请输入添加的代理商信息</span>
            <!--<span class="close1"></span>-->
        </div>
        <div class="xie">
            <ul>
                <li>
                    <span>姓名</span>
                    <input type="text" name="name" autocomplete="off"/>
                    <i></i>
                </li>
                <li>
                    <span>手机号码</span>
                    <input type="text"  name="mobile" autocomplete="off"/>
                    <i></i>
                </li>
                <li>
                    <span>身份证号码</span>
                    <input type="text"  name="cardno" autocomplete="off"/>
                    <i></i>
                </li>
                <?php if($certification->category == 2){?>
                <li>
                    <span>执业证号</span>
                    <input type="text" name="zycardno" autocomplete="off"/>'
                    <i></i>
                </li>
                <?php }?>
                <li>
                    <span>密码</span>
                    <div class="yjin">
                        <input type="password"  name="password" autocomplete="off"/>
                        <span class="eye_01">
                        </span>
                        <span class="eye_02"></span>
                    </div>
                    <i></i>
                </li>
            </ul>
        </div>
        <div class="a_niu">
            <a href="javascript:;" class="addLater">取消</a>
            <a href="javascript:;" class="addNow">确定</a>
        </div>
    </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("input").val("");
        var validateCheckForm = {
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

        $(".addAgent").click(function () {
            $.msgbox({
                closeImg: '/images/yuan2.png',
                async: false,
                height:540,
                width:380,
                title:'添加',
                content:$('.tanchu').html(),
            });
        });
        $(document).delegate('.eye_02','click',function(){
             $('input[type=password]').attr('type','texts');
        })
        $(document).delegate('.eye_01','click',function(){
            $('input[type=texts]').attr('type','password');
        })
        $(document).delegate('.addLater','click',function(){
                $(".msgbox_wrapper").css('display','none');
                $(".msgbox_bg").css('display','none');
                $("#jMsgboxBox").css('display','none');
            }
        );

        $(document).delegate('.addNow','click',function(){
                var validateCheck = true;

                if($.trim($(this).parent().parent().children().children().find('input[name="name"]').val()) == ''){
                    $(this).parent().parent().children().children().find('input[name="name"]').parent().children('i').addClass('error').removeClass('yesshow').html('姓名不可为空');
                    validateCheck = false;
                }else{
                    $(this).parent().parent().children().children().find('input[name="name"]').parent().children('i').addClass('yesshow').removeClass('error').html('');
                }
                if(!validateCheckForm.isMobile($.trim($(this).parent().parent().children().children().find('input[name="mobile"]').val()))){
                    $(this).parent().parent().children().children().find('input[name="mobile"]').parent().children('i').addClass('error').removeClass('yesshow').html('手机号格式错误');
                    validateCheck = false;
                }else{
                    $(this).parent().parent().children().children().find('input[name="mobile"]').parent().children('i').addClass('yesshow').removeClass('error').html('');
                }
                if(!idCardNoUtil.checkIdCardNo($.trim($(this).parent().parent().children().children().find('input[name="cardno"]').val()))){
                    $(this).parent().parent().children().children().find('input[name="cardno"]').parent().children('i').addClass('error').removeClass('yesshow').html('身份证号错误');
                    validateCheck = false;
                }else{
                    $(this).parent().parent().children().children().find('input[name="cardno"]').parent().children('i').addClass('yesshow').removeClass('error').html('');
                }
                <?php if($certification->category == 2){?>
                if(!validateCheckForm.isZhiyezhenghao($.trim($(this).parent().parent().children().children().find('input[name="zycardno"]').val()))){
                    $(this).parent().parent().children().children().find('input[name="zycardno"]').parent().children('i').addClass('error').removeClass('yesshow').html('律师执业证号错误');
                    validateCheck = false;
                }else{
                    $(this).parent().parent().children().children().find('input[name="zycardno"]').parent().children('i').addClass('yesshow').removeClass('error').html('');
                }
                <?php }?>
                if($.trim($(this).parent().parent().children().children().children().find('input[name="password"]').val()) == ''){
                    $(this).parent().parent().children().children().children().find('input[name="password"]').parent().parent().children('i').addClass('error').removeClass('yesshow').html('密码不能为空');
                    validateCheck = false;
                }else{
                    $(this).parent().parent().children().children().children().find('input[name="password"]').parent().parent().children('i').addClass('yesshow').removeClass('error').html('');
                }
                var certifi =<?php echo $certification->managersnumber ?>;
                var pid = <?php echo Yii::$app->db->createCommand("select count(pid) from zcb_user where pid = $certification->uid")->queryScalar();?>;
                if(!certifi){
                    alert('请申请添加代理人个数');
                    validateCheck = false;
                }else if (pid > certifi) {
                    alert('添加代理人请不要超给' + certifi + '个');
                    validateCheck = false;
                }

                if(!validateCheck)return false;
                $.ajax({
                    url:"<?php echo \yii\helpers\Url::toRoute('/certification/agent')?>",
                    type:"post",
                    data:$(this).parent().parent().parent('form').serialize(),
                    dataType:'json',
                    success: function (json) {
                        if(json.code == '0000'){
                            location.reload();
                        }else{
                            alert(json.msg);
                        }
                    }
                });
            }
        );
    });
    function stop(id) {
        if (confirm('请问是否要停用该用户？')) {
            var url = "<?php echo \yii\helpers\Url::toRoute('/certification/stop')?>";
            $.post(url, {id: id}, function (v) {
                 if(v == 1){
                     window.location="<?php echo yii\helpers\Url::toRoute('/certification/agent')?>";
                 }
            }, 'json')
        }
    }


</script>