<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceOvertimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$leavetypeLabel = \app\models\AttendanceLeave::$leavetypeLabel;
$action = Yii::$app->controller->action->id;

$this->title = '请假单审核';
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = Html::a('我的请假单', ['index'], ['class' => 'btn btn-default ']);
	$this->blocks['content-header'].= "　".Html::a("待审核", ['audit'], ['class' =>isset($action)&&$action=='audit'?'btn btn-default active':'btn btn-default']);
	$this->blocks['content-header'].= "　".Html::a('已审核', ['audited-list'], ['class' =>isset($action)&&$action=='audited-list'?'btn btn-default active':'btn btn-default']);
?>
<div class="attendance-overtime-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'id',
				'value' => function ($data) {
					return $data->id;
				},
				'headerOptions' => ['width' => '60'],
			],
			[
				'attribute' => 'username',
				'value' => function ($data) {
					return $data->username;
				},
				'headerOptions' => ['width' => '80'],
			],
			[
				'attribute' => 'department',
				'value' => function ($data) {
					return $data->department;
				},
				'headerOptions' => ['width' => '120'],
			],
			[
				'attribute' => 'leavetype',
				'filter'=>$leavetypeLabel,
				'value' => function ($data) {
					// var_dump($leavetypeLabel);
					$leavetypeLabel = \app\models\AttendanceLeave::$leavetypeLabel;
					return isset($leavetypeLabel[$data->leavetype])?$leavetypeLabel[$data->leavetype]:"";
				},
				'headerOptions' => ['width' => '70'],
			],
			['attribute'=>'status',
			 'filter' => \app\models\AttendanceOvertime::$statusLabel,
			 'label'=>'状态',
			 'value'=>function($data){
				  return $data->statusLabel;
			 },
			 'options' => ['width' => '130'],
			],
			[
				'attribute' => 'leavestart',
				'value' => function ($data) {
					$html = "";
					$html .=$data->leavestart?date("Y-m-d",$data->leavestart):"";
					$date2 =$data->leavestart?date("H:i",$data->leavestart):"";
					$html .=$date2=="00:00"?"":" {$date2}";
					return $html;
				},
				'headerOptions' => ['width' => '135'],
			],
			[
				'attribute' => 'leaveend',
				'value' => function ($data) {
					$html = "";
					$html .=$data->leaveend?date("Y-m-d",$data->leaveend):"";
					$date2 =$data->leaveend?date("H:i",$data->leaveend):"";
					$html .=$date2=="00:00"?"":" {$date2}";
					return $html;
				},
				'headerOptions' => ['width' => '135'],
			],
			[
				'attribute' => 'leaveday',
				'value' => function ($data) {
					return ($data->leaveday>0?($data->leaveday."天"):"").($data->leavehour>0?($data->leavehour."时"):"");
				},
				'headerOptions' => ['width' => '90'],
			],
			['attribute'=>'toexamineid',
			 'label'=>'待操作人',
			 'value'=>function($data){
				 $data = $data->getOperationUser($data->toexamineid);
				 return $data;
			 },
			 'options' => ['width' => '130'],
			],
            ['class' => 'yii\grid\ActionColumn',
			 'header'=>'操作',
			 'template'=>'&nbsp;&nbsp;{view}&nbsp;&nbsp;{auditBtns}',
			 'buttons'=>[
				'auditBtns'=>function($uid,$data){
					 $userid = Yii::$app->user->getId();
					if($data->toexamineid&&in_array($userid,explode(",",$data->toexamineid))){
						 return $data->auditBtns($uid);
					}
				 },
			],
			'options' => ['style' => 'width:120px;'],
        ],
		],
    ]); ?>
<?php Pjax::end(); ?></div>
<style>
.js-signature{width:320px;height:100px;position:relative;}
#signature{width:320px;height:100px;position:absolute;top:-41px;left:48px;z-index:-5;}
.butt{margin:0 364px 10px;}
</style>
<script src="/js/signature/jq-signature.js"></script>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		var curTips ="";
	$(document).on("click",".layertips",function(){
		var title =$(this).attr("data-title");
		layer.tips(title, $(this), {
		  tips: [1, '#3595CC'],
		  time: 4000
		});
	}).on("mouseover",".layertips",function(){
		var title =$(this).attr("data-title");
		curTips = layer.tips(title, $(this), {
		  tips: [1, '#3595CC'],
		  time: 4000
		});
	}).on("mouseout",".layertips",function(){
		if(curTips){
			layer.close(curTips)
			curTips = '';
		}
	})
		 $(document).on('click','.delete',function(){
			 var id = $(this).attr('data-id');
			  var afterstatus = $(this).attr('data-afterstatus');
			  var beforestatus = $(this).attr('data-beforestatus');
			  var status = $(this).attr('data-status');
			 var obj = $(this);
		     var load = layer.load(1,{shade:[0.4,'#fff']});
			 layer.confirm('',
			 {   
				 // type:1,
				 content:'<span style="float:left;margin-right:20px;">备注</span><textarea id="supervisormemo" class="form-control" name="supervisormemo" maxlength="1000" style="width:400px;height:80px;"></textarea><button id="autograph" class="btn btn-default"style="margin:24px 0px 7px -12px">签名</button><div class="htmleaf-container" style="display:none"><div class="container"><div class="row"><div class="col-xs-12" ><div class="js-signature" id="ceb" data-width="600" data-height="200" data-border="1px solid black" data-line-color="#bc0000" data-auto-fit="true" style="margin:-41px 0px -100px 33px;"></div><p class="butt"><button data-tpid="" id="saveBtn" class="btn btn-default" onclick="saveSignature();" class="butts" style="margin:-58px 0px -65px 0px" disabled >保存</button></p><div id="signature"></div></div></div></div></div>',
				 title:'加班单审核',
				 conseBtn:false,
				 area:['500px','420px'],
				 btn:['确定','取消'],
			 },function(){
				 var supervisormemo = $('#supervisormemo').val();
				 var tpid = $('#saveBtn').attr('data-tpid');
				 $.ajax({
					 url:'<?= yii\helpers\Url::toRoute('leave/status') ?>',
					 type:'post',
					 data:{id:id,supervisormemo:supervisormemo,tpid:tpid,beforestatus:beforestatus,afterstatus:afterstatus,status:status},
					 dataType:'json',
					 success:function(json){
						 if(json.code == '0000'){
								layer.msg(json.msg,{time:2000},function(){window.location.reload()});
								layer.close(load);
						}else{
								layer.msg(json.msg);
								layer.close(load)
						}
					 }
				 })
			},function(){
				layer.close(load);
			})
			layer.close(load);
			$('.js-signature').jqSignature();
			$('#ceb').on('jq.signature.changed', function() {
				$('#saveBtn').attr('disabled', false);
			});
		 })
	})
	
	$(document).on('click','#autograph',function(){
		$('.htmleaf-container').toggle();//css('display','block');
		$('#ceb').jqSignature('clearCanvas');
		$('#saveBtn').attr('disabled', true).css('display','block').parent().siblings('.js-signature').css('display','block');
	})
	function saveSignature() {
		$('#signature').empty();
		var dataUrl = $('#ceb').jqSignature('getDataURL');
		$.ajax({
			url:"<?php echo yii\helpers\Url::toRoute(['/site/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			data: {dataUrl:dataUrl},
			dataType:'json',
			success: function (data) {
				if(data.error == '0'&&data.fileid){	
						var img = $('<img>').attr('src',data.url);
						$('#saveBtn').attr('data-tpid',data.fileid);
						$('#signature').append(img).siblings('p').css('margin','-41px 364px 10px').siblings('.js-signature').css('display','none');
						$('#saveBtn').attr('disabled','disabled').css('display','none');
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			}
		})
	}
</script>
	<script type="text/javascript">

	</script>
