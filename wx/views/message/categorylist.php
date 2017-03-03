<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;


?>
<?=wxHeaderWidget::widget(['title'=>$result['type']['name'],'gohtml'=>''])?>
<section>
<p id="show"></p> 
<div id="wrapper" style="overflow:scroll;">
	<div id="scroller" class="news" style="position:absolute;top:20px;">
		<div id="pullDown">
			<span class="pullDownIcon"></span><span class="pullDownLabel"></span>
		</div>
        <ul id="thelist" data-catr="<?php echo count($result['message']);?>">
            <?php foreach($result['message'] as $m){?>
                <li class="examine"  <?php if($m['isRead'] == 1){echo 'id="yidu"';}?>>
                    <p>
                        <span><?php if($m['isRead'] == 0){echo '<i style="color:red;">*</i>';}?><?php echo $m['title']?></span>
                        <span class="time01"><?php echo date('Y-m-d H:i:s',$m['create_time'])?></span>
                    </p>
                    <p>
                        <?php $params = $m['params']?unserialize($m['params']):[]; $url = $result['MessageUrl'][$m['type']]['wx']; $params_url = array_merge(explode(',',$url),$params); ?>
                        <span class="time02" data-content-url = "<?php echo yii\helpers\Url::toRoute($params_url) ?>" data-id="<?php echo $m['id']?>" data-content-id="<?php echo isset($params['id'])?$params['id']:'';?>" data-category = "<?php echo isset($params['category'])?$params['category']:'';?>"><?php echo $m['content']?></span>
                        <!---<span class="time03"></span>--->
                    </p>
                </li>
            <?php }?>
        </ul>  
		
		<div id="pullUp" style="display:none;" >
			<span class="pullUpIcon"></span><span class="pullUpLabel"></span>
		</div>
	</div>
</div>
</section>
<script type="text/javascript" src="/js/fastclick.js"></script>
<script>
    $(document).ready(
        function(){
            $('.xi-icon').click(function(){
                window.location  = "<?php echo \yii\helpers\Url::toRoute('/message/categorylist')?>?type="+$(this).attr('data-content-id');
            });

            $('.confirm').click(function(){
                var id = $(this).closest('.time02').attr('data-content-id');
                var category =  $(this).closest('.time02').attr('data-category');
                $.ajax({
                    url:"<?php echo yii\helpers\Url::toRoute('/releases/coufirm');?>",
                    type:'post',
                    data:{id:id,category:category},
                    dataType:'json',
                    success:function(json){
                        if(json.code == '0000'){
                            alert(json.msg);
                        }else{
                            alert(json.msg);
                        }
                    }
                })
            });
            $('.cancel').click(function(){
                var id = $(this).closest('.time02').attr('data-content-id');
                var category =  $(this).closest('.time02').attr('data-category');
                $.ajax({
                    url:"<?php echo yii\helpers\Url::toRoute('/releases/cancel');?>",
                    type:'post',
                    data:{id:id,category:category},
                    dataType:'json',
                    success:function(json){
                        if(json.code == '0000'){
                            alert(json.msg);
                        }else{
                            alert(json.msg);
                        }
                    }
                })
            });

            $('.examine').click(function(){
                var sid = $(this).children().last().children('.time02').attr('data-id');
                var url  = $(this).children().last().children('.time02').attr('data-content-url');
                var category  = $(this).children().last().children('.time02').attr('data-content-id');
                var type = "<?php echo yii::$app->request->get('type')?>";
                $.ajax({
                    url:"<?php echo yii\helpers\Url::toRoute('/message/read')?>",
                    type:'post',
                    data:{sid:sid,category:category},
                    dataType:'json',
                    success:function(json){
                        if(json.code == '0000'){
                            if(type == 4 ){
                                if(category){
                                    location.href = url+'&type='+type;
                                }

                            }else{
                                location.href = url+'&type='+type;
                            }

                        }else{
							alert(json.msg);
						}
                    }
                })
            });
        });
    var page = "<?php echo Yii::$app->request->get('page')<=0?1:Yii::$app->request->get('page')?>";
    function pullDownAction () {
        var type = "<?php echo Yii::$app->request->get('type');?>";
        setTimeout(function () {
            var el, li, i;
            el = document.getElementById('thelist');
            page  --;
            window.location = "<?php echo yii\helpers\Url::toRoute('/message/categorylist')?>"+"?type="+type+"&page="+page;
            myScroll.refresh();
        },1000);
    }

    function pullUpAction () {
        var type = "<?php echo Yii::$app->request->get('type');?>";
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
                window.location = "<?php echo yii\helpers\Url::toRoute('/message/categorylist')?>"+"?type="+type+"&page="+page;
                myScroll.refresh();
            },1000);
        }
    }
</script>
