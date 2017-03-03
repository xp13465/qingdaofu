<?php
namespace frontend\controllers;

use Yii;
use frontend\services\Func;
use frontend\components\FrontController;
use yii\helpers;
use yii\data\Pagination;

class MessageController extends FrontController{
	public $defaultAction ="group-list";
    public function init(){
        if (\Yii::$app->user->isGuest) {
            $this->redirect('/site/login/')->send();
			exit;
        }

        $this->layout = "message";
    }
	
	public function actionGroupList(){
		$this->layout = "main";
		// $MessageQuery = \app\models\Message::find();
		// $MessageQuery->addMessage(100,["code"=>"BX22222222"],1,75,40,0);
		
		$uid=''; 
		$MessageQuery = \app\models\Message::find();
		$params = Yii::$app->request->get();
		$provider = $MessageQuery->groupList($params,$uid);
		
		$SystemMessageQuery = \app\models\Message::find();
		$systemCount = $SystemMessageQuery->systemList([],$uid,true);
		
		$data = $provider->getModels();
		$data = $MessageQuery->filterAll($data);
		return $this->render("grouplist",
			[
			"data"=>$data,
			"systemCount"=>$systemCount,
			"provider"=>$provider,
			"totalCount"=>$provider->getTotalCount(),
			"curCount"=>$provider->getCount(),
			"pageSize"=>$provider->pagination->getPageSize(),
			"curpage"=>$provider->pagination->getPage()+1
			]
		);
	}
	public function actionSystemList(){
		$this->layout = "main";
		$uid=''; 
		$MessageQuery = \app\models\Message::find();
		$params = Yii::$app->request->get();
		$provider = $MessageQuery->systemList($params,$uid,false,true);
		$data = $provider->getModels();
		$data = $MessageQuery->filterAll($data);
		$SystemMessageQuery = \app\models\Message::find();
		$systemCount = $SystemMessageQuery->systemList([],$uid,true);
		return $this->render("systemlist",
			[
			"data"=>$data,
			"systemCount"=>$systemCount,
			"provider"=>$provider,
			"totalCount"=>$provider->getTotalCount(),
			"curCount"=>$provider->getCount(),
			"pageSize"=>$provider->pagination->getPageSize(),
			"curpage"=>$provider->pagination->getPage()+1
			]
		);
		
	}

    //系统消息已读
    public function actionList(){
        $MC = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId()])->andFilterWhere(['!=','type','24'])->count();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$MC]);
        $messages = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId()])->andFilterWhere(['!=','type','24'])->offset($pagination->offset)->limit($pagination->limit)->orderBy("isRead,create_time desc")->all();
        return $this->render('list',['messages'=>$messages,'pagination'=>$pagination]);
    }


    //系统消息已读
    public function actionRead(){
        return $this->redirect(helpers\Url::to("/message/list"));
        $MC = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>1])->andFilterWhere(['!=','type','24'])->count();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$MC]);
        $messages = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>1])->andFilterWhere(['!=','type','24'])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('read',['messages'=>$messages,'pagination'=>$pagination]);
    }

    //系统消息未读
    public function actionUnread(){
        return $this->redirect(helpers\Url::to("/message/list"));
        $MC = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>0])->andFilterWhere(['!=','type','24'])->count();
        $pagination = new Pagination(['defaultPageSize'=>5,'totalCount'=>$MC]);
        $messages = \common\models\Message::find()->where(['belonguid'=>\Yii::$app->user->getId(),'isRead'=>0])->andFilterWhere(['!=','type','24'])->offset($pagination->offset)->limit($pagination->limit)->all();
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