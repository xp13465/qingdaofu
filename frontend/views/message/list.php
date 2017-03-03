<table>
    <tr class="table_top">
        <td  style="font-size:14px;">时间</td>
        <td class="info1" style="font-size:14px;">消息名称</td>
        <td  style="font-size:14px;">内容</td>
        <td style="width:44px;font-size:14px;">操作</td>
    </tr>
    <?php foreach($messages  as $ms){?>
        <tr>
            <td><?php echo date('Y-m-d H:i:s',$ms->create_time);?></td>
            <td>[<?php echo $ms->title?>]</td>
            <td>
                <?php echo $ms->content?>
            </td>
            <td><?php if($ms->isRead == 1){ echo "已读" ;}else {?>  <a href="javascript:chakan('<?php echo $ms->uri?>','<?php echo $ms->id?>')">阅读</a><?php }?></td>
        </tr>
    <?php }?>
</table>

<div class="fenye clearfix" style="top:20px">
    <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
    <?php echo '<span class="fenyes" style="font-size:12px;margin:0px 35px -41px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
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
    function chakan(uri,id){
        $.ajax({
            url:'<?php echo \yii\helpers\Url::toRoute('/message/isread')?>',
            type:'post',
            data:{
                id:id
            },
            dataType:'html',
            success:function(html){
                if(uri == ''){
                    window.location =  window.location ;
                }else{
                    window.location = uri;
                }

            }
        })
    }
</script>