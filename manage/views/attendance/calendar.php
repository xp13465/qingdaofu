<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceCheckinReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$curdate = date("Y-m-d",time());
if($type=="All"){
	$this->title = '考勤日历';
	$this->blocks['content-header'] = Html::a('考勤报表', ['index'], ['class' => 'btn btn-default ']);
	$this->blocks['content-header'].= "　".Html::a("考勤日历", ['calendar-all'], ['class' => 'btn btn-default  active  ']);
	$this->blocks['content-header'].= "　".Html::a('考勤统计', ['statistical'], ['class' => 'btn btn-default ']);
	$this->blocks['content-header'].= "　".Html::a('打卡记录', ['data'], ['class' => 'btn btn-default ']);
}else{
	$this->title = '我的考勤';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<link href='/fullcalendar-3.1.0/fullcalendar.min.css' rel='stylesheet' />
<link href='/fullcalendar-3.1.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='/fullcalendar-3.1.0/lib/moment.min.js'></script>
<script src='/fullcalendar-3.1.0/lib/jquery.min.js'></script>
<script src='/fullcalendar-3.1.0/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,listYear,agendaWeek,agendaDay'
			},
			// weekends:false,
			// hiddenDays:[2],
			defaultDate: '<?=$curdate?>',
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			 
			navLinks: false, // can click day/week names to navigate views
			businessHours: true, // display business hours
			contentHeight: 1000,
			eventOrder: "sort,title",
			monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			dayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
			dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
			// today: ["今天"],
			firstDay: 0,
			buttonText: {
				today: '今天',
				month: '月',
				list: '列表',
				week: '周',
				day: '日',
				// prev: '上一月',
				// next: '下一月'
            },
			eventMouseover: function (calEvent, jsEvent, view) {
				
			    var fstart = calEvent.start.format()
				var fend = calEvent.end?calEvent.end.format():''
			   $(this).attr('title', fstart + " - " + fend + " " + "\n"  + calEvent.title + "\n"  + calEvent.desc);
			   if(calEvent.eventType=="checkinTotal"||calEvent.eventType=="leaveTotal"||calEvent.eventType=="overtimeTotal"||calEvent.eventType=="gooutTotal"){
				$(this).css('cursor', 'pointer');
			   }
			   // $(this).tooltip({
				   // effect: 'toggle',
				   // cancelDefault: true
			   // });
			},
			eventClick: function(event, jsEvent, view) {
				// console.log(event)
				// console.log(jsEvent)
				// console.log(view)
				// alert(event.eventType)
				var targetRoute ='<?=Url::toRoute(["attendance/index"])?>';
				var Url = '';
				if(event.eventType=="checkinTotal"){
					Url = targetRoute+"?AttendanceCheckinReportsSearch%5Bsignstatus%5D="+event.signstatus+"&AttendanceCheckinReportsSearch%5Bsigndate%5D="+event.signdate;
				}else if(event.eventType=="leaveTotal"){
					Url = targetRoute+"?AttendanceCheckinReportsSearch%5Bleaveids%5D=1&AttendanceCheckinReportsSearch%5Bsigndate%5D="+event.signdate;
				}else if(event.eventType=="overtimeTotal"){
					Url = targetRoute +"?AttendanceCheckinReportsSearch%5Bovertimeids%5D=1&AttendanceCheckinReportsSearch%5Bsigndate%5D="+event.signdate;
				}else if(event.eventType=="gooutTotal"){
					Url = targetRoute +"?AttendanceCheckinReportsSearch%5Bgooutids%5D=1&AttendanceCheckinReportsSearch%5Bsigndate%5D="+event.signdate;
				}
				if(Url){
					window.open(Url)
				}
			},
			timeFormat: 'H:mm',
			events: '<?=$eventUrl?>'
		});
		
	});

</script>
<style>

 
	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
</head>
 
	<div id='calendar'></div>

 
