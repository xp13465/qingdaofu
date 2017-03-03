<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?php if(!empty($product['progress_status'])&&$product['progress_status'] == 1){$clock = 'clock';}else{$clock='';}?>
<?=wxHeaderWidget::widget(['title'=>'发布方详情','gohtml'=>'<div class="ck-header cp-header"><!--<span class='.$clock.'></span>--></div>'])?>
<?php  if(!empty($product) && !empty($certi)) { ?>
<section>
    <?php if($certi['category'] == 1){ ?>
        <div class="apply_xx">
            <div class="apply_m">
                <span></span>
                <span>发布方信息</span>
                <span></span>
            </div>
            <ul class="apply_id">
                <li>
                    <span>姓名</span>
                    <span><?php echo isset($certi['name'])?$certi['name']:''?></span>
                </li>
                <li>
                    <span>身份证号码</span>
                    <span><?php echo isset($certi['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certi['cardno'],'*',4,4):''?></span>
                </li>
                <li>
                    <span>身份图片</span>
                    <span><?php echo isset($certi['cardimg'])?'已上传':'未上传'?></span>
                </li>
                <li>
                    <span>邮箱</span>
                    <span><?php echo isset($certi['email'])?\frontend\services\Func::HideStrRepalceByChar($certi['email'],'*',3,10):''?></span>
                </li>
                <li>
                    <span>经典案例</span>
                     <?php if($certi['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certi['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span class="check">暂无</span></a>
                <?php } ?>
                </li>
            </ul>
        </div>
    <?php }else if($certi['category'] == 2){ ?>
        <div class="apply_xx">
            <div class="apply_m">
                <span></span>
                <span>发布方信息</span>
                <span></span>
            </div>
            <ul class="apply_id">
                <li>
                    <span>律所名称</span>
                    <span><?php echo isset($certi['name'])?$certi['name']:''?></span>
                </li>
                <li>
                    <span>执业证号</span>
                    <span><?php echo isset($certi['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certi['cardno'],'*',4,4):''?></span>
                </li>
                <li>
                    <span>身份图片</span>
                    <span><?php echo isset($certi['cardimg'])?'已上传':'未上传'?></span>
                </li>
                <li>
                    <span>联系人</span>
                    <span><?php echo isset($certi['contact'])?$certi['contact']:''?></span>
                </li>
                <li>
                    <span>联系方式</span>
                    <span><a href="tel:4008557022"><?php echo isset($certi['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certi['mobile'],'*',3,4):''?></a></span>
                </li>
                <li>
                    <span>邮箱</span>
                    <span><?php echo isset($certi['email'])?\frontend\services\Func::HideStrRepalceByChar($certi['email'],'*',3,10):''?></span>
                </li>
                <li>
                    <span>经典案例</span>
                    <?php if($certi['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certi['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span class="check">暂无</span></a>
                <?php } ?>
                </li>
            </ul>
        </div>
    <?php }else if($certi['category'] == 3) { ?>
        <div class="apply_xx">
            <div class="apply_m">
                <span></span>
                <span style="color:#333;">基本信息</span>
                <span>已认证公司</span>
            </div>
            <ul class="apply_id">
                <li>
                    <span>公司名称</span>
                    <span><?php echo isset($certi['name'])?$certi['name']:''?></span>
                </li>
                <li>
                    <span>营业许可证号</span>
                    <span><?php echo isset($certi['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certi['cardno'],'*',4,4):''?></span>
                </li>
                <li>
                    <span>身份图片</span>
                    <span><?php echo isset($certi['cardimg'])?'已上传':'未上传'?></span>
                </li>
                <li>
                    <span>联系人</span>
                    <span><?php echo isset($certi['contact'])?$certi['contact']:''?></span>
                </li>
                <li>
                    <span>联系方式</span>
                    <span><a href="tel:4008557022" style="color:#666;"><?php echo isset($certi['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certi['mobile'],'*',3,4):''?></a></span>
                </li>
                <li>
                    <span>公司经营地址</span>
                    <span><?php echo isset($certi['address'])?$certi['address']:''?></span>
                </li>
                <li>
                    <span>公司网站</span>
                    <span><?php echo isset($certi['enterprisewebsite'])?$certi['enterprisewebsite']:''?></span>
                </li>
                <li>
                    <span>邮箱</span>
                    <span><?php echo isset($certi['email'])?\frontend\services\Func::HideStrRepalceByChar($certi['email'],'*',3,10):''?></span>
                </li>
                <li>
                    <span>经典案例</span>
                     <?php if($certi['casedesc']){ ?>
                     <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/case','id'=>$certi['id']])?>"><span class="check" style="color:#0065b3;">查看</span></a>
                <?php }else{ ?>
                     <a href="javascript:void(0);"><span class="check">暂无</span></a>
                <?php } ?>
                </li>
            </ul>
        </div>
    <?php } ?>
</section>
<?php echo  \wx\widget\wxEvaluateWidget::widget(['pid'=>$product['uid']])?>
<footer>
    <div class="apply apply1">
        <?php if($product['progress_status'] == 1 && in_array(Yii::$app->request->get('app'),[2,3])){?>
            <div class="apply_sq">
                <a href="javascript:void(0)" class="apply_submit application" data-category="<?php echo $product['category']?>" data-id="<?php echo $product['id'] ?>">立即申请</a>
            </div>
        <?php }else{ ?>
            <div class="apply_sq apply-sq01">
                <a href="javascript:void(0)" class="apply_submit">已申请</a>
            </div>
        <?php } ?>
    </div>
</footer>
<?php } ?>
<script>
    $(document).ready(function() {
        $('.application').click(function () {
            var category = $(this).attr('data-category');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo yii\helpers\Url::to('/list/application')?>",
                type: 'post',
                data: {category: category, id: id},
                dataType: 'json',
                success: function (json) {
                    if (json.code == '0000') {
                        alert(json.msg);
                        location.href = "<?php echo yii\helpers\Url::toRoute(['/usercenter/orders', 'progress_status' => 1]);?>";
                    } else {
                        alert(json.msg);
                    }
                }
            })
        });
        $('.clock').click(function(){
            var id = "<?php echo $product['id']?>";
            var category = "<?php echo $product['category']?>";
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/list/remind/')?>",
                type:'post',
                data:{id:id,category:category},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        alert(json.msg);
                        location.reload();
                    }else{
                        alert(json.msg);
                    }

                }
            })
        })
    })
</script>
