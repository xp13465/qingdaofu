<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use backend\components\BackController;
use backend\models\Form;
use backend\models\FormCategory;
use backend\models\search\FormSearch;
use backend\behaviors\StatusController;


/**
 * Site controller
 */
class FormController extends BackController
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Form::className()
            ]
        ];
    }

    public $modelClass = 'backend\models\Form';
    public $modelSearchClass = 'backend\models\search\FormSearch';

    public function actionIndex($id)
    {

        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->with('category');
        $dataProvider->query->where(['category_id'=>$id]);

        if(!($model = FormCategory::findOne($id))){
                return $this->redirect(['/'.$this->module->id]);
        }

        return $this->render('index', compact('dataProvider', 'searchModel', 'model'));
    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $dataForm = $this->generateForm($model->category->fields);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                //$model->category_id = $formcategory->primaryKey;
                $model->updated_by = Yii::$app->user->getId();
                $this->parseData($model);

                if ($model->save()) {
                    Yii::$app->session->setFlash('crudMessage', '表单添加成功.');
                    return $this->redirect($this->getRedirectPage('create', $model));

                } else {
                    Yii::$app->session->setFlash('crudMessage', '表单添加错误.');
                    return $this->refresh();
                }
            }
        }

        return $this->renderIsAjax('update', compact('model', 'dataForm'));
    }

    public function actionCreate ($id)
    {
        if(!($formcategory = FormCategory::findOne($id))){
            return $this->redirect('/form/index');
        }

        $model = new $this->modelClass;
        $dataForm = $this->generateForm($formcategory->fields);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $formcategory->primaryKey;
                $model->status = $formcategory->steps;
                $this->parseData($model);

                if ($model->save()) {
                    Yii::$app->session->setFlash('crudMessage', '表单添加成功.');
                    return $this->redirect($this->getRedirectPage('create', $model));

                } else {
                    Yii::$app->session->setFlash('crudMessage', '表单添加错误.');
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->renderIsAjax('create', compact('model', 'dataForm', 'formcategory'));
        }

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


    public function actionOn($id)
    {
        return $this->changeStatus($id, Form::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Form::STATUS_OFF);
    }


    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
                case 'delete':
                        return ['index'];
                        break;
                case 'update':
                    return ['index', 'id' => $model->id];
                        break;
                case 'create':
                    return ['index', 'id' => $model->category_id];
                        break;
                default:
                        return ['index'];
        }
    }

}
