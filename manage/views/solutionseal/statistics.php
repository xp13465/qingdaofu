<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolutionsealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '统计报表';
$this->params['breadcrumbs'][] = $this->title;
$curMonth = date("m",time());
?>
<script>
 var statisticsData = <?=json_encode($statisticsData)?>;
</script>
<p>
     <?= Html::a('管理单据', ['manage'], ['class' => 'btn btn-success']) ?>
</p>
<link rel="stylesheet" href="/css/detaed.css">
<!--div class="title">
   <div class="tx"><span class="left">数据统计</span><span class="right">数据刷新时间:2017-2-17  10:30:29</span></div>
</div-->
<div class="con">
  <div class="shuju">
    <div class="title">
      <div class="tit-l"><p>月度数据统计</p></div>
      <div class="tit-r">
         <select id="staticsticMonth">
			<?php for($i=1;$i<=12;$i++){?>
			<option <?=$i==$curMonth?"selected":"" ?> value="<?=$i?>"><?=$i?>月</option>
			<?php }?>
         </select>
		 <input type="hidden">
      </div>
    </div>
    <div class="pro">
      <ul>
        <li class="first">
          <p class="text dan"><i></i>接单</p>
          <p class="number">58</p>
          <p data-type="40,50,60,70" data-monthtype="startMonth" class="link">查看详情</p>
        </li>
        <li class="second">
          <p class="text an"><i></i>结案</p>
          <p class="number">58</p>
          <p data-type="60" data-monthtype="closedMonth" class="link">查看详情</p>
        </li>
        <li class="third">
          <p class="text hui"><i></i>回款</p>
          <p class="number">100.8<i>万</i></p>
          <p data-type="60" data-monthtype="closedMonth" class="link">查看详情</p>
        </li>
        <li class="fourth">
          <p class="text zhi"><i></i>支付利息</p>
          <p class="number">1.8<i>万</i></p>
          <p data-type="60" data-monthtype="closedMonth" class="link">查看详情</p>
        </li>
      </ul>
    </div>
  </div>

<div class="jiedan">
    <div class="title">
      <div class="tit-l"><p>年度线性图</p></div>
      <div class="tit-r">
         <select id="years">
           <option value="jiedanNum">风控接单</option>
           <option value="jieanNum">风控结案</option>
           <option value="jinjianNum">销售进件</option>
           <option value="payinterestSum">月支付利息</option>
           <option value="actualamountSum">月佣金</option>
           <option value="borrowmoneySum">月放款</option>
           <option value="backmoneySum">月回款</option> 
         </select>
		 <input type="hidden">
      </div>
    </div>
    <div class="rows" style="margin-bottom: 0.8%; overflow: hidden;">
            <div style="float: left; width: 100%;">
                <div style="height: 290px; border: 1px solid #e6e6e6; background-color: #fff;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <canvas id="Canvas3" style="height: 230px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    
 </div>
 
  <div class="bangdan">
    <div class="title">
      <div class="tit-l"><p>榜单</p></div>
      <div class="tit-r">
         <select id="topList">
          <?php for($i=1;$i<=12;$i++){?>
			<option <?=$i==$curMonth?"selected":"" ?> value="<?=$i?>"><?=$i?>月</option>
			<?php }?>
         </select>
      </div>
    </div>
    <div class="infro">
        <div class="list">
          <div class="sales">
            <div class="tit">
              <p class="first"></p>
            </div>
              <ul id="jinjianTopList">
				<?php foreach($statisticsData['jinjianTopList'] as $key=>$top):
					$class=["0"=>"first","1"=>"second","2"=>"third"];
				
				?>
					<li>
					  <span class="name <?=$key<3?$class[$key]:''?>"><?=$top['personnel_name']?></span>
					  <span class="number one"><?=$top['status']?>单</span>
					</li>
				<?php endforeach; ?>
              </ul>
           </div>
          </div>
          <div class="list">
          <div class="sales">
              <div class="tit">
                <p class="second"></p>
              </div>
               <ul id="jiedanTopList" >
                <?php foreach($statisticsData['jiedanTopList'] as $key=>$top):
					$class=["0"=>"first","1"=>"second","2"=>"third"];
				
				?>
					<li>
					  <span class="name <?=$key<3?$class[$key]:''?>"><?=$top['personnel_name']?></span>
					  <span class="number one"><?=$top['status']?>单</span>
					</li>
				<?php endforeach; ?>
              </ul>
           </div>
          </div>
          <div class="list">
          <div class="sales">
              <div class="tit">
                <p class="third"></p>
              </div>
               <ul id="jieanTopList">
                <?php foreach($statisticsData['jieanTopList'] as $key=>$top):
					$class=["0"=>"first","1"=>"second","2"=>"third"];
				
				?>
					<li>
					  <span class="name <?=$key<3?$class[$key]:''?>"><?=$top['personnel_name']?></span>
					  <span class="number one"><?=$top['status']?>单</span>
					</li>
				<?php endforeach; ?>
              </ul>
           </div>
          </div>   
      </div>
  </div>
</div>
<script src="/js/jquery2.2.4.js"></script>
<script src="/js/Chart.js"></script>
<script>
$(document).ready(function(){
	function Canvas3(type) {
		if(type.indexOf("Sum")>-1){
			var unit = "元";
		}else{
			var unit = "单";
		}
        $('#Canvas3').remove();
		$('.panel-body').append('<canvas id="Canvas3" style="height: 230px; width: 100%;"></canvas>');
		var data = [];
		
		for (i in statisticsData[type]){
			data.push(statisticsData[type][i]);
			
		}
		var max = Math.max.apply(null, data);
		data.push("");
		data.push(max*1.2);
        var lineChartData = {
            labels: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "8月", "10月", "11月", "12月"],
			
            datasets: [
                {
                    fillColor: "#578ebe",
					data: data
				}
            ]
        }
        var ctx = document.getElementById("Canvas3").getContext("2d");
        window.myLine = new Chart(ctx).Bar(lineChartData, {
            bezierCurve: false,
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>"+unit,
        });
    }
	var years = $(this).val();
	$("#years").change(function(){
		Canvas3($(this).val());
       
	}).trigger("change")
 
	 //console.log(statisticsData)
	$("#staticsticMonth").change(function(){
		var Month = $(this).val();
		// alert(Month)
		$("li.first p.number").html(statisticsData.jiedanNum[Month]+"单")
		$("li.second p.number").html(statisticsData.jieanNum[Month]+"单")
		var backmoneySum = "";
		if(statisticsData.backmoneySum[Month]>=100000000){
			backmoneySum = (statisticsData.backmoneySum[Month]/10000/10000).toFixed(2)+"亿";
		}else if(statisticsData.backmoneySum[Month]>=10000){
			backmoneySum = (statisticsData.backmoneySum[Month]/10000).toFixed(2)+"万";
		}else{
			backmoneySum = (statisticsData.backmoneySum[Month]/1).toFixed(2)+"元";
		}
		$("li.third p.number").html(backmoneySum).attr("title",statisticsData.backmoneySum[Month]+"元")

		var payinterestSum = "";
		if(statisticsData.payinterestSum[Month]>=100000000){
			payinterestSum = (statisticsData.payinterestSum[Month]/10000/10000).toFixed(2)+"亿";
		}else if(statisticsData.payinterestSum[Month]>=10000){
			payinterestSum = (statisticsData.payinterestSum[Month]/10000).toFixed(2)+"万";
		}else{
			payinterestSum = (statisticsData.payinterestSum[Month]/1).toFixed(2)+"元";
		}
		$("li.fourth p.number").html(payinterestSum).attr("title",statisticsData.payinterestSum[Month]+"元")
	}).trigger("change")
	
	$(".link").click(function(){
		var month = $("#staticsticMonth").val();
		var type = $(this).attr("data-type");
		var monthtype = $(this).attr("data-monthtype");
		var url = "/solutionseal/manage?"+monthtype+"="+month+"&SolutionsealSearch[status]="+type;
		window.open(url)
	})
	
	$("#topList").change(function(){
		var Month = $(this).val();
		$.ajax({
            type: "POST",
            url :"/solutionseal/top?month="+Month,
            data: {},
            dataType: "json",
            success: function(result){
				if(result.code=="0000"){
					for(list in result.data){
						var html = "";
						var className="";
						for(i in  result.data[list] ){
							var curTop = result.data[list][i];
							className ="";
							if(i =="0"){
								className = "first";
							}else if(i =="1"){
								className = "second";
							}else if(i =="2"){
								className = "third";
							}
							html +='<li><span class="name '+className+'">'+curTop['personnel_name']+'</span><span class="number one">'+curTop['status']+'单</span></li>';
						}
						$("#"+list).html(html);
					}
					
				}else if(data.msg){
					layer.msg(data.msg)
				}
                
            }
         });
		
		//console.log(statisticsData.jiedanNum[Month])
	})
	
})

</script>	