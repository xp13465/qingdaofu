<?php

namespace frontend\controllers;
use Yii;
use frontend\components\FrontController;
use frontend\services\Func;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class FangjiaController extends FrontController
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	public $enableCsrfValidation = false;
	
    public function actionIndex()
    {
        $this->layout ='info';
		$keywords= Yii::$app->request->get('keywords');
		$model = new \frontend\models\HousingPrice;
		$query  = $model::find()->where(['userid'=>Yii::$app->user->identity->id,"code"=>"200"]);
		if($keywords)$query->andWhere(["like",'concat(city,"市",district,"区",address,buildingNumber,"号",unitNumber,"室")', str_replace("浦东新区",'浦东区',$keywords)]);
		$data = new ActiveDataProvider([
				'query' => $query,
                'sort' => [
					'defaultOrder' => [
						'create_time' => SORT_DESC,            
					]
				],
				'pagination' => [
					'pageSize' => 10,
				],	
			
		]);
		
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
		];
        return $this->renderIsAjax('index', compact('model','data','citySelect'));
    }
	
	public function actionTotalprice()
    {	
		
		$model = new \frontend\models\HousingPrice;
		$post = Yii::$app->request->post();
		$result = $model->getTotalPrice($post);
		switch($result['errorCode']){
			case 'ok':
				$this->success("",$result['data']);
				break;
			case 'UserLogin':
				$this->errorMsg("UserLogin",'');
				break;
			case 'TotalpriceCheck':
				$this->errorMsg("TotalpriceCheck",$model->formatErrors());
				break;
			case 'TotalpriceSave':
				$this->errorMsg("TotalpriceSave",$model->formatErrors());
				break;
			case 'TotalpriceAPI':
				$this->errorMsg("TotalpriceAPI",'');
				break;
			case 'FangjiaToken':
				$this->errorMsg("TotalpriceAPI",'');
				break;
			case 'TotalpriceLimit':
				$this->errorMsg("TotalpriceLimit",'每日最多评估10次.');
				break;
		}
		exit;
    }
	
}
