<div class="content_right">
    <div class="gren">
    <div class="gren_xi">
        <div class="personal_xi">
            <h4>个人基本信息</h4>
            <span></span>
            <p>(个人可发布清收、诉讼，暂不支持代理)</p>
            <?php if(\common\models\Certification::findOne(['uid'=>Yii::$app->user->getId()])){?>
			<?php if(isset($certi->state)&&$certi->state == 2){ ?>
				<a href="<?php echo \yii\helpers\Url::toRoute(['/certification/perfect','type'=>1])?>">
					<img src="/images/yi1.png" alt="">
				</a>
			<?php }else{ ?>
				<a href="javascript:;">
						<img src="/images/yi2.png" alt="">
					</a>
			<?php } ?>
			<?php }else{ echo "";}?>
            <!--  if(isset($certi->email)&&$certi->email == ''|| isset($certi->casedesc)&&$certi->casedesc == ''|| isset( $certi->cardimg)&& $certi->cardimg == ''){?>
                <a href=" echo \yii\helpers\Url::toRoute(['/certification/perfect','type'=>1])?>">
                    <img src="/images/yi1.png" alt="">
                </a>
             } else{ 
                <a href="javascript:;">
                    <img src="/images/yi2.png" alt="">
                </a>
             } 
             }else{ echo"";} -->
        </div>
		<?php if(isset($certi->state)&&$certi->state == '1'){ ?>
        <ul>
            <li class="">
                <span class="xi_l">姓名 :</span>
                <span class="grey"><?php echo $certi->name ?></span>
            </li>
            <li>
                <span class="xi_l">身份证号码 :</span>
                <span class="grey"><?php echo $certi->cardno ?></span>
            </li>

            <?php if($certi->email){?>
            <li>
                <span class="xi_l">邮箱 :</span>
                <span class="grey"><?php echo $certi->email ?></span>
            </li>
            <?php } else {echo '';}?>
        </ul>
		<?php }else if(isset($certi->state)&&$certi->state == '0'){ echo "<h1 style=' font-size: 20px; font-weight: 400;margin:26.7% auto;padding:12px;text-align:center;'>请耐心等待客服审核</h1>";}else if(isset($certi->state)&&$certi->state == '2'){echo "<h1 style=' font-size: 20px; font-weight: 400;margin:25% auto;padding:12px;text-align:center;'>认证失败<br/>原因：".$certi->resultmemo."</h1>";} ?>
    </div>
        <!-- if(\common\models\Certification::findOne(['uid'=>Yii::$app->user->getId()])){?>
         if(!\common\models\Apply::findOne(['uid'=>$certi->uid])&&!frontend\services\Func::hasProduct()){?>
            <div class="yi3">
                <a href=" echo \yii\helpers\Url::toRoute(['/certification/add','type'=>1,'modify'=>3])?>">
                    <img src="/images/yi3.png" alt="">
                </a>
            </div>
         }else{ ?>
            <div class="yi3">
                <a href="javascript:;">
                    <img src="/images/yi05.png" alt="">
                </a>
            </div>
        } ?>
        <p class="yii">
            在您未发布及未接单前，您可以根据自身情况，修改您的身份认证。
        </p>
         }else{ echo"";} -->
</div>
</div>
