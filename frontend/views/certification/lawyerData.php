<?php
use yii\helpers\Html;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'agent','method'=>'post','action'=>\yii\helpers\Url::to("/certification/signup")])?>
<div class="content_right">
    <div class="lvshi">
        <h3 class="bj">律所</h3>
        <ul class="c_right_2 lvsuo_2">
            <li><span>事务所名：</span><?php echo isset($certi['name'])?$certi['name']:''?></li>
            <li>
                <span>执照许可证：</span><?php echo isset($certi['cardno'])?$certi['cardno']:''?>
                <span class="color uploadsChe" style="font-size:12px; padding-left:0;">查看</span>
            </li>
            <li><span>联系人：</span><?php echo isset($certi['contact'])?$certi['contact']:''?></li>
            <li><span>联系方式：</span><?php echo isset($certi['mobile'])?$certi['mobile']:'' ?></li>
            <?php if($certi['email']){echo "<li>"."<span>邮箱：</span>".$certi['email']."<li>";}else{echo '';}?>
            <?php if($certi['casedesc']){echo "<li>"."<span>清收经典案例：</span>".$certi['casedesc']."<li>";}else{echo '';}?>
            <li type="button" class="yrz" style="color:#fff;font-size:16px;">已认证</li><br>
            <li><span  class="bord_l" style="color:#0061ac; font-size: 20px;margin-bottom:10px; margin-left:0;">代理人列表</span><!-- <i class="dec"></i> --></li>
            <li class="xuhao xuhao3">
                <span style="margin-left:2px;">序号</span>　　　
                <span>姓名</span> 　　　
                <span>联系方式</span> 　　　
                <span>身份证号码</span> 　
                <span style="margin-left:90px;">执业证号</span>　　　　　
                <span style="margin-left:5px;">密码</span> 　　　
                <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){echo '';}else{echo '<span style="margin-left:15px;">操作</span>';}?>
            </li>
            <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){?>
                <li class="xuhao1">
                    <span style="margin-left:-6px;width:40px;">1</span>　
                    　　　　<span style="width:85px;margin-left:-77px;"><?php echo isset($username['username'])?$username['username']:''?></span> 　　　
                    　　    <span style="width:90px; margin-left:-79px;"><?php echo isset($username['mobile'])?$username['mobile']:''?></span> 　　　
                            <span style="width:170px;margin-left:-30px;"><?php echo isset($username['cardno'])?$username['cardno']:''?></span>
                            <span style="width:170px;margin-left:4px;"><?php echo isset($username['password_reset_token'])?$username['password_reset_token']:''?></span> 　　　　
                    　      <span style="margin-left:-48px;width:90px;">******</span> 　　　
                </li>
            <?php }else{?>
                <?php foreach(isset($username)?$username:'' as $kye=>$value):?>
                    <li class="xuhao1">
                        <span style="margin-left:-6px;width:40px;"><?php echo $kye+1;?></span>　　　
                        　　　　<span style="width:85px;margin-left:-77px;"><?php echo isset($value['username'])?$value['username']:''?></span> 　　　
                        　　　  <span style="width:90px; margin-left:-79px;"><?php echo isset($value['mobile'])?$value['mobile']:''?></span> 　　　
                                <span style="width:170px;margin-left:-30px;"><?php echo \frontend\services\Func::HideStrRepalceByChar($value['cardno'],'*',4,2) ?></span>
                                <span style="width:170px;margin-left:4px;"><?php echo isset($value['password_reset_token'])?$value['password_reset_token']:''?></span> 　　　
                                <span style="margin-left:-48px;width:90px;">******</span>
                        <?php if(isset($value['isstop'])&&$value['isstop'] == 0){?>　
                            <input type="button" value="停用" name="stops" style="margin:0 0 0 5px; background:#0061ac; width:60px;height:28px;" onclick="stop(<?php echo isset($value['id'])?$value['id']:''?>);">
                        <?php }else{?>
                            　<input type="button" value="停用" name="stops" style="margin:0 0 0 9px; background:#b5b5b5; width:60px;height:28px;">
                        <?php }?>
                    </li>
                <?php endforeach;?>
                <li class="inputs inputs_1">
                    <?php echo html::input('text','username','',['style'=>'width:70px;margin-left:15px;height:28px;'])?>
                    <?php echo html::input('text','mobile','',['style'=>'margin-left:6px; width:135px;height:28px;'])?>
                    <?php echo html::input('text','cardno','',['style'=>'width:165px; margin-left:3px;height:28px;'])?>
                    <?php echo html::input('text','password_reset_token','',['style'=>'width:150px; margin-left:1px;height:28px;'])?>
                    <?php echo html::input('password','passwordf','',['style'=>'width:90px; margin-left:-1px; line-height:28px;height:28px;'])?>
                    <input type="button" value="添加" style="margin:0 0 0 18px; width:60px;height:28px;" onclick="addAgent();">
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="rz_alert" style="display:none; font-size: 16px;">
    <b class="close"><img src="/images/yuan2.png" ></b>
    <img src="/images/yuan.png" height="70" width="70" alt="" class="yuan">
    <p class="art" >认证成功！是否现在去添加代理人？</p>
    <div>
        <a href="<?php echo yii\helpers\Url::to('/capital/list')?>" style="background:#c3c3c3;">返回产品列表</a>　　　<a href="<?php echo yii\helpers\Url::to('/certification/lawyer')?>">确认</a>
    </div>
</div>
<script type="text/javascript">

    $('.box').hide();
   $(document).ready(function(){
        var cookies = "<?php echo yii::$app->request->get('id');?>";
        if(cookies == 1){
            $('.rz_alert').show();
        }else{
            $('.rz_alert').hide();
        }
       $('.close').click(function(){
           $('.rz_alert').hide();
       })
    })


    $.validator.addMethod("isIdCardNo", function(value, element) {
        return this.optional(element) || idCardNoUtil.checkIdCardNo(value);
    }, "请输入正确的身份证号码");
    $(document).ready(function(){
        $("#agent").validate({
            ignore: "",
            rules: {
                username: "required",
                mobile:"required",
                cardno:{
                    required:true,
                    isIdCardNo:true,
                },
                passwordf:"required",
                password_reset_token:"required",
            },

            messages: {
                username: "忘记填写姓名啦",
                mobile: "忘记填写联系方式啦",
                cardno: {
                    required:"忘记填写证件号啦",
                    isIdCardNo:'请输入正确的身份证号码',
                },
                passwordf:"忘记填写密码啦",

                password_reset_token:"忘记填写执业号啦",

            }
        });
    });

    function addAgent() {
        var mobile =$('input[name=mobile]').val();
        if(!(/^13\d{9}$/g.test(mobile))&&!(/^15\d{9}$/g.test(mobile))&&!(/^18\d{9}$/g.test(mobile))&&!(/^17\d{9}$/g.test(mobile))&&!(/^14\d{9}$/g.test(mobile))){
            alert('请输入格式正确的手机号');
            return;
        }
        var i = "<?php echo  Yii::$app->db->createCommand("select count(*) from zcb_user where pid = {$certi['uid']} and isstop = 0")->queryScalar(); ?>";
        var a = "<?php echo $certi['managersnumber']?>";
        if(!a){
            alert('请申请添加代理人个数');
        }else if (i == a) {
            alert('添加代理人请不要超给' + a + '个');
        } else {
            var r = $('#agent').valid()
            $('#agent').submit();
        }
    }


    function stop(id){
        if(confirm('请问是否要停用该用户？')){
            var url = "<?php echo yii\helpers\Url::to('/certification/stop')?>";
            $.post(url,{id:id},function(v){
                if(v == 1){
                    location.href="<?php echo yii\helpers\Url::to('/certification/corporation')?>";
                }
            },'json');
        }
    }

    $('.uploadsChe').click(function(){
        var id = "<?php echo $certi['id'] ?>";
        $.msgbox({
            closeImg: '/images/close.png',
            height:500,
            width:600,
            content:"<?php echo yii\helpers\Url::to(["/certification/uploadschec"])?>?id="+id,
            type:'ajax',
        });
    })
</script>