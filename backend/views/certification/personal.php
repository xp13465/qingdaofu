<div class="content_right">
    <div class="gren">
    <div class="gren_xi">
        <div class="personal_xi">
            <h4>个人基本信息</h4>
            <span></span>
            <p>(个人可发布融资、清收、诉讼，暂不支持代理)</p>
            <?php if(isset($certi->email)&&$certi->email == ''|| isset($certi->casedesc)&&$certi->casedesc == ''|| isset( $certi->cardimg)&& $certi->cardimg == ''){?>
                <a href="<?php echo \yii\helpers\Url::toRoute(['/certification/modify','type'=>1])?>">
                    <img src="/images/yi1.png" alt="">
                </a>
            <?php } else{ ?>
                <a href="javascript:;">
                    <img src="/images/yi2.png" alt="">
                </a>
            <?php } ?>
        </div>
        <ul>
            <li class="">
                <span class="xi_l">姓名 :</span>
                <span class="grey"><?php echo $certi->name ?></span>
            </li>
            <li>
                <span class="xi_l">身份证号码 :</span>
                <span class="grey"><?php echo $certi->cardno ?></span>
            </li>
            <li>
                <?php if($certi->cardimg){?>
                <span class="xi_l"></span>
                    <span class="picture"><img width="161px;" height="88px" src="<?php echo substr($certi->cardimg,1,-1) ?>"/></span>
                <?php } else{echo '';}?>
            </li>
            <?php if($certi->email){?>
            <li>
                <span class="xi_l">邮箱 :</span>
                <span class="grey"><?php echo $certi->email ?></span>
            </li>
            <?php } else {echo '';}?>
            <?php if($certi->casedesc){?>
            <li>
                <span class="xi_l">经典案例 :</span>
                <span class="grey"><?php echo $certi->casedesc ?></span>
            </li>
            <?php } else {echo '';}?>
        </ul>
    </div>
        <?php if(!\common\models\Apply::findOne(['uid'=>$certi->uid])&&!frontend\services\Func::hasProduct()){?>
            <div class="yi3">
                <a href="<?php echo \yii\helpers\Url::toRoute(['/certification/add','type'=>1])?>">
                    <img src="/images/yi3.png" alt="">
                </a>
            </div>
        <?php }else{ ?>
            <div class="yi3">
                <a href="javascript:;">
                    <img src="/images/yi05.png" alt="">
                </a>
            </div>
        <?php } ?>
        <p class="yii">
            在您未发布及未接单前，您可以根据自身情况，修改您的身份认证。
        </p>
</div>
</div>
