<!-- 终止发布 -->
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
    <table cellspacing="1" cellpadding="0">
        <thead>
        <tr>
            <th>区域</th>
            <th>类型</th>
            <th>编号</th>
            <th>发布时间</th>
            <th>金额(万元)</th>
            <th>申请记录(次)</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($creditor as $v):?>
            <tr>
                <td><?php echo isset($v['city_id']) && $v['city_id']== 310100 ?'上海市':''?></td>
                <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
                <td><?php echo isset($v['code'])?$v['code']:'';?></td>
                <td><?php echo isset($v['create_time'])?date('Y-m-d',$v['create_time']):''?></td>
                <td><?php echo isset($v['money'])?$v['money']:''?></td>
                <td>
                    <?php echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id = {$v['id']}")->queryScalar(); ?>
                </td>
                <td><?php echo \frontend\services\Func::$listMenu[$v['progress_status']];?></td>
                <td><a href="#" onclick="inquire('<?php echo $v['id']?>','<?php echo $v['category']?>');">查看</a>
                    <a href="#" onclick="editUserInfo('<?php echo $v['id']?>','<?php echo $v['category']?>');">编辑</a>
                    <?php if(!$v['applyclose']){?>
                        <a href="#" onclick="closeProduct(<?php echo $v['category'];?>,<?php echo $v['id']?>,'3');">终止</a>

                        <a href="#" onclick="closeProduct(<?php echo $v['category'];?>,<?php echo $v['id']?>,'4');">结案</a>
                    <?php }else{
                        if($v['applyclosefrom'] == Yii::$app->user->getId()){echo "申请中";}
                        else{?> <a href="#"   onclick="closeProductAgree(<?php echo $v['category'];?>,<?php echo $v['id']?>,<?php echo $v['applyclose']?>);">待确认</a><?php }
                    }?>
                     </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table><div class="fenye clearfix">
<?= linkPager::widget([
    'firstPageLabel' => '首页',
    'lastPageLabel' => '最后一页',
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'pagination' => $pagination,
    'maxButtonCount'=>4,
]);?></div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::toRoute('/apply/treatmentfinancing')?>";
        } else if(uid == '2'|| uid == '3'){
            user_url = "<?php echo Url::toRoute('/apply/treatmentcreditor')?>";
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
            location.href  = "<?php echo Url::toRoute('/publish/afterfinancing')?>/?id="+id;
        } else if(category == '2'|| category == '3'){
            location.href  = "<?php echo Url::toRoute('/publish/aftercreditor')?>/?id="+id;
        }
    }

    function closeProduct(category,id,status){
        var url = "<?php echo \yii\helpers\Url::toRoute('/apply/closeproduct')?>";
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
        var url = "<?php echo \yii\helpers\Url::toRoute('/apply/closeproductagree')?>";
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
</script>