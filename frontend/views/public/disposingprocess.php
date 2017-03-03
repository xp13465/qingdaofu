<?php
use yii\helpers\Html;
use yii\data\Pagination;
use yii\widgets\LinkPager;
$count = \common\models\DisposingProcess::find()->Where(['category'=>$category,'product_id'=>$id])->count();
$pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$count]);
$disposingprocess = \common\models\DisposingProcess::find()->Where(['category'=>$category,'product_id'=>$id])->offset($pagination->offset)->limit($pagination->limit)->orderBy('create_time desc')->all();
if($category == 1){
    $user = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $user = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}
?>
<div class="progressa">
    <?php if($user->uid == Yii::$app->user->getId() || $user->progress_status == 4){?>
    <span class="bord_l">处置进度</span>
	<?php } ?>
    <?php if($user->uid == Yii::$app->user->getId() || in_array($category,[1,2])){?>
        <?php echo "";?>
    <?php }else{?>
        <p class="anhao">
            <label for="">案号 : </label>
            <select id="case">
            <option>请选择</option>
               <option value="0">一审</option>
               <option value="1">二审</option>
               <option value="2">再审</option>
               <option value="3">执行</option>
            </select>
            <span class="audits"></span>
        </p>
    <?php } ?>
    <div class="manage">
        <table collapse="collapse">
            <tr collspan="2">
                <th width="180">日期</th>
                <th width="80">状态</th>
                <th>详情</th>
            </tr>
            <?php foreach($disposingprocess as $dp){?>
            <tr>
                <td><?php echo date('Y',$dp->create_time).'年'.date('m',$dp->create_time).'月'.date('d',$dp->create_time).'日'.date('H',$dp->create_time).':'.date('i',$dp->create_time)?></td>
                <td><?php echo \frontend\services\Func::$Status[$category][$dp->status];?></td>
                <td>
                    <?php echo $dp->content;?>
                </td>
            </tr>
            <?php }?>
        </table>

    </div>
    <div class="fenye clearfix">
    <?php echo LinkPager::widget([
        'firstPageLabel' => '首页',
        'lastPageLabel' => '最后一页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'pagination' => $pagination,
        'maxButtonCount'=>4,
    ]);?>
</div>
</div>


<script typt="text/javascript">
    $(function() {
        $('#case').change(function () {
            var cases = $(this).val();
            var id = "<?php echo $id ?>";
            var category = "<?php echo $category?>";
            var url = "<?php echo yii\helpers\Url::toRoute('/order/audit')?>";
            $.ajax({
                url:url,
                type:'post',
                data:{cases: cases, id: id, category: category},
                dataType:'html',
                success:function(html){
                    $('.audits').html(html);
                }
            });
        });
    })
</script>
