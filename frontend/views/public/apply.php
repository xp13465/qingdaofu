<style>
    .product1{
        width: 205px;
        height: 33px;
        line-height: 32px;
        text-align: center;
        color: #fff;
        font-size: 16px;
        background: #0065b3;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        margin-top: 40px;
        float: right;
        margin: 70px 327px 0 40px;
    }
</style>
<?php $apply = \common\models\Apply::findOne(['product_id'=>$id,'uid'=>Yii::$app->user->getId(),'category'=>$category])?>
<?php if(isset($apply['app_id'])&&$apply['app_id'] == 2){?>
<div class="product1">
    <a href="#" onclick="applys('<?php echo $id;?>','<?php echo $category;?>')" style="color:#fff">申请</a><br/>
</div>
<?php }else{echo'';}?>
<script type="text/javascript">
    function applys(id,cate){
        var save_url = '';
        if(cate == 1){
            save_url = "<?php echo \yii\helpers\Url::toRoute('/order/saveapp')?>";
        }else if(cate == 2 || cate == 3){
            save_url = "<?php echo \yii\helpers\Url::toRoute('/order/savecoll')?>";
        }else{
            save_url = FALSE;
        }
        $.post(save_url,{id:id,cate:cate},function(v){
            if(v == 1){
                $.msgbox({
                    height: 130,
                    width: 300,
                    content: '<p style="text-align:center">请不要重复申请。</p>',
                    type: 'confirmyes',
                    onClose: function (v) {
                        if(v){
                            location.href="<?php echo \yii\helpers\Url::toRoute('/order/index')?>";
                        }
                    }
                });
            }else if(v == 2){
                $.msgbox({
                    height: 130,
                    width: 300,
                    content: '<p style="text-align:center">您还未认证请先去认证',
                    type: 'confirm',
                    onClose: function (v) {
                        if(v){
                            location.href="<?php echo \yii\helpers\Url::toRoute('/certification/index')?>";
                        }
                    }
                });
            }else if(v == 3){
                alert('已发出申请');
                location.reload();
            } else{
                alert('申请失败');
            }
        },'json');
    }

</script>