<?php \yii\widgets\ActiveForm::begin(['id'=>'NewsList','method'=>'post'])?>
<table class="table">
    <thead>
    <tr>
        <td colspan="2">经办人个数</td>
    </tr>
    </thead>
    <tr>
        <td align="right">经办人个数：</td>
        <td><?php echo \yii\helpers\Html::input('text','managersnumber',$model->managersnumber)?><?php echo \yii\helpers\Html::input('hidden','id',Yii::$app->request->get('id'))?></td>
    </tr>
    <tr><td colspan="2" align="center"><input type="submit" class="submit" value="保存" /></td></tr>
</table>
<?php \yii\widgets\ActiveForm::end();?>