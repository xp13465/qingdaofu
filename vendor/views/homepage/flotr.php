<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body {
            margin: 0px;
            padding: 0px;
        }
        #container {
            width : 490px;
            height: 295px;
            margin-left: 8px;
            margin-top:30px;
        }
    </style>
</head>
<body>
<div id="container">  </div>
<?php
$first = date("Y-01-01");
$second = date("Y-04-01");
$third = date("Y-07-01");
$four = date("Y-10-01");
$end = date("Y-12-31 23:59:59");

//$numPerMonthdAY =  (strtotime(" + 1 months ",strtotime(date("Y-m-01")))-strtotime(date("Y-m-01")))/86400;
$days = [];

for($inum = 1;$inum <= 31 ;$inum++){
    $start = strtotime(" - 1 months +{$inum} days ",strtotime(date("Y-m-d")));
    $next = strtotime(" - 1 months +".($inum+1)." days ",strtotime(date("Y-m-d")));
    $days[$inum] = getCountFinance('create_time '. ' between ' . ($start).' and '.$next) + getCountCreditor('create_time '. ' between ' . ($start).' and '.$next) ;
}

//echo $numPerMonthdAY;
          /*$firstC =  getCountFinance('create_time '. ' between ' . (strtotime($first).' and '.strtotime($second)))+getCountCreditor('create_time '. ' between ' . (strtotime($first).' and '.strtotime($second)));
          $secondC = getCountFinance('create_time '. ' between ' . (strtotime($second).' and '.strtotime($third)))+getCountCreditor('create_time '. ' between ' . (strtotime($second).' and '.strtotime($third)));
          $thirdC =  getCountFinance('create_time '. ' between ' . (strtotime($third).' and '.strtotime($four)))+getCountCreditor('create_time '. ' between ' . (strtotime($third).' and '.strtotime($four)));
          $fourthC = getCountFinance('create_time '. ' between ' . (strtotime($four).' and '.strtotime($end)))+getCountCreditor('create_time '. ' between ' . (strtotime($four).' and '.strtotime($end)));*/

// $app = Yii::$app->db->createCommand("select count(*) from zcb_creditor_product as z left join zcb_finance_product as p on where category = {}")->queryScalar();

$total = max($days);
$yzhouzhi = $total/10<=1?1*10:($total/10+1)*10-$total%10;

    function getCountFinance($where){
        return Yii::$app->db->createCommand("select count(*) from zcb_finance_product where category = 1 and progress_status !=0 and ".$where)->queryScalar();
    }
    function getCountCreditor($where){
        return Yii::$app->db->createCommand( "select count(*) from zcb_creditor_product where category in (2,3) and progress_status !=0 and ".$where)->queryScalar();
    }
    ?>
<script type="text/javascript" src="/js/flotr2/flotr2.min.js">  </script>
<script type="text/javascript">
    function drawChart(data){
        var container = document.getElementById("container");
        var option = {
            colors: ['#00A8F0', '#C0D800', '#CB4B4B', '#4DA74D', '#9440ED'],  //线条的颜色
            ieBackgroundColor:'#3ec5ff',                    //选中时的背景颜色
            shadowSize:5,                                 //线条阴影
            defaultType:'lines',                           //图表类型,可选值:bars,bubbles,candles,gantt,lines,markers,pie,points,radar
            HtmlText:true,                                //是使用html或者canvas显示 true:使用html  false:使用canvas
            fontColor:'#ff3ec5',                           //字体颜色
            fontSize:7.5,                                  //字体大小
            resolution:1,                                  //分辨率 数字越大越模糊
            parseFloat:true,                               //是否将数据转化为浮点型
            xaxis: {
                ticks:[<?php
                    for($inum = 1;$inum <= 31 ;$inum++){
                        $start = strtotime(" - 1 months +{$inum} days ",strtotime(date("Y-m-d")));
                        echo "[$inum,'".date("d日",$start)."'],";
                    }
                ?>], // 自定义X轴
                noTicks:31,                                   //当使用自动增长时,x轴刻度的个数
                tickFormatter: Flotr.defaultTickFormatter,   //刻度的格式化方式
                autoscale:true,
            },
            x2axis:{
            },
            yaxis:{
                //// =>  Y轴配置与X轴类似
                ticks: [
                    [0,"0"],
                    [<?php echo $yzhouzhi/10*1?>,"<?php echo $yzhouzhi/10*1?>"],
                    [<?php echo $yzhouzhi/10*2?>,"<?php echo $yzhouzhi/10*2?>"],
                    [<?php echo $yzhouzhi/10*3?>,"<?php echo $yzhouzhi/10*3?>"],
                    [<?php echo $yzhouzhi/10*4?>,"<?php echo $yzhouzhi/10*4?>"],
                    [<?php echo $yzhouzhi/10*5?>,"<?php echo $yzhouzhi/10*5?>"],
                    [<?php echo $yzhouzhi/10*6?>,"<?php echo $yzhouzhi/10*6?>"],
                    [<?php echo $yzhouzhi/10*7?>,"<?php echo $yzhouzhi/10*7?>"],
                    [<?php echo $yzhouzhi/10*8?>,"<?php echo $yzhouzhi/10*8?>"],
                    [<?php echo $yzhouzhi/10*9?>,"<?php echo $yzhouzhi/10*9?>"]
                ],
                min: -1,             // =>  min. value to show, null means set automatically
                max: <?php echo $yzhouzhi;?>,             // =>  max. value to show, null means set automatically
                autoscale: false,      // =>  Turns autoscaling on with true
            },
            y2axis:{
            },
            grid: {
                color: '#545454',      // =>  表格外边框和标题以及所有刻度的颜色
                labelMargin: 0,        // =>  margin in pixels
                verticalLines: false,   // =>  表格内部是否显示垂直线条
                horizontalLines: true, // =>  表格内部是否显示水平线条
                outlineWidth: 0,       // =>  表格外边框的粗细
            },
            mouse:{
                track: true,       // =>  为true时,当鼠标在线条上移动时,显示所在点的坐标
                trackAll: true,
                position: 'se',        // =>  鼠标事件显示数据的位置 (default south-east)
                relative: false,       // =>  当为true时,鼠标移动时,即使不在线条上,也会显示相应点的数据
                trackFormatter: Flotr.defaultTrackFormatter, // =>  formats the values in the value box
                margin: 0,             // =>  margin in pixels of the valuebox
                lineColor: '#FF3F19',  // =>  鼠标移动到线条上时,点的颜色
                trackDecimals: 0,      // =>  数值小数点后的位数
                sensibility: 2,        // =>  值越小,鼠标事件越精确
                trackY: true,          // =>  whether or not to track the mouse in the y axis
                radius: 0,             // =>  radius of the track point
                fillColor: null,       // =>  color to fill our select bar with only applies to bar and similar graphs (only bars for now)
                fillOpacity: 0.4      // =>  o
            }
        };
        // Draw Graph
       Flotr.draw(container, data, option);
    }
</script>
<script type="text/javascript">
        var first = "<?php echo isset($firstC)?$firstC:''; ?>";
        var second = "<?php echo isset($secondC)?$secondC:''; ?>";
        var third = "<?php echo isset($thirdC)?$thirdC:''; ?>";
        var fourth = "<?php echo isset($fourthC)?$fourthC:''; ?>";


    var
        bj = [[0],<?php
                    for($inum = 1;$inum <= 31 ;$inum++){
                        echo "[$inum,'".$days[$inum]."'],";
                    }
                ?>],// First data series
        sz = [[1, 15], [2, 16], [3, 19], [4, 22],[5, 26], [6, 27], [7, 28], [8, 28],[9, 27], [10, 25], [11, 20], [12, 16]]; //Second data series
    var data = [
        { data : bj,lines : { show : true }, points : { show : true }},
    ];
    drawChart(data);
</script>
</body>
</html>