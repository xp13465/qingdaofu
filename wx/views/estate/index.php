<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
// print_r($data);
// exit;
?>
<?=wxHeaderWidget::widget(['title'=>'我的房产评估',"backurl"=>Url::toRoute("/user/index"),'gohtml'=>'<a href="javascript:void(0);" class="reload"><img src="../images/refresh.png"  width="25" height="25"></a>'])?>

 <div class="basic_01">        
    <div class="basic_main current">
      <p id="show"></p> 
        <div id="wrapper" style="overflow:scroll;">
            <div id="scroller" class="news" style="position:absolute;top:70px;">
                <div id="pullDown">
                    <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
                </div>
				<div id="thelist" data-catr="<?php echo count($data);?>" data-url="<?php echo yii\helpers\Url::toRoute(['/estate/list'])?>">
				  <?php foreach($data as $value) {?>
						<ul class="thelist" >      
                              <li>
                                  <div class="types">
                                      <div class="rongzi" style="margin:0px;padding:0px;">
                                          <div class="over" style="margin:0 10px;">
                                              <div class="flo_l" style="margin-top:-10px;" data_id="<?= $value['id']?>">
                                                 <span class="rz_ig8"><img src="/images/house_property_evaluation.png" style="padding-bottom:5px;"></span>                                
                                                 <span class="code">评估结果</span>
                                                 <span class="flo_blue infor_arrow" style="margin-right: 5px;"><font style='color:red' ><?=round($value['totalPrice']/10000,0)?>万</font></span>
                                              </div>                            
                                          </div>                          
                                      </div>
                                      <div class="num">
                                          <ul>
                                              <li  style="width:95%;text-align:left;color:#666;">
                                                  <p style="margin-left: 10px;font-size:16px;width: 90%;padding:0px;">房源信息：<?=$value['city']?><?=$value['district']?><?=$value['address']?><?=$value['buildingNumber']?>号<?=$value['unitNumber']?>室</i></p>								
                                                  <p class="loan" style="padding: 10px 15px 15px 10px;">评估时间：<?= isset($value['create_time'])?date("Y-m-d",$value['create_time']):''?></p>
                                              </li>                                        
                                          </ul>
                                      </div>
                                  </div>                   
                             </li>
                        </ul>
                  <?php }?>
				</div>
                <div id="pullUp" style="display:none;" >
                  <span class="pullUpIcon"></span><span class="pullUpLabel"></span>
              </div>
          </div>
      </div>

</div>
</div>
<footer>
    <div class="zhen">
        <a href="<?php echo yii\helpers\Url::toRoute('/estate/index')?>">房产评估</a>
    </div>
</footer>
<script>
    $(function(){ 
        
      $('.flo_l').click(function(){
             var id = $(this).attr('data_id');
             location.href = "<?= yii\helpers\Url::toRoute(['/estate/result','type'=>'detail'])?>"+"&id="+id;
         })
       
     $('.reload').click(function(){
         window.location.reload();
       });
    });
	/*
	var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
    function pullDownAction () {
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page  --;
            window.location = "<?php echo yii\helpers\Url::toRoute('/estate/list')?>"+             "?page="+page;
            myScroll.refresh();
        },1000);
    }

    function pullUpAction () {
        var catr = $('#thelist').attr('data-catr');
        if(catr<10){
            setTimeout(function () {
                var el, li, i;
                el = document.getElementById('thelist');
                myScroll.refresh();
            }, 1000);
        }else{
            setTimeout(function () {
                var el, li, i;
                el = document.getElementById('thelist');
                page  ++;
                window.location = "<?php echo yii\helpers\Url::toRoute('/estate/list')?>"+		                "?page="+page;
                myScroll.refresh();
            },1000);
        }
    }
	*/

</script>           	     
