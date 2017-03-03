<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$certi = $data['certification'];
$status = ['0'=>'认证中','1'=>'已认证','2'=>'认证失败'];
?>
<?=wxHeaderWidget::widget(['title'=>'个人中心','gohtml'=>'','backurl'=>Url::toRoute("/user/index")])?>
<section>
<ul class="keep">
    <li class="keep01  txiang">
		<a style="display:block" href="<?=Url::toRoute("/user/head")?>">
			<span class="baocun">头像</span>
			<i class="jiantou jiantou01"></i>
			<span class='headbtn' style="float:right;margin-right:10px;">
				<img class='headpicture' src="<?=$data['pictureurl']?>" style="width:50px;height:50px;border-radius: 50%;vertical-align: middle;">
			</span>
		</a>
		</li>
    <li class="keep01 ncheng" data-name='<?=$data['realname']?>' >
		<a>
			<span class="baocun">昵称</span>
			<i class="jiantou jiantou01"></i>
			<span class='nkname' style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;"><code><?=$data['realname']?:"未设定"?></code></span>
		</a> 
	</li>
    <li class='keep01 sji'>
		<a >
			<span class="baocun">绑定手机</span>
			<i class="jiantou jiantou01"></i>
			<span class='bdmobile' style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;"><?=$data['mobile']?></span>
		</a>
	</li>
	<li class=''>
		<a  style='display:block' href="<?=Url::toRoute(["site/modify",'type'=>($data['isSetPassword']?"1":"2")])?>">
			<span class="baocun"><?=$data['isSetPassword']?"修改密码":"设置密码"?></span>
			<i class="jiantou jiantou01"></i>
			<span class='bdmobile' style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;"></span>
		</a>
	</li>
</ul>
    <div class="fabu01"> 
		<span class="baocun">实名认证</span> 
		<i class="jiantou jiantou01"></i>
		<?php if(isset($certi['state'])&&$certi['state'] == 1){ ?>
		   <a href="<?php echo yii\helpers\Url::toRoute('/certification/index')?>">
			   <span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;">
			   <?php if($certi['category'] == 1){ echo '已认证个人';}else if($certi['category'] == 2){echo '已认证律所';}else if($certi['category'] == 3){echo '已认证公司';} ?>
			   </span>
		   </a>
		<?php }else{ ?>
		   <?php if($userid){ ?>
			   <a href="<?php echo yii\helpers\Url::toRoute('/certification/index')?>">
				  <span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;" ><?= isset($certi['state'])?$status[$certi['state']]:'未认证' ?></span>
			   </a>
		   <?php }else{ ?>
			   <a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">
				   <span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;" >未认证</span>
			   </a>
		   <?php } ?>
		<?php } ?>
	</div>
	
	<div class="tuichu">
        <a href="<?php echo yii\helpers\Url::toRoute('/site/logout')?>">退出登录</a>
    </div>
 </section>
<style>
.layui-layer-btn{padding: 10px 10px 10px;pointer-events: auto;border-top: 1px solid #ddd;}
.layui-layer-btn a {
    height: 35px;
    line-height: 35px;
    margin: 0 6px;
    width: 120px;
    text-align: center;
    border: 0px solid #dedede;
    background: #fff;
    color: #0da3f9;
    border-radius: 2px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
}
.layui-layer-btn .layui-layer-btn0 {background-color: #fff;color: #0da3f9;}

</style> 
 <script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
	$(".sji").click(function(){
		var mobile = $(".bdmobile").html();
		layer.confirm("当前绑定手机："+mobile,{
			title:'绑定手机',
			btn:["更改手机号","返回"]
		},
		function(){
			location.href = "<?php echo yii\helpers\Url::toRoute(['/user/changemobileform',"backurl"=>Yii::$app->request->url])?>";
			
		})
	})
	/*	
	//照片异步上传
	$(".headbtn").click(function(){
		
		var limit = 0;
		var inputName = 'headpicture';
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"limit":limit}).click();
	})*/
	$(".ncheng").click(function(){
		var oldname = $(this).attr("data-name");
		layer.prompt({
		  title: '昵称修改',
		  value: oldname,
		  btn: ['修改','取消'],
		  formType:0 //prompt风格，支持0-2
		}, function(newname){
		   if(newname==oldname)return false;
		   $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/user/nkname')?>",
                type:'post',
                data:{nickname:newname},
                dataType:'json',
                success:function(json){
					if(json.code=="0000"){
						$(".ncheng").find("span.nkname").html(newname);
					}
                    layer.msg(json.msg)
                }
            })
		});
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var inputName = $(this).attr('inputName');
		if(!inputName)return false;
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/user/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {},
			textareas:{},
			dataType: "json",
			success: function (data) {
				layer.close(index)
				if(data.code=='0000'){
					$("."+inputName).attr("src",data.result.url)
				}
				layer.msg(data.msg)
				
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
});
</script>	