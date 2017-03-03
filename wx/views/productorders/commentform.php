<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wx\widget\wxHeaderWidget;
$title = $type==2?"追加评价":'添加评价';
$backurl = $type==2?yii\helpers\Url::toRoute(['/productorders/comment-list',"ordersid"=>$ordersid]):true;
$callbackurl = yii\helpers\Url::toRoute(['/productorders/comment-list',"ordersid"=>$ordersid]);
?>
<?=wxHeaderWidget::widget(['title'=>$title,'gohtml'=>'','backurl'=>$backurl,'reload'=>false])?>
  <div class="pj_star" <?=$type==2?"style='display:none'":""?>>
    <ul >
      <li> <span>真实性</span>
        <div class="stars">
          <div id="star1">  <input type='checkbox' name='truth_score' class='truth_score'></div>
          <div id="result1"><input type='checkbox' name='truth_score' class='truth_score'></div>
        </div>
      </li>
      <li> <span>配合度</span>
        <div class="stars">
          <div id="star2">  <input type='checkbox' name='assort_score' class='assort_score'></div>
          <div id="result2"><input type='checkbox' name='assort_score' class='assort_score'></div>
        </div>
      </li>
      <li> <span>响应度</span>
        <div class="stars">
          <div id="star3">  <input type='checkbox' name='response_score' class='response_score'></div>
          <div id="result3"><input type='checkbox' name='response_score' class='response_score'></div>
        </div>
      </li>
    </ul>
  </div>
	<div class="xq">
	 <textarea type="text" name="memo" id="memo" placeholder="请填写评价内容" style="width:100%;height:60px;padding-left:10px;resize:none"></textarea>
	  <ul>  
		<li class='pic'>
		<?php echo Html::hiddenInput("files",'',['data_url'=>'']);?>
		<a href="javascript:void(0)" class="addtu" inputName='files' limit = '10' >
		<img src="/bate2.0/images/tian.png">
		</a>
		</li> 
	  </ul>
</div>
<footer>
  <div class="case">
    <a href="javascript:void(0);" class="agree" data-ordersid = '<?= Yii::$app->request->get('ordersid')?>' data-type='<?=$type?>'><?=$title?></a> 
  </div>
</footer>
<script>

$(document).ready(function(){
	$(".agree").click(function(){
		var truth_score = $("#star1").children('input[name=score]:hidden').val();
		var assort_score = $("#star2").children('input[name=score]:hidden').val();
		var response_score = $("#star3").children('input[name=score]:hidden').val();
		var memo = $("#memo").val();
		var files = $("input[name='files']").val();
		var ordersid = $(this).attr('data-ordersid');
		var commentType = $(this).attr('data-type');
		if(commentType == 1){
			var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>";
            var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files};
		}else if(commentType == 2){
			var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-additional')?>";
			var data = {ordersid:ordersid,memo:memo,files:files};
		}else{
			var url = "<?php echo yii\helpers\Url::toRoute('/productorders/comment-add')?>";
			var data = {ordersid:ordersid,truth_score:truth_score,assort_score:assort_score,response_score:response_score,memo:memo,files:files};
		}
		var index = layer.load(1,{
			 shade:[0.4,'#fff']
		 });
		$.ajax({
			url:url,
			type:'post',
			data:data,
			dataType:'json',
			success:function(json){
				if(json.code=="0000"){
					layer.msg(json.msg,{time:1000},function(){location.href='<?=$callbackurl?>'})
				}else{
					layer.msg(json.msg)
				}
				layer.close(index);
			}
		})
	})
	
 
}); 

	 
</script>

<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="file" id='id_photos' value="" />
<script>	
$(document).ready(function(){
	
    
    $(document).on("click",".closebutton",function(){
                var id = $(this).parents().children('.pic').children('input').val();
                var bid = $(this).next().attr('data_bid');
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                    		if(ids[i]==bid){
                    			continue;
                    		}
                    		temp+=temp?","+ids[i]:ids[i];
                    	}
               	$(this).parents().children('.pic').children('input').val(temp)
            	$(this).parent().remove();
    });
		
	//照片异步上传
	$(".addtu").click(function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"limit":limit}).click();
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var inputName = $(this).attr('inputName');
		var limit = $(this).attr('limit')?$(this).attr('limit'):5;
		var aa = $("input[name='" + inputName + "']").val();
		if(limit&&aa.split(",").length>=limit){
			layer.close(index)
			layer.alert("最多上传"+limit+"张图片");
			return false;
		} 
		if(!inputName)return false;
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/common/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {},
			textareas:{},
			dataType: "json",
			success: function (data) {
				// console.log(data);
				layer.close(index)
				 var wx = "<?php echo Yii::$app->params['wx'];?>";
				if(data.code == '0000'&&data.result.fileid){
					var aa = $("input[name='" + inputName + "']").val();
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						return false;
					}  
					$("input[name='" + inputName + "']").val((aa ? (aa + ",") : '')+data.result.fileid).trigger("change"); 
					var div ='<li><img onclick="javascript:void(0)" src = "/images/close.gif" class="closebutton"/ style="position: absolute;z-index: 20;display: block;"> <img onclick="javascript:void(0)" class="imagePreview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;width:65px;height:65px;"/></li>';
					
					var spandiv = $("input[name='" + inputName + "']").parent("li");
					if(spandiv.index()==0){
						spandiv.parent().prepend(div)
					}else{
						spandiv.prev().after(div);
					}
				}else if(data.msg){
					
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
});
</script>	
<script src="/js/jquery.raty.js" type="text/javascript"></script>
<script type="text/javascript">
rat('star1','result1',2);
rat('star2','result2',2);
rat('star3','result3',2);
function rat(star,result,m){

	star= '#' + star;
	result= '#' + result;
	$(result).hide();

	$(star).raty({
		hints: ['10','20', '30', '40', '50'],
		path: "../images",
		starOff: 'star-off-big.png',
		starOn: 'star-on-big.png',
		size: 24,
		start: 50,
		showHalf: true,
		target: result,
		targetKeep : true,
		click: function (score, evt) {
			//alert('你的评分是'+score*m+'分');
		}
	});

}
</script>
