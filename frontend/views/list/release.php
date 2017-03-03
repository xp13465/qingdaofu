<!--发布列表-->
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
        <td><?php echo \frontend\services\Func::$listMenu[$v['progress_status']];?></td>
        <td><a  onclick="inquire('<?php echo $v['id']?>','<?php echo $v['category']?>')">查看</a></td>
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
         var release_url = '';
         if(uid == '1'){
             release_url = "<?php echo Url::toRoute('/publish/editfinancing')?>";
         } else if(uid == '2'){
             release_url = "<?php echo Url::toRoute('/publish/editcollection')?>";
         } else if(uid == '3'){
             release_url = "<?php echo Url::toRoute('/publish/editlitigation')?>";
         } else {
             release_url = FALSE;
         }
        if(release_url) {
             location.href = release_url+'?id='+id;
         } else {
             alert('链接有问题！');
         }
     }
</script>

