<?php
   use yii\helpers\html;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'agent','method'=>'post','action'=>\yii\helpers\Url::to("/certification/signup")])?>
<div class="content_right">
    <div class="gongsi">
        <h3 class="bj">　　公司</h3>
        <ul class="c_right_2">
            <li><span style="color:#0061ac; font-size: 16px;">基本信息</span><i class="dec"></i></li>
            <li><span>企业名称：</span><?php echo isset($certi['name'])?$certi['name']:''?></li>
            <li>
               <span>营业执照号：</span><?php echo isset($certi['cardno'])?$certi['cardno']:''?><span class="suolve" status="0" style="color:#0061ac;">详情<i class="box"></i></span>
            </li>
            <li><span>联系人：</span><?php echo isset($certi['contact'])?$certi['contact']:''?></li>
            <li><span>联系方式：</span><?php echo isset($certi['mobile'])?$certi['mobile']:'' ?></li>
            <?php if($certi['email']){echo "<li>"."<span>邮箱：</span>".$certi['email']."<li>";}else{echo '';}?>
            <li><span style="color:#0061ac; font-size: 16px;">业务信息</span><i class="dec"></i></li>
            <li><span>企业经营地址：</span><?php echo isset($certi['address'])?$certi['address']:''?>
            <?php if($certi['enterprisewebsite']){echo "<li>"."<span>企业网站：</span>".$certi['enterprisewebsite']."<li>";}else{echo '';}?>
            <?php if($certi['casedesc']){echo "<li>"."<span>清收经典案例：</span>".$certi['casedesc']."<li>";}else{echo '';}?>
            <li type="button" class="yrz" style="color:#fff;font-size:16px;">已认证</li><br>
            <li><span style="color:#0061ac; font-size: 16px;">经办人列表</span><i class="dec"></i></li>
            <li class="xuhao">
                <span style="margin-left:2px;">序号</span>　　　
                <span>姓名</span> 　　　
                <span>联系方式</span> 　　　
                <span>身份证号码</span> 　　　
                <span style="margin-left:44px;">密码</span> 　　　
                <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){echo '';}else{echo '<span style="margin-left:49px;">操作</span>';}?>
            </li>
           <?php if(isset($username['pid'])&&$username['pid'] != Yii::$app->user->getId()){?>
                    <li class="xuhao1">
                        <span style="margin-left:-6px;width:40px;">1</span>　
                        　　　　<span style="width:85px;margin-left:-28px;"><?php echo isset($username['username'])?$username['username']:''?></span> 　　　
                        　　　<span style="width:90px; margin-left:-25px;"><?php echo isset($username['mobile'])?$username['mobile']:''?></span> 　　　
                              <span style="width:170px;margin-left:-4px;"><?php echo isset($username['cardno'])?$username['cardno']:''?></span> 　　　
                        　    <span style="margin-left:-40px;width:90px;">******</span> 　　　
                    </li>
            <?php }else{?>
              <?php foreach(isset($username)?$username:'' as $kye=>$value):?>
                    <li class="xuhao1">
                        <span style="margin-left:-6px;width:40px;"><?php echo $kye+1;?></span>　　　
                        　　　　<span style="width:85px;margin-left:-28px;"><?php echo isset($value['username'])?$value['username']:''?></span> 　　　
                        　　　  <span style="width:90px; margin-left:-25px;"><?php echo isset($value['mobile'])?$value['mobile']:''?></span> 　　　
                                <span style="width:170px;margin-left:-4px;"><?php echo isset($value['cardno'])?$value['cardno']:''?></span> 　　　
                              　<span style="margin-left:-40px;width:90px;">******</span>
                        <?php if($value['isstop'] == 0){?>　
                            <input type="button" value="停用" name="stops" style="margin:0 0 0 37px; background:#0061ac; width:60px;" onclick="stop(<?php echo isset($value['id'])?$value['id']:''?>);">
                       <?php }else{?>
                         　<input type="button" value="停用" name="stops" style="margin:0 0 0 41px; background:#b5b5b5; width:60px;">
                       <?php }?>
                    </li>
                   <?php endforeach;?>
                    <li class="inputs">
                        <?php echo html::input('text','username','',['style'=>'width:85px;margin-left:90px;height:28px;'])?>
                        <?php echo html::input('text','mobile','',['style'=>'margin-left:28px; width:180px;'])?>
                        <?php echo html::input('text','cardno','',['style'=>'width:180px; margin-left:10px;'])?>
                        <?php echo html::input('password','passwordf','',['style'=>'width:90px; margin-left:16px; line-height:28px;'])?>
                        <input type="button" value="添加" style="margin:0 0 0 55px; width:60px;" onclick="addAgent();">
                    </li>
        <?php } ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
        $(function(){
            $('.box').hide();
            $('.suolve').hover(function(){
                var img ={
                    0:"<?php echo $certi['cardimg']?>",
                };
                var img_index = $(this).attr('status');
                $('i.box').html('<img src="/'+img[img_index]+'" >');
                $('i.box').find('img').each(function(){
                    var $imgH = $('i.box').outerHeight();
                    var $imgW = $('i.box').outerWidth();
                    $('i.box').find('img').css({
                        "width":$imgW,
                        "height":$imgH
                    })
                })
                $(this).find('i').stop().fadeToggle(500);
            });
        });

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
                username: "请输入姓名",
                mobile: "请输入联系方式",
                cardno: {
                    required:"请输入证件号",
                    isIdCardNo:'请输入正确的身份证号码',
                },
                passwordf:"请输入密码",
            }
        });
    });

    function addAgent() {
        var i = "<?php echo  Yii::$app->db->createCommand("select count(*) from zcb_user where pid = {$certi['uid']} and isstop = 0")->queryScalar(); ?>";
        var a = "<?php echo $certi['managersnumber']?>";
        if(!a){
            alert('请申请添加经办人个数');
        }else if (i >= a) {
                alert('添加经办人请不要超给' + a + '个');
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
</script>
