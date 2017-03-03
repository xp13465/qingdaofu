
<div class="details">
    <div class="look">
        <h3>查看信息</h3><a href="<?php echo yii\helpers\Url::toRoute('/list/index')?>"><img src="/images/close1.png" style="float: right;margin: -25px 10px 0px 0px;"></a>
    </div>
    <!-- 融资详情 -->
    <div class="finance">
        <?php echo $this->renderFile('@app/views/public/pubdetail.php',['category'=>$dt['category'],'id' =>$dt['id']]);?>
    </div>

    <?php
        //申请详情
        if(in_array($dt->progress_status,[1])){
            echo $this->renderFile('@app/views/public/applydetail.php',['category'=>$dt['category'],'id'=>$dt['id']]);
        }
    ?>
    <?php
        //协议
        if(in_array($dt->progress_status,[2,4])){
            echo $this->renderFile('@app/views/public/protocoldetail.php', ['category' => $dt['category'], 'id' => $dt['id']]);
        }
    ?>
    <?php
        //处置方信息
        if(in_array($dt->progress_status,[2,4])){
            echo $this->renderFile('@app/views/public/disposinguser.php', ['category' => $dt['category'], 'id' => $dt['id']]);
        }
    ?>
    <?php
        //处置进度
        if(in_array($dt->progress_status,[2,4])){
            echo $this->renderFile('@app/views/public/disposingprocess.php', ['category' => $dt['category'], 'id' => $dt['id']]);
        }
    ?>
    <?php
    //延迟申请
    if(in_array($dt->progress_status,[2])){
        echo $this->renderFile('@app/views/public/delaydetail.php', ['category' => $dt['category'], 'id' => $dt['id']]);
        }
    ?>
    <?php
    //申请关闭
    $status = Yii::$app->request->get('status');
    if(in_array($dt->progress_status,[1,2]) && isset($status) && in_array($status,[3,4])){
        echo $this->renderFile('@app/views/public/close.php', ['category' => $dt['category'],'uid'=>$dt['uid'], 'id' => $dt['id'],'applyclose'=>$dt['applyclose'],'applyclosefrom'=>$dt['applyclosefrom'],'progress_status'=>$dt['progress_status'],"status"=>$status]);
        }
    ?>

    <?php
    //评价
    if(in_array($dt->progress_status,[4])){
        echo $this->renderFile('@app/views/public/evaluatedetail.php', ['category' => $dt['category'], 'id' => $dt['id']]);
    }
    ?>

</div>

