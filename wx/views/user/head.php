<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$certi = $data['certification'];
$status = ['0'=>'认证中','1'=>'已认证','2'=>'认证失败'];
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

?>
<?=wxHeaderWidget::widget(['title'=>'头像','gohtml'=>'','backurl'=>Url::toRoute("/user/info")])?>
  <link rel="stylesheet" href="/cropper-master/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="/cropper-master/dist/cropper.css">
  <link rel="stylesheet" href="/cropper-master/demo/css/main.css">
 
<section>
   <div class="img-container" style='width:100%;height:300px;'>  
	    <img align=center; src="<?=$img?>" alt="" class="cropper-hidden">
	</div>
    <input type="file" class="sr-only" id="inputImage" name="file" style="display:none" accept="image/*">
    <div class="docs-preview clearfix">
      <div class="img-preview preview-md"></div>
    </div>

	
	
 </section>
<footer>
 <div class="zhen">
     <a id="changeImg" href="javascript:void(0)" class="shang">选图</a>
     <a id="saveHead" href="javascript:void(0)" class="baocun">保存</a>                   
   </div>
</footer>
<script src="/cropper-master/assets/js/jquery.min.js"></script>
<script src="/cropper-master/dist/cropper.js"></script>
<script>
$(function(){
	
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
		var basechar = (result.toDataURL('image/jpeg'))
		$(".newImg").attr("src", basechar)  
		
		$.ajax({
            url:"/user/upload2",
            type:'post',
            data:{basechar:basechar},
            dataType:'json',
            success:function(json){
               // layer.close(index)
				if(json.code=='0000'){
					layer.msg(json.msg,{},function(){ location.href= "<?=Url::toRoute("/user/info")?>" })
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
