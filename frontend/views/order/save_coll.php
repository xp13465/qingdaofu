<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="details">
    <div class="look">
        <h3>查看信息</h3>
    </div>
    <!-- 融资详情 -->
    <div class="finance">
        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>$dt['category'],'id'=>$dt['id']])?>
    </div>    <!-- 处置方详情 -->
    <!-- 结案开始 -->
    <div class="affirm">
        <a href="#" onclick="applys('<?php echo $dt['id']?>','<?php echo $dt['category']?>')">申请</a><br/>
    </div>
</div>
<script type="text/javascript">
    function applys(id,cate){
        var save_url = '';
        if(cate == 1){
            save_url = "<?php echo Url::toRoute('/order/saveapp')?>";
        }else if(cate == 2 || cate == 3){
            save_url = "<?php echo Url::toRoute('/order/savecoll')?>";
        }else{
            save_url = FALSE;
        }
        $.post(save_url,{id:id,cate:cate},function(v){
            if(v == 1){
                alert('请不要重复申请');
                location.href = "<?php echo Url::toRoute('/order/ordersave')?>";

            }else if(v == 2){
                alert('已发出申请');
                location.href = "<?php echo Url::toRoute('/order/orderapply')?>";
            }else{
                alert('申请失败');
            }
        },'json');
    }

</script>