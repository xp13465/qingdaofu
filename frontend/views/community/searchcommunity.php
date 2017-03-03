<style></style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=eKpQ7Zu69dOk2X4IQ4iRrqCkiEadm3R9"></script>

<div id = "communitylist" style="position: relative;float: left">
    <?php echo \yii\helpers\Html::textInput("searchkey",'',['class'=>'searchkey'])?>
    <div class="communitycommon" style="position: absolute;top:30px;left:0px;border: solid 1px #CCCCCC;width:122px;">

    </div>
    <?php echo \yii\helpers\Html::buttonInput("search",['class'=>'searchbtn'])?>
</div>
<script>

    $(document).ready(
        function(){
            $('.searchkey').keyup(function(){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute("/community/getsearch")?>',
                    type:'post',
                    data:{name:$(this).val()},
                    dataType:'json',
                    success:function(json){
                        var html = '';
                        for(var j in json){
                            html += "<a id = '"+json[j].id+"'>"+json[j].name+"</a><br/>";
                        }
                        $('.communitycommon').html(html);
                    }
                });
            });

            $(document).delegate('.communitycommon a','click',function(){
                var map = new BMap.Map("上海市");
                var point = new BMap.Point(121.474488,31.256805);
                // 创建地址解析器实例
                var myGeo = new BMap.Geocoder();

                // 将地址解析结果显示在地图上,并调整地图视野
                myGeo.getPoint($(this).html(), function(point){
                    if (point) {
                        var pt = point;
                        myGeo.getLocation(pt, function(rs){
                            var addComp = rs.addressComponents;
                            alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
                        });

                    }else{
                        alert("您选择地址没有解析到结果!");
                    }
                }, "上海市");
            });
        }
    );
</script>
