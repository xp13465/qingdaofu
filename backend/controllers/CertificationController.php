<?php
namespace backend\controllers;

use Yii;

use backend\components\BackController;
use frontend\services\Func;

/**
 * Site controller
 */
class CertificationController extends BackController
{

    public $modelClass = 'common\models\Certification';
    public $modelSearchClass = 'common\models\search\CertificationSearch';

    public function actionList($keyword='')
    { 
		
        // $sql = "select * from zcb_certification where 1";
		// if($keyword)$sql.=" and ( mobile like '%{$keyword}%' ) "; 
		$query = \common\models\Certification::find()->orderby("modifytime desc");
		if($keyword)$query->andWhere(["like",'concat(mobile,name,cardno)',$keyword]);
		$count = $query->count();
        $pagination = new \yii\data\Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        // $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        
		
		$certification= $query->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('list', ['certification' => $certification, 'pagination' => $pagination, 'keyword' => $keyword]);
    }

    public function actionVerify()
    {
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type');
        $resultmemo = Yii::$app->request->post('resultmemo');

        $mm = \common\models\Certification::findOne(['id' => $id]);
        $mm->state = $type;
        $mm->resultmemo = $resultmemo;
        $mm->save();

        echo 1;
        die;
    }

    public function actionAdd($id)
    {
        $mm = \common\models\Certification::findOne(['id' => $id]);
        if (Yii::$app->request->post('managersnumber')) {
            $managersnumber = Yii::$app->request->post('managersnumber') ? Yii::$app->request->post('managersnumber') : 0;

            $mm->managersnumber = $managersnumber;
            $mm->save();
        }
        return $this->render('add', ['model' => $mm]);
    }

    public function actionChakan($id)
    {
        $certi = \common\models\Certification::findOne(['id' => $id]);
        return $this->render('chakan', ['certi' => $certi]);
    }

    public function actionCerti()
    {
        $this->header = "代理认证";
        return $this->render('certifications');
    }
    public function actionUploadsimgsingle(){
        return $this->renderPartial('uploadsImgsingle');
    }
    public function actionPersonal()
    {
        $model = new \common\models\Certification();
        if (yii::$app->request->post()) {
            $type = Yii::$app->request->post('types');
            $uid = yii::$app->request->post('userId');
            $casedesc = Yii::$app->request->post('casedesc');
            $cardimgs = Yii::$app->request->post('cardimg');
            if (isset(\common\models\Certification::findOne(['uid' => $uid])->id)) {
                echo json_encode(['code' => 1002, 'msg' => '您已经认证不可重复认证']);
                die;
            }
            if (!in_array($type + 1, [1, 2, 3])) {
                echo json_encode(['code' => 1003, 'msg' => '你选择的身份有误']);
                die;
            }

            $cardimg = $cardimgs[1];
           /* if (trim($cardimg) == '') {
                echo json_encode(['code' => 1006, 'msg' => '执业证照片未上传']);
                die;
            }*/
            $model->uid = $uid;
            $model->category = 1;
            $model->state = 1;
            $model->name = Yii::$app->request->post('name');
            $model->cardno = Yii::$app->request->post('cardno');
            $model->email = Yii::$app->request->post('email');
            $model->casedesc = $casedesc;
            $model->cardimg = $cardimg;
            Yii::$app->session->set("fromWhat", "personal");
            if ($model->save()) echo json_encode(['code' => 0000, 'msg' => '插入数据成功']); else {
                echo json_encode(['code' => 1001, 'msg' => $model->errors]);
            }
        }
    }

    public function actionLawyer()
    {
        $model = new \common\models\Certification();
        if (yii::$app->request->post()) {
            $type = Yii::$app->request->post('types');
            $uid = yii::$app->request->post('userId');
            $casedesc = Yii::$app->request->post('casedesc2');
            $cardimgs = Yii::$app->request->post('cardimg');
            if (isset(\common\models\Certification::findOne(['uid' => $uid])->id)) {
                echo json_encode(['code' => 1002, 'msg' => '您已经认证不可重复认证']);
                die;
            }
            if (!in_array($type + 1, [1, 2, 3])) {
                echo json_encode(['code' => 1003, 'msg' => '你选择的身份有误']);
                die;
            }
            $cardimg = $cardimgs[2];
            if (trim($cardimg) == '') {
                echo json_encode(['code' => 1006, 'msg' => '执业证照片未上传']);
                die;
            }
           /* if(!Func::isEmail(Yii::$app->request->post('email2'))){
                echo json_encode(['code'=>1007,'msg'=>'邮箱格式不对']);die;
            }*/
            $model->uid = $uid;
            $model->category = 2;
            $model->state = 1;
            $model->name = Yii::$app->request->post('name2');
            $model->cardno = Yii::$app->request->post('cardno2');
            $model->email = $email = Yii::$app->request->post('email2');
            $model->contact = Yii::$app->request->post('contact2');
            $model->mobile = Yii::$app->request->post('mobile2');
            $model->casedesc = $casedesc;
            $model->cardimg = $cardimg;
            Yii::$app->session->set("fromWhat", "lawyer");
            if ($model->save()) echo json_encode(['code' => 0000, 'msg' => '插入数据成功']); else {
                echo json_encode(['code' => 1001, 'msg' => $model->errors]);
            }
        }
    }

    public function actionOrgnization()
    {
        $model = new \common\models\Certification();
        if (yii::$app->request->post()) {
            $type = Yii::$app->request->post('types');
            $uid = yii::$app->request->post('userId');
            $casedesc = Yii::$app->request->post('casedesc3');
            $enterprisewebsite = Yii::$app->request->post('enterprisewebsite3');
            $cardimgs = Yii::$app->request->post('cardimg');
            if (isset(\common\models\Certification::findOne(['uid' => $uid])->id)) {
                echo json_encode(['code' => 1002, 'msg' => '您已经认证不可重复认证']);
                die;
            }
            if (!in_array($type + 1, [1, 2, 3])) {
                echo json_encode(['code' => 1003, 'msg' => '你选择的身份有误']);
                die;
            }
           /* if(!Func::isEmail(Yii::$app->request->post('email3'))){
                echo json_encode(['code'=>1007,'msg'=>'邮箱格式不对']);die;
            }*/
            $cardimg = $cardimgs[3];
            if (trim($cardimg) == '') {
                echo json_encode(['code' => 1006, 'msg' => '营业执照照片未上传']);
                die;
            }
            if (!$enterprisewebsite && Func::isNet($enterprisewebsite)) {
                echo json_encode(['code' => 1009, 'msg' => '网址格式不对']);
                die;
            }
            $model->uid = $uid;
            $model->category = 3;
            $model->state = 1;
            $model->name = Yii::$app->request->post('name3');
            $model->cardno = Yii::$app->request->post('cardno3');
            $model->email = Yii::$app->request->post('email3');
            $model->contact = Yii::$app->request->post('contact3');
            $model->mobile = Yii::$app->request->post('mobile3');
            $model->casedesc = $casedesc;
            $model->cardimg = $cardimg;
            $model->enterprisewebsite = $enterprisewebsite;
            $model->address = Yii::$app->request->post('address');

            Yii::$app->session->set("fromWhat", "orgnization");
            if ($model->save()) echo json_encode(['code' => 0000, 'msg' => '插入数据成功']); else {
                echo json_encode(['code' => 1001, 'msg' => $model->errors]);
            }
        }
    }

    public function actionModify($id)
    {
        $this->header = "修改认证";
        $certification = \common\models\Certification::findOne(['uid' => $id]);
        $cardimgs = Yii::$app->request->post('cardimg');
        $cardimg = $cardimgs[1];
        if (Yii::$app->request->post()) {
            $certification->name = Yii::$app->request->post('name');
            $certification->cardno = Yii::$app->request->post('cardno');
            $certification->email = Yii::$app->request->post('email');
            $certification->contact = Yii::$app->request->post('contact');
            $certification->mobile = Yii::$app->request->post('mobile');
            $certification->casedesc = Yii::$app->request->post('casedesc');
            $certification->cardimg = $cardimg;
            $certification->enterprisewebsite = Yii::$app->request->post('enterprisewebsite');
            $certification->address = Yii::$app->request->post('address');
            if($certification->save()){exit(json_encode(1));}else{
                exit(json_encode(2));
            }

        }
        return $this->render('modify', ['certifi' => $certification]);
    }
	/*
    public function actionDeletes(){
          $category = yii::$app->request->post('category');
          $id = yii::$app->request->post('id');
         if($category == 1){
             $deletes = \common\models\FinanceProduct::findOne(['id'=>$id,'category'=>$category]);
         }else{
             $deletes = \common\models\CreditorProduct::findOne(['id'=>$id,'category'=>$category]);
         }
         if($deletes->delete()){
             exit(json_encode(1));
         }else{
             return false;
         }
    }
	*/
    public function actionOutput($keyword=''){
        // $sql = "select c.*,u.mobile as umobile from zcb_certification c left join zcb_user u on u.id = c.uid where 1";
        // $result = Yii::$app->db->createCommand($sql)->query();
		$query = \common\models\Certification::find()->orderby("modifytime desc")->alias("cert");
		if($keyword)$query->andWhere(["like",'concat(cert.mobile,cert.name,cert.cardno)',$keyword]);
		$count = $query->count();
        $pagination = new \yii\data\Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        // $sql = $sql . " limit ".$pagination->offset." , ".$pagination->limit;
        
		
		$result= $query->joinWith('userinfo')->asArray()->all();
// var_dump($result);
// exit;
        $arr = [];
        $ic = 1;

        foreach($result as $r) {
            $aone = [];

            $aone['sortID'] = $ic++;
            $aone['id'] = $r['id'];
            $aone['umobile'] = $r['userinfo']['mobile']?:'';
            $aone['name'] = $r['name'];
            $category = [1 => '个人', 2 => '律所', 3 => '机构'];
            $aone['category'] = $category[$r['category']];
            $aone['mobile'] = $r['mobile']?:'';
            $aone['cardno'] = $r['cardno'];
            $aone['address'] = $r['address'];
            $aone['email'] = $r['email'];
            $aone['contact'] = $r['contact'];
            $aone['enterprisewebsite'] = $r['enterprisewebsite'];
            $aone['casedesc'] = $r['casedesc'];
            $aone['create_time'] = $r['create_time'];
            $aone['managersnumber'] = $r['managersnumber'];
            $aone['law_cardno'] = $r['law_cardno'];


            $arr[] = $aone;
        }
        // var_dump($arr);die;
        \moonland\phpexcel\Excel::export([
            'fileName' => '认证信息.xlsx',
            'format' => 'Excel2007',
            'models' => $arr,
			'mode' => 'export',
            'columns' => ['sortID','id','umobile:text','category','name','mobile:text','create_time','cardno:text',
			'email:text','address','contact','enterprisewebsite','casedesc','managersnumber','law_cardno'], //without header working, because the header will be get label from attribute label.
            'headers'=>['sortID'=>'序号','id'=>'ID','umobile'=>'用户手机号','category'=>'类型','name'=>'姓名h/律所名/机构名','mobile'=>'联系方式','create_time'=>'认证时间','cardno'=>'身份证号/职业资格证号/组织机构代码','email'=>'电子邮件','address'=>'地址','contact'=>'联系人','enterprisewebsite'=>'公司网址','casedesc'=>'经典案例','managersnumber'=>'可添加代理人个数','law_cardno'=>'律师执业证号']
        ]);
		exit;
    }
}
