<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this View */
$this->title = '聊天界面';
list(, $mainUrl) = Yii::$app->getAssetManager()->publish('@backend/assets/main');
?>

<div class="col-lg-4 col-lg-offset-8">
    <div class="input-group">
        <input type="text" id="inp-name" placeholder="Type Name ..."
               value="<?= Html::encode($name); ?>" class="form-control">
        <span class="input-group-btn">
            <button type="button" class="btn btn-primary btn-flat" id="btn-name">设置聊天名字</button>
        </span>
    </div><br>
</div>

<div class="col-lg-12">
    <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
            <h3 class="box-title">聊天界面</h3>
        </div>
        <div class="box-body">
            <div class="direct-chat-messages" id="message-container">

            </div>
        </div>
        <div class="box-footer">
            <div class="input-group">
                <input type="text" id="inp-chat" placeholder="请输入聊天内容..." class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-flat" id="btn-chat">发送</button>
                </span>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('template_you') ?>
<div class="direct-chat-msg">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left" data-attr="name"></span>
        <span class="direct-chat-timestamp pull-right" data-attr="time"></span>
    </div>
    <?= Html::img($mainUrl . '/img/user-you.jpg', ['class' => 'direct-chat-img']) ?>
    <div class="direct-chat-text" data-attr="text"></div>
</div>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('template_me') ?>
<div class="direct-chat-msg right">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-right" data-attr="name">Me</span>
        <span class="direct-chat-timestamp pull-left" data-attr="time"></span>
    </div>
    <?= Html::img($mainUrl . '/img/user-me.jpg', ['class' => 'direct-chat-img']) ?>
    <div class="direct-chat-text" data-attr="text"></div>
</div>
<?php $this->endBlock(); ?>
<?php
$opts = json_encode([
    'messageUrl' => Url::to(['message']),
    'chatUrl' => Url::to(['chat']),
    'setNameUrl'=>Url::to(['set-name']),
    'lastSeenUrl' => Url::to(['last-seen']),
    'templateYou' => $this->blocks['template_you'],
    'templateMe' => $this->blocks['template_me'],
    ]);

$this->registerJs("var chatOpts = $opts;");
$this->registerJs($this->render('script.js'));
