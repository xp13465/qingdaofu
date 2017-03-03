<style>
    .wj_add{width:100%;bottom:0;position:fixed;max-width:640px;}
    .wj_add a{display:block;background-color: #0065b3;color:#fff;line-height:50px;text-align:center;}
    .l-text{width:100%;background-color: #fff;padding:0 15px;margin-top: 20px;}
    .l-text li{overflow:hidden;width:100%;line-height:46px;}
    .l-text span{display:block;}
    .l-text li span:first-child{width:30%;float:left;}
    .l-text li span:last-child{float:left;font-size:14px;color:#999;}
    .r-bj {overflow:hidden;}
    .r-bj .bj-01{float:right;color:#0065b3;}
</style>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'代理人详情','gohtml'=>''])?>
<section>
    <div class="l-text">
        <ul>
            <li class="r-bj">
                <div>
                    <span>姓名:</span>
                    <span><?php echo isset($user['username'])?$user['username']:''?></span>
                </div>
                <?php if($user['isstop'] == 1){echo "";}else{ ?>
                <a href="<?php echo yii\helpers\Url::toRoute(['/agent/addagent','id'=>$user['id'],'list'=>2])?>" class="bj-01">编辑</a>
                <?php } ?>
            </li>
            <li>
                <span>联系方式:</span>
                <span><a href="tel:4008557022"><?php echo isset($user['mobile'])?$user['mobile']:''?></a></span>
            </li>
            <li>
                <span>身份证号:</span>
                <span><?php echo isset($user['cardno'])?$user['cardno']:''?></span>
            </li>
            <?php if($certification == 2){ ?>
            <li>
                <span>执业证号:</span>
                <span><?php echo isset($user['zycardno'])?$user['zycardno']:''?></span>
            </li>
            <?php } ?>
            <li>
                <span>登录密码:</span>
                <span>******</span>
            </li>
        </ul>
    </div>
</section>
<section>
    <?php if($user['isstop'] == 1){ echo '<div class="case case01"><a href="javascript:void(0);">已停用</a></div>';}else{ ?>
    <div class="wj_add">
        <a href="javascript:void(0);" class="stop" data-id="<?php echo $user['id']?>">停用</a>
    </div>
    <?php } ?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
         $('.stop').click(function(){
             var id = $(this).attr('data-id');
             $.ajax({
                 url:'<?php echo yii\helpers\Url::toRoute("/agent/stopagent")?>',
                 type:'post',
                 data:{id:id,status:1},
                 dataType:'json',
                 success:function(json){
                       if(json.code == '0000'){
                           alert(json.msg);
                           window.location = "<?php echo yii\helpers\Url::toRoute('/agent/agentlist')?>";
                       }else{
                           alert(json.msg);
                       }
                 }
             })
         })
    })
</script>

