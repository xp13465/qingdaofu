<?php

/* @var $this yii\web\View */
?>
<div class="col-md-<?= $width ?>">
    <div class="box box-widget">
    <div class="box-header with-border">
    <h3 class="box-title">System Info</h3>
    <div class="box-tools pull-right">
    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
    </div>
    <div class="box-body">
    <b><?= Yii::t('zcb', 'Zcb CMS Version') ?>:</b> <?= Yii::$app->params['version']; ?><br/>
<b><?= Yii::t('zcb', 'Zcb Core Version') ?>:</b> <?php  //Zcb::getVersion(); ?><br/>
    <b><?= Yii::t('zcb', 'Yii Framework Version') ?>:</b> <?= Yii::getVersion(); ?><br/>
<b><?= Yii::t('zcb', 'PHP Version') ?>:</b> <?= phpversion(); ?><br/>
</div>
</div>
</div>
