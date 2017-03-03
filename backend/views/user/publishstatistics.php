<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
?>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th width="20px">ID</th>
                    <th width="60px">标题</th>
                    <th width="120px">摘要</th>
                    <th width="60px">发布时间</th>
                    <th width="100px">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($news as $v):?>
                    <tr>

                        <td><?php echo isset($v['id'])?$v['id']:''?></td>
                        <td><?php echo isset($v['title'])?$v['title']:''?></td>
                        <td><?php echo isset($v['abstract'])?mb_substr($v['abstract'],0,20,'utf-8'):''?></td>
                        <td><?php echo isset($v['create_time'])?date('Y-m-d',$v['create_time']):''?></td>
                        <td>【<a href="<?php echo yii\helpers\Url::to(['/news/editor','id'=>$v['id']])?>">编辑</a>】　【<a href="<?php echo yii\helpers\Url::to(['/news/delete','id'=>$v['id']])?>">删除</a>】</td>

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
