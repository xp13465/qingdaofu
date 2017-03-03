<?php
   use yii\helpers\Html;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'agent','method'=>'post','action'=>\yii\helpers\Url::to("/certification/signup")])?>
<div class="content_right">
    <div class="gongsi">
        <h3 class="bj">公司</h3>
        <ul class="c_right_2">
            <li><span  class="bord_l" style="color:#0061ac; font-size: 20px;margin-left:0;">基本信息</span><!-- <i class="dec"> </i>--></li>
            <li><span>企业名称：</span><?php echo isset($certi['name'])?$certi['name']:''?></li>
            <li>
               <span>营业执照号：</span><?php echo isset($certi['cardno'])?$certi['cardno']:''?>
                <span class="color uploadsChe" style="font-size:12px; padding-left:0;">查看</span>
            </li>
            <li><span>联系人：</span><?php echo isset($certi['contact'])?$certi['contact']:''?></li>
            <li><span>联系方式：</span><?php echo isset($certi['mobile'])?$certi['mobile']:'' ?></li>
            <?php if($certi['email']){echo "<li>"."<span>邮箱：</span>".$certi['email']."<li>";}else{echo '';}?>
            <li><span  class="bord_l" style="color:#0061ac; font-size: 20px;margin-left:0;">业务信息</span><!-- <i class="dec"></i> --></li>
            <?php if($certi['address']){echo "<li>"."<span>企业经营地址：</span>".$certi['address']."<li>";}else{echo '';}?>
            <?php if($certi['enterprisewebsite']){echo "<li>"."<span>企业网站：</span>".$certi['enterprisewebsite']."<li>";}else{echo '';}?>
            <?php if($certi['casedesc']){echo "<li>"."<span>清收经典案例：</span>".$certi['casedesc']."<li>";}else{echo '';}?>
            <li type="button" class="yrz" style="color:#fff;font-size:16px;">已认证</li><br>
            <li><span  class="bord_l" style="color:#0061ac; font-size: 20px; margin-bottom:10px;margin-left:0;">代理人列表</span><!-- <i class="dec"></i> --></li>
            <li class="xuhao">
                <span style="margin-left:2px;">序号</span>　　　
                <span>姓名</span> 　　　
                <span>联系方式</span> 　　　
                <span>身份证号码</span> 　　　
                <span style="margin-left:20px;">密码</span> 　　　
                <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){echo '';}else{echo '<span style="margin-left:49px;">操作</span>';}?>
            </li>
           <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){?>
                    <li class="xuhao1">
                        <span style="margin-left:-6px;width:40px;">1</span>　
                        　　　　<span style="width:85px;margin-left:-61px;"><?php echo isset($username['username'])?$username['username']:''?></span> 　　　
                        　　　<span style="width:90px; margin-left:-64px;"><?php echo isset($username['mobile'])?$username['mobile']:''?></span> 　　　
                              <span style="width:155px;margin-left:-15px;"><?php echo isset($username['cardno'])?$username['cardno']:''?></span> 　　　
                        　    <span style="margin-left:-78px;width:90px;">******</span> 　　　
                    </li>
            <?php }else{?>
              <?php foreach(isset($username)?$username:'' as $kye=>$value):?>
                    <li class="xuhao1">
                        <span style="margin-left:-6px;width:40px;"><?php echo $kye+1;?></span>　　　
                        　　　　<span style="width:85px;margin-left:-61px;"><?php echo isset($value['username'])?$value['username']:''?></span> 　　　
                        　　　  <span style="width:90px; margin-left:-64px;"><?php echo isset($value['mobile'])?$value['mobile']:''?></span> 　　　
                                <span style="width:170px;margin-left:-15px;"><?php echo \frontend\services\Func::HideStrRepalceByChar($value['cardno'],'*',4,2) ?></span> 　　　
                              　<span style="margin-left:-78px;width:90px;">******</span>
                        <?php if($value['isstop'] == 0){?>　
                            <input type="button" value="停用" name="stops" style="margin:0 0 0 37px; background:#0061ac; width:60px;" onclick="stop(<?php echo isset($value['id'])?$value['id']:''?>);">
                       <?php }else{?>
                         　<input type="button" value="停用" name="stops" style="margin:0 0 0 41px; background:#b5b5b5; width:60px;">
                       <?php }?>
                    </li>
                   <?php endforeach;?>
                    <li class="inputs inputs_1">
                        <?php echo html::input('text','username','',['style'=>'width:85px;margin-left:55px;height:28px;'])?>
                        <?php echo html::input('text','mobile','',['style'=>'margin-left:3px; width:155px;height:28px;'])?>
                        <?php echo html::input('text','cardno','',['style'=>'width:180px; margin-left:10px;height:28px;'])?>
                        <?php echo html::input('password','passwordf','',['style'=>'width:90px; line-height:28px;height:28px;'])?>
                        <input type="button" value="添加" style="margin:0 0 0 35px !important; width:60px;height:28px;" onclick="addAgent();">
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
        <a href="<?php echo yii\helpers\Url::to('/capital/list')?>" style="background:#c3c3c3;">返回产品列表</a>　　　<a href="<?php echo yii\helpers\Url::to('/certification/corporation')?>">确认</a>
    </div>
</div>
<script type="text/javascript">
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
            },

            messages: {
                username: "忘记填写姓名啦",
                mobile: "忘记填写联系方式啦",
                cardno: {
                    required:"忘记填写证件号啦",
                    isIdCardNo:'请输入正确的身份证号码',
                },
                passwordf:"忘记填写密码啦",
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
