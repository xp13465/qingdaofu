<?php

namespace frontend\modules\wap\controllers;

use common\models\Apply;
use common\models\User;
use frontend\modules\wap\components\WapNoLoginController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use \yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class CapitalController extends WapNoLoginController
{
    /**
     * 注册方法
     * @param string id
     * @param string category
     * @param string province
     * @param string city
     * @param string area
     * @param string money
     * @param string status
     * @param string page
     * @param string limit
     * @return json
     * **/
    public function actionList(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $province = Yii::$app->request->post('province')?Yii::$app->request->post('province'):0;
        $city = Yii::$app->request->post('city')?Yii::$app->request->post('city'):0;
        $area = Yii::$app->request->post('area')?Yii::$app->request->post('area'):0;
        $money= Yii::$app->request->post('money')?Yii::$app->request->post('money'):0;
        $status = Yii::$app->request->post('status')?Yii::$app->request->post('status'):0;
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):0;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;

        $where = " and is_del = 0 ";
        if($id){
            $where .= " and id = '{$id}' ";
        }
        if(!in_array($province,['0',''])) {
            $where .= " and province_id = '{$province}' ";
        }
        if(!in_array($city,['0',''])) {
            $where .= " and city_id = '{$city}' ";
        }
        if(!in_array($area,['0',''])) {
            $where .= " and district_id = '{$area}' ";
        }
        if(in_array($category,[1,2,3])){
            $where .= " and category = '{$category}'";
        }
        if(in_array($status,[1,2,3,4])){
            $where.=" and progress_status in ({$status})";
        }
        if ($money == 0 && !in_array($money, [1, 2, 3, 4])) {

        } else if (in_array($money, [1, 2, 3, 4])) {
            switch ($money) {
                case 1:
                    $where .= "and money < ".(30);
                    break;
                case 2:
                    $where .= "and money between ".(30). " and ".(100);
                    break;
                case 3:
                    $where .= "and money between ".(100)." and ".(500);
                    break;
                case 4:
                    $where .= "and money > ".(500);
                    break;
                default:
                    break;
            }
        }

        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }

        $sql = "select id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status,browsenumber,province_id ,uid,uid as agencycommissiontype,uid as agencycommission,uid as loan_type,rebate,rate,rate_cat,mortorage_community from zcb_finance_product where  progress_status != 0 ".$where." union
                select id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber,province_id ,uid,agencycommissiontype,agencycommission,loan_type,rebate,rate,rate_cat,mortorage_community from zcb_creditor_product where  progress_status != 0  ".$where." order by progress_status asc ,modify_time desc";

        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
        foreach ($rows as $key => $value){
            $rows[$key]['location'] = $value['seatmortgage'].$value['mortorage_community'];
        }
        echo Json::encode(['code'=>'0000','result'=>$rows]);die;
    }

    /**
     * 推荐产品
     * @param string page
     * @param string limit
     * @return json
     */
    public function actionRecommendlist(){
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):0;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):6;
        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
        $sql = "select id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status,browsenumber,province_id ,uid,uid as agencycommissiontype,uid as agencycommission,uid as loan_type,rebate,rate,rate_cat,mortorage_community from zcb_finance_product where  progress_status = 1  union
                select id,seatmortgage,city_id,category,code,term,district_id,rate_cat,create_time,modify_time,money,progress_status ,browsenumber,province_id ,uid,agencycommissiontype,agencycommission,loan_type,rebate,rate,rate_cat,mortorage_community from zcb_creditor_product where  progress_status = 1 order by progress_status asc ,modify_time desc";
        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
        foreach ($rows as $key => $value){
            $rows[$key]['location'] = $value['seatmortgage'].$value['mortorage_community'];
        }
        echo Json::encode(['code'=>'0000','result'=>$rows]);die;
    }

    /**
     *  查看产品详细
     * @param string id
     * @param string category
     * @return json
     */
    public function actionView(){
        if($token = Yii::$app->request->post('token')){
            $user = User::findOne(['token'=>$token]);
            if(!isset($user->id)||!$user->id){
                echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
            }else{
                $this->user = $user;
                Yii::$app->session->set("user_id",$user->id);
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }

        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);
            $users = User::findOne(['id'=>$product['uid']]);
            if($users['pid']){
                $certification = \common\models\Certification::findOne(['uid'=>$users['pid']]);
            }else{
                $certification = \common\models\Certification::findOne(['uid'=>$users['id']]);
            }
            if($product['category']!=1) {
                $car = isset($product['loan_type'])&&$product['loan_type'] == 3?\frontend\services\Func::getCarBrand($product['carbrand']) . \frontend\services\Func::getCarAudi($product['audi']):'';
                $license = isset($product['loan_type'])&&$product['loan_type'] == 3?\common\models\creditorProduct::$licenseplate[$product['licenseplate']]:'';
                $creditorfiles =isset($product['creditorfile'])?unserialize($product['creditorfile']):[];
                $creditorinfo = isset($product['creditorinfo'])?unserialize($product['creditorinfo']):[];
                $borrowinginfo = isset($product['borrowinginfo'])?unserialize($product['borrowinginfo']):[];
                foreach($creditorinfo as $k =>$v){
                    $creditorinfo[$k]['creditorcardimage'] = isset($v['creditorcardimage'])?explode(',',$v['creditorcardimage']):[];
                }
                foreach($borrowinginfo as $k=>$v){
                    $borrowinginfo[$k]['borrowingcardimage'] = isset($v['borrowingcardimage'])?explode(',',$v['borrowingcardimage']):[];
                }
                $creditorfile = [];
                foreach($creditorfiles as $k=>$v){
                    $creditorfile[$k] = explode(',',$v);
                }
                $product=['product'=>$product,
                          'guaranteemethods'=>is_numeric($product['guaranteemethod'])?$product['guaranteemethod']:unserialize($product['guaranteemethod']),
                          'creditorfiles'=>$creditorfile,
                          'creditorinfos'=>$creditorinfo,
                          'borrowinginfos'=>$borrowinginfo,
                          'car' => $car,
                          'license' => $license,
                          'state'=>$certification['state'],
                       ];
            }else{
                $product = ['product'=>$product,'state'=>$certification['state']];
            }
            echo Json::encode(['code'=>'0000','result'=>$product,'uid'=>$user->id]);die;
        }else{
             echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     *  是否收藏
     * @param string id
     * @param string category
     * @return json
     */
    public function actionShoucans(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $token = Yii::$app->request->post('token');
        $user = User::findOne(['token'=>$token]);
        if(!isset($user->id)||!$user->id){
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }else{
            $this->user = $user;
            Yii::$app->session->set("user_id",$user->id);
        }
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])) {
            $product = Func::getProduct($category,$id);
            $users = User::findOne(['id'=>$product['uid']]);
            $apply = Apply::findOne(['uid' => $user->id, 'category' => $category, 'product_id' => $id]);
            $mobile = $users['mobile'];
            if ($apply) {
                echo Json::encode(['code' => '0000', 'result' => $apply, 'mobile' => $mobile]);
                die;
            }else{
                echo Json::encode(['code' => '1001', 'msg' =>'false']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     *  收藏方法
     * @param string id
     * @param string category
     * @return json
    */
    public function actionShoucang(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $token = Yii::$app->request->post('token');
        $user = User::findOne(['token'=>$token]);
        if(!isset($user->id)||!$user->id){
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }else{
            $this->user = $user;
            Yii::$app->session->set("user_id",$user->id);
        }
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);

            $certification = Func::getCertification();
            if($certification['category'] == 1){
                echo Json::encode(['code'=>'4001','msg'=>'个人用户只能用于发布数据']);die;
            }
            if($certification['category'] == 2 && $product->category == 1){
                echo Json::encode(['code'=>'4005','msg'=>'律所用户只能收藏诉讼、清收']);die;
            }
            if($certification['category'] == 3 && $product->category == 3){
                echo Json::encode(['code'=>'4003','msg'=>'公司用户只能收藏融资、清收']);die;
            }
            if($product->uid == $user->id){
                echo Json::encode(['code'=>'4004','msg'=>'自己不能收藏自己发布的数据']);die;
            }
            $apply = Apply::findOne(['uid'=>$user->id,'category'=>$category,'product_id'=>$id]);

            if(isset($apply->id)&&$apply->app_id == 2){
                echo Json::encode(['code'=>'4002','msg'=>'您已经收藏该产品']);die;
            }

            if(isset($apply->id)&&$apply->app_id == 0){
                echo Json::encode(['code'=>'4003','msg'=>'您已经申请对该产品接单']);die;
            }

            if(isset($apply->id)&&$apply->app_id == 1){
                echo Json::encode(['code'=>'4004','msg'=>'您对该产品已经申请成功']);die;
            }

            $apply_new = new Apply;
            $apply_new->category = $category;
            $apply_new->product_id = $id;
            $apply_new->app_id = 2;
            $apply_new->create_time = time();
            $apply_new->uid = $user->id;
            $apply_new->is_del = 0;
            $apply_new->agree_time = 0;
            if($apply_new->save()){
                Func::addMessagesPerType('收藏成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",收藏成功。",8,serialize(['id' => $product->id,'category' => $product->category]));
                echo Json::encode(['code'=>'0000','msg'=>'恭喜您收藏成功']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$apply_new->errors]);die;
            }

        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     *  申请接单方法
     * @param string id
     * @param string category
     * @return json
    */
    public function actionShenqing(){
        $id = Yii::$app->request->post('id')?Yii::$app->request->post('id'):'';
        $category = Yii::$app->request->post('category')?Yii::$app->request->post('category'):0;
        $token = Yii::$app->request->post('token');
        $user = User::findOne(['token'=>$token]);
        if(!isset($user->id)||!$user->id){
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }else{
            $this->user = $user;
            $uid = $user->id;
            Yii::$app->session->set("user_id",$user->id);
        }
        if(is_numeric($id)&&is_numeric($category)&&$id>0&&ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);

            $certification = Func::getCertification();
            if($product->uid == $uid){
                echo Json::encode(['code'=>'4001','msg'=>'自己不能申请自己发布的数据']);die;
            }
            if($certification['category'] == 1){
                echo Json::encode(['code'=>'4007','msg'=>'个人用户只能用于发布数据']);die;
            }
            if($certification['category'] == 2 && $product->category == 1){
                echo Json::encode(['code'=>'4008','msg'=>'律所用户只能申请诉讼、清收']);die;
            }
            if($certification['category'] == 3 && $product->category == 3){
                echo Json::encode(['code'=>'4009','msg'=>'公司用户只能申请融资、清收']);die;
            }
            $apply = Apply::findOne(['uid'=>$uid,'category'=>$category,'product_id'=>$id]);
            if($certification) {
                if (isset($apply->id) && $apply->app_id == 2) {
                    $apply->app_id = 0;
                    if ($apply->save()) {
                        Func::addMessagesPerType('申请成功', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",请耐心等待发布方同意。", 8, serialize(['id' => $product->id, 'category' => $product->category]));
                        Func::addMessagesPerType('申请接单', ($product->category == 1 ? '融资' : ($product->category == 2 ? '清收' : '诉讼')) . "编号:" . $product->code . ",有人接单，请赶快去看看。", 9, serialize(['id' => $product->id, 'category' => $product->category]), $product->uid);
                        echo Json::encode(['code' => '0000', 'msg' => '恭喜您申请成功']);
                        die;
                    } else {
                        echo Json::encode(['code' => '1014', 'msg' => $apply->errors]);
                        die;
                    }
                }
            }else{
                echo Json::encode(['code' => '3015', 'msg' => '请先认证用户']);
                die;
            }

            if(isset($apply->id)&&$apply->app_id == 0){
                echo Json::encode(['code'=>'4003','msg'=>'您已经申请对该产品接单']);die;
            }

            if(isset($apply->id)&&$apply->app_id == 1){
                echo Json::encode(['code'=>'4004','msg'=>'您对该产品已经申请成功']);die;
            }
            $apply_new = new Apply;
            $apply_new->category = $category;
            $apply_new->product_id = $id;
            $apply_new->app_id = 0;
            $apply_new->create_time = time();
            $apply_new->uid = $uid;
            $apply_new->is_del = 0;
            $apply_new->agree_time = 0;
            if($apply_new->save()){
                Func::addMessagesPerType('申请成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",请耐心等待发布方同意。",8,serialize(['id' => $product->id,'category' => $product->category]));
                Func::addMessagesPerType('申请接单',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",有人接单，请赶快去看看。",9,serialize(['id' => $product->id,'category' => $product->category]),$product->uid);
                echo Json::encode(['code'=>'0000','msg'=>'恭喜您申请成功']);die;
            }else{
                echo Json::encode(['code'=>'1014','msg'=>$apply_new->errors]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    public function actionPicture(){
        $images = ['banner1ios'=>Yii::$app->params['www'].'/images/banner1ios.png','banner1'=>Yii::$app->params['www'].'/protocol/carousel?type=1','banner2ios'=>Yii::$app->params['www'].'/images/banner2ios.png','banner2'=>Yii::$app->params['www'].'/protocol/carousel?type=2','banner3ios'=>Yii::$app->params['www'].'/images/banner3ios.png','banner3'=>Yii::$app->params['www'].'/protocol/carousel?type=3','banner4ios'=>Yii::$app->params['www'].'/images/banner4ios.png','banner4'=>Yii::$app->params['www'].'/protocol/carousel?type=4','banner5ios'=>Yii::$app->params['www'].'/images/banner5ios.png','banner5'=>Yii::$app->params['www'].'/protocol/carousel?type=5'];
        echo Json::encode($images);die;
    }
}
