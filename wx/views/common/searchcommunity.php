<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=w10qOR1ck89d9YevXrdWPqGg"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="/js/function.js"></script>
<header>
  <div class="map">
        <span></span>
    </div>
</header>
<header class="header_m header_m01">
    <div class="search01 search02">
        <input type="text" placeholder="请输入小区/写字楼/商铺等" class="searchkey"/>
        <span class="suo"></span>
    </div>
    <a href="#" class="my" onclick="window.location = document.referrer ">
        取消
    </a>
</header>
<section>
    <div class="place">
        <ul class="communitycommon">

        </ul>
    </div>
</section>
<script>

    $(document).ready(
        function(){
            $('.searchkey').keyup(function(){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute("/common/getsearch")?>',
                    type:'post',
                    data:{name:$(this).val()},
                    dataType:'json',
                    success:function(json){
                        var html = '';
                        if(json.code != '0000'){
                            html = "<li><h4 id = '0'>"+json.result+"</h4></li>";
                        }else{
                            var jsVal = json.result;
                            for(var j in jsVal){
                                html += "<li><h4 style=‘cursor: pointer;’ id = '"+jsVal[j].id+"'>"+jsVal[j].name+"</h4></li>";
                            }
                        }

                        $('.communitycommon').html(html);
                    }
                });
            });

            $(document).delegate('.communitycommon li h4','click touchstart',function(){
                var map = new BMap.Map("上海市");
                var point = new BMap.Point(121.474488,31.256805);
                // 创建地址解析器实例
                var myGeo = new BMap.Geocoder();

                var mortorage_communityvalue = $(this).html();
                // 将地址解析结果显示在地图上,并调整地图视野
                myGeo.getPoint(mortorage_communityvalue, function(point){
                    if (point) {
                        var pt = point;
                        myGeo.getLocation(pt, function(rs){
                            var addComp = rs.addressComponents;
                            var arr = [];
                            arr['mortorage_community']= mortorage_communityvalue ;
                            arr['seatmortgage']= addComp.province  + addComp.province==addComp.city?'市辖区':addComp.city + addComp.district + addComp.street + addComp.streetNumber ;
                            arr['province_id']= addComp.province ;
                            arr['city_id']= addComp.province==addComp.city?'市辖区':addComp.city ;
                            arr['district_id']= addComp.district ;


                            var financing_arr = getCookie(getCookie('publishCookieName'))?formUnserialize(getCookie(getCookie('publishCookieName'))):arr;

                            if(getCookie(getCookie('publishCookieName'))){
                                    financing_arr['mortorage_community'] = arr['mortorage_community'];
                                    financing_arr['seatmortgage'] = arr['seatmortgage'];
                                    financing_arr['province_id'] = arr['province_id'];
                                    financing_arr['city_id'] = arr['city_id'];
                                    financing_arr['district_id'] = arr['district_id'];
                                    setCookie(getCookie('publishCookieName'),arrayserializa(financing_arr),'h1');
                            }
                            window.location = document.referrer
                        });

                    }else{
                        alert("您选择地址没有解析到结果!点击取消关闭页面");

                    }
                }, "上海市");
            });
        }
    );
</script>