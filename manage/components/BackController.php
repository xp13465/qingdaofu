<?php
namespace manage\components;

use Yii;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class BackController extends \yii\web\Controller
{

    public $modelClass;
    public $modelSearchClass;
    public $error = null;

    public $title = '';
    public $header = '';
    public $keywords = '';
    public $description = '';
    public $enableCsrfValidation  = false;

    public function init(){
        parent::init();

        if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
            Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'zh-cn',
                'basePath' => '@manage/messages'
            ];
        }
    }

    public function formatResponse($success = '', $back = true)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($this->error){
                return ['result' => 'error', 'error' => $this->error];
            } else {
                $response = ['result' => 'success'];
                if($success) {
                    if(is_array($success)){
                        $response = array_merge(['result' => 'success'], $success);
                    } else {
                        $response = array_merge(['result' => 'success'], ['message' => $success]);
                    }
                }
                return $response;
            }
        }
        else{
            if($this->error){
                $this->flash('error', $this->error);
            } else {
                if(is_array($success) && isset($success['message'])){
                    $this->flash('success', $success['message']);
                }
                elseif(is_string($success)){
                    $this->flash('success', $success);
                }
            }
            return $back ? $this->back() : $this->refresh();
        }
    }

    public function flash($type, $message)
    {
        Yii::$app->getSession()->setFlash($type=='error'?'danger':$type, $message);
    }

    public function back()
    {
        return $this->redirect(Yii::$app->request->referrer);
    }

    /*public function beforeAction($action)
    {
        if(!parent::beforeAction($action))
            return false;

        if(Yii::$app->user->isGuest){
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            Yii::$app->getResponse()->redirect(['/login'])->send();
            return false;
        }else{
            if($action->id === 'index'){
                $this->setReturnUrl();
            }
            return true;
        }

        }*/

    public function setReturnUrl($url = null)
    {
        Yii::$app->getSession()->set($this->module->id.'_return', $url ? Url::to($url) : Url::current());
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        
		$query = $modelClass::find();
		$query->alias('admin');
		$query->joinWith(['files']);
		$query->andFilterWhere(['admin.id'=>$id]);
		
        //$model = $modelClass::findOne($id);

        if (($model = $query->one()) !== null) {
                return $model;
        } else {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }

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
                Yii::$app->session->setFlash('crudMessage', 'Your item has been created.');
                return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model'));
    }


    public function actionView($id)
    {
        return $this->renderIsAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionUpdate($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
                Yii::$app->session->setFlash('crudMessage', 'Your item has been updated.');
                return $this->redirect($this->getRedirectPage('update', $model));
        }

        return $this->renderIsAjax('update', compact('model'));
    }


    public function actionDelete($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->session->setFlash('crudMessage', 'Your item has been deleted.');
        return $this->redirect($this->getRedirectPage('delete', $model));
    }

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
                case 'delete':
                        return ['index'];
                        break;
                case 'update':
                        return ['view', 'id' => $model->id];
                        break;
                case 'create':
                        return ['view', 'id' => $model->id];
                        break;
                default:
                        return ['index'];
        }
    }



    protected function renderIsAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax($view, $params);
        } else {
                return $this->render($view, $params);
        }
    }
	
	
	/**
	*   正确信息返回
	*	param $msg String 提示文字
	*	param $data Array 返回数据
	*	param $type String  返回类型
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function success($msg='',$data=array(),$type='json'){
		$return = ['code'=>'0000','msg'=>$msg,'data'=>$data];
		switch($type){
			case 'json':
				$Msg = \yii\helpers\Json::encode($return);
				break;
			default:
				$Msg = \yii\helpers\Json::encode($return);
				break;
		}
		exit($Msg);
	}
	
	/**
	*  错误信息返回
	*	param $label String 错误信息代码
	*	param $type String 返回类型 可拓展
	*	return $Msg JsonString 默认为JSON格式
	*/
	protected function errorMsg($label,$msg='',$type='json'){
		$errorMsg = Yii::$app->params['errorMsg'];
		$jsonData = isset($errorMsg[$label])?$errorMsg[$label]:[];
		$jsonData['msg'] = $msg?$msg:$jsonData['msg'];
		switch($type){
			case 'json':
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
			default:
				$Msg = \yii\helpers\Json::encode($jsonData);
				break;
		}
		exit($Msg);
	}



}
