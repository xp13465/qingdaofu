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
        <span>融资详情</span>
        <i></i>
        <?php foreach($finance as $v):?>
            <p>
                借款本金:<?php echo isset($v['money'])?$v['money']:''?>元<br/>
                期限:<?php echo isset($v['term'])?$v['term']:''?>天<br/>
                利率:<?php echo isset($v['rate'])?$v['rate']:''?>%<br/>
                返点:<?php echo isset($v['rebate'])?$v['rebate']:''?> <br>
                资金到账日: <?php echo isset($v['fundstime'])?date('Y年m月d日',$v['fundstime']):''?><br/>
                抵押物面积:<?php echo isset($v['mortgagearea'])?$v['mortgagearea']:''?>m2<br/>
                抵押物地址:<?php echo isset($v['seatmortgage'])?\frontend\services\Func::getSubstrs($v['seatmortgage']):'';?><br>
                抵押物类型:<?php  switch(isset($v['mortgagecategory'])?$v['mortgagecategory']:''){
                    case 1:
                        echo $type[1];
                        break;
                    case 2:
                        echo $type[2];
                        break;
                    case 3:
                        echo $type[3];
                        break;
                    default:
                        break;
                }?><br>
                租金:<?php echo isset($v['rentmoney'])?$v['rentmoney']:''?>元<br>
            </p>
        <?php endforeach;?>
    </div>
    <div class="affirm">
        <a href="#" onclick="applys('<?php echo $v['id']?>','<?php echo $v['category']?>')">申请</a>
    </div>
</div>
<script type="text/javascript">
    function applys(id,cate){
        var save_url = '';
        if(cate == 1){
             save_url = "<?php echo Url::to('/user/save_app')?>";
        }else if(cate == 2 || cate == 3){
            save_url = "<?php echo Url::to('/user/save_cll')?>";
        }else{
            save_url = FALSE;
        }
        $.post(save_url,{id:id,cate:cate},function(v){
            if(v == 1){
                alert('请不要重复申请');
                location.href = "<?php echo Url::to('/order/order_save')?>";

            }else if(v == 2){
                alert('已发出申请');
                location.href = "<?php echo Url::to('/order/order_termination')?>";
            }else{
                alert('申请失败');
            }
        },'json');
    }

</script>