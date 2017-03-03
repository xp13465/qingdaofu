<table>
    <tr class="table_top">
        <td>时间</td>
        <td class="info1">消息名称</td>
        <td>内容</td>
        <td>操作</td>
    </tr>
    <?php foreach($messages  as $ms){?>
        <tr>
            <td><?php echo date('Y-m-d H:i:s',$ms->create_time);?></td>
            <td>[<?php echo $ms->title?>]</td>
            <td>
                <?php echo $ms->content?>
            </td>
            <td>已读</td>
        </tr>
    <?php }?>
</table>
<div class="fenye clearfix"><?= \yii\widgets\LinkPager::widget([
        'firstPageLabel' => '首页',
        'lastPageLabel' => '最后一页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'pagination' => $pagination,
        'maxButtonCount'=>4,
    ]);?></div>