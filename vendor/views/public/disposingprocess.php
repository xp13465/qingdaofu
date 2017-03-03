<?php
use yii\data\Pagination;
use yii\widgets\LinkPager;
$count = \common\models\DisposingProcess::find()->Where(['category'=>$category,'product_id'=>$id])->count();
$pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$count]);
$disposingprocess = \common\models\DisposingProcess::find()->Where(['category'=>$category,'product_id'=>$id])->offset($pagination->offset)->limit($pagination->limit)->orderBy('create_time desc')->all();
?>
<div class="progressa">
    <span>处置进度</span>
    <i></i>
    <div class="mytable">
        <table cellspacing="1" cellpadding="0">
            <thead>
            <tr>
                <th>日期</th>
                <th>状态</th>
                <th>详情</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($disposingprocess as $dp){?>
            <tr>
                <td><?php echo date('Y',$dp->create_time).'年'.date('m',$dp->create_time).'月'.date('d',$dp->create_time).'日'.date('H',$dp->create_time).':'.date('i',$dp->create_time)?></td>
                <td><?php echo \frontend\services\Func::$Status[$dp->status];?></td>
                <td>
                    <?php echo $dp->content;?>
                </td>
            </tr>
            <?php }?>

            </tbody>

        </table>

    </div>
</div>
<div class="fenye clearfix">
    <?php echo LinkPager::widget([
        'firstPageLabel' => '首页',
        'lastPageLabel' => '最后一页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'pagination' => $pagination,
        'maxButtonCount'=>4,
    ]);?></div>
