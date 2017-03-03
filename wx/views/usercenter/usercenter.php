<header>
    <div class="user">
        <span>用户中心</span>
    </div>
</header>
<section>
    <ul class="con11">
        <li class="hm-01">
            <div class="hm-01_shuzi">
                <span <?php if($certi){echo 'class="num_sz"';}?>><a href="tel:4008557022"><?php echo isset($mobile)?$mobile:$certi['mobile']?></a></span>
                <div class="renzhen-01">
                        <?php if(isset($certi['state'])&&$certi['state'] == 1){ ?>
                            <a href="<?php echo yii\helpers\Url::toRoute('/certification/index')?>">

                                <span>
                                <?php if($certi['category'] == 1){ echo '已认证个人';}else if($certi['category'] == 2){echo '已认证律所';}else if($certi['category'] == 3){echo '已认证公司';} ?>
                                </span>
                            </a>
                        <?php }else{ ?>
                            <?php if($uid){ ?>
                                <a href="<?php echo yii\helpers\Url::toRoute('/certification/index')?>">
                                   <span>未认证</span>
                                </a>
                            <?php }else{ ?>
                                <a href="<?php echo yii\helpers\Url::toRoute('/site/login')?>">
                                    <span>未认证</span>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    <i class="jiantou"></i>
                </div>
            </div>
             <div class="detail-m det">
                <ul>
                    <li id = "0" style="border-right:1px solid #ddd;" class="fabus">
                        <p class="fb_img01">我的发布</p>
                    </li>
                    <li id = "0" class="jiedan">
                        <p class="fb_img02">我的接单</p>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</section>
<section>
    <ul class="keep">
        <?php if(isset($certi['uid'])&&$certi['uid'] == $uid && isset($certi['category'])&&$certi['category'] != 1){ ?>
        
        <?php } ?>
        <a href="<?php echo yii\helpers\Url::toRoute('/usercenter/preservation')?>">
            <li class="keep01">
                <span class="keep_ig01"></span>
                <span class="baocun">我的保存</span>
                <i class="jiantou jiantou01"></i>
                <span style="float:right;margin-right:10px;vertical-align: middle;color: #999; font-size: 14px;">查看待发布</span>
            </li>
        </a>
        <a href="<?php echo yii\helpers\Url::toRoute('/usercenter/collection')?>">
             <li>
                 <span class="keep_ig02"></span>
                 <span class="baocun">我的收藏</span>
                 <i class="jiantou jiantou01"></i>
             </li>
        </a>
    </ul>
    
    
    <ul class="keep">
       
        <a href="<?php echo yii\helpers\Url::toRoute(['/preservation/baoquanlist','type'=>1])?>">
            <li class="keep01">
                <span class="keep_ig"></span>
                <span class="baocun">我的保全</span>
                <i class="jiantou jiantou01"></i>
            </li>
        </a>
        <a href="<?php echo yii\helpers\Url::toRoute(['/policy/index','type'=>1])?>">
            <li class="keep01">
                <span class="keep_ig04"></span>
                <span class="baocun">我的保函</span>
                <i class="jiantou jiantou01"></i>
            </li>
        </a>
        <a href="<?php echo yii\helpers\Url::toRoute('/property/index')?>">
             <li class="keep01">
                 <span class="keep_ig05"></span>
                 <span class="baocun">我的产调</span>
                 <i class="jiantou jiantou01"></i>
             </li>
        </a>
		<a href="<?php echo yii\helpers\Url::toRoute('/estate/list')?>">
             <li>
                 <span class="keep_ig07"></span>
                 <span class="baocun">我的房产评估</span>
                 <i class="jiantou jiantou01"></i>
             </li>
        </a>
    </ul>
   
   <a href="<?php echo yii\helpers\Url::toRoute('/agent/agentlist')?>">
    <div class="fabu01">
        <span class="keep_ig03"></span>
        <span class="baocun">我的代理</span>
        <i class="jiantou jiantou01"></i>
    </div>
    </a>
   
    <a href="<?php echo yii\helpers\Url::toRoute('/usercenter/setup')?>">
    <div class="fabu01">
        <span class="keep_ig06"></span>
        <span class="baocun">设置</span>
        <i class="jiantou jiantou01"></i>
    </div>
    </a>
</section>
<?php echo  \wx\widget\wxFooterWidget::widget()?>

<script type="text/javascript">
    $(document).ready(function(){
        function release(status){
            location.href = "<?php echo yii\helpers\Url::toRoute('/usercenter/release')?>?status="+status;
        };
        $('.fabus').click(function(){
            release($(this).attr('id'));
        });
    
        function orders(progress_status){
            location.href = "<?php echo yii\helpers\Url::toRoute('/usercenter/orders')?>?progress_status="+progress_status;
        }
        $('.jiedan').click(function(){
            orders($(this).attr('id'));
        })

    })
</script>
