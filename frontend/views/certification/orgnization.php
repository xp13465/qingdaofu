<div class="content_right">
<div class="gren">
    <div class="gren_xi">
        <div class="personal_xi">
            <h4>公司基本信息</h4>
            <span></span>
            <p>(公司可发布清收、诉讼，可代理清收)</p>
            <?php if(\common\models\Certification::findOne(['uid'=>Yii::$app->user->getId()])){?>
			<?php if(isset($certi->state)&&$certi->state == 2){ ?>
				<a href="<?php echo \yii\helpers\Url::toRoute(['/certification/perfect','type'=>3])?>">
					<img src="/images/yi1.png" alt="">
				</a>
			<?php }else{ ?>
				<a href="javascript:;">
						<img src="/images/yi2.png" alt="">
					</a>
			<?php } ?>
			<?php }else{ echo "";}?>
             <!--if(isset($certi->email)&&$certi->email == ''|| isset($certi->casedesc)&&$certi->casedesc == ''
                ||isset($certi->address)&&$certi->address == ''||isset($certi->enterprisewebsite)&&$certi->enterprisewebsite == ''){?>
                <a href="<?php echo \yii\helpers\Url::toRoute(['/certification/perfect','type'=>3])?>">
                    <img src="/images/yi1.png" alt="">
                </a>
            }else{ 
                <a href="javascript:;">
                    <img src="/images/yi2.png" alt="">
                </a>
            } 
           }else{ echo"";}-->
        </div>
		<?php if(isset($certi->state)&&$certi->state == '1'){ ?>
        <ul>
            <li>
                <span class="xi_l">公司名称 :</span>
                <span class="grey"><?php echo $certi->name ?></span>
            </li>
            <li>
                <span class="xi_l">营业许可证号 :</span>
                <span class="grey"><?php echo $certi->cardno ?></span>
            </li>
            <li>
                <span class="xi_l">联系人 :</span>
                <span class="grey"><?php echo $certi->contact ?></span>
            </li>
            <li>
                <span class="xi_l">联系方式 :</span>
                <span class="grey"><?php echo $certi->mobile ?></span>
            </li>
            <?php if($certi->email){?>
            <li>
                <span class="xi_l">邮箱 :</span>
                <span class="grey"><?php echo $certi->email ?></span>
            </li>
            <?php } else {echo '';}?>
        </ul>
        <div class="personal_xi">
            <h4>公司业务信息</h4>
            <span></span>
        </div>
        <ul>
            <?php if($certi->address){?>
            <li>
                <span class="xi_l">公司经营地址 :</span>
                <span class="grey"><?php echo $certi->address ?></span>
            </li>
            <?php } else {echo '';}?>
            <?php if($certi->enterprisewebsite){?>
            <li>
                <span class="xi_l">公司网站 :</span>
                <span class="grey"><?php echo $certi->enterprisewebsite ?></span>
            </li>
            <?php } else {echo '';}?>
            <?php if($certi->casedesc){?>
            <li>
                <span class="xi_l">经典案例 :</span>
                <span class="grey"><?php echo $certi->casedesc ?></span>
            </li>
            <?php } else {echo '';}?>
        </ul>
		<?php }else if(isset($certi->state)&&$certi->state == '0'){ echo "<h1 style=' font-size: 20px; font-weight: 400;margin:26.7% auto;padding:12px;text-align:center;'>请耐心等待客服审核</h1>";}else if(isset($certi->state)&&$certi->state == '2'){echo "<h1 style=' font-size: 20px; font-weight: 400;margin:25% auto;padding:12px;text-align:center;'>认证失败<br/>原因：".$certi->resultmemo."</h1>";} ?>
        <!-- if(\common\models\Certification::findOne(['uid'=>Yii::$app->user->getId()])){?>
         if(!\common\models\Apply::findOne(['uid'=>$certi->uid])&&!frontend\services\Func::hasProduct()){
            <div class="yi3">
                <a href="echo \yii\helpers\Url::toRoute(['/certification/add','type'=>3,'modify'=>3])">
                    <img src="/images/yi3.png" alt="">
                </a>
            </div>
        }else{ 
            <div class="yi3">
                <a href="javascript:;">
                    <img src="/images/yi05.png" alt="">
                </a>
            </div>
         } 
        <p class="yii">
            在您未发布及未接单前，您可以根据自身情况，修改您的身份认证。
        </p>
         }else{ echo"";} -->
    </div>
</div>
</div>
<div style="display:none; " class="tanchu">
    <div class="rz_alert2c" style="font-size: 16px;">
        <img src="/images/yuan.png" height="70" width="70" alt="" class="yuan">
        <p class="art" >申请认证已发出，请耐心等待客服审核</p>
        <div>
            <a href="<?php echo yii\helpers\Url::toRoute('/capital/list')?>" style="background:#c3c3c3;">返回产品列表</a>　　　<a href="<?php echo yii\helpers\Url::toRoute('/certification/lawyer')?>">确认</a>
        </div>
    </div>
</div>



<script>
    $(document).ready(function(){
        var FormWhat = "<?php echo  Yii::$app->session->get("fromWhat");Yii::$app->session->set("fromWhat",'');?>";
        if(FormWhat == 'orgnization'){
            $.msgbox({
                closeImg: '/images/yuan2.png',
                async: false,
                height:240,
                width:340,
                title:'提示',
                content:$('.tanchu').html(),
            });
        }
    });

</script>