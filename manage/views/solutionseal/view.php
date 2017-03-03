<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Solutionseal */
// echo "<pre>";
// print_r($model);
$this->title = $model->solutionsealid;
$this->params['breadcrumbs'][] = ['label' => 'Solutionseals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solutionseal-view">
<?php if($type = '1' && $model->status == '0'){ ?>
	 <p>
        <?= Html::a('修改', ['update', 'id' => $model->solutionsealid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($model->validflag=='1'?'删除':'恢复','javascript:void(0);', [
            'class' => 'btn btn-danger',
			'data-id'=>$model->solutionsealid,
			'data-validflag'=>$model->validflag=='1'?'0':'1',
        ]) ?>
    </p>
<?php } ?>
	<table border=1 width="1000" class="solutionsealform" >
	<colgroup>
		<col width="120"/>
		<col/>
	</colgroup>
		<tr>
				<td>
				<?= Html::label('客户姓名','custname')?>
				</td>
				<td><?= Html::input('text','custname',$model->custname,['style' => "width:300px;display:inline-block",'readonly'=>'readonly'])?></td>
		</tr>
		<tr>
				<td>
				<?= Html::label('客户联系方式','custmobile')?>
				</td>
				<td>
				<?= Html::input('text','custmobile',$model->custmobile,['style' => "width:300px;display:inline-block",'readonly'=>'readonly'])?>
				</td>
		</tr>
		<tr>
				
			<td>
				<?= Html::label('销售姓名','personnel_name')?>
			</td>
			<td>
			<?= Html::input('text','personnel_name',$model->personnel_name,['style' => "width:300px;display:inline-block",'readonly'=>'readonly'])?>
				员工：<?= $model->personnel_name ?>
			</td>
		</tr>
		
		<tr>
			<td>
				<?= Html::label('债权类型','category')?>
			</td>
			<td>
				<?= Html::checkboxList('category',[$model->category],\app\models\Product::$category,['style' => "display:inline-block",'class'=>'checkbok'])?>
			
			</td>
		</tr>
		
		<tr>
			<td>
				<?= Html::label('委托事项','entrust_other')?>
			</td>
			<td>
				<?= Html::input('text','entrust_other',$model->entrust_other,['style' => "width:300px;",'readonly'=>'readonly'])?>
			</td>
		</tr>
		
		<tr>
			<td>
				<?= Html::label('金额','account')?>
			</td>
			<td>
				<?= Html::input('text','account',$model->account,['style' => "width:300px;display:inline-block",'readonly'=>'readonly'])?>
			</td>
		</tr>
		
		<tr>
			<td>
				
				<?= Html::label($model->type='1'?'固定费用':'比例费用','account')?>
			</td>
			<td>
				<?= Html::input('text','typenum',$model->typenum,['style' => "width:300px;display:inline-block",'readonly'=>'readonly'])?>   <?= $model->type='1'?'元':'%' ?>
			</td>
		</tr>
		
		<tr>
			<td>
				<?= Html::label('违约期限','overdue')?>
			</td>
			<td>
			    <?= Html::input('text','overdue',$model->overdue,['style'=>'width:300px;display:inline-block','readonly'=>'readonly'])?>
			</td>
		</tr>
		<tr>
			<td>
			     <?= Html::label('合同履行地','province_id')?>
			</td>
			<td>
			    <?= Html::input('text','overdue',$model->provincename->province .' '. $model->cityname->city .' '. $model->areaname->area,['style'=>'width:300px;display:inline-block','readonly'=>'readonly'])?>
			</td>

		</tr>
	</table>
</div>
<script>
	$(document).ready(function(){
		var category = '<?= $model->category ?>';
		if(category){
			$('.checkbok').each(function(){
				$(this).children().children().attr('disabled','disabled');
			})
		};
		 $(document).on('click','.btn-danger',function(){
			 var id = $(this).attr('data-id');
			 var validflag = $(this).attr('data-validflag');
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm(validflag=='1'?'是否需要删除该员工信息':'确定是否恢复该员工信息',
			 {
				 title:false,
				 conseBtn:false,
				 btn:['确定','取消'],
			 },function(){
				 $.ajax({
					 url:'<?= yii\helpers\Url::toRoute('solutionseal/delete') ?>',
					 type:'post',
					 data:{id:id,validflag:validflag},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){
									if(validflag == '1'){
											obj.html('删除');
											obj.attr('data-validflag','0')	
										}else{
											obj.html('恢复');
											obj.attr('data-validflag','1')					
										}
								});
								layer.close(load);
						}else{
								layer.msg('参数错误');
								layer.close(load)
						}
					 }
				 })
			 },function(){
				layer.close(load);
			})
		 })
	})
</script>
