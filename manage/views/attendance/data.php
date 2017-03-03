<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceCheckinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '打卡记录';
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = Html::a('考勤报表', ['index'], ['class' => 'btn btn-default ']);
$this->blocks['content-header'].= "　".Html::a("考勤日历", ['calendar-all'], ['class' => 'btn btn-default    ']);
$this->blocks['content-header'].= "　".Html::a('考勤统计', ['statistical'], ['class' => 'btn btn-default ']);
$this->blocks['content-header'].= "　".Html::a('打卡记录', ['data'], ['class' => 'btn btn-default active']);
$firstday  = date("Y-m-01",strtotime("-1 month",time()));
?>
<div class="attendance-checkin-index">

<form id="importform" method="post" action="<?=Url::toRoute("import")?>" target="_blank" enctype="multipart/form-data">
<input style="display:none" id="import" type="file" name="Filedata" class='btn' onchange="if(this.value){$('#importform').submit();this.value=''}">
<a  class="btn btn-2g btn-info" onclick="$('#import').click()" >门禁记录导入</a>
&nbsp;&nbsp;
<?php echo \kartik\date\DatePicker::widget([
							'name' => 'startdate',
							'id' => 'startdate',
							'value'=>$firstday,
							'type' => 1, 
							'options' => [
								"style"=>"width:100px;display:inline-block",
							],
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
				To
<?php echo \kartik\date\DatePicker::widget([
							'name' => 'enddate',
							'id' => 'enddate',
							'value'=>date("Y-m-d",strtotime("+1 month -1 day",strtotime($firstday))),
							'type' => 1, 
							'options' => [
								"style"=>"width:100px;display:inline-block",
							],
							'pluginOptions' => [
								'todayBtn' => true,
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd',
							]
				]);?>
&nbsp;&nbsp;
<a target="_blank" id="btn-script" class="btn btn-2g btn-info" >生成考勤报表</a>
</form>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
			[
				'attribute' => 'number',
				'value' => function ($data) {
					return $data->number;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'employeeid',
				'value' => function ($data) {
					return $data->employeeid;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'username',
				'value' => function ($data) {
					return $data->username;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'signtype',
				'value' => function ($data) {
					return $data->signtype;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'signtime',
				'value' => function ($data) {
					return $data->signtime?date("Y-m-d H:i:s",$data->signtime):'';
				},
				'headerOptions' => ['width' => '180'],
			],
			[
				'attribute' => 'signdate',
				'value' => function ($data) {
					return $data->signdate;
				},
				'headerOptions' => ['width' => '100'],
			],
            'gengzheng',
            'yichang',
            // 'modify_at',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>

<script>
$(document).ready(function(){
	$(document).on("click",'#btn-script',function(){
			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();
			if(enddate&&startdate){
				window.open('/attendance/script?startdate='+startdate+"&enddate="+enddate);
			}else{
				layer.msg("请设置时间范围")
			}
			
			 
	});
})
</script>
