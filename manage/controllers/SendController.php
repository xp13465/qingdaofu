<?php
namespace manage\controllers;

use Yii;
use manage\components\BackController;
use yii\widgets\ActiveForm;
use common\models\News;
use common\models\ClassicCase;
use yii\db\ActiveRecord;
use yii\helpers;
use common\components;
use yii\data\Pagination;
use yii\filters\VerbFilter;
/**
 * Site controller
 */
class SendController extends BackController
{


    public $layout = 'main';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionMessages(){
        if(Yii::$app->request->isPost){
            $mobile = Yii::$app->request->post('mobile');
            $messages = Yii::$app->request->post('messages');

            if($mobile&&$messages){
                if(Yii::$app->smser->sendMsgByMobile($mobile,$messages)){echo json_encode(['code'=>'0000','msg'=>'发送短信成功']);die;}
                else{echo json_encode(['code'=>'1001','msg'=>'发送短信失败']);die;}
            }
        }
       return  $this->render('messages');
    }
}
