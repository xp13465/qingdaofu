<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$mobile = $userinfo['mobile'];
?>
<?=wxHeaderWidget::widget(['title'=>'更改手机号','gohtml'=>'','backurl'=>$backurl])?>
<section>
  <div class="changephone" style="display:none;">
    <p>绑定新的手机号码，用新的手机号码和验证码登录</p>
    <ul>
      <li style="width:90%;height:50px;">
        <input type="text" name="newmobile" id='newmobile' placeholder="输入新的手机号" maxlength='11' style="height:50px;">
      </li>
      <li style="width:50%;height:50px;">
        <input type="text" name="newcode" id='newcode' placeholder="输入验证码" maxlength='4' style="height:50px;">
      </li>
      <li class="code validateCode">点击获取</li>
    </ul>
    <div class="next"> <a href="#" class="saruman" disabled='disabled' >确定</a> </div>
  </div>
</section>
<section>
  <div class="changephone01"  >
    <p>发送验证码到当前手机号</p>
    <h1 class='oldmobile'><?=$mobile?></h1>
    <ul style='display:none'>
      <li style="border:0px solid #ddd;">
        <input type="text" maxlength='1' class="oldcode" style="padding:26px;width:65px;background:url(/bate2.0/images/inputbg.png) left 5px top 0px no-repeat;">
        <input type="text" maxlength='1' class="oldcode" style="padding:26px;width:65px;background:url(/bate2.0/images/inputbg.png) left 5px top 0px no-repeat;">
        <input type="text" maxlength='1' class="oldcode" style="padding:26px;width:65px;background:url(/bate2.0/images/inputbg.png) left 5px top 0px no-repeat;">
        <input type="text" maxlength='1' class="oldcode" style="padding:26px;width:65px;background:url(/bate2.0/images/inputbg.png) left 5px top 0px no-repeat;">
      </li>
    </ul>
	<ul>
      <li style="width:320px;border:0px">
        <input type="text" id='oldcode' name="number" maxlength ='4'  style="letter-spacing: 55px;background: url(/bate2.0/images/code_bg.png) center center no-repeat;padding-left: 60px;">
      </li>
    </ul>
    <div class="next"> <a href="#" class="nextbtn" disabled='disabled' >下一步</a>
      <p>没收到验证码?<i class='oldmobilecode' style="color:#0065b3;">重新发送</i></p>
    </div>
  </div>
</section>
<script>
$(document).ready(function(){
	$(".oldcode").eq(0).click().focus();
	var oldcode = '';
	var curCount = 0;
	function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                $(".validateCode").removeAttr("disabled");//启用按钮
                $(".validateCode").css("background","#0da3f9");
                $(".validateCode").html('获取验证码');
            }
            else {
				$(".validateCode").css("background","#ccc");
                curCount--;
                $(".validateCode").html(curCount);
            }
        }
	$("#oldcode").on("keyup",function(event){
		oldcode = $("#oldcode").val()
		if($(this).val().length>=4){
			$(".nextbtn").css("background",'#0da3f9').css("color",'#fff').attr("disabled",false)
		}else{
			$(".nextbtn").css("background",'#ccc').css("color",'#999').attr("disabled",true)
		}
	})	
	$("#oldcode").on("change",function(event){
		if($(this).val().length>=4){
			$(".nextbtn").css("background",'#0da3f9').css("color",'#fff').attr("disabled",false)
		}else{
			$(".nextbtn").css("background",'#ccc').css("color",'#999').attr("disabled",true)
		}
	})
	$(".oldcode").on("focus",function(event){
		if($(this).val()){
			$(this).next().focus()
		}
		if($(this).val()){
			$(this).next().focus()
		}
		oldcode = '';
		$(".oldcode").each(function(){
			oldcode +=$(this).val()
		})
		$("#oldcode").val(oldcode).trigger("change")
	})
	$(".oldcode").on("keypress",function(event){
		var val = String.fromCharCode(event.keyCode);
		if(val){
			if($(this).index()==3){
				$(this).focus();
				if($("#oldcode").val().length==3){
					oldcode = oldcode+val;
					$("#oldcode").val(oldcode).trigger("change")
				}
			}else{
				$(this).val(val);
				$(this).next().focus()
			}
		}
		
	})
	$(".oldcode").on("keydown",function(event){
		if(event.keyCode=='8'&&$(this).val()==''){
			$(this).prev().val("").focus()
		}else if(event.keyCode=='8'){
			$(this).val("").focus()
		}
	})
	$("#newcode").on("keyup",function(event){
		if($(this).val().length>=4){
			$(".saruman").css("background",'#0da3f9').css("color",'#fff').attr("disabled",false)
		}else{
			$(".saruman").css("background",'#ccc').css("color",'#999').attr("disabled",true)
		}
	})
	$('.validateCode').click(function () {
            var mobile = $('#newmobile').val();
            if(mobile == ""){
                layer.msg("请输入手机号码");
                return false;
            }
			var loadindex = layer.load(1)
            if(curCount == 0) {
                $.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/user/smscode')?>',
                    type: 'post',
                    data: {mobile: mobile,type: 4},
                    dataType: 'json',
                    success: function (json) {
						layer.close(loadindex)
                        if (json.code == '0000') {
                            layer.msg(json.msg);
                            $(".validateCode").attr("disabled", "disabled");
                            $(".validateCode").addClass("huoqudisa");
                            curCount = 60;
                            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        } else {
                            layer.msg(json.msg);
                        }
                    }
                });
            }
    });
	$('.oldmobilecode').click(function () {
            if(!$(this).attr("disabled")){
				var oldmobile = $('.oldmobile').text();
				var loadindex = layer.load(1)
				$.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/user/smscode')?>',
                    type: 'post',
                    data: {mobile: oldmobile,type: 4},
                    dataType: 'json',
                    success: function (json) {
						layer.close(loadindex)
                        if (json.code == '0000') {
							$('.oldmobilecode').attr("disabled",true).css("color","#A2A2A2")
                        } 
						layer.msg(json.msg);
                         
                    }
                });
			}
        });
	$('.oldmobilecode').trigger("click")
	$('.oldmobilecode').attr("disabled",false).css("color","#0065b3")
	// $(".icon-back").trigger("click")
	$(".nextbtn").click(function(){
			if(!$(this).attr("disabled")){
				var oldmobile = $('.oldmobile').text();
				var oldcode = $("#oldcode").val()
				// alert(oldmobile+"\n"+oldcode)
				var loadindex = layer.load(1)
				$.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/user/checksmscode')?>',
                    type: 'post',
                    data: {mobile: oldmobile,type: 4,code: oldcode},
                    dataType: 'json',
                    success: function (json) {
						layer.close(loadindex)
                        if (json.code == '0000') {
                            $(".changephone").show();
							$(".changephone01").hide();
                        } else {
                            layer.msg(json.msg);
                        }
                    }
                });
			}
		})
	
	$(".saruman").click(function(){
			if(!$(this).attr("disabled")){
				var oldmobile = $('.oldmobile').text();
				var oldcode = $('#oldcode').val();
				var newmobile = $('#newmobile').val();
				var newcode = $('#newcode').val();
				$.ajax({
                    url: '<?=\yii\helpers\Url::toRoute('/user/changemobile')?>',
                    type: 'post',
                    data: {oldmobile: oldmobile,newmobile:newmobile,oldcode: oldcode,newcode: newcode},
                    dataType: 'json',
                    success: function (json) {
                        if (json.code == '0000') {
                           location.href='<?=$backurl?>';
                        }
						layer.msg(json.msg);
                    }
                });
			}
		})
	})
</script>