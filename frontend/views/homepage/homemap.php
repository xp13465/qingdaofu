<style type="text/css">
    #allmap {width: 1000px;height: 650px;overflow: hidden;margin:20px 0px;font-family:"微软雅黑";}
    #allmap a:hover{color:white;}
    #allmap .zindextop:hover{z-index: 100;}
</style>


<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=w10qOR1ck89d9YevXrdWPqGg"></script>
<?php
$category = [
    0=>'资产类型',
    1=>'　融资　',
    2=>'　清收　',
    3=>'　诉讼　',
];
$money = [
    0=>'资产总额',
    1=>'30万以下',
    2=>'30-100万',
    3=>'100-500万',
    4=>'500万以上',
];

$district = \frontend\services\Func::getDistrictByCity('310100');
?>

<div class="index">
    <div class="map">
        <div class="map_img">
<div class="cons1">
    <div class="maps">

        <div id="allmap"></div>

        <div class="con1_left">
            <div class="con1_top">
                <input type="text" value="<?php echo Yii::$app->request->get('nsearch');?>" class="nsearch" placeholder="请输入关键字"onkeydown="if(event.keyCode==13) searchMapForm();"/><i class="search"></i>
                <p class="chos">
                                <span class="district" id="<?php echo $dis =  Yii::$app->request->get('district') ?Yii::$app->request->get('district'):0; ?>">
                                <?php echo isset($district[$dis])?$district[$dis]:'区域'?></span><i class="jt1"></i>&nbsp&nbsp&nbsp |<span id="<?php echo $cat = Yii::$app->request->get('category') ?Yii::$app->request->get('category'):0; ?>" class='category'>
                                <?php echo $category[$cat];?></span><i class="jt2"></i>  　|<span class="money" id = "<?php echo $mon = Yii::$app->request->get('money') ?Yii::$app->request->get('money'):0; ?>">
                                <?php echo $money[$mon];?></span><i class="jt3"></i>
                </p>

                <div id="qy" >
                    <span id="0">不限</span>
                    <?php foreach($district as $k=>$dt){
                        if($k != '')echo '<span id="'.$k.'">'.$dt.'</span>';
                    }?>
                </div>
                <div id="zclx">
		            <span id="0">　不限　</span>
		            <span id="1">　融资　</span>
		            <span id="2">　清收　</span>
		            <span id="3">　诉讼　</span>
		        </div>
		        <div id="zcze">
		            <span id="0">不限</span>
		            <span id="1">30万以下</span>
                    <span></span>
		            <span id="2">30-100万</span>
		            <span id="3">100-500万</span>
		            <span id="4">500万以上</span>
		        </div>

            </div>
            <div class="con1_bottom">
                <i class="shangla "></i>
                <div class="quyu ">

                    <?php
                    if(Yii::$app->request->get('district') ==0){
                    foreach($data as $key=>$val){
                        echo "<a href=".\yii\helpers\Url::toRoute('/homepage/homemap')."?category=0&&money=0&&district={$key}&&nsearch=>
                        <p class='clearfix'>"."<span class='fl'>".(\Yii::$app->db->createCommand("select area from zcb_area where areaID={$key}")->queryScalar())."</span>"."<span class='fr'>".$val."件</span></p></a>";
                    }
                    } else{
                      foreach($data as $dd){
                          $district_name= \Yii::$app->db->createCommand("select area from zcb_area where areaID='".$dd['district_id']."'")->queryScalar();
                          echo "<a href='".\yii\helpers\Url::toRoute(['/capital/applyorder/','id'=>$dd['id'],'category'=>$dd['category'],'browsenumber'=>$dd['browsenumber']])."'>
                          <p class='clearfix'>"."<span class='fl'>".$district_name.' '.$dd['seatmortgage']."</span>"."<span class='fr'>1件</span></p></a>";
                      }}?>

                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    </div>
    </div>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.enableScrollWheelZoom();
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();

    <?php  if(Yii::$app->request->get('district') ==0)
    {
            foreach($data as $key=>$val){
            $district_name= \Yii::$app->db->createCommand("select area from zcb_area where areaID={$key}")->queryScalar();
            ?>
    map.centerAndZoom('上海市', 11);

    myGeo.getPoint("<?php echo $district_name?>", function(point){
        if (point) {
            var myLabel = new BMap.Label("<a href='<?php echo \yii\helpers\Url::toRoute('/homepage/homemap')?>/?category=0&&money=0&&district=<?php echo $key;?>&&nsearch=' target = '_blank'><?php echo $district_name.' '.$val.'件'?></a>", {offset:new BMap.Size(0,0), position:point});
            myLabel.setTitle("<?php echo $val?>件");
            myLabel.setStyle({
                "color":"white",
                "fontSize":"12px",
                "border":"0",
                "height":"80px",
                "width":"80px",
                "textAlign":"center",
                "lineHeight":"80px",
                "background":"url(/images/mappoint.png) no-repeat",
                "cursor":"pointer"
            });
            map.addOverlay(myLabel);
        }
    }, "上海市");

    <?php }}
    else{
    ?>

    map.centerAndZoom('<?php echo "上海市"?>', 11);
    <?php
    foreach($data as $dd){
     $district_name= \Yii::$app->db->createCommand("select area from zcb_area where areaID='".$dd['district_id']."'")->queryScalar();

     ?>

    map.centerAndZoom('<?php echo "".$district_name?>', 13);
    myGeo.getPoint("<?php echo $district_name.' '.$dd['seatmortgage'];?>", function(point){
        if (point) {
            var myLabel = new BMap.Label("", {offset:new BMap.Size(0,0), position:point});
            myLabel.setTitle("<?php echo $district_name.' '.$dd['seatmortgage'].' 编号：'.$dd['code'];?>");
            myLabel.setStyle({
                "color":"white",
                "fontSize":"12px",
                "border":"0",
                "height":"43px",
                "width":"32px",
                "textAlign":"center",
                "lineHeight":"80px",
                "background":"url(/images/img.png) no-repeat",
                "cursor":"pointer"
            });
            myLabel.addEventListener('click',function(){
                window.open("<?php echo \yii\helpers\Url::toRoute(['/capital/applyorder/','id'=>$dd['id'],'category'=>$dd['category'],'browsenumber'=>$dd['browsenumber']])?>");
            });
            map.addOverlay(myLabel);
        }
    }, "上海市");
    <?php
    }
    }?>
    function searchMapForm(){
        window.location = "<?php echo \yii\helpers\Url::toRoute('/homepage/homemap/')?>"+"?category="+$('.category').attr('id')+"&&money="+$('.money').attr('id')+"&&district="+$('.district').attr('id')+"&&nsearch="+$('.nsearch').val();
    }



</script>