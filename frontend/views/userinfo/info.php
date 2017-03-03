<?php
use yii\helpers\Url;
// $type = 1;
// echo "<pre>";
// print_r($data);die;

$categoryLabel = "未认证";
$nameLabel = "";
$cardnoLabel = "";
$contactLabel = "";
$mobileLabel = "";
$emailLabel = "";
$casedescLabel ="";
$addressLabel = "";
$enterprisewebsiteLabel = "";
if($data['certification']&&$data['certification']['state']==1){
	switch($data['certification']['category']){
		case 1:
			$categoryLabel='已认证个人';
			$nameLabel = '姓名';
			$cardnoLabel='身份证号码';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			break;
		case 2:
			$categoryLabel='已认证律所';
			$nameLabel = '律所名称';
			$cardnoLabel='执业证号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '邮箱';
			$casedescLabel = '经典案例';
			break;
		case 3:
			$categoryLabel='已认证公司';
			$nameLabel = '企业名称';
			$cardnoLabel='营业执照号';
			$contactLabel = '联系人';
			$mobileLabel = '联系方式';
			$emailLabel = '企业邮箱';
			$addressLabel = '企业地址';
			$enterprisewebsiteLabel = '公司网站';
			$casedescLabel = '经典案例';
			break;
	}
}
?>
<div class="content clearfix">
  <?=$this->render("/layouts/leftmenu")?>
  <div>
    <div class="new-list" style="border:0px solid #ddd;">
      <div class="right-next">
        <div class="right-topl">
          <p>个人资料</p>
        </div>
      </div>
      <div class="list">
        <table class="editor">
          <colgroup>
          <col width='320px'/>
          <col width='520px'/>
          <col width='120px'/>
          </colgroup>
          <thead>
            <tr>
              <th><p class="first">昵称</p></th>
              <th><p class="second complete"><span class="name"  ><?=$data['realname']?:"未设定"?></span></p><input class='editor box' value="<?=$data['realname']?>" type="text" name="name" ></th>
              <th><p><a class="bian">编辑</a></p></th>
            </tr>
            <tr>
              <th><p class="first">头像</p></th>
              <th><p class="second"><img src="<?=$data['pictureimg']?$data['pictureimg']['file']:"/images/defaulthead.png"?>"></p></th>
              <th><p><a class='headEdit'>编辑</a></p></th>
            </tr>
            <tr>
              <th><p class="first">真实姓名</p></th>
              <th><p class="wei"><?=$categoryLabel?></p></th>
              <th><p><a href="<?=Url::toRoute(["/certifications/index"])?>"><?php
			  $rzStatuLabels = ["0"=>"认证中","1"=>"已认证","2"=>"认证失败"];
			  echo $data['certification']?$rzStatuLabels[$data['certification']['state']]:"立即认证";
			  ?></a></p></th>
            </tr>
            <tr>
              <th><p class="first">手机号码</p></th>
              <th><p class="second"><?=$data['mobile']?></p></th>
              <th><p><a target="_blank" href="<?=Url::toRoute("userinfo/changemobile")?>">修改绑定</a></p></th>
            </tr>
            <!--<tr>
              <th><p class="first">收货地址</p></th>
              <th><p class="second">已添加<i>0</i>个收货地址</p></th>
              <th><p><a href="#">管理</a></p></th>
            </tr>-->
            <tr>
              <th><p class="first">登录密码</p></th>
              <th><p class="second">登录清道夫的密码</p></th>
              <!--<th><p><a class="change" target="_blank" href="<?=Url::toRoute(["user/security",'type'=>($data['isSetPassword']?"1":"2")])?>"><?=$data['isSetPassword']?"修改密码":"设置密码"?></a></p></th>--->
              <th><p><a  href="javascript:void(0);" class="changed"><?= $data['isSetPassword']=='1'?'修改密码':'设置密码'?></a></p></th>
            </tr>
           </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script id='changedList' type="text/template">
      <p class="title">修改登录密码</p>
	  <?php if($data['isSetPassword'] =="1"){ ?>
	  <input type="password" name="Current" placeholder="请输入旧密码" style="margin-top:30px;">
	  <?php } ?>
	  <input type="password" name="New" placeholder="请输入新密码">
	  <input type="password" name="passwords" placeholder="请再次输入一次">
 </script>

<script>
$(document).ready(function(){
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
	$(".bian").click(function(){
		/*layer.confirm($("#header").html(), {
				skin:'head',
				type:1,
				move:false,
				btn: ['保存'] 
			}	*/
		var oldValue = $(".complete").text();
		var newValue = $("input.editor").val();
		if($(".complete").is(":visible")){
			$(".complete").hide();
			$("input.editor").show()
			$('.bian').html("完成");
		}else{
			if(newValue!=oldValue){
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/userinfo/nkname')?>",
					type:'post',
					data:{nickname:newValue,_csrf:csrf},
					dataType:'json',
					success:function(json){
						if(json.code=="0000"){
							$(".complete span").html(newValue);
							$("input.editor").hide()
							$(".complete").show();
							$('.bian').html("编辑");
							
							layer.msg(json.msg,{},function(){window.location.reload()})
						}else{
							layer.msg(json.msg)
						}
						
					}
				})
			}else{
				$("input.editor").hide()
				$(".complete").show();
				$('.bian').html("编辑");
			}
			
		}
	})
	$(".headEdit").click(function(){
		layer.open({
		  type: 2,
		  title: '修改头像',
		  shadeClose: true,
		  shade: 0.8,
		  skin:'xiu',
		  area: ['780px', '530px'],
		  content: '<?=Url::toRoute("userinfo/head")?>'
		}); 
	})
	
		$(".changed").click(function(){
			layer.confirm($("#changedList").html(),{
			  type: 1,
			  btn:['确定'],
			  title: '<p><img src="/bate2.0/images/title2.png"></p>',
			  skin: 'password', 
			  area: ['420px', '310px'],
			},function(){
						var current = $('input[name=Current]').val();
						var news = $('input[name=New]').val();
						var passwords = $('input[name=passwords]').val();
						var flag = true;
						<?php if($data['isSetPassword'] =="1"){ ?>
						if (!current) {
							flag = false;
							$('b[name=cur]').css('color', 'red');
							$('b[name=cur]').html('请输入旧密码');
						}
						<?php }?>
						if (!news) {
							flag = false;
							$('b[name=news]').css('color', 'red');
							$('b[name=news]').html('请输入新密码');
						}
						if (!passwords) {
							flag = false;
							$('b[name=pas]').css('color', 'red');
							$('b[name=pas]').html('请输入新密码');
						}
						if (news != passwords) {
							flag = false;
							layer.msg('新密码两次输入的不一致');
						}
						if (!flag) {
							return;
						}
						var url = "<?php echo Url::toRoute(($data['isSetPassword']!=1?"/userinfo/setpassword":'/userinfo/modifypassword'))?>";
						var index = layer.load(1);
						$.post(url, {old_password:current,new_password:passwords,_csrf:csrf}, function (json) {
							layer.close(index)
							if(json.code == '0000'){
								layer.msg(json.msg,{},function(){window.location.reload()});
							}else{
								layer.msg(json.msg);
							}
						}, 'json');
			})		
		});
		$(".changed01").click(function(){
			layer.open({
			  type: 1,
			  btn:['确定'],
			  title: '<p><img src="/bate2.0/images/title2.png"></p>',
			  skin: 'password', 
			  area: ['420px', '310px'],
			  content: '<p class="title">设置登录密码</p><input type="text" name="password" placeholder="请输入密码" style="margin-top:50px;"><input type="text" name="password" placeholder="请再次输入密码" style="margin-top:20px;">',
			})		
		});
  })
</script>