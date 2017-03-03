<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '接单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <button type="button" class="btn btn-2g btn-primary" onclick="window.open('<?php echo \yii\helpers\Url::to('/apply/output')?>')">导出</button><br /><br />
            <br/>
            <?php echo Html::dropDownList('category',Yii::$app->request->get('category'),array_merge([0=>'请选择类型'],\backend\services\Func::$category));?>
            <?php
            $arr = ['0'=>'待发布','1'=>'已发布','2'=>'处理中','3'=>'已终止','4'=>'已结案'];
            echo Html::dropDownList('progress_status',Yii::$app->request->get('progress_status'),array_merge([""=>"请选择处置进度"],$arr) );
            ?>
            <?php echo Html::input('text','mobile',Yii::$app->request->get('mobile'));?>

            <br/>
            <br/>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th width="20px">序号</th>
                    <th width="20px">ID</th>
                    <th width="20px">用户手机号</th>
                    <th width="60px">类型</th>
                    <th width="60px">金额(万)</th>
                    <th width="60px">编码</th>
                    <th width="60px">发布时间</th>
                    <th width="60px">是否接单</th>
                    <th width="60px">是否成交</th>
                    <th width="60px">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php $cc = 1;foreach($models as $v):?>
                    <tr>
                        <td><?php echo $pagination->page*$pagination->pageSize+$cc++;?></td>
                        <td><?php echo isset($v['id'])?$v['id']:''?></td>
                        <td><?php echo Yii::$app->db->createCommand("select mobile from zcb_user where id = ".$v['apply_uid'])->queryScalar();?></td>
                        <td><?php echo isset($v['category'])?\backend\services\Func::$category[$v['category']]:''?></td>
                        <td style="color:#FF4500;font-weight: bold;"><?php echo isset($v['money'])?$v['money']:''?></td>
                        <td><?php echo isset($v['code'])?$v['code']:''?></td>
                        <td><?php echo isset($v['create_time'])?date('Y-m-d H:i:s',$v['create_time']):''?></td>
                        <td><?php $app_id = Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$v['category']}' and product_id = '{$v['id']}' ")->queryScalar()==''?-1:Yii::$app->db->createCommand("select app_id from zcb_apply where category = '{$v['category']}' and product_id = '{$v['id']}' ")->queryScalar();echo  $app_id== 1?"已接单":( $app_id == 0?"已申请":"未申请");?></td>
                        <td><?php  echo isset($v['progress_status'])?$arr[$v['progress_status']]:''?></td>
                        <td><a href="<?php echo \yii\helpers\Url::to(["/apply/chakan","category"=>$v['category'],"id"=>$v['id']])?>">查看</a>
                        <?php /*<a href="javascript:;" onclick = "deletes('<?php echo $v['category']; ?>','<?php echo $v['id']; ?>');">删除</a> 
                         <?php if($v['progress_status'] == 2){?>
                        <a href = "javascript:;" onclick = "modifys('<?php echo $v['id']; ?>','<?php echo $v['category']; ?>')">修改</a>
                            <?php }else{echo '';}?>*/?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <div class="fenye clearfix"><?= \yii\widgets\LinkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '最后一页',
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?></div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        function searchForm(){
            var progress_status = $('select[name="progress_status"]').val();
            var isPick = $('select[name="isPick"]').val();
            var category = $('select[name="category"]').val();
            window.location = "<?php echo \yii\helpers\Url::to('/apply/index/')?>?progress_status="+progress_status+"&isPick="+isPick+"&category="+category;
        }

        $('select[name="progress_status"]').change(function(){
            searchForm();
        });

        $('select[name="isPick"]').change(function(){
            searchForm();
        });

        $('select[name="category"]').change(function(){
            searchForm();
        });

        $("input[name='mobile']").bind('keypress',function(event){
            if(event.keyCode == "13")
            {
                var mobile = $(this).val();
                window.location = "<?php echo \yii\helpers\Url::to('/apply/index/')?>?mobile="+mobile;
            }
        });
    });

    function deletes(category,id){
        if(confirm('是否要删除该条数据？')) {
            var de_url = "<?php echo \Yii\helpers\Url::toRoute(['/certification/deletes'])?>";
            $.ajax({
                type: 'post',
                url: de_url,
                data: {category: category, id: id},
                dataType: 'json',
                success:function(v){
                    if(v == 1){
                        window.location = "<?php echo \yii\helpers\Url::toRoute('/apply/index')?>";
                    }
                }

            });
        }
    }

    function modifys(id,category){
        if(confirm('是否要修改成发布中的状态？')){
            $.ajax({
                type:'post',
                url:"<?php echo \yii\helpers\Url::to("/apply/modify")?>",
                data:{id:id,category:category},
                dataType:'json',
                success:function(v){
                    if(v == 1){
                        window.location = "<?php echo \yii\helpers\Url::toRoute('/apply/index')?>";
                    }
                }
            })
        }
    }
</script>
