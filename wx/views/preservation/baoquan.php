<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'我的保全',"backurl"=>Url::toRoute("/user/index"),'gohtml'=>'<a href="javascript:void(0);" class="reload"><img src="../images/refresh.png"  width="25" height="25"></a>'])?>

        <div class="basic">
            <ul>
                <li>
                    <span id="1" <?php if(Yii::$app->request->get('type')==1){echo "class='current'";}?>>未完成订单</span>
                </li>
                <li>
                    <span id="2" <?php if(Yii::$app->request->get('type')==2){echo "class='current'";}?>>已完成订单</span>
                </li>
            </ul>
        </div>
    <div class="basic_01" >        
        <div class="basic_main current">
            <p id="show"></p> 
              <div id="wrapper" style="overflow:scroll;">
                  <div id="scroller" class="news" style="position:absolute;top:70px;">
                      <div id="pullDown">
                          <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
                      </div>
						<div id="thelist" data-catr="<?php echo count($model);?>" data-url="<?php echo yii\helpers\Url::toRoute(['/preservation/baoquanlist',"type"=>Yii::$app->request->get('type')])?>">
                      <?php foreach($model as $value){ ?>
                        <ul id="thelist" class="thelist"  data-catr="<?php echo count($model);?>">      
                          <li>
                              <div class="types">
                                  <div class="rongzi" style="margin:0px;padding:0px;">
                                      <div class="over" style="margin:0 20px;">
                                          <div class="flo_l" data_id="<?= $value['id']?>" style="width:75%;float:left;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
                                             <span class="rz_ig8"><img src="../images/right.png" style="padding-bottom:6px;"></span>                                
                                             <span class="code"><?= isset($value['number'])?$value['number']:''?></span>
                                             <?php $category = ['1'=>'等待审核','10'=>'审核通过','20'=>'协议已签订','30'=>'保函已出','40'=>'完成/退保']?>
                                             <span class="flo_blue"></span>
                                          </div> 
                                          <div class='flo_r'>
									     <span style="color:red;"><?php echo $category[$value['status']]?></span>
										</div>                            
                                      </div>                          
                                  </div>
                                  <div class="num">
                                      <ul>
                                          <li class="listl" style="width:95%;">
                                              <p style="font-size:16px;margin-top:10px;">金额:<?= isset($value['account'])?round($value['account']/10000):''?>万<i style="font-size:14px;float:right;"><?= isset($value['create_time'])?date('Y-m-d h:i',$value['create_time']):''?></i></p>								
                                              <p class="loan">法院：<?= isset($value['fayuan_name'])?$value['fayuan_name']:''?></p>
                                          </li>                                        
                                      </ul>
                                  </div>
                              </div>                   
                         </li>
                      </ul>
                      <?php } ?>
					  </div>
                  <div id="pullUp" style="display:none;" >
                          <span class="pullUpIcon"></span><span class="pullUpLabel"></span>
                      </div>
                  </div>
              </div>
        </div> 
    </div>
</section>
<footer>
    <div class="zhen">
        <a href="<?php echo yii\helpers\Url::toRoute('/preservation/index')?>">申请保全</a>
    </div>
</footer>
<script>
    $(function(){
        $('.basic li span').click(function(){
            var type = $(this).attr('id');
			location.href = "<?php echo yii\helpers\Url::toRoute('/preservation/baoquanlist')?>?type="+type
         //    var index = $(this).parent().index();
         //   $(this).addClass('current').parent().siblings().children().removeClass('current').parents().parent().next().children().eq(index).show().siblings().hide();
        })
        

        
         $('.flo_l').click(function(){
                var id = $(this).attr('data_id');
                location.href = "<?= yii\helpers\Url::toRoute('/preservation/audit')?>"+"?id="+id;
            })
          
        $('.reload').click(function(){
            window.location.reload();
          });
    })
	/*
	var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
	var type = "<?php echo Yii::$app->request->get('type')?>";
    function pullDownAction () {
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page  --;
            window.location = "<?php echo yii\helpers\Url::toRoute('/preservation/baoquanlist')?>"+"?type="+type+"&page="+page;
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
                window.location = "<?php echo yii\helpers\Url::toRoute('/preservation/baoquanlist')?>"+"?type="+type+"&page="+page;
                myScroll.refresh();
            },1000);
        }
    }
	*/

</script>           	     

