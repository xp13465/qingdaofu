<?php
use yii\helpers\Html;
use yii\helpers\Url; 
$certi = $data['certification'];

$image_file = $data['pictureurl'];
if($content = @file_get_contents($image_file)){
	$info = @pathinfo($image_file);
	$image_info = @getimagesize($image_file);
	if($image_info){
		switch($image_info[2]){//判读图片类型  
			case 1:$img_type="gif";break;  
			case 2:$img_type="jpg";break;  
			case 3:$img_type="png";break;  
		} 
	}else{
		$img_type ='';
	}
	$base64_image_content = chunk_split(base64_encode($content)); 

			
	$img='data:image/'.$img_type.';base64,'.$base64_image_content;//合成图片的base64编码  
}else{
	$img="";
}
// $img="";
?>
<link rel="stylesheet" href="/cropper-master/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/cropper-master/dist/cropper.css">
<link rel="stylesheet" href="/cropper-master/demo/css/main.css">

<div class="bg">
      <div class="change">
         <a id="changeImg" href="javascript:void(0)" class="shang">选择图片</a>
      </div>

      <div class="img-container">  
          <img align=center; src="<?=$img?>" alt="" class="cropper-hidden">
      </div>
      <input type="file" class="sr-only" id="inputImage" name="file" style="display:none" accept="image/*">
      <div class="small">
          <div class="docs-preview1 clearfix">
            <div class="img-preview"></div>
          </div>
          <div class="docs-preview2 clearfix">
            <div class="img-preview"></div>
          </div>
          <div class="docs-preview3 clearfix">
            <div class="img-preview"></div>
          </div>
          <div class="save">
             <a id="saveHead" href="javascript:void(0)" class="baocun">保存修改</a>                   
          </div>
      </div>
</div>	  
<style>
.bg{width:780px;height:360px;background:#fff;}
.img-container{width:600px;height:360px;float:left;margin:20px 0px 0px 20px;}
.cropper-wrap-box{width:600px;height:360px;}
.cropper-bg{background:#e8ebf0;}
.small{width:120px;height:360px;float:left;margin:20px 0px 0px 30px;}
.docs-preview1{width:120px; height:120px;border-radius:50%;}
.docs-preview1 .img-preview{width:120px;height:120px;background:#000;vertical-align:middle;}
.docs-preview2{width:60px; height:60px;border-radius:50%;margin:30px 0px 0px 30px;}
.docs-preview2 .img-preview{width:60px;height:60px;background:#000;vertical-align:middle;}
.docs-preview3{width:30px; height:30px;border-radius:50%;margin:30px 0px 0px 47px;}
.docs-preview3 .img-preview{width:30px;height:30px;background:#000;vertical-align:middle;}

.change{width:200px;height:30px;margin:20px 0px 0px 20px;}
.bg .change a{line-height:30px;background:#5996e7;color:#fff;font-size:12px;padding:8px 20px;border-radius:5px;font-family:"微软雅黑";}
.save{width:120px;height:30px;margin:50px 0px 0px 0px;}
.small .save a{line-height:40px;background:#5996e7;color:#fff;font-size:12px;padding:14px 30px;border-radius:5px;font-family:"微软雅黑";}
a{text-decoration:none;cursor: pointer;}
</style>

<script src="/cropper-master/assets/js/jquery.min.js"></script>
<script src="/cropper-master/dist/cropper.js"></script>
<script src="/js/layer/layer.js"></script>
<script>
$(function(){
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>'
	var console = window.console || { log: function () {} };
	var URL = window.URL || window.webkitURL;
	//var $image = $('#image');
	var $image = $('.img-container > img'); 
	var uploadedImageURL ="<?=$data['pictureurl']?>";
 	var $inputImage = $('#inputImage');
	var options = {
		preview: '.img-preview',
		dragMode:'move',
        aspectRatio: 1 / 1
      };
	$("#changeImg").click(function(){
		$("#inputImage").trigger("click");
	})
	$image.cropper(options);  
	$("#saveHead").on("click",saveHead)
	
	function saveHead() {  
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var $image = $('.img-container > img'); 
		result = $image.cropper("getCroppedCanvas", { width: 300, height: 300 })
		// console.log(result.toDataURL)
		if(result.toDataURL){
			var basechar = (result.toDataURL('image/jpeg'))
		}else{
			layer.close(index)
			return false;
		}
		
		
		$(".newImg").attr("src", basechar)  
		
		$.ajax({
            url:"/userinfo/uploadsimg",
            type:'post',
            data:{basechar:basechar,_csrf:csrf},
            dataType:'json',
            success:function(json){
               // layer.close(index)
				if(json.code=='0000'){
					layer.msg(json.msg,{},function(){
						var parentindex = parent.layer.getFrameIndex(window.name); 
						parent.location.reload()
						parent.layer.close(parentindex); 
					})
				}else{
					layer.msg(json.msg,{},function(){})
				}
				layer.close(index)
				
            },
			error:function(){
				layer.close(index)
			}
        })
		
	}
	

	  
	//$image.on("load", function() {        // 等待图片加载成功后，才进行图片的裁剪功能  
	   
	//})

	
	// $(".reset").on("click",function(){
	// $image.cropper("reset")	
	// $image.cropper("rotate",45)	
	
	
	// })

  
	$inputImage.change(function () {
      var files = this.files;
      var file;
      if (!$image.data('cropper')) {
        return;
      }

      if (files && files.length) {
        file = files[0];
        if (/^image\/\w+$/.test(file.type)) {
          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
          }
          uploadedImageURL = URL.createObjectURL(file);
          $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
          $inputImage.val('');
        } else {
			window.alert('请选择一个图片文件.');
        }
      }
    });
})
</script>
</body></html>
