<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\FieldsAsset;
use common\models\FormCategory;

$this->title = Yii::t('zcb', 'Update "{item}"', ['item' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('zcb', 'Form'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->primaryKey]];
$this->params['breadcrumbs'][] = Yii::t('zcb', 'Update');

$action = $this->context->action->id;


$this->registerAssetBundle(FieldsAsset::className());

$this->registerJs('
var fieldTemplate = \'\
    <tr>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-name']) .'</td>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-title']) .'</td>\
        <td>\
            <select class="form-control field-type">'.str_replace("\n", "", Html::renderSelectOptions('', FormCategory::$fieldTypes)).'</select>\
        </td>\
        <td><textarea class="form-control field-options" placeholder="'.Yii::t('zcb', 'Type options with `comma` as delimiter').'" style="display: none;"></textarea></td>\
        <td class="text-right">\
            <div class="btn-group btn-group-sm" role="group">\
                <a href="#" class="btn btn-default color-red delete-field" title="'. Yii::t('zcb', 'Delete item') .'"><span class="glyphicon glyphicon-remove"></span></a>\
            </div>\
        </td>\
    </tr>\';
', \yii\web\View::POS_HEAD);

?>

<div class="page-create">

<div class="nav-tabs-justified">

    <ul class="nav nav-tabs">
        <li <?= ($action === 'update') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/form-category/update', 'id' => $model->primaryKey]) ?>"><?= Yii::t('zcb', 'Edit') ?></a></li>
        <li <?= ($action === 'fields') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/form-category/fields', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-cog"></span> <?= Yii::t('zcb', 'Fields') ?></a></li>
    </ul>
</div>

<p class="addField"><?= Html::button('<i class="glyphicon glyphicon-plus font-12"></i> '.Yii::t('zcb', 'Add field'), ['class' => 'btn btn-default', 'id' => 'addField']) ?></p>

<table id="FormFields" class="table table-hover">
    <thead>
        <th><?= Yii::t('zcb', 'Field Name') ?></th>
        <th><?= Yii::t('zcb', 'Field Title') ?></th>
        <th><?= Yii::t('zcb', 'Field Type') ?></th>
        <th><?= Yii::t('zcb', 'Field Options') ?></th>
        <th width="120"></th>
    </thead>
    <tbody>
    <?php foreach($model->fields as $field) : ?>
        <tr>
            <td><?= Html::input('text', null, $field->name, ['class' => 'form-control field-name']) ?></td>
            <td><?= Html::input('text', null, $field->title, ['class' => 'form-control field-title']) ?></td>
            <td>
                <select class="form-control field-type">
                    <?= Html::renderSelectOptions($field->type, FormCategory::$fieldTypes) ?>
                </select>
            </td>
            <td>
                <textarea class="form-control field-options" placeholder="<?= Yii::t('zcb', 'Type options with `comma` as delimiter') ?>" <?= (!$field->options && $field->type != 'file') ? 'style="display: none;"' : '' ?> ><?= is_array($field->options) ? implode(',', $field->options) : $field->options ?></textarea>
            </td>
            <td class="text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="#" class="btn btn-default color-red delete-field" title="<?= Yii::t('zcb', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= Html::button('<i class="glyphicon glyphicon-ok"></i> '.Yii::t('zcb', 'Save fields'), ['class' => 'btn btn-primary', 'id' => 'saveCategoryBtn']) ?>

</div>