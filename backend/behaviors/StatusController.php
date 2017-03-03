<?php
namespace backend\behaviors;

use Yii;


class StatusController extends \yii\base\Behavior
{
    public $model;
    public $error = null;

    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        }
        else{
            $this->error = Yii::t('zcb', 'Not found');
        }

        return $this->owner->formatResponse(Yii::t('zcb', 'Status successfully changed'));
    }
}
