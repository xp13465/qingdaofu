<div class="kuang">
    <div class="fa01">
        <p class="fa01_img">
            <span class="jiedan">我的接单 </span>(共 20 条接单信息,其中<span class="x_jiedan">新的处理信息</span> 2 个)
        </p>
        <form>
            <a class="btn-select" id="btn_select">
                <span class="cur-select">全部</span>
                <select name="status" onchange="changestatus(this.value)">
                    <option value="0">全部</option>
                    <option value="1">待发布</option>
                    <option value="2">已发布</option>
                    <option value="3">处理中</option>
                    <option value="4">已终止</option>
                    <option value="5" selected="">已结案</option>
                </select>
            </a>
        </form>
    </div>
    <div class="tm_table">
        <table style="width:830px;text-align:center;" cellpadding="0" cellspacing="0">
            <thead>
            <th>区域</th>
            <th>类型</th>
            <th>编号</th>
            <th>发布时间</th>
            <th>金额(万元)</th>
            <th>申请记录(次)</th>
            <th>状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            <tr>
                <?php foreach($creditor as $v):?>
                <td><?php echo \frontend\services\Func::getProvinceNameById($v['province_id']);?></td>
                <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
                <td><?php echo isset($v['code'])?$v['code']:'';?></td>
                <td><?php echo isset($v['create_time'])?date('Y-m-d H:i',$v['create_time']):''?></td>
                <td class="jin"><?php echo isset($v['money'])?$v['money']:''?></td>
                <td><?php echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id =  {$v['id']}")->queryScalar()?></td>
                <td class="chuli"> <?php
                    if($v['app_id'] == 2){
                        echo "收藏中";
                    }elseif($v['app_id'] == 0){
                        echo "申请中";
                    }elseif($v['app_id'] == 1){
                        if($v['progress_status'] ==2){
                            echo "处理中";
                        }elseif(in_array($v['progress_status'],[3,4])){
                            echo $v['progress_status']==3?"已终止":"已结案";
                        }
                    }
                    ?></td>
                <td class="td_color">
                    <?php if($v['progress_status'] == 2) { ?>
                        <?php if ($v['app_id'] == 1) { ?>
                            <a href="<?php echo Url::to(["/order/chakan",'category'=>$v['category'],'id'=>$v['id']])?>">查看</a>
                        <?php }?>
                    <?php }else{?>
                        <a href="<?php echo Url::to(["/order/chakan",'category'=>$v['category'],'id'=>$v['id']])?>">查看</a>
                    <?php  }?>
                    <?php if($v['progress_status'] == 2) { ?>

                        <?php if ($v['app_id'] == 1) { ?>

                            <?php if (!$v['applyclose']) { ?>
                                <a href="<?php echo Url::to(["/order/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => 3]) ?>">终止</a>
                                <a href="<?php echo Url::to(["/order/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => 4]) ?>">结案</a>

                            <?php } else {
                                if ($v['applyclosefrom'] == Yii::$app->user->getId()) {
                                    ?>
                                    <a href="<?php echo Url::to(["/list/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => $v['applyclose']]) ?>"><?php echo $v['applyclose'] == 3 ? "终止申请中" : '结案申请中'; ?></a>
                                <?php } else {
                                    ?>
                                    <a href="<?php echo Url::to(["/list/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => $v['applyclose']]) ?>"><?php echo $v['applyclose'] == 3 ? "终止待确认" : '结案待确认'; ?></a>
                                <?php }
                            } ?>
                        <?php }else {
                            echo "申请失败";
                            ?>

                            <?php
                        }
                    }?>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="fenye clearfix">
            <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
                <?php echo '<span class="fenyes" style="line-height:34px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条消息'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
                <?= \yii\widgets\LinkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
            <?php }else{ echo '';} ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::to('/order/financingprogress')?>";
        } else if(uid == '2' || uid == '3'){
            user_url = "<?php echo Url::to('/order/creditorprogress')?>";
        }  else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }

    function closeProduct(category,id,status){
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproduct')?>";
        var tips = "请确定是否结案";
        if(status == 3){
            tips = "请确定是否终止";
        }
        if(confirm(tips)){
            $.ajax({
                url:url,
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
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproductagree')?>";
        var tips = "请确定是否同意终止";
        if(status == 4)tips = "请确定是否同意结案";
        if(confirm(tips)){
            $.ajax({
                url:url,
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
    }
    function changestatus(status){
        location.href = "<?php echo Url::to(['/order/index/'])?>?status="+status;
    }
</script>






<div class="kuang">
    <div class="fa01">
        <img src="images/fa.png" height="43" width="140" alt="" class="fa01_img" />
            <form>
                <a class="btn-select" id="btn_select">
                    <span class="cur-select">全部</span>
                    <select>
                        <option>发布中</option>
                        <option>待处理</option>
                        <option>已发布</option>
                    </select>
                </a>
            </form>
    </div>
    <div class="tm_table">
        <table style="width:830px;text-align:center;" cellpadding="0" cellspacing="0">
            <thead>
            <th>区域</th>
            <th>类型</th>
            <th>编号</th>
            <th>发布时间</th>
            <th>金额(万元)</th>
            <th>申请记录(次)</th>
            <th>状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            <?php foreach($creditor as $v):?>
            <tr>
                <td><?php echo \frontend\services\Func::getProvinceNameById($v['province_id']);?></td>
                <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
                <td><?php echo isset($v['code'])?$v['code']:'';?></td>
                <td><?php echo isset($v['create_time'])?date('Y-m-d H:i',$v['create_time']):''?></td>
                <td class="jin"><?php echo isset($v['money'])?$v['money']:''?></td>
                <td>
                    <?php echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id = {$v['id']}")->queryScalar(); ?>
                </td>
                <td class="chuli"><?php echo \frontend\services\Func::$listMenu[$v['progress_status']];?></td>
                <td class="td_color">
                    <a href="javascript:void(0)" onclick="check('<?php echo $v['category']?>','<?php echo $v['id']?>');">查看</a>

                    <?php if($v['progress_status'] == 2){?>
                        <a href="#" onclick="editUserInfo('<?php echo $v['id']?>','<?php echo $v['category']?>');">编辑</a>

                        <?php if(!$v['applyclose']){?>
                            <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>3])?>">终止</a>
                            <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>4])?>">结案</a>

                        <?php }else{
                            if($v['applyclosefrom'] == Yii::$app->user->getId()){?>
                                <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>"><?php echo $v['applyclose']==3?"终止申请中":'结案申请中';?></a>
                            <?php }
                            else{?>
                                <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>"><?php echo $v['applyclose']==3?"终止待确认":'结案待确认';?></a>
                            <?php }
                        }?>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <div class="fenye clearfix">
            <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
                <?php echo '<span class="fenyes" style="line-height:34px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条消息'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
                <?= \yii\widgets\LinkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
            <?php }else{ echo '';} ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::to('/apply/treatmentfinancing')?>";
        } else if(uid == '2'|| uid == '3'){
            user_url = "<?php echo Url::to('/apply/treatmentcreditor')?>";
        } else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }

    function editUserInfo(id,category){
        if(category == '1'){
            location.href  = "<?php echo Url::to('/publish/afterfinancing')?>/?id="+id;
        } else if(category == '2'|| category == '3'){
            location.href  = "<?php echo Url::to('/publish/aftercreditor')?>/?id="+id;
        }
    }

    function closeProduct(category,id,status){
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproduct')?>";
        var tips = "请确定是否结案";
        if(status == 3){
            tips = "请确定是否终止";
        }
        if(confirm(tips)){
            $.ajax({
                url:url,
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
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproductagree')?>";
        var tips = "请确定是否同意终止";
        if(status == 4)tips = "请确定是否同意结案";
        if(confirm(tips)){
            $.ajax({
                url:url,
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
    }
    function changestatus(status){
        location.href = "<?php echo Url::to(['/list/index/'])?>?status="+(status-1);
    }

    function check(category,id){
        var url = "<?php echo Url::to("/list/attestation")?>";
        $.post(url,{category:category,id:id},function(i){
            if(i.status == 0 ){
                location.href="<?php echo Url::to("/list/chakan");?>?category="+category+"&id="+id;
            }else if(i.status == 1){
                $.msgbox({
                    height: 140,
                    width: 280,
                    content: '<p style="text-align:center">您还未认证,请进行身份认证!</p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::to("/certification/index");?>";
                        } else {

                        }
                    }
                });
            }
        },'json');
    }
</script>















<!-- 终止发布 -->
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<table cellspacing="1" cellpadding="0">
    <thead>
    <tr>

        <th width="60px">区域</th>
        <th width="60px">类型</th>
        <th width="140px">编号</th>
        <th width="140px">发布时间</th>
        <th width="90px">金额(万元)</th>
        <th width="90px">申请记录(次)</th>
        <th width="140px">状态　<?php echo Html::dropDownList('status',(Yii::$app->request->get('status')==''?-1:Yii::$app->request->get('status'))+1,['0'=>'全部','1'=>'待发布','2'=>'已发布','3'=>'处理中','4'=>'已终止','5'=>'已结案'],['onchange'=>"changestatus(this.value)"])?></th>
        <th width="160px">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($creditor as $v):?>
        <tr>
            <td><?php echo \frontend\services\Func::getProvinceNameById($v['province_id']);?></td>
            <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
            <td><?php echo isset($v['code'])?$v['code']:'';?></td>
            <td><?php echo isset($v['create_time'])?date('Y-m-d H:i',$v['create_time']):''?></td>
            <td><?php echo isset($v['money'])?$v['money']:''?></td>
            <td>
                <?php echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id = {$v['id']}")->queryScalar(); ?>
            </td>
            <td><?php echo \frontend\services\Func::$listMenu[$v['progress_status']];?></td>
            <td>

                <a href="javascript:void(0)" onclick="check('<?php echo $v['category']?>','<?php echo $v['id']?>');">查看</a>

                <?php if($v['progress_status'] == 2){?>
                    <a href="#" onclick="editUserInfo('<?php echo $v['id']?>','<?php echo $v['category']?>');">编辑</a>

                    <?php if(!$v['applyclose']){?>
                        <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>3])?>">终止</a>
                        <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>4])?>">结案</a>

                    <?php }else{
                        if($v['applyclosefrom'] == Yii::$app->user->getId()){?>
                            <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>"><?php echo $v['applyclose']==3?"终止申请中":'结案申请中';?></a>
                        <?php }
                        else{?>
                            <a href="<?php echo Url::to(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>"><?php echo $v['applyclose']==3?"终止待确认":'结案待确认';?></a>
                        <?php }
                    }?>
                <?php }?>

            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<div class="fenye clearfix">
    <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
        <?php echo '<span class="fenyes" style="line-height:34px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
            '条消息'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
            .'页'. '</span>';?>
        <?= \yii\widgets\LinkPager::widget([
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'pagination' => $pagination,
            'maxButtonCount'=>4,
        ]);?>
    <?php }else{ echo '';} ?>
</div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::to('/apply/treatmentfinancing')?>";
        } else if(uid == '2'|| uid == '3'){
            user_url = "<?php echo Url::to('/apply/treatmentcreditor')?>";
        } else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }

    function editUserInfo(id,category){
        if(category == '1'){
            location.href  = "<?php echo Url::to('/publish/afterfinancing')?>/?id="+id;
        } else if(category == '2'|| category == '3'){
            location.href  = "<?php echo Url::to('/publish/aftercreditor')?>/?id="+id;
        }
    }

    function closeProduct(category,id,status){
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproduct')?>";
        var tips = "请确定是否结案";
        if(status == 3){
            tips = "请确定是否终止";
        }
        if(confirm(tips)){
            $.ajax({
                url:url,
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
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproductagree')?>";
        var tips = "请确定是否同意终止";
        if(status == 4)tips = "请确定是否同意结案";
        if(confirm(tips)){
            $.ajax({
                url:url,
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
    }
    function changestatus(status){
        location.href = "<?php echo Url::to(['/list/index/'])?>?status="+(status-1);
    }

    function check(category,id){
        var url = "<?php echo Url::to("/list/attestation")?>";
        $.post(url,{category:category,id:id},function(i){
            if(i.status == 0 ){
                location.href="<?php echo Url::to("/list/chakan");?>?category="+category+"&id="+id;
            }else if(i.status == 1){
                $.msgbox({
                    height: 140,
                    width: 280,
                    content: '<p style="text-align:center">您还未认证,请进行身份认证!</p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::to("/certification/index");?>";
                        } else {

                        }
                    }
                });
            }
        },'json');
    }
</script>






<!-- 终止发布 -->
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<table cellspacing="1" cellpadding="0">
    <thead>
    <tr>
        <th width="60px">区域</th>
        <th width="60px">类型</th>
        <th width="140px">编号</th>
        <th width="140px">发布时间</th>
        <th width="90px">金额(万元)</th>
        <!--<th width="90px">申请记录(次)</th>-->
        <th width="140px">状态　<?php echo Html::dropDownList('status',Yii::$app->request->get('status'),['0'=>'全部','1'=>'收藏中','2'=>'申请中','3'=>'处理中','4'=>'已终止','5'=>'已结案'],['onchange'=>"changestatus(this.value)"])?></th>
        <th width="160px">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($creditor as $v):?>
        <tr>
            <td><?php echo \frontend\services\Func::getProvinceNameById($v['province_id']);?></td>
            <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
            <td><?php echo isset($v['code'])?$v['code']:'';?></td>
            <td><?php echo isset($v['create_time'])?date('Y-m-d H:i',$v['create_time']):''?></td>
            <td><?php echo isset($v['money'])?$v['money']:''?></td>
            <!--<td><?php /*echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id =  {$v['id']}")->queryScalar()*/?></td>-->
            <td>
                <?php
                if($v['app_id'] == 2){
                    echo "收藏中";
                }elseif($v['app_id'] == 0){
                    echo "申请中";
                }elseif($v['app_id'] == 1){
                    if($v['progress_status'] ==2){
                        echo "处理中";
                    }elseif(in_array($v['progress_status'],[3,4])){
                        echo $v['progress_status']==3?"已终止":"已结案";
                    }
                }
                ?>
            </td>
            <td>

                <?php if($v['progress_status'] == 2) { ?>
                    <?php if ($v['app_id'] == 1) { ?>
                        <a href="<?php echo Url::to(["/order/chakan",'category'=>$v['category'],'id'=>$v['id']])?>">查看</a>
                    <?php }?>
                <?php }else{?>
                    <a href="<?php echo Url::to(["/order/chakan",'category'=>$v['category'],'id'=>$v['id']])?>">查看</a>
                <?php  }?>
                <?php if($v['progress_status'] == 2) { ?>

                    <?php if ($v['app_id'] == 1) { ?>

                        <?php if (!$v['applyclose']) { ?>
                            <a href="<?php echo Url::to(["/order/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => 3]) ?>">终止</a>
                            <a href="<?php echo Url::to(["/order/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => 4]) ?>">结案</a>

                        <?php } else {
                            if ($v['applyclosefrom'] == Yii::$app->user->getId()) {
                                ?>
                                <a href="<?php echo Url::to(["/list/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => $v['applyclose']]) ?>"><?php echo $v['applyclose'] == 3 ? "终止申请中" : '结案申请中'; ?></a>
                            <?php } else {
                                ?>
                                <a href="<?php echo Url::to(["/list/chakan", "category" => $v['category'], "id" => $v['id'], 'status' => $v['applyclose']]) ?>"><?php echo $v['applyclose'] == 3 ? "终止待确认" : '结案待确认'; ?></a>
                            <?php }
                        } ?>
                    <?php }else {
                        echo "申请失败";
                        ?>

                        <?php
                    }
                }?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<div class="fenye clearfix">
    <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
        <?php echo '<span class="fenyes" style="line-height:34px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
            '条消息'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
            .'页'. '</span>';?>
        <?= \yii\widgets\LinkPager::widget([
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'pagination' => $pagination,
            'maxButtonCount'=>4,
        ]);?>
    <?php }else{ echo '';} ?>

</div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::to('/order/financingprogress')?>";
        } else if(uid == '2' || uid == '3'){
            user_url = "<?php echo Url::to('/order/creditorprogress')?>";
        }  else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }

    function closeProduct(category,id,status){
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproduct')?>";
        var tips = "请确定是否结案";
        if(status == 3){
            tips = "请确定是否终止";
        }
        if(confirm(tips)){
            $.ajax({
                url:url,
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
        var url = "<?php echo \yii\helpers\Url::to('/apply/closeproductagree')?>";
        var tips = "请确定是否同意终止";
        if(status == 4)tips = "请确定是否同意结案";
        if(confirm(tips)){
            $.ajax({
                url:url,
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
    }
    function changestatus(status){
        location.href = "<?php echo Url::to(['/order/index/'])?>?status="+status;
    }
</script>