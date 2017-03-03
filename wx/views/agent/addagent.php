<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'添加代理','gohtml'=>'<a href="javascript:void(0);" class="submits">保存</a>'])?>
<section>
    <?php yii\widgets\ActiveForm::begin(['id'=>'addagent'])?>
    <div class="zh-text">
        <?php echo Html::hiddenInput('id',isset($user['id'])?$user['id']:'')?>
        <ul>
            <li>
                <span>姓名</span>
                <?= Html::input('text','name',isset($user['username'])?$user['username']:'',['style'=>'width:65%','placeholder'=>"请输入姓名"])?>
            </li>
            <li>
                <span>联系方式</span>
                <?= Html::input('text','mobile',isset($user['mobile'])?$user['mobile']:'',['style'=>'width:65%','placeholder'=>"请输入联系方式"])?>
            </li>
            <li>
                <span>身份证号</span>
                <?= Html::input('text','cardon',isset($user['cardno'])?$user['cardno']:'',['style'=>'width:65%','placeholder'=>"请输入身份证号"])?>
            </li>
            <?php if($certification == 2){ ?>
            <li>
                <span>执业证号</span>
                <?= Html::input('text','zycardno',isset($user['zycardno'])?$user['zycardno']:'',['style'=>'width:65%','placeholder'=>"请输入职业证号"])?>
            </li>
            <?php } ?>
            <li>
                <span>登录密码</span>
                <?= Html::input('password','password',isset($user['password'])?$user['password']:'',['style'=>'width:65%','placeholder'=>"请设置代理人登录密码"])?>
            </li>
        </ul>
    </div>
    <?php yii\widgets\ActiveForm::end()?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.submits').click(function(){
            var list = "<?php echo Yii::$app->request->get('list')?>";
            if(list == 2){
                var url_agent = "<?php echo yii\helpers\Url::toRoute('/agent/modifyagent')?>";
            }else{
                var url_agent = "<?php echo yii\helpers\Url::toRoute('/agent/addagentdata')?>";
            }
            $.post(url_agent,$('#addagent').serialize(),function(json){
                  if(json.code == '0000'){
                      alert(json.msg);
                      window.location = "<?php echo yii\helpers\Url::toRoute('/agent/agentlist')?>";
                  }else{
                      alert(json.msg);
                  }
            },'json')
        })
    })
</script>
