<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm; 
use app\models\Property;
// var_dump($data['result']);
$this->title = "产调本地操作#".$data['orderId'];
$this->params['breadcrumbs'][] = ['label' => '产调管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status = Property::$status;
?>
<link rel="stylesheet" type="text/css" href="/Jbox/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="/Jbox/diyUpload/css/diyUpload.css">
<script type="text/javascript" src="/Jbox/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="/Jbox/diyUpload/js/diyUpload.js"></script>
<div class="row">
    <div class="col-lg-12">
		<?php 
			echo isset($status[$data['status']])?("<h3><b>产调状态：</b>产调".$status[$data['status']]."</h3>"):'';
			$html = '';
			if($data['status']==3){
				if($data['result']){
					$html ="<h2>仟房反馈信息：</h2>";
					$html .="<p><b>申请时间：</b>".date("Y-m-d H:i:s" , $data['result']['create_time'])."</p>";
					$html .="<p><b>处理时间：</b>".date("Y-m-d H:i:s" , $data['result']['deal_time'])."</p>";
					$html .="<p><b>退款原因：</b>{$data['result']['refund_msg']}</p>";
					$html .="<p><b>支付金额：</b>{$data['result']['amount']}</p>";
					$html .="<p><b>退款金额：</b>{$data['result']['refund_fee']}</p>";
					
				}else{
					$html ="<h2>仟房无反馈信息！：</h2>";
				}
				echo Html::tag("h4",$html);
			}
		?>
		<hr/>
        <div class="table-responsive">
			<b>产调地址：</b><?php echo $data['area'],$data['address'];?><br><br>
			<input type='hidden' id='id' value="<?php echo $data['id'];?>"  />
			<input type='hidden' id='cid' value="<?php echo $data['cid'];?>"  />
			<input type='hidden' id='area' value="<?php echo $data['area'];?>"  />
			<input type='hidden' id='address' value="<?php echo $data['address'];?>"  />
			<input type='hidden' id='orderId' value="<?php echo $data['orderId'];?>"  />
			<input type='hidden' id='phone' value="<?php echo $data['phone'];?>"  />
			<b>退款金额：</b><input type='text' id='refund_fee' size='50' placeholder="没有可以为空,有金额代表退款操作！" /><br><br>
			<b>退款原因：</b><input type='text' id='refund_msg' size='50' placeholder="没有可以为空" /><br><br>
			<span><input type='hidden' id='pics' value='' /></span>
			<div id="as"></div> 
			<center>
				<button class="btn btn-2g btn-primary" onClick='sublic();'>确认提交</button>
			</center>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#as').diyUpload({
	url:'/Jbox/server/fileupload.php',
	success:function( data ) {
		//console.info( data );
		var pic = $('#pics').val();
		if(pic == ''){
			$('#pics').val(data._raw);
		}else{
			$('#pics').val(pic+','+data._raw);
		}
	},
	error:function( err ) {
		console.info( err );	
	},
	buttonText : '选择上传成功产调的文件',
	chunked:true,
	// 分片大小
	chunkSize:512 * 1024,
	//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
	fileNumLimit:50,
	fileSizeLimit:500000 * 1024,
	fileSingleSizeLimit:50000 * 1024,
	accept: {
		title:"Images",
		extensions:"gif,jpg,jpeg,bmp,png",
		mimeTypes:"image/*"
	}
});

function sublic(){
	var id = $('#id').val();
	var cid = $('#cid').val();
	var orderId = $('#orderId').val();
	var refund_fee = $('#refund_fee').val();
	var refund_msg = $('#refund_msg').val();
	var pics = $('#pics').val();
	var area = $('#area').val();
	var address = $('#address').val();
	var phone = $('#phone').val();
	if(! id){
		alert('数据有误...');
		return;
	}
	if(! refund_fee && ! refund_msg && ! pics){ 
		alert('请上传产调图片...');
		return;
	}
<?php 
		if($data['status']==3){ 
?>
		if(!refund_fee){
			var ok = confirm("仟房反馈为失败退款，当前操作属于产调成功！确认操作？");
			if(!ok)return false;
		}
<?php 	
		}
?> 
	$.post('/property/dosd',{id:id,refund_fee:refund_fee,refund_msg:refund_msg,pics:pics,orderId:orderId,address:address,area:area,phone:phone,cid:cid},function(s){
		if(s.status == 1){
			// alert('操作成功...');
			alert(s.info);
			location.href = '/property/index';
		}else{
			alert(s.info);
			// alert('操作失败...');
		}
	},'json');
}
</script>
<style>
	.parentFileBox{border:1px solid #CCC;}
	.parentFileBox img{width:170px;height:150px;}
</style>
