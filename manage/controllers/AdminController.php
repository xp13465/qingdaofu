<?php
namespace manage\controllers;

use Yii;
use manage\components\BackController;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use app\models\Department;
use app\models\Personnel;
use manage\services\Func;

/**
 * Site controller
 */
class AdminController extends BackController
{
    public $modelClass = '\manage\models\Admin';
    public $modelSearchClass = '\manage\models\AdminSearch';

    /**
     * @inheritdoc
     */
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

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel = $this->modelSearchClass ? new $this->modelSearchClass : null;

        $role = (new \yii\db\Query())->select('item_name')->from('zcb_auth_assignment')->where('user_id=:id', [':id'=>5])->createCommand();
        //echo $role->sql;
        //$rows = $role->queryAll();
        //print_r($rows);

        //echo Yii::$app->user->identity->auth_key;
        $query = (new \yii\db\Query())->select(['name', 'description'])->from('zcb_auth_item')->where(['type' => 1])->createCommand();
        //echo $query->sql;

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
        $model = new $this->modelClass;
        $model->scenario = 'create';
		$Department = Department::find()->where(['status'=>'1','validflag'=>'1'])->select("id,pid,name")->asArray()->all();
		//$data = $model->queue($Department);
		$data = Func::queue($Department);
		foreach($data as $value){
			if($value['pid'] !=='0'){
				$value['name'] = $value['lev'].'┖┄'.$value['name'];
			}
			$html[] = $value;
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '后台用户添加成功.');
                return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model','html'));
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$Department = Department::find()->where(['status'=>'1','validflag'=>'1'])->select("id,pid,name")->asArray()->all();
		//$data = $model->queue($Department);
		$data = Func::queue($Department);
		foreach($data as $value){
			if($value['pid'] !=='0'){
				$value['name'] = $value['lev'].'┖┄'.$value['name'];
			}
			$html[] = $value;
		}
		if(Yii::$app->request->post()){
			$post = Yii::$app->request->post();
			$post['Admin']['headimg'] = isset($post['Admin']['headimg'])&&$post['Admin']['headimg']?$post['Admin']['headimg']:0;
			if ($model->load($post) AND $model->save()) {
					Yii::$app->session->setFlash('crudMessage', '后台管理员用户更新成功');
					return $this->redirect($this->getRedirectPage('update', $model));
			}
		}

        return $this->renderIsAjax('update', compact('model','html'));
    }


    public function actionProfile(){
        $data = \manage\models\Admin::findOne(['id'=>Yii::$app->user->getId()]);

        return $this->render("profile",['data'=>$data]);
    }





    public function actionList(){

        $MC = \manage\models\Admin::find()->where(true)->count();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$MC]);
        $data = \manage\models\Admin::find()->where(true)->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('list',['data'=>$data,'pagination'=>$pagination]);
    }

    public function actionAdduser(){

        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post('username');
            $password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password_hash'));
            $admin = new \manage\models\Admin;
            $admin->password_hash = $password_hash;
            $admin->auth_key = Yii::$app->security->generateRandomString();;
            $admin->username = $username;
            $admin->status = 10;
            $admin->created_at = time();
            $admin->updated_at = time();
            if($admin->save()){
                return $this->redirect("/admin/list");
            }
        }

        return $this->render("adduser");
    }
	
	
	//员工信息查询
	public function actionStaff(){
		$postId = Yii::$app->request->post('post_id');
		if(isset($postId)&&$postId){
			$data = Personnel::find()->where(['post_id'=>$postId,'validflag'=>'1'])->select('id,name')->asArray()->all();
			if($data){
				foreach($data as $value){
					$data[]= "<option value='".$value['id']."'>".$value['name']."</option>";
				}
				return $this->success('',['html'=>$data]);die;
			}
		}else{
			return $this->success('ParamsCheck');die;
		}
		
	}
}
