<?php

namespace frontend\modules\wap\controllers;

use common\models\Apply;
use common\models\Evaluate;
use common\models\User;
use frontend\modules\wap\components\WapController;
use yii;
use frontend\modules\wap\services\Func;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * 用户操作控制器
 */
class ApplyController extends WapController
{
    public function actionIndex(){
        $status = Yii::$app->request->post('status',-1);
        $progress_status = Yii::$app->request->post('progress_status')?Yii::$app->request->post('progress_status'):-1;
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
        $uid = Yii::$app->session->get('user_id');
        
        $where = ' 1 and ap.uid = '.$uid.' ';
        if(ArrayHelper::isIn($status,[0,1])){
            $where .= ' and ap.app_id = '.$status.'  ';
        }
        if(ArrayHelper::isIn($progress_status,[0,1,2,3,4])){
            $where .= ' and progress_status = '.$progress_status.'  ';
        }

        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
		
        $sql ="select zfp.id as id,province_id,zfp.category as category,zfp.city_id,zfp.fundstime as commissionperiod,zfp.district_id,zfp.seatmortgage,zfp.uid,code,zfp.create_time,zfp.modify_time,zfp.loan_type,zfp.mortorage_community ,zfp.mortgagemoney as agencycommission ,zfp.rentmoney as agencycommissiontype,zfp.rebate,zfp.rate_cat, zfp.mortorage_has as carbrand , zfp.mortgagearea as audi,zfp.mortgagecategory as licenseplate,zfp.loanyear as accountr ,zfp.term,zfp.rate,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id ,ap.agree_time
               from zcb_finance_product zfp left JOIN zcb_apply as ap  on (ap.product_id = zfp.id)
               where {$where}  and ap.category  = 1 and zfp.is_del in(0,1)  and ap.app_id in (0,1) union
               select zcp.id as id, province_id,zcp.category as category,zcp.city_id,zcp.commissionperiod,zcp.district_id,zcp.seatmortgage,zcp.uid,code,zcp.create_time,zcp.modify_time,zcp.loan_type,zcp.mortorage_community,zcp.agencycommission ,zcp.agencycommissiontype ,zcp.rebate,zcp.rate_cat,zcp.carbrand,zcp.audi,zcp.licenseplate,zcp.accountr,zcp.term, zcp.rate,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id ,ap.agree_time
               from zcb_creditor_product zcp left JOIN zcb_apply as ap  on (ap.product_id = zcp.id)
               where {$where}  and ap.category in(2,3) and zcp.is_del in (0,1) and  ap.app_id in (0,1) and ap.is_del=0 order by case app_id when 2 then 1 when 0 then 2 else 3 end ,progress_status asc, modify_time desc";
        
		$rowse = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();	
        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->query();
        $st =[];
        $sr = [];
		foreach($rowse as $key=> $value){
			$rowse[$key]['carbrand'] = \frontend\services\Func::getCarBrand($value['carbrand']);
			$rowse[$key]['audi'] = \frontend\services\Func::getCarAudi($value['audi']);
			$apply = Apply::findOne(['app_id'=>[0,1],'product_id'=>$value['id'],'category'=>$value['category']]);
			$user = User::findOne(['id'=>$apply['uid']]);
			$rowse[$key]['applyid'] = isset($apply['id'])?$apply['id']:'';
			$rowse[$key]['applymobile'] = isset($user['mobile'])?$user['mobile']:'';
			
		}
        foreach($rows as $k=>$r){
                if($r['category'] == 1 && $r['rate_cat'] == 1){
                        $a = $r['term']?$r['term']:30;
                        $delays = date("Y-m-d",strtotime(date("Y-m-d",$r['agree_time']).' + '.$a." days "));
                    }else if($r['category'] == 1 && $r['rate_cat'] == 2 ){
                        $a = $r['term']?$r['term']:1;
                        $delays = date("Y-m-d",strtotime(date("Y-m-d",$r['agree_time']).' + '.$a." months ")) ;
                    }else{
                        $a = $r['commissionperiod']?$r['commissionperiod']:1;
                        $delays = date("Y-m-d",strtotime(date("Y-m-d",$r['agree_time']).' + '.$a." months ")) ;
                    }
                $creditor = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate  where uid ={$uid} and product_id ={$r['id']} and category={$r['category']}")->queryScalar();
                $st[$r['id'].'_'.$r['category']] = $creditor;
                $sr[$r['id'].'_'.$r['category']] =  $delays;
        }
        echo Json::encode(['code'=>'0000','result'=>['rows'=>$rowse,'page'=>$page ,'creditor'=>$st,'delays'=>$sr]]);die;
    }

    public function actionView(){
        $uid = Yii::$app->session->get('user_id');
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        if($token = Yii::$app->session->get('user_token')) {
            if (is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category, [1, 2, 3])) {
                $product = Func::getProduct($category, $id);
                if (!$product) {
                    echo Json::encode(['code' => '4001', 'msg' => '没有数据，请检查参数是否正确']);
                    die;
                } else {
                    echo Json::encode($product);
                    die;
                }
            } else {
                echo Json::encode(['code' => '1001', 'msg' => '参数错误']);
                die;
            }
        }else{
            echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }
    /**
     * 我的发布
     *  @param string progress_status
     * @param string page
     * @param string limit
     * @return json
     **/
    public function actionReleased()
    {
        $progress_status = Yii::$app->request->post('progress_status');
        $page = Yii::$app->request->post('page') ? Yii::$app->request->post('page') : 1;
        $limit = Yii::$app->request->post('limit') ? Yii::$app->request->post('limit') : 10;
        $uid = Yii::$app->session->get('user_id');
        if (ArrayHelper::isIn($progress_status, [0,1, 2, 3, 4])) {
            $where = " ";
            if($progress_status == 0){
                $pro = '1,2,3,4';
                $where .= " progress_status in(".$pro.")";
            }else{
                $where .= " progress_status = ".$progress_status."";
            }
			$rowse = (new \yii\db\Query())
                        ->select('zcp.*')
						->from('zcb_creditor_product as zcp')
                        ->where(['zcp.uid'=>$uid,'zcp.is_del'=>0])
						->andWhere($where)
						->offset(($page-1)*$limit)
                        ->limit($limit)
						->orderBy(['modify_time'=>SORT_DESC])
                        ->all();				
            $st =[];
            foreach ($rowse as $k=>$v){
                $num = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate where uid ={$uid} and product_id ={$v['id']} and category={$v['category']}")->queryScalar();
                $st[$v['id'].'_'.$v['category']] = isset($num)?$num:'';
				$apply = Apply::findOne(['app_id'=>[0,1],'product_id'=>$v['id'],'category'=>$v['category']]);
				if($v['progress_status'] == 2){
					$mobile = User::findOne(['id'=>$apply['uid']]);
				} 
                    $rowse[$k]['app_id'] = $apply['app_id'];
                    $rowse[$k]['product_id'] = $apply['product_id'];
                    $rowse[$k]['pid']     = $apply['uid'];
					$rowse[$k]['carbrand'] = \frontend\services\Func::getCarBrand($v['carbrand']);
					$rowse[$k]['audi'] = \frontend\services\Func::getCarAudi($v['audi']);
					$rowse[$k]['applymobile']= isset($mobile['mobile'])?$mobile['mobile']:'';
        }
            echo Json::encode(['code'=>'0000','result'=>['rows'=>$rowse,'page'=>$page ,'creditor'=>$st]]);die;
        }
    }
    /**
     * 查询接单方信息
     * @param string id
     * @param string category
     * @param string page
     * @param string limit
     * @return json
     **/
    public function actionOrdertaking(){
        $id       = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $page = Yii::$app->request->post('page') ? Yii::$app->request->post('page') : 1;
        $limit = Yii::$app->request->post('limit') ? Yii::$app->request->post('limit') : 10;
        $limitstr = "";
        if (is_numeric($page) && is_numeric($limit)) {
            $page = $page <= 1 ? 1 : $page;
            $limit = $limit <= 0 ? 10 : $limit;
            $limitstr = " limit " . ($page - 1) * $limit . "," . $limit;
        }
        if(is_numeric($id) && is_numeric($category)){
            $sql = "select a.uid, a.category, a.product_id ,a.app_id ,a.create_time , u.id, u.mobile as mobiles, u.username ,u.cardno , u.pid  from zcb_apply as a left join zcb_user as u on a.uid = u.id where a.category = '{$category}' and a.product_id = '{$id}' and a.app_id = 0 order by a.create_time desc";
            $rows = \Yii::$app->db->createCommand($sql . $limitstr)->queryAll();
            $certification = Func::getCertification();
            foreach($rows as $key => $value){
                $rows[$key]['state'] = isset($certification['state'])?$certification['state']:'';
				$rows[$key]['mobile'] = isset($value['mobiles'])?\frontend\services\Func::HideStrRepalceByChar($value['mobiles'],'*',3,4):'';
				$rows[$key]['name'] = isset($certification['name'])?\frontend\services\Func::HideStrRepalceByChar($certification['name'],'*',2,2):'';
            }
            echo Json::encode(['code'=>'0000','user'=>$rows]);die;
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
        
    }
    /**
     * 接单人信息
     * @param string pid
     * @param string id
     * @param string category
     * @return json
     **/
    public function actionUser(){
        $pid = Yii::$app->request->post('pid');
        $id  = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $uid      = Yii::$app->session->get('user_id');
        if(is_numeric($pid)&&is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $certification = Func::getCertifications($pid);
            $product = Func::getProduct($category, $id);
            if(!$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                  $product = ['id'=>$product['id'],'category'=>$product['category'],'progress_status'=>$product['progress_status'],'uid'=>$product['uid']];
                  $cer = ['id'=>$certification['id'],
                        'category'=>$certification['category'],
                        'name'=>isset($certification['name'])?\frontend\services\Func::HideStrRepalceByChar($certification['name'],'*',2,2):'',
                        'cardno'=>isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'',
                        'cardimg'=>$certification['cardimg'],
                        'contact'=>isset($certification['contact'])?\frontend\services\Func::HideStrRepalceByChar($certification['contact'],'*',1,0):'',
                        'mobile'=>isset($certification['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certification['mobile'],'*',3,4):'',
                        'address'=>isset($certification['address'])?\frontend\services\Func::HideStrRepalceByChar($certification['address'],'*',8,0):'',
                        'enterprisewebsite'=>$certification['enterprisewebsite'],
                        'email'=>isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'',
                        'casedesc'=>$certification['casedesc'],
                        'state'=>$certification['state'],
                        'uid' => $certification['uid'],
                    ];
                echo Json::encode(['code'=>'0000','result'=>['certification'=>$cer,'product'=>$product,'pid'=>$pid,'uid'=>$uid]]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 发布方信息
     * @param string id
     * @param string category
     * @return json
     **/
    public function actionUsers(){
        $id  = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $uid      = Yii::$app->session->get('user_id');
        if(is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $product = Func::getProduct($category,$id);
            $certification = Func::getCertifications($product['uid']);
            if(!$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确','product'=>$product]);die;
            }else{
                $product = ['id'=>$product['id'],'category'=>$product['category'],'progress_status'=>$product['progress_status'],'uid'=>$product['uid']];
                $cer = ['id'=>$certification['id'],
                        'category'=>$certification['category'],
                        'name'=>isset($certification['name'])?\frontend\services\Func::HideStrRepalceByChar($certification['name'],'*',2,2):'',
                        'cardno'=>isset($certification['cardno'])?\frontend\services\Func::HideStrRepalceByChar($certification['cardno'],'*',4,4):'',
                        'cardimg'=>$certification['cardimg'],
                        'contact'=>isset($certification['contact'])?\frontend\services\Func::HideStrRepalceByChar($certification['contact'],'*',1,0):'',
                        'mobile'=>isset($certification['mobile'])?\frontend\services\Func::HideStrRepalceByChar($certification['mobile'],'*',3,4):'',
                        'address'=>isset($certification['address'])?\frontend\services\Func::HideStrRepalceByChar($certification['address'],'*',8,0):'',
                        'enterprisewebsite'=>$certification['enterprisewebsite'],
                        'email'=>isset($certification['email'])?\frontend\services\Func::HideStrRepalceByChar($certification['email'],'*',3,10):'',
                        'casedesc'=>$certification['casedesc'],
                        'state'=>$certification['state'],
                        'uid' => $certification['uid'],
                    ];
                echo Json::encode(['code'=>'0000','result'=>['certification'=>$cer,'product'=>$product]]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 接单人评价信息列表
     * @param string uid
     * @return json
     **/
    public function actionUserlist(){
        $uid = Yii::$app->request->post('uid');
        if(is_numeric($uid)){
           $certification = Func::getCertifications($uid);
            $sql = "select e.* , u.mobile as mobiles ,zf.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where buid ={$certification['uid']}  and zf.id is not null  and zf.is_del = 0
                    union
                    select e.* , u.mobile as mobiles ,zc.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3) )  where buid ={$certification['uid']} and zc.id is not null  and zc.is_del = 0 order by create_time desc
            ";
            $sqls = "select e.* , u.mobile as mobiles ,zf.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where e.uid ={$certification['uid']}  and zf.id is not null  and  zf.is_del = 0
                    union
                    select e.* , u.mobile as mobiles ,zc.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3) )  where e.uid ={$certification['uid']} and zc.id is not null  and zc.is_del = 0 order by create_time desc
            ";
            //被评人
            $evaluate = \Yii::$app->db->createCommand($sql)->query();
            //发起评价人
            $launchevaluation = \Yii::$app->db->createCommand($sqls)->query();
            $creditor = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$certification['uid']}")->queryScalar();
            if(!$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['code'=>'0000','result'=>['evaluate'=>$evaluate,'creditor'=>$creditor,'uid'=>$uid,'launchevaluation'=>$launchevaluation]]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }
    
    /**
     * 接单人评价信息列表
     * @param string uid
     * @return json
     **/
    public function actionListdata(){
        $uid = Yii::$app->session->get('user_id');
	    $page = Yii::$app->request->post('page') ? Yii::$app->request->post('page') : 1;
        $limit = Yii::$app->request->post('limit') ? Yii::$app->request->post('limit') : 10;
        $limitstr = "";
        if (is_numeric($page) && is_numeric($limit)) {
            $page = $page <= 1 ? 1 : $page;
            $limit = $limit <= 0 ? 10 : $limit;
            $limitstr = " limit " . ($page - 1) * $limit . "," . $limit;
        }
        if(is_numeric($uid)){
           $certification = Func::getCertifications($uid);
            $sql = "select e.* , u.mobile ,zf.code,zf.uid as cuid from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where buid ={$certification['uid']}  and zf.id is not null  and zf.is_del = 0
                    union
                    select e.* , u.mobile ,zc.code,zc.uid as cuid from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3) )  where buid ={$certification['uid']} and zc.id is not null  and zc.is_del = 0 order by create_time desc
            ";
            $sqls = "select e.* , u.mobile ,zf.code,zf.uid as cuid from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where e.uid ={$certification['uid']}  and zf.id is not null  and  zf.is_del = 0
                    union
                    select e.* , u.mobile ,zc.code,zc.uid as cuid from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3) )  where e.uid ={$certification['uid']} and zc.id is not null  and zc.is_del = 0 order by create_time desc
            ";
            //被评人
            $evaluate = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
           
            foreach ($evaluate as $key => $value){
                $superaddition  = Evaluate::findOne(['buid'=>$value['buid'],'product_id'=>$value['product_id'],'category'=>$value['category'],'superaddition'=>1]);
                $evaluate[$key]['contents'] = $superaddition['content'];
				
                $evaluate[$key]['pictures'] = array_filter(explode(',',$value['picture']));
                $evaluate[$key]['create_times'] = isset($superaddition['create_time'])&&$superaddition['create_time'] != ''?(floor(($superaddition['create_time']-$value['create_time'])/3600/24)>=0?floor(($superaddition['create_time']-$value['create_time'])/3600/24):''):'';
                $evaluate[$key]['sid'] = $superaddition['id'];
                $evaluate[$key]['creditor'] = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$value['buid']} and product_id = {$value['product_id']} and category = {$value['category']}")->queryScalar();
				$evaluate[$key]['mobiles'] = isset($value['mobile'])?\frontend\services\Func::HideStrRepalceByChar($value['mobile'],'*',3,4):'';
            }
            //发起评价人
            $launchevaluation = \Yii::$app->db->createCommand($sqls.$limitstr)->queryAll();
            foreach ($launchevaluation as $key => $value){
                $superaddition  = Evaluate::findOne(['uid'=>$value['uid'],'product_id'=>$value['product_id'],'category'=>$value['category'],'superaddition'=>1]);
                $launchevaluation[$key]['contents'] = $superaddition['content'];
                $launchevaluation[$key]['pictures'] = array_filter(explode(',',$value['picture']));
                $launchevaluation[$key]['create_times'] = isset($superaddition['create_time'])&&$superaddition['create_time'] != ''?(floor(($superaddition['create_time']-$value['create_time'])/3600/24)>=0?floor(($superaddition['create_time']-$value['create_time'])/3600/24):''):'';
                $launchevaluation[$key]['sid'] = $superaddition['id'];
                $launchevaluation[$key]['creditor'] = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where uid ={$value['uid']} and product_id = {$value['product_id']} and category = {$value['category']}")->queryScalar();
                $num = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate where uid ={$uid} and product_id ={$value['product_id']} and category={$value['category']}")->queryScalar();
                $launchevaluation[$key]['frequency'] = isset($num)?$num:'';
				$launchevaluation[$key]['mobiles'] = isset($value['mobile'])?\frontend\services\Func::HideStrRepalceByChar($value['mobile'],'*',3,4):'';
            }
            if(!$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['code'=>'0000','result'=>['evaluate'=>$evaluate,'uid'=>$uid,'launchevaluation'=>$launchevaluation]]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 互评价信息列表
     * @param string id
     * @param string category
     * @return json
     **/
    public function actionUserlists(){
        $id   = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
        $uid = Yii::$app->session->get('user_id');
        if(is_numeric($id) && is_numeric($category) && ArrayHelper::isIn($category,[1,2,3])){
            $sql = "select e.* , u.mobile as mobiles,zf.code,zf.category from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where buid ={$uid} and e.product_id = {$id} and e.category = {$category} and e.superaddition = 0 and zf.id is not null
                    union
                    select e.* , u.mobile as mobiles ,zc.code,zc.category from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3))  where buid ={$uid} and e.product_id = {$id} and e.category = {$category} and e.superaddition = 0 and zc.id is not null order by create_time desc
            ";
            $sqls = "select e.* , u.mobile as mobiles ,zf.code,zf.category from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where e.uid ={$uid} and e.product_id = {$id} and e.category = {$category}  and e.superaddition = 0 and zf.id is not null
                    union
                    select e.* , u.mobile as mobiles ,zc.code,zc.category from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category in (2,3))  where e.uid ={$uid} and e.product_id = {$id} and e.category = {$category} and e.superaddition = 0 and zc.id is not null order by create_time desc
            ";
            //被评人
            $evaluate = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($evaluate as $key => $value){
                $superaddition  = Evaluate::findOne(['buid'=>$value['buid'],'product_id'=>$value['product_id'],'category'=>$value['category'],'superaddition'=>1]);
                $evaluate[$key]['contents'] = $superaddition['content'];
                $evaluate[$key]['pictures'] = array_filter(explode(',',$value['picture']));
                $evaluate[$key]['create_times'] = isset($superaddition['create_time'])&&$superaddition['create_time'] != ''?(floor(($superaddition['create_time']-$value['create_time'])/3600/24)>=0?floor(($superaddition['create_time']-$value['create_time'])/3600/24):''):'';
                $evaluate[$key]['sid'] = $superaddition['id'];
                $evaluate[$key]['creditor'] = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$value['buid']} and product_id = {$value['product_id']} and category = {$value['category']}")->queryScalar();
            }
			
            //发起评价人
            $launchevaluation = \Yii::$app->db->createCommand($sqls)->queryAll();
            foreach ($launchevaluation as $key => $value){
                $superaddition  = Evaluate::findOne(['uid'=>$value['uid'],'product_id'=>$value['product_id'],'category'=>$value['category'],'superaddition'=>1]);
                $launchevaluation[$key]['contents'] = $superaddition['content'];
                $launchevaluation[$key]['pictures'] = array_filter(explode(',',$value['picture']));
                $launchevaluation[$key]['create_times'] = isset($superaddition['create_time'])&&$superaddition['create_time'] != ''?(floor(($superaddition['create_time']-$value['create_time'])/3600/24)>=0?floor(($superaddition['create_time']-$value['create_time'])/3600/24):''):'';
                $launchevaluation[$key]['sid'] = $superaddition['id'];
                $launchevaluation[$key]['creditor'] = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where uid ={$value['uid']} and product_id = {$value['product_id']} and category = {$value['category']}")->queryScalar();
            }
            //发起人评价所得的星星的总数
            $creditor = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where uid ={$uid} and product_id = {$id} and category = {$category}")->queryScalar();
            //被评人的总数
            $creditors = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$uid} and product_id = {$id} and category = {$category}")->queryScalar();
            //评价次数
            $evalua = Yii::$app->db->createCommand("select (count(uid)) from zcb_evaluate  where uid ={$uid} and product_id ={$id} and category={$category}")->queryScalar();
            echo Json::encode(['code'=>'0000','result'=>['evaluate'=>$evaluate,'creditor'=>$creditor,'uid'=>$uid,'launchevaluation'=>$launchevaluation,'evalua'=>$evalua,'creditors'=>$creditors]]);die;
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 删除评价信息
     * @param string id
     * @return json
     **/
    public function actionDeleteeva(){
        $id = Yii::$app->request->post('id');
        $sid = Yii::$app->request->post('sid',0);
        $uid = Yii::$app->session->get('user_id');
        if(is_numeric($id)){
            $models = \common\models\Evaluate::findOne(['id'=>$id,'uid'=>$uid]);
            $evaluate = \common\models\Evaluate::findOne(['id'=>$sid,'uid'=>$uid]);
            $model = Func::getProduct($models->category,$models->product_id);
            if($models){
                if($evaluate){
                    $evaluate->delete();
                }
                if($models->delete()){
                   Func::addMessagesPerType('评价删除',($model->category == 1?'融资':($model->category == 2?'清收':'诉讼'))."编号:".$model->code.",。",$model->uid == $uid?5:6,serialize(['id' => $model->id,'category' => $model->category]));
                    echo Json::encode(['code'=>'0000','msg'=>'删除成功']);die;
                }else{
                    echo Json::encode(['code'=>'1014','msg'=>$models->errors]);die;
                }
            }else{
                    echo Json::encode(['code'=>'4001','msg'=>'没有数据,请查询参数是否正确']);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 案例
     * @param string id
     * @return json
     **/
    public function actionCase(){
        $id = Yii::$app->request->post('id');
        if(is_numeric($id)){
            $certification = \common\models\Certification::findOne(['id'=>$id]);
            if(!$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['certification'=>$certification]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 协议数据
     * @param string id
     * @param string category
     * @return json
     **/
    public function actionAgreement(){
        $id = Yii::$app->request->post('id');
        $category = Yii::$app->request->post('category');
		$uid = Yii::$app->session->get('user_id');
        if(is_numeric($id) && is_numeric($category)){
            $product = Func::getProduct($category, $id);
			$certification = Func::getCertifications($uid);
			$user = \common\models\User::findOne(['id'=>$uid]);
            $desca = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'app_id'=>1]);
            if(!$product && !$certification){
                echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
            }else{
                echo Json::encode(['code'=>'0000','result'=>['certification'=>$certification,'product'=>$product,'desca'=>isset($desca['agree_time'])?$desca['agree_time']:time(),'user'=>$user]]);die;
            }
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 同意接单方接单
     * @param string id
     * @param string uid
     * @param string category
     * @return json
     **/
    public function actionAgreeorder(){
        $id  = Yii::$app->request->post('id');
        $uid = Yii::$app->request->post('uid');
        $category = Yii::$app->request->post('category');
        if(is_numeric($id) && is_numeric($uid) && is_numeric($category)){
            $desca = \common\models\Apply::findOne(['category'=>$category,'product_id'=>$id,'uid'=>$uid,'app_id'=>0]);
            $product = Func::getProduct($category,$id);
            $user = \common\models\User::findOne(['id' => $desca['uid']]);
			if($desca && $product){
			if($product['progress_status'] != 1 && $desca['app_id'] != 0){
                echo Json::encode(['code'=>'4001','msg'=>'参数错误']);die;
            }else{
				$app = $desca::updateAll(['app_id'=>1,'agree_time'=>time()],['product_id'=>$id,'category'=>$category]);
				$products = $product::updateAll(['progress_status'=>2,'modify_time'=>time()],['id'=>$id]);
                if($app && $products){
                   Func::addMessagesPerType('申请成功',($product->category == 1?'融资':($product->category == 2?'清收':'诉讼'))."编号:".$product->code.",申请成功，赶紧去处理吧。",7,serialize(['id' => $product->id,'category' => $product->category]),$desca->uid);
                    echo Json::encode(['code'=>'0000','msg'=>'申请成功','product'=>$product,'user'=>$user]);
                }else{
					 $this->errorMsg("PageTimeOut");	
				}
            }
			}else{
				$this->errorMsg("参数错误");	
			}
            
        }else{
            echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
        }
    }

    /**
     * 我的收藏
     * @param string page
     * @param string limit
     * @return json
     **/
    public function actionMyshoucang(){
        $status = 2;
        $progress_status = 1;
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
        $uid = Yii::$app->session->get('user_id');

        $where = ' 1 and ap.uid = '.$uid.'  ';
        if(ArrayHelper::isIn($status,[0,1,2])){
            $where .= ' and ap.app_id = '.$status.'  ';
        }
        if(ArrayHelper::isIn($progress_status,[0,1,2,3,4])){
            $where .= ' and progress_status = '.$progress_status.'  ';
        }

        $limitstr= "";
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }

        $sql ="select zfp.id as id,province_id,zfp.category as category,zfp.city_id,zfp.district_id,zfp.seatmortgage,zfp.uid,code,zfp.create_time,zfp.modify_time,zfp.loan_type ,zfp.mortgagemoney as agencycommission ,zfp.rentmoney as agencycommissiontype,zfp.rebate, zfp.mortorage_has as carbrand ,zfp.mortgagearea as audi,zfp.mortgagecategory as licenseplate,zfp.rate_cat ,zfp.term,zfp.rate,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id ,ap.agree_time
               from zcb_finance_product zfp left JOIN zcb_apply as ap  on (ap.product_id = zfp.id)
               where {$where}  and ap.category  = 1  and zfp.is_del =0  union
               select zcp.id as id, province_id,zcp.category as category,zcp.city_id,zcp.district_id,zcp.seatmortgage,zcp.uid,code,zcp.create_time,zcp.modify_time,zcp.loan_type,zcp.agencycommission ,zcp.agencycommissiontype ,zcp.rebate,zcp.carbrand,zcp.audi,zcp.licenseplate,zcp.rate_cat ,zcp.term, zcp.rate,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id ,ap.agree_time
               from zcb_creditor_product zcp left JOIN zcb_apply as ap  on (ap.product_id = zcp.id)
               where {$where}  and ap.category in(2,3)  and zcp.is_del =0  order by case app_id when 2 then 1 when 0 then 2 else 3 end ,progress_status asc, modify_time desc";

        $rows = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
		foreach($rows as $k =>$v){
		  $rowse[$k]['carbrand'] = \frontend\services\Func::getCarBrand($v['carbrand']);
		  $rowse[$k]['audi'] = \frontend\services\Func::getCarAudi($v['audi']);
		}
        
        echo Json::encode(['code'=>'0000','result'=>['rows'=>$rows,'page'=>$page]]);die;
    }

    /**
     * 接单方被评信息
     * @param string pid
     * @param string limit
     * @param string page
     * @return json
     **/
    public function actionEvaluatewidget(){
        $pid = Yii::$app->request->post('pid');
        $token    = Yii::$app->request->post('token');
        $page = Yii::$app->request->post('page')?Yii::$app->request->post('page'):1;
        $limit = Yii::$app->request->post('limit')?Yii::$app->request->post('limit'):10;
        if(is_numeric($page)&&is_numeric($limit)){
            $page = $page<=1?1:$page;
            $limit = $limit<=0?10:$limit;
            $limitstr = " limit ".($page-1)*$limit.",".$limit;
        }
        if($token){
            if(is_numeric($pid)){
                $sql      = "select e.* , u.mobile as mobiles ,zf.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_finance_product as zf on (e.product_id = zf.id and e.category = 1)   where buid ={$pid} and superaddition = 0 and zf.id is not null  and zf.is_del =0
                             union
                             select e.* , u.mobile as mobiles ,zc.code from zcb_evaluate as e left join zcb_user as u on e.uid = u.id left join zcb_creditor_product as zc on (e.product_id = zc.id and e.category IN(2,3) )  where buid ={$pid} and superaddition = 0 and zc.id is not null  and zc.is_del =0  order by create_time desc
                             ";
                $evaluate = \Yii::$app->db->createCommand($sql.$limitstr)->queryAll();
                foreach ($evaluate as $key => $value){
                    $evaluate[$key]['pictures'] = array_filter(explode(',',$value['picture']));
                    $superaddition  = Evaluate::findOne(['buid'=>$value['buid'],'product_id'=>$value['product_id'],'category'=>$value['category'],'superaddition'=>1]);
                    $evaluate[$key]['contents'] = $superaddition['content'];
                    $evaluate[$key]['create_times'] = isset($superaddition['create_time'])&&$superaddition['create_time'] != ''?(floor(($superaddition['create_time']-$value['create_time'])/3600/24)>=0?floor(($superaddition['create_time']-$value['create_time'])/3600/24):''):'';
                    $evaluate[$key]['creditor'] = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$value['buid']} and product_id = {$value['product_id']} and category = {$value['category']}")->queryScalar();
                }
                $creditor = Yii::$app->db->createCommand("select (sum(serviceattitude)+sum(professionalknowledge)+sum(workefficiency))/(3*count(buid)) from zcb_evaluate  where buid ={$pid}")->queryScalar();
                if($evaluate){
                    echo Json::encode(['code'=>'0000','result'=>['evaluate'=>$evaluate,'creditor'=>$creditor,'page'=>$page]]);die;
                }else{
                    echo Json::encode(['code'=>'4001','msg'=>'没有数据，请检查参数是否正确']);die;
                }
            }else{
                    echo Json::encode(['code'=>'1001','msg'=>'参数错误']);die;
            }
        }else{
                    echo Json::encode(['code'=>'3001','msg'=>'你无此权限']);die;
        }
    }
}
