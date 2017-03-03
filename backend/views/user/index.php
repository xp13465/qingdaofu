<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '用户列表';
?>

<div class="row">
    <div class="col-lg-12">
        <button type="button" class="btn btn-2g btn-primary" onclick="window.open('<?php echo \yii\helpers\Url::to('/user/output')?>')">导出</button><br /><br />
        <button type="button" class="btn btn-2g btn-primary" onclick="window.open('<?php echo \yii\helpers\Url::to('/user/outputpublishstatistics')?>')">导出用户发布数据</button><br /><br />
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th width="20px">序号</th>
                    <th width="20px">ID</th>
                    <th width="60px">用户名</th>
                    <th width="60px">注册时间</th>
                    <th width="60px">是否停用</th>
                    <th width="60px">是否认证</th>
                    <th width="60px">是否发布</th>
                    <th width="60px">是否接单</th>
                    <th width="60px">代理认证</th>
                </tr>
                </thead>
                <tbody>
                <?php $cc = 1;foreach($models as $v):?>
                    <tr>
                        <td><?php echo $pagination->page*$pagination->pageSize+$cc++;?></td>
                        <td><?php echo isset($v['id'])?$v['id']:''?></td>
                        <td><?php echo isset($v['mobile'])?$v['mobile']:''?></td>
                        <td><?php echo isset($v['created_at'])?date('Y-m-d H:i:s',$v['created_at']):''?></td>
                        <td>
                            <?php
                                $stop = ['1'=>'停用','0'=>'启用'];
                                echo isset($v['isstop'])?$stop[$v['isstop']]:''
                            ?>
                        </td>
                        <td><?php echo isset($v['pid'])?"经办人已认证":(Yii::$app->db->createCommand("select state from zcb_certification where uid = ".$v['id'])->queryScalar() == 1?"已认证":(Yii::$app->db->createCommand("select state from zcb_certification where uid = ".$v['id'])->queryScalar() == 2?'认证失败':'未认证'));?></td>
                        <td><?php echo Yii::$app->db->createCommand("select id from zcb_finance_product "."where uid = ".$v['id']." union select id from zcb_finance_product where uid = ".$v['id'])->queryScalar()> 0?"已发布":"未发布"?></td>
                        <td><?php echo Yii::$app->db->createCommand("select id from zcb_apply  "."where 	app_id = 1 and  uid = ".$v['id'])->queryScalar()> 0?"已接单":"未接单"?></td>
                        <?php
                         $certifiction = \common\models\Certification::findOne(['uid'=>$v['id']]);
                        $certifictions = \common\models\Certification::findOne(['uid'=>$v['pid']]);
                        if($certifiction){?>
                            <td><a style="color: #fff;background-color: #337ab7;   border: 1px solid transparent;border-radius: 4px;padding: 2px 6px;"
                                   href="<?php echo yii\helpers\Url::to(['/certification/modify','id'=>$v['id']])?>">修改认证</a></td>
                            <?php } else if($certifictions){?>
                               <td></td>
                        <?php }else{?>
                        <td><a style="color: #fff;background-color:#E6812F;   border: 1px solid transparent;border-radius: 4px;padding: 2px 6px;"
                                href="<?php echo yii\helpers\Url::to(['/certification/certi','id'=>$v['id']])?>">代理认证</a></td>
                        <?php }?>
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
