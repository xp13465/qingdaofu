<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'设置','gohtml'=>''])?>
<section>
    <div class="xiugai">
        <a href="<?php echo yii\helpers\Url::toRoute('/site/modify')?>">
            <span class="arrow_r">修改密码</span>
        </a>
    </div>
    <!--<div class="xiugai">
        <span class="arrow_r">消息提醒</span>
    </div>-->
    <div class="relation hezuo yijian">
        <ul>  
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/opinion')?>">
                    <span class="hot_line arrow_r">意见反馈</span>
                </a>
            </li>
            <li>
                 <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/askanswer')?>">
                    <span class="hot_line arrow_r">常见问答</span>
                 </a>
            </li>
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/contactus')?>">
                    <span class="hot_line arrow_r">联系我们</span>
                </a>
            </li>
            <li>
                <a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>">
                    <span class="hot_line arrow_r">关于清道夫</span>
                </a>
            </li>
        </ul>
    </div>
    <!--<div class="tuichu">
        <a href="javascript:void(0);">退出登录</a>
    </div>-->
</section>

<script type="text/javascript">
    $(document).ready(function(){
    $('.tuichu').click(function(){
        $.ajax({
            url:"<?php echo yii\helpers\Url::toRoute('/usercenter/out')?>",
            dataType:'json',
            success:function(json){
                if(json.code == "0000"){
                    location.href = "<?php echo yii\helpers\Url::toRoute('/site/login')?>";
                }
            }
        })
    })
    })
</script>
