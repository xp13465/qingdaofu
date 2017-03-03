<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class EstateController extends \wx\components\FrontController
{
    //房产评估
    public function actionList(){
        $this->title = "清道夫债管家-房产评估结果";
        $this->keywords = "清道夫债管家-房产评估结果";
        $this->description = "清道夫债管家-房产评估结果";
		$token = Yii::$app->session->get('user_token');
	    $page = Yii::$app->request->get('page',1);
		$limit = Yii::$app->request->get('limit',10);
        $str = Func::CurlPost(yii\helpers\Url::toRoute('/wap/producedata/estatelist'),['token'=>$token,'page'=>$page,'limit'=>$limit]);
        $arr = $this->json2array($str);		
        if($arr['code'] == '0000'){
            return $this->render('index',['data'=>$arr['result']['data']]);
        }else{
            $this->notFound();
        } 
	}
    //房产评估
    public function actionIndex(){
        $this->title = "清道夫债管家-房产评估";
        $this->keywords = "清道夫债管家-房产评估";
        $this->description = "清道夫债管家-房产评估";
        $citySelect = [
			"浦东"=>"浦东新区",
			"黄浦"=>"黄浦区",
			"静安"=>"静安区",
			"徐汇"=>"徐汇区",
			"长宁"=>"长宁区",
			"杨浦"=>"杨浦区",
			"虹口"=>"虹口区",
			"普陀"=>"普陀区",
			"宝山"=>"宝山区",
			"嘉定"=>"嘉定区",
			"闵行"=>"闵行区",
			"松江"=>"松江区",
			"青浦"=>"青浦区",
			"奉贤"=>"奉贤区",
			"金山"=>"金山区",
			"崇明"=>"崇明县",
			"闸北"=>"闸北区",
			"卢湾"=>"卢湾区",
			"南汇"=>"南汇区",
			
			
			
			
		];
        return $this->render('assessment',['citySelect'=>$citySelect]);
    }
    
    //房产评估动作
    public function actionPinggu(){
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $post['token'] = Yii::$app->session->get('user_token'); 
            $pinggu = Func::CurlPost(Yii\helpers\Url::toRoute('/wap/producedata/estate'),$post);
            $ping = yii\helpers\Json::decode($pinggu);
            if($ping['code'] == '0000'){
                //return $this->redirect(\yii\helpers\Url::toRoute(''));
                echo json_encode(['code'=>$ping['code'],'result'=>$ping['result']['data']['id']]);die;
            }else{
                echo json_encode(['code'=>$ping['code'],'msg'=>$ping['msg']]);die;
            }
        }
    }
    //房产评估结果
    public function actionResult($id){
          $this->title = "清道夫债管家-房产评估";
          $this->keywords = "清道夫债管家-房产评估";
          $this->description = "清道夫债管家-房产评估";
          $result = \frontend\models\HousingPrice::findOne(['id' => $id]);
          return $this->render('result',['result'=>$result]); 
    }
    
}