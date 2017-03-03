<?php
namespace backend\controllers;

use Yii;
use backend\components\BackController;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;

/**
 * Site controller
 */
class AdminController extends BackController
{
    public $modelClass = 'backend\models\Admin';
    public $modelSearchClass = 'backend\models\AdminSearch';

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '后台用户添加成功.');
                return $this->redirect($this->getRedirectPage('create', $model));
        }

        return $this->renderIsAjax('create', compact('model'));
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
                Yii::$app->session->setFlash('crudMessage', '后台管理员用户更新成功');
                return $this->redirect($this->getRedirectPage('update', $model));
        }

        return $this->renderIsAjax('update', compact('model'));
    }


    public function actionProfile(){
        $data = \backend\models\Admin::findOne(['id'=>Yii::$app->user->getId()]);

        return $this->render("profile",['data'=>$data]);
    }





    public function actionList(){

        $MC = \backend\models\Admin::find()->where(true)->count();
        $pagination = new Pagination(['defaultPageSize'=>10,'totalCount'=>$MC]);
        $data = \backend\models\Admin::find()->where(true)->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('list',['data'=>$data,'pagination'=>$pagination]);
    }

    public function actionAdduser(){

        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post('username');
            $password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password_hash'));
            $admin = new \backend\models\Admin;
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
	
	public function actionDelete($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->session->setFlash('crudMessage', '该帐号已删除');
        return $this->redirect($this->getRedirectPage('delete', $model));
    }
}
