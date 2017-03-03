<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider; 
use frontend\services\Func; 
use frontend\components\FrontController;

class ServicesController extends FrontController
{
	public $footer = "footer";
	public $layout = 'main';
	public function init(){
		$action = explode("/",$this->module->requestedRoute);
		$action = $action[count($action)-1];
		// echo $action;exit;
        if (!in_array($action,['index'])&&\Yii::$app->user->isGuest) {
            $this->redirect('/site/login/')->send();
			exit;
        }
		
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' =>['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    
	 

    /**
     * 获取所需城市
     * @param fatherID为空时获取全部
     * @return json
     * **/
    public function actionCity(){
		if(isset($_POST['depdrop_parents'])&&$_POST['depdrop_parents']){
				$fatherID = $_POST['depdrop_parents'][0];
				$selected = $fatherID=="310000"?"310100":"";
				// $where  = ' 1 ';
				// if($fatherID)$where = "fatherID = '{$fatherID}'";
				if(!$fatherID)echo Json::encode(['output'=>'', 'selected'=>'']);
				$city = \common\models\City::find()->select("cityID,city,fatherID")->where(["fatherID"=>$fatherID])->all();
				$desc = [];

				foreach($city as $k=>$c){
					$desc[$k]["id"] = $c->cityID;
					$desc[$k]["name"] = $c->city;
				}
			echo Json::encode(['output'=>$desc, 'selected'=>$selected]);
		}
    }




    /**
     * 获取所需区域
     * @return json
     * **/
    public function actionArea(){
		if(isset($_POST['depdrop_parents'])&&$_POST['depdrop_parents']){
			$fatherID = $_POST['depdrop_parents'][0];
			if(!$fatherID)echo Json::encode(['output'=>'', 'selected'=>'']);
			// $where  = ' 1 ';

			$area =  \common\models\Area::find()->select("areaID,area,fatherID")->where(["fatherID"=>$fatherID])->all();
			$desc = [];

			foreach($area as $k=>$a){
				$desc[$k]["id"] = $a->areaID;
				$desc[$k]["name"] = $a->area;
			}
			echo Json::encode(['output'=>$desc, 'selected'=>'']);
		}
    }



	public function actionIndex()
    {
       return $this->renderIsAjax('index');
    }
    // public function actionMy()
    // {
        // $model = new $this->modelClass;
        // $data = new ActiveDataProvider([
            // 'query' => Policy::find()->where(['and',['created_by' => Yii::$app->user->identity->id], ['>', 'shenhe_status', 0]])->sortDate(),
            // 'pagination' => [
                // 'pageSize' => 5,
            // ],
        // ]);

        // return $this->renderIsAjax('index', compact('data', 'model'));
    // }

    public function actionAgreement()
    {
		$this->footer = "";
        $model = new \app\models\ServicesAgreement;
		$post=Yii::$app->request->post();
		$modelQuery = $model::find();
		if ($post&&isset($post['ServicesAgreement'])) {
			$status = $modelQuery->change($post['ServicesAgreement']);
			switch($status){
				case 'ok':
					$this->success("预约成功" ,['id'=>$modelQuery->id]);
					break;
				default:
					$this->errorMsg($status,$modelQuery->formatErrors());
					break ;
			}
		}else{
            $model->contacts=$this->user->identity->realname?:'';
            $model->tel=$this->user->identity->mobile;
            return $this->render('agreement', compact('model'));
        }
    }
	
	public function actionReservation()
    {
		$this->footer = "";
		$province = \common\models\Province::find()->select("provinceID,province")->where("1")->all();
		$provinceData = yii\helpers\ArrayHelper::map($province, 'provinceID', 'province');
		
		
        $model = new \app\models\ServicesReservation;
		$post=Yii::$app->request->post();
		$modelQuery = $model::find();
		if ($post&&isset($post['ServicesReservation'])) {
			$status = $modelQuery->change($post['ServicesReservation']);
			switch($status){
				case 'ok':
					$this->success("预约成功" ,['id'=>$modelQuery->id]);
					break;
				default:
					$this->errorMsg($status,$modelQuery->formatErrors());
					break ;
			}
		}else{
            $model->contacts=$this->user->identity->realname?:'';
            $model->tel=$this->user->identity->mobile;
            return $this->render('reservation', compact('model','provinceData'));
        }
    }
	public function actionInstrument()
    {
		$this->footer = "";
		$province = \common\models\Province::find()->select("provinceID,province")->where("1")->all();
		$provinceData = yii\helpers\ArrayHelper::map($province, 'provinceID', 'province');
        $model = new \app\models\ServicesInstrument;
		$post=Yii::$app->request->post();
		$modelQuery = $model::find();
		if ($post&&isset($post['ServicesInstrument'])) {
			$status = $modelQuery->change($post['ServicesInstrument']);
			switch($status){
				case 'ok':
					$this->success("预约成功" ,['id'=>$modelQuery->id]);
					break;
				default:
					$this->errorMsg($status,$modelQuery->formatErrors());
					break ;
			}
		}else{
            $model->contacts=$this->user->identity->realname?:'';
            $model->tel=$this->user->identity->mobile;
            return $this->render('instrument', compact('model','provinceData'));
        }
    }
  

}