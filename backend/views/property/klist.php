<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '快递列表';
?>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
		<!--
			<select name='status' id='status'>
				<option value=''>所有状态</option>
				<option value='-1'>已付款</option>
				<option value='1'>审核中</option>
				<option value='2'>已成功</option>
				<option value='3'>退款中</option>
				<option value='4'>已退款</option>
				<option value='0'>未付款</option>
			</select>
			<input name='phone' type='text' id='phone' />
			<button type="button" class="btn btn-2g btn-primary" id='seach'>查找</button>
			<a href='/property/klist'>快递</a>
			<br /><br />
			-->
            <table class="table table-bordered table-hover table-striped" style='text-align:center;'>
                <thead>
					<tr>
						<th><center>序号</center></th>
						<!--th width="15%"><center>用户名</center></th-->
						<th><center>订单号</center></th>
						<th width="35%"><center>地址</center></th>
						<th width="15%"><center>手机</center></th>
						<th width="10%"><center>付款</center></th>
						<th width="15%"><center>时间</center></th>
						<th width="10%"><center>状态</center></th>
						<th width="10%"><center>快递单号</center></th>
						<th width="10%"><center>操作</center></th>
					</tr>
                </thead>
                <tbody>
                <?php $cc = 1;foreach($data as $v):?>
                    <tr>
                        <td><?php echo $pagination->page*$pagination->pageSize+$cc++;?></td>
						<td><?php echo isset($v['orderId'])?$v['orderId']:''?></td>
                        <td>
							<?php echo isset($v['city'])?$v['city']:''?>
							<?php echo isset($v['address'])?$v['address']:''?>
						</td>
                        <td><?php echo isset($v['phone'])?$v['phone']:''?></td>
						<td><?php echo isset($v['money'])?$v['money']:''?></td>
                        <td><?php echo isset($v['time'])?date('Y-m-d H:i:s',$v['time']):''?></td>
                        <td>
                            <?php
                                $status = ['0'=>'未付款','-1'=>'已付款','1'=>'审核中','2'=>'成功','3'=>'退款中','4'=>'已退款'];
                                echo isset($v['status'])?$status[$v['status']]:''
                            ?>
                        </td>
						 <td><?php echo isset($v['kdOrderOd'])?$v['kdOrderOd']:''?></td>
						
						<td>
							<a href='javascript:;' class='bdkd' rel="<?php echo $v['id']?>">快递</a>
						</td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <div class="fenye clearfix">
				<?= \yii\widgets\LinkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '最后一页',
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
			</div>
        </div>
    </div>
</div>
</div>
<script>
	$(function(){
		$('#status').change(function(){
			var status = $("#status").val();
			var phone = $('#phone').val();
			self.location = '/property/klist?status='+status+'&phone='+phone;
		});
		$('#seach').click(function(){
			var status = $("#status").val();
			var phone = $('#phone').val();
			self.location = '/property/klist?status='+status+'&phone='+phone;
		});
		$('.bdkd').click(function(){
			var od = $(this).attr('rel');
			location.href = '/property/express?id='+od;
		});
	});
</script>