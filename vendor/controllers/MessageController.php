<?php
namespace frontend\controllers;

use Yii;
use frontend\services\Func;
use frontend\components\FrontController;
use yii\helpers;
use yii\data\Pagination;

class MessageController extends FrontController{

    public function init(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $this->layout = "message";
    }

    //系统消息已读
    public function actionRead(){
        $MC = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>1])->count();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$MC]);
        $messages = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>1])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('read',['messages'=>$messages,'pagination'=>$pagination]);
    }

    //系统消息未读
    public function actionUnread(){
        $MC = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>0])->count();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$MC]);
        $messages = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>0])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('unread',['messages'=>$messages,'pagination'=>$pagination]);
    }

    public function actionIsread(){
        if(Yii::$app->request->post('id')){
            $me  = \common\models\Message::findOne(['id'=>Yii::$app->request->post('id')]);
            $me->isRead = 1;
            $me->save();
            echo 1;
        }else{
            echo 2;
        }
    }
}