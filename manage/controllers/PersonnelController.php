<?php

namespace manage\controllers;

use Yii;
use app\models\Personnel;
use app\models\PersonnelSearch;
use app\models\DepartmentSearch;
use app\models\PostSearch;
use app\models\OrganizationSearch;
use manage\components\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Organization;
use app\models\Department;
use app\models\Post;
use manage\services\Func;

/**
 * PersonnelController implements the CRUD actions for Personnel model.
 */
class PersonnelController extends BackController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Personnel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonnelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	

    /**
     * Displays a single Personnel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Personnel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Personnel();
		//机构
        $organization = Organization::find()->where(['status'=>'1','validflag'=>'1'])->select('organization_id,organization_name')->asArray()->all();
		if (Yii::$app->request->post()){
			$data = Yii::$app->request->post();
			// echo "<pre>";
			// print_r($data);die;
			$model->load($data);
		    $model->department_pid = isset($data['Personnel']['department_id'])&&$data['Personnel']['department_id']?$data['Personnel']['department_id']:'0';
			$model->department_id = isset($data['Personnel']['department_pid'])&&$data['Personnel']['department_pid']?$data['Personnel']['department_pid']:'0';
			$model->headimg = isset($data['Personnel']['headimg'])&&$data['Personnel']['headimg']?$data['Personnel']['headimg']:'0';
			$model->created_at = Yii::$app->user->getId();
			$model->created_by = time();
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('create',['model' => $model,'organization' => $organization]);
        }
    }

    /**
     * Updates an existing Personnel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $organization = Organization::find()->where(['status'=>'1','validflag'=>'1'])->select('organization_id,organization_name')->asArray()->all();
		if (Yii::$app->request->post()){
			$data = Yii::$app->request->post();
			$model->load($data);
			$model->department_pid = isset($data['Personnel']['department_id'])&&$data['Personnel']['department_id']?$data['Personnel']['department_id']:'0';
			$model->department_id = isset($data['Personnel']['department_pid'])&&$data['Personnel']['department_pid']?$data['Personnel']['department_pid']:'0';
			$model->modifyd_by = Yii::$app->user->getId();
			$model->updated_at = time();
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
				'organization'=>$organization,
            ]);
        }
    }

    /**
     * Deletes an existing Personnel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id='')
    {
		$id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type');
		if($type == 1){
			$status = $this->findModel($id)->updateAll(['validflag'=>'0','updated_at'=>time()],['validflag'=>'1','id'=>$id]);
			if($status){
			 $data = "删除成功" ;
			}
		}else{
			$status = $this->findModel($id)->updateAll(['validflag'=>'1'],['validflag'=>'0','id'=>$id]);
			$data = "恢复成功";
		}
		if($status){
			 return $this->success($data);
		}
       
        //return $this->redirect(['index']);
    }

    /**
     * Finds the Personnel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Personnel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		 $query = Personnel::find();
		 $query->alias('personnel');
		 $query->joinWith(['files','organization','departmentPid','departmentId','post','admin','parent']);
		 $query->andFilterWhere(['personnel.id'=>$id]);
        if (($model = $query->one()) !== null) {
			// echo "<pre>";
			// print_r($model);die;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionHierarchy($organization_id=''){
		$organization_id = Yii::$app->request->post('organization_id');
		$name = Yii::$app->request->post('name');
		$types = Yii::$app->request->post('types');
		$department_id = Yii::$app->request->post('department_id');
		$searchModel = new PersonnelSearch();
		switch($name){
			case 'Department':
				$html = $searchModel->department($organization_id,$department_id,$types);
				return $html;die;
			break;
			case 'Post':
				$html = $searchModel->post($department_id);
				return $this->success('',['html'=>$html]);die;
			break;
		}
	}
	
	
	//机构列表
    public function actionOrganizationIndex() 
    { 
        $searchModel = new OrganizationSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); 

        return $this->render('organizationIndex', [ 
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider, 
        ]); 
    } 
	
    
	//机构数据添加
	 public function actionOrganizationCreate() 
    { 
        $model = new Organization(); 
        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
            return $this->redirect(['organization-view', 'id' => $model->organization_id]); 
        } else { 
            return $this->render('organizationCreate', [ 
                'model' => $model, 
            ]); 
        }		
    } 
	//机构数据修改
    public function actionOrganizationUpdate($id) 
    { 
        $model = $this->OrganizationModel($id); 
        if (Yii::$app->request->post()) {
			$data = Yii::$app->request->post();
			$model->modifyd_by = Yii::$app->user->getId();
		    $model->load($data);
		    if($model->save()){
				return $this->redirect(['organization-view', 'id' => $model->organization_id]); 
			}
        }else {
            return $this->render('organizationCreate', [ 
                'model' => $model, 
            ]); 
        } 
    } 
	
	//机构视图
	public function actionOrganizationView($id) 
    { 
        return $this->render('organizationView', [ 
            'model' => $this->OrganizationModel($id), 
        ]); 
    } 
	
	//机构删除
	public function actionOrganizationDelete($id='') 
    { 
        $id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type');
		if($type == 1){
			$status = $this->OrganizationModel($id)->updateAll(['validflag'=>'0','updated_at'=>time()],['validflag'=>'1','organization_id'=>$id]);
			if($status){
			 $data = "删除成功" ;
			}
		}else{
			$status = $this->OrganizationModel($id)->updateAll(['validflag'=>'1'],['validflag'=>'0','organization_id'=>$id]);
			$data = "恢复成功";
		}
		if($status){
			 return $this->success($data);
		}
    } 
	
	
    //机构数据查询	
	protected function OrganizationModel($id)
    { 
		 $query = Organization::find();
		 $query->alias('organization');
		 $query->joinWith(['admin']);
		 $query->andFilterWhere(['organization.organization_id'=>$id]);
        if (($model = $query->one()) !== null) {
			// echo "<pre>";
			// print_r($model);die;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    } 
	
	
	
	
	//部门列表
    public function actionDepartmentIndex() 
    { 
        $searchModel = new DepartmentSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('departmentIndex', [ 
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider, 
        ]); 
    } 
	
    
	//部门数据添加
	 public function actionDepartmentCreate() 
    { 
        $model = new Department();
		//机构
		$organization = Organization::find()->where(['status'=>'1','validflag'=>'1'])->select('organization_id,organization_name')->asArray()->all();
		//部门
        $department = Department::find()->where(['status'=>'1','validflag'=>'1','pid'=>'0'])->select('id,name')->asArray()->all();		
        
		if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
			$model->load($data);
			if($data['Department']['second'] == '0'){
				$model->pid = '0';
			}
			if($model->save()){
				 return $this->redirect(['department-view', 'id' => $model->id]); 
			}
        } else { 
            return $this->render('departmentCreate', [ 
                'model' => $model,
				'department' => $department,
				'organization' => $organization,				
            ]); 
        }		
    } 
	//部门数据修改
    public function actionDepartmentUpdate($id) 
    { 
        $model = $this->DepartmentModel($id);
		$model->second = isset($model->pid)&&$model->pid=='0'?'0':'1';
		// echo "<pre>";
		// print_r($model);die;

		//机构
		$organization = Organization::find()->where(['status'=>'1','validflag'=>'1'])->select('organization_id,organization_name')->asArray()->all();
		//部门
        $department = Department::find()->where(['status'=>'1','validflag'=>'1','pid'=>'0'])->select('id,name')->asArray()->all();	
        if (Yii::$app->request->post()) {
			$data = Yii::$app->request->post();
		    $model->load($data);
			$model->modifyd_by = Yii::$app->user->getId();
			if($data['Department']['second'] == '0'){
				$model->pid = '0';
			}
		    if($model->save()){
				return $this->redirect(['department-view', 'id' => $model->id]); 
			}
        }else {
            return $this->render('departmentCreate', [ 
                'model' => $model, 
				'department' => $department,
				'organization' => $organization,
            ]); 
        } 
    } 
	
	//部门视图
	public function actionDepartmentView($id) 
    { 
        return $this->render('DepartmentView', [ 
            'model' => $this->DepartmentModel($id), 
        ]); 
    } 
	
	//部门删除
	public function actionDepartmentDelete($id='') 
    { 
        $id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type');
		if($type == 1){
			$status = $this->DepartmentModel($id)->updateAll(['validflag'=>'0','updated_at'=>time()],['validflag'=>'1','id'=>$id]);
			if($status){
			 $data = "删除成功" ;
			}
		}else{
			$status = $this->DepartmentModel($id)->updateAll(['validflag'=>'1'],['validflag'=>'0','id'=>$id]);
			$data = "恢复成功";
		}
		if($status){
			 return $this->success($data);
		}
    } 
	
	
    //部门数据查询	
	protected function DepartmentModel($id)
    { 
		 $query = Department::find();
		 $query->alias('department');
		 $query->joinWith(['organization','admin']);
		 $query->andFilterWhere(['department.id'=>$id]);
        if (($model = $query->one()) !== null) {
			if($model->pid!=='0'){
				$data = Department::findOne(['id'=>$model->pid]);
				$model->secondName = $data['name'];
			}
			// echo "<pre>";
			// print_r($model);die;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    } 
	
	
	
	
	
	//岗位列表
    public function actionPostIndex() 
    { 
        $searchModel = new PostSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('postIndex', [ 
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider, 
        ]); 
    } 
	
    
	//岗位数据添加
	 public function actionPostCreate() 
    { 
        $model = new Post();
		//部门
        $department = Department::find()->where(['status'=>'1','validflag'=>'1'])->select('id,name,pid')->asArray()->all();		
        
		if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
			$model->load($data);
			if($data['Post']['type'] == '0'){
				$model->type = '0';
				$model->department_id = '0';
			}
			if($model->save()){
				 return $this->redirect(['post-view', 'id' => $model->id]); 
			}
        } else { 
		    $data = Func::queue($department);
			foreach($data as $value){
				if($value['pid'] !=='0'){
					$value['name'] = $value['lev'].'┖┄'.$value['name'];
				}
				$html[] = $value;
			}
            return $this->render('postCreate', [ 
                'model' => $model,
				'html' => $html,				
            ]); 
        }		
    } 
	//岗位数据修改
    public function actionPostUpdate($id) 
    { 
        $model = $this->PostModel($id);
		//部门
        $department = Department::find()->where(['status'=>'1','validflag'=>'1'])->select('id,name,pid')->asArray()->all();	
        if (Yii::$app->request->post()) {
			$data = Yii::$app->request->post();
		    $model->load($data);
			$model->modifyd_by = Yii::$app->user->getId();
			if($data['Post']['type'] == '0'){
				$model->type = '0';
				$model->department_id = '0';
			}
		    if($model->save()){
				return $this->redirect(['post-view', 'id' => $model->id]); 
			}
        }else {
			$data = Func::queue($department);
			foreach($data as $value){
				if($value['pid'] !=='0'){
					$value['name'] = $value['lev'].'┖┄'.$value['name'];
				}
				$html[] = $value;
			}
            return $this->render('postCreate', [ 
                'model' => $model, 
				'html' => $html,
            ]); 
        } 
    } 
	
	//岗位视图
	public function actionPostView($id) 
    { 
        return $this->render('postView', [ 
            'model' => $this->PostModel($id), 
        ]); 
    } 
	
	//岗位删除
	public function actionPostDelete($id='') 
    { 
        $id = Yii::$app->request->post('id');
		$type = Yii::$app->request->post('type');
		if($type == 1){
			$status = $this->PostModel($id)->updateAll(['validflag'=>'0','updated_at'=>time()],['validflag'=>'1','id'=>$id]);
			if($status){
			 $data = "删除成功" ;
			}
		}else{
			$status = $this->PostModel($id)->updateAll(['validflag'=>'1'],['validflag'=>'0','id'=>$id]);
			$data = "恢复成功";
		}
		if($status){
			 return $this->success($data);
		}
    } 
	
	
    //岗位数据查询	
	protected function PostModel($id)
    { 
		 $query = Post::find();
		 $query->alias('post');
		 $query->joinWith(['department','admin']);
		 $query->andFilterWhere(['post.id'=>$id]);
        if (($model = $query->one()) !== null) {
			// if($model->pid!=='0'){
				// $data = Department::findOne(['id'=>$model->pid]);
				// $model->secondName = $data['name'];
			// }
			// echo "<pre>";
			// print_r($model);die;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    } 
	public function actionAutocomplete($term){
		$return = (new Personnel)->autoComplete($term);
		 
		echo json_encode($return);
		
	}
 
}
