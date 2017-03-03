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
        <td><a href="javascript:chakan('<?php echo $ms->uri?>','<?php echo $ms->id?>')">查看</a> </td>
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
<script type="text/javascript">
    function chakan(uri,id){
        $.ajax({
        url:'<?php echo \yii\helpers\Url::to('/message/isread')?>',
        type:'post',
        data:{
            id:id
        },
        dataType:'html',
        success:function(html){

        }
    })
    }
</script>