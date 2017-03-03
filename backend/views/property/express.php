<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '快递信息';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            产调地址:<?php echo $data['area'],$data['address'];?><br><br>
			收件人名:<?php echo $data['name'];?><br><br>
			收件地址:<?php echo $data['addre'];?><br><br>
			收件手机:<?php echo $data['phone'];?><br><br>
			<input type='hidden' id='id' value="<?php echo $data['id'];?>"  />
			<input type='hidden' id='phone' value="<?php echo $data['phone'];?>" />
			快递单号:<input type='text' id='orderId' />
			<center>
				<button class="btn btn-2g btn-primary" onClick='sublic();'>确认提交</button>
			</center>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

function sublic(){
	var id = $('#id').val();
	var phone = $('#phone').val();
	var orderId = $('#orderId').val();
	if(! id){
		alert('数据有误...');
		return;
	}
	if(! orderId){
		alert('请填写快递单号...');
		return;
	}
	$.post('/property/pexp',{id:id,orderId:orderId,phone:phone},function(s){
		if(s.status == 1){
			alert('操作成功...');
			location.href = '/property/index';
		}else{
			alert('操作失败...');
		}
	},'json');
}
</script>
