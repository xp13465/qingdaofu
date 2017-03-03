<br/>
<br/>
<br/>
<br/>
<a href ="javascript:void(1)">asdasdas</a>
<p><img class='imageWxview' src="http://testq.zcb2016.com/uploads/images/20160812/1_14709663108448.jpg" data-img="http://testq.zcb2016.com//uploads/images/20160812/1_14709663108448.jpg"></p>
<p><img class='imageWxview1' src="http://testq.zcb2016.com/uploads/images/20160812/1_14709663108448.jpg" data-img="http://testq.zcb2016.com//uploads/images/20160812/1_14709663108448.jpg"></p>

<div id='assaas'>

</div>
<script>	
$(document).ready(function(){
	alert(19)
	$('.imageWxview1').on('click',function(){
		var img = $(this).attr('data-img');
		if(!img)return false;
		var pic_list = [img];
		alert(pic_list)
		WeixinJSBridge.invoke('imagePreview', {  
		 'current' : pic_list[0],  
		 'urls' : pic_list
		});
		
		$("#assaas").html("<p class='ccc' ><img class='imageWxview' src='http://testq.zcb2016.com/uploads/images/20160812/1_14709663108448.jpg' data-img='http://testq.zcb2016.com/uploads/images/20160812/1_14709663108448.jpg'></p>")
	});
	$(document).on('click','.imageWxview2',function(){
		var img = $(this).attr('data-img');
		if(!img)return false;
		var pic_list = [img];
		WxImg(img,pic_list)
	});
	

})(jQuery);





</script>