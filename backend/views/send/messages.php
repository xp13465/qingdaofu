<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '发送短信';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-3">
        <div class="table-responsive">
            <form>
            <table class="table table-bordered table-hover table-striped" style='text-align:center;'>

                <tbody>
                    <tr>
                        <td>手机号：</td>
                        <td><?php echo Html::textInput('mobile');?></td>
                    </tr>
                    <tr>
                        <td>内容：</td>
                        <td><?php echo Html::textInput('messages');?></td>
                    </tr>
                    <tr >
                        <td colspan="2"><?php echo Html::buttonInput('发送',['class'=>'subbtn']);?></td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.subbtn').click(function(){
            $.ajax({
                url:'<?php echo \yii\helpers\Url::to('/send/messages')?>',
                type:'post',
                data:$('form').serialize(),
                dataType:'json',
                success: function (json) {
                    if(json.code == '0000'){
                        alert("发送短信成功");
                        location.reload();
                    }else{
                        alert(json.msg);
                    }
                }
            });
        });
    });
</script>