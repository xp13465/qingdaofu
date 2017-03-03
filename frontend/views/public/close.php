<style>
    .btns input[type="button"]{ font-size:18px;width:110px; border-radius:2px;height:34px;color:#fff;background-color:#0065b3;margin-top:20px;border:1px solid gray;}

</style>
<div style="text-align: center;width: 840px;"  class="btns">
<?php
if($progress_status  == 2){
if(!$applyclose){?>
    <?php if($uid == Yii::$app->user->getId()){?>
    <input type="button"  class="product"  value="终止"/>
    <input  type="button" class="finish" value="结案"/>
        <?php }else{?>
        <input  type="button" class="finish" value="结案"/>
        <?php } ?>

<?php }else{
    if($applyclosefrom == Yii::$app->user->getId()){?>
        <?php if($applyclose==4){?>

            <?php if($uid == Yii::$app->user->getId()){?>
            <input type="button"  class="product"  value="终止"/>
                <?php }?>
            <input type="button01" value="结案申请中"/>
            <?php } ?>
        <!--<input  type="button" value="<?php /*echo $applyclose==3?"终止申请中":'结案申请中';*/?>"/>-->
    <?php }
    else{?>
        <?php if($applyclose==4){?>
            <input type="button" class = "agree" value="结案待确认"/>
        <?php } ?>
        <!--<input type="button" onclick="closeProductAgree(<?php /*echo $category;*/?>,<?php /*echo $id*/?>,<?php /*echo $applyclose*/?>);" value="<?php /*echo $applyclose==3?"终止待确认":'结案待确认';*/?>"/>-->
    <?php }
}
}elseif($progress_status  == 1){
    ?>

    <input type="button"  class="product"  value="终止"/>

    <?php
}?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var id = "<?php echo $id ?>";
        var category ="<?php echo $category ?>";
        var status = "<?php echo $applyclose ?>";
        $('.product').click(function(){
            if(confirm("请确定是否终止")) {
                $.ajax({
                    url: "<?php echo yii\helpers\Url::toRoute('/apply/closeproduct')?>",
                    type: 'post',
                    data: {category: category, id: id, status: 3},
                    dataType: "html",
                    success:function(html){
                            if(html == 1){
                                location.reload();
                            }else{
                                alert('参数错误');
                            }
                    }
                });
            }
        });
        $('.finish').click(function(){
            if(confirm("请确定是否结案")) {
                $.ajax({
                    url: "<?php echo yii\helpers\Url::toRoute('/apply/closeproduct')?>",
                    type: 'post',
                    data: {category: category, id: id, status: 4},
                    dataType: "html",
                    success:function(html){
                        if(html == 1){
                            location.reload();
                        }else{
                            alert('参数错误');
                        }
                    }
                });
            }
        })
        $('.agree').click(function(){
            if(confirm("请确定是否结案")){
                $.ajax({
                    url:"<?php echo \yii\helpers\Url::toRoute('/apply/closeproductagree')?>",
                    type:'post',
                    data:{category:category,id:id,status:status},
                    dataType:"html",
                    success:function(html){
                        if(html == 1){
                            location.reload();
                        }else{
                            alert('参数错误');
                        }
                    }
                });
            }
        })
    })

/*
    function closeProduct(category,id,status){
        if(confirm(status == 3?"请确定是否终止":"请确定是否结案")){
            $.ajax({
                url:"<?php /*echo \yii\helpers\Url::toRoute('/apply/closeproduct')*/?>",
                type:'post',
                data:{category:category,id:id,status:status},
                dataType:"html",
                success:function(html){
                    if(html == 1){
                        location.reload();
                    }else{
                        alert('参数错误');
                    }
                }
            });
        }
    }
    function closeProductAgree(category,id,status){
        if(confirm(status == 3?"请确定是否终止":"请确定是否结案")){
            $.ajax({
                url:"<?php /*echo \yii\helpers\Url::toRoute('/apply/closeproductagree')*/?>",
                type:'post',
                data:{category:category,id:id},
                dataType:"html",
                success:function(html){
                    if(html == 1){
                        location.reload();
                    }else{
                        alert('参数错误');
                    }
                }
            });
        }
    }*/
</script>
