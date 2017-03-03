<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class CommonController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public $title = '';
    public $keywords = '';
    public $description = '';
    public function actionUploadimage(){
        $this->layout = false;
        return $this->render('uploadimage');
    }
    public function actionUploadswx(){
        $this->layout = false;
        return $this->render('uploadswx');
    }
    public function actionUploadimagegallery(){
        $this->layout = false;
        return $this->render('uploadimagegallery');
    }

    public function actionViewimages(){
        $this->layout = false;
        $name = Yii::$app->request->get('name');
        $typeName = Yii::$app->request->get('typeName');
        return $this->render('viewimages',['name'=>$name,'typeName'=>$typeName]);
    }

    public function actionSearchcommunity(){
        $this->layout = 'main';
        return $this->render('searchcommunity');
    }

    public function actionGetsearch(){
        if(Yii::$app->request->isPost){
            $community =  '';
            if(Yii::$app->request->post('name')) {
                $community = Yii::$app->db->createCommand("select * from zcb_community where name like '%" . Yii::$app->request->post('name') . "%'")->queryAll();
            }
            if(empty($community)){
                echo yii\helpers\Json::encode(['code'=>'1001','result'=>Yii::$app->request->post('name')]);die;
            }else{
                echo yii\helpers\Json::encode(['code'=>'0000','result'=>$community]);die;
            }
        }
    }

    public function actionGallery(){
        if(Yii::$app->request->isPost){
            $str  = Yii::$app->request->post('str');

            return $this->render('gallery',['str'=>$str]);
        }
    }

    public function actionGetbrand(){
        if(Yii::$app->request->isPost){
            $json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/common/brand']));
            if($json){
                echo yii\helpers\Json::encode(['code'=>'0000','result'=>yii\helpers\Json::decode($json)]);die;
            }else{
                echo yii\helpers\Json::encode(['code'=>'1001','msg'=>'获取品牌失败']);die;
            }
        }
    }

    public function actionGetbrandchild(){
        if(Yii::$app->request->isPost){
            $pid = Yii::$app->request->post('pid');
            $json = Func::CurlPost(yii\helpers\Url::toRoute(['/wap/common/brandchild']),['pid'=>$pid]);
            $jsonArr = \yii\helpers\Json::decode($json);
            echo "<option value=''>请选择车系</option>";
            foreach ($jsonArr as $k=>$v) {
                echo "<option value='".$k."'>{$v}</option>";
            }
            die;
        }
    }
    
    /**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*/
	public function actionUpload($filetype=1)
    {    
        $model = new \common\models\UploadForm();
        $model->imageFile = \wx\components\UploadedFile::getInstance($model, 'file');
        $return = $model->upload($filetype,false);
		if($return['error']!=0){ 
			echo  \yii\helpers\Json::encode(['code'=>$return['error'],'msg'=>isset($return['msg'])?$return['msg']:'']);die;
		}
        $image_file = ".".$return['url'];
        $info = pathinfo($image_file);
        $image_info = getimagesize($image_file);
        $base64_image_content = chunk_split(base64_encode(file_get_contents($image_file))); 
        echo Func::CurlPost(yii\helpers\Url::toRoute(['/wap/common/uploads']),['data'=>$base64_image_content,'filetype'=>1,'extension'=>isset($info['extension'])?$info['extension']:'']);
        
    }
}
