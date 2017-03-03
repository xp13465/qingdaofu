<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use backend\components\BackController;
use common\models\Form;
use common\models\FormCategory;
use common\models\search\FormSearch;

/**
 * Site controller
 */
class FormCategoryController extends BackController
{

    public $modelClass = 'common\models\FormCategory';
    public $modelSearchClass = 'common\models\search\FormCategorySearch';


    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
                $searchName = StringHelper::basename($searchModel::className());
                $params = Yii::$app->request->getQueryParams();
                $dataProvider = $searchModel->search($params);
        } else {
                $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
                $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('index', compact('dataProvider', 'searchModel'));

    }

    public function actionCreate()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '表单添加成功.');
                return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model'));
    }

    public function actionFields($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('save'))
        {
            $fields = Yii::$app->request->post('Field') ?: [];
            $result = [];

            foreach($fields as $field){
                $temp = json_decode($field);

                if( $temp === null && json_last_error() !== JSON_ERROR_NONE ||
                    empty($temp->name) ||
                    empty($temp->title) ||
                    empty($temp->type) ||
                    !($temp->name = trim($temp->name)) ||
                    !($temp->title = trim($temp->title)) ||
                    !array_key_exists($temp->type, FormCategory::$fieldTypes)
                ){
                    continue;
                }
                $options = trim($temp->options);
                if($temp->type == 'select' || $temp->type == 'checkbox'){
                    if($options == ''){
                        continue;
                    }
                    $optionsArray = [];
                    foreach(explode(',', $options) as $option){
                        $optionsArray[] = trim($option);
                    }
                    $options = $optionsArray;
                }

                $result[] = [
                    'name' => \yii\helpers\Inflector::slug($temp->name),
                    'title' => $temp->title,
                    'type' => $temp->type,
                    'options' => $options
                ];
            }

            $model->fields = $result;

            if($model->save()){
                $ids = [];
                if(count($ids)){
                    Category::updateAll(['fields' => json_encode($model->fields)], ['in', 'category_id', $ids]);
                }
                Yii::$app->session->setFlash('crudMessage', '表单字段更新成功.');
            }
            else{
                Yii::$app->session->setFlash('crudMessage', '表单字段更新失败.');
            }
            return $this->refresh();
        }
        else {
            return $this->renderIsAjax('fields', compact('model'));
        }

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '表单更新成功.');
                return $this->redirect($this->getRedirectPage('update', $model));
        }

        return $this->renderIsAjax('update', compact('model'));
    }

    private function generateForm($fields, $data = null)
    {
        $result = '';
        foreach($fields as $field)
        {
            $value = !empty($data->{$field->name}) ? $data->{$field->name} : null;
            if ($field->type === 'string') {
                $result .= '<div class="form-group"><label>'. $field->title .'</label>'. Html::input('text', "Data[{$field->name}]", $value, ['class' => 'form-control']) .'</div>';
            }
            elseif ($field->type === 'text') {
                $result .= '<div class="form-group"><label>'. $field->title .'</label>'. Html::textarea("Data[{$field->name}]", $value, ['class' => 'form-control']) .'</div>';
            }

            elseif ($field->type === 'boolean') {
                $result .= '<div class="checkbox"><label>'. Html::checkbox("Data[{$field->name}]", $value, ['uncheck' => 0]) .' '. $field->title .'</label></div>';
            }
            elseif ($field->type === 'select') {
                $options = ['' => Yii::t('zcb', 'Select')];
                foreach($field->options as $option){
                    $options[$option] = $option;
                }
                $result .= '<div class="form-group"><label>'. $field->title .'</label><select name="Data['.$field->name.']" class="form-control">'. Html::renderSelectOptions($value, $options) .'</select></div>';
            }
            elseif ($field->type === 'checkbox') {
                $options = '';
                foreach($field->options as $option){
                    $checked = $value && in_array($option, $value);
                    $options .= '<br><label>'. Html::checkbox("Data[{$field->name}][]", $checked, ['value' => $option]) .' '. $option .'</label>';
                }
                $result .= '<div class="checkbox well well-sm"><b>'. $field->title .'</b>'. $options .'</div>';
            }

        }
        return $result;
    }


    private function parseData(&$model)
    {
        $data = Yii::$app->request->post('Data');
        $model->data = $data;
    }


    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
                case 'delete':
                        return ['index'];
                        break;
                case 'update':
                        return ['index'];
                        break;
                case 'create':
                        return ['index'];
                        break;
                default:
                        return ['index'];
        }
    }

}
