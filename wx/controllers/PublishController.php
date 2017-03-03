<?php

namespace wx\controllers;
use wx\services\Func;
use yii;
class PublishController extends \wx\components\FrontController
{
    public $enableCsrfValidation = false;
    public function actionFinancing(){
        $this->title = "清道夫债管家";
        $this->keywords = "清道夫债管家";
        $this->description = "清道夫债管家";

        $token = Yii::$app->session->get('user_token');

        setCookie('publishCookieName',md5($token)."finance",time()+3600,'/');

        if(Yii::$app->request->isAjax){
            //必填信息
            $money = Yii::$app->request->post('money');
            $rebate = Yii::$app->request->post('rebate');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat');
            $mortorage_has = Yii::$app->request->post('mortorage_has');
            $mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $progress_status = Yii::$app->request->post('progress_status');
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
            $district_id = Yii::$app->request->post('district_id');


            //选填信息
            $mortgagecategory = Yii::$app->request->post('mortgagecategory');
            $status = Yii::$app->request->post('status');
            
            //$mortgagestatus = Yii::$app->request->post('mortgagestatus');
            $rentmoney = Yii::$app->request->post('rentmoney');
            $mortgagearea = Yii::$app->request->post('mortgagearea');
            $loanyear = Yii::$app->request->post('loanyear');
            $obligeeyear = Yii::$app->request->post('obligeeyear');

            $collection = [
                'money'=>$money,
                'rebate'=>$rebate,
                'rate'=>$rate,
                'category'=>1,
                'rate_cat'=>$rate_cat,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                'district_id'=>$district_id,
                'mortorage_has'=>$mortorage_has,
                'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'progress_status'=>$progress_status,
                'mortgagecategory'=>$mortgagecategory,
                'status'=>$status,
                'rentmoney'=>$rentmoney,
                'obligeeyear'=>$obligeeyear,
                'loanyear'=>$loanyear,
                'mortgagearea'=>$mortgagearea,
                'token'=>$token,
            ];
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/financing'),$collection);
            die;
        }

        return $this->render('financing');
    }

    public function actionCollection(){
        $this->title = "清道夫债管家-清收";
        $this->keywords = "清道夫债管家-清收";
        $this->description = "清道夫债管家-清收";
        $token = Yii::$app->session->get('user_token');
        setCookie('publishCookieName',md5($token)."collection",time()+3600,'/');
        setCookie('fromWhere',"collection",time()+3600,'/');
        if(Yii::$app->request->isAjax){
            $money = Yii::$app->request->post('money');
            $agencycommissiontype = Yii::$app->request->post('agencycommissiontype',1);
			if($agencycommissiontype == 1){
				$agencycommission = Yii::$app->request->post('agencycommissions');
			}else{
				$agencycommission = Yii::$app->request->post('agencycommission');
			}
            
            $loan_type = Yii::$app->request->post('loan_type');
            $category = 2;
            $mortorage_has = Yii::$app->request->post('mortorage_has');
            //$mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $carbrand = Yii::$app->request->post('carbrand');
            $audi = Yii::$app->request->post('audi');
            $licenseplate = Yii::$app->request->post('licenseplate');
            $accountr = Yii::$app->request->post('accountr');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat',1);
            $term = Yii::$app->request->post('term');
            $repaymethod = Yii::$app->request->post('repaymethod');
            $obligor = Yii::$app->request->post('obligor');
            $commitment = Yii::$app->request->post('commitment');
            $commissionperiod = Yii::$app->request->post('commissionperiod');
            $paidmoney = Yii::$app->request->post('paidmoney');
            $interestpaid = Yii::$app->request->post('interestpaid');
            $performancecontract = Yii::$app->request->post('performancecontract');
			$place_province_id  = Yii::$app->request->post('place_province_id');
			$place_city_id      = Yii::$app->request->post('place_city_id');
			$place_district_id  = Yii::$app->request->post('place_district_id');
            $paymethod = Yii::$app->request->post('paymethod');
            $progress_status = Yii::$app->request->post('progress_status');
			$year= Yii::$app->request->post('year').'-'.Yii::$app->request->post('month').'-'.Yii::$app->request->post('day');
            $start = strtotime($year);
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
            $district_id = Yii::$app->request->post('district_id');
             

            //上传债权文件
            $imgnotarization = Yii::$app->request->post('imgnotarization');
            $imgcontract = Yii::$app->request->post('imgcontract');
            $imgcreditor = Yii::$app->request->post('imgcreditor');
            $imgpick = Yii::$app->request->post('imgpick');
            $imgbenjin = Yii::$app->request->post('imgbenjin');
            $imgshouju = Yii::$app->request->post('imgshouju');
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
            //债权人信息
            $creditorname = Yii::$app->request->post('creditorname');
            $creditormobile = Yii::$app->request->post('creditormobile');
            $creditoraddress = Yii::$app->request->post('creditoraddress');
            $creditorcardcode = Yii::$app->request->post('creditorcardcode');
            $creditorcardimage = Yii::$app->request->post('creditorcardimage');
            if($creditorname||$creditormobile||$creditoraddress||$creditorcardcode||$creditorcardimage){
                $creditorinfo = serialize([0=>[
                    'creditorname' => $creditorname ,
                    'creditormobile' => $creditormobile ,
                    'creditoraddress' => $creditoraddress ,
                    'creditorcardcode' => $creditorcardcode ,
                    'creditorcardimage' => $creditorcardimage ,
                ]]);
            }else{
                unset($creditorname);
                unset($creditormobile);
                unset($creditoraddress);
                unset($creditorcardcode);
                unset($creditorcardimage);
                $creditorinfo = serialize([]);
            }
            //债务人信息
            $borrowingname = Yii::$app->request->post('borrowingname');
            $borrowingmobile = Yii::$app->request->post('borrowingmobile');
            $borrowingaddress = Yii::$app->request->post('borrowingaddress');
            $borrowingcardcode = Yii::$app->request->post('borrowingcardcode');
            $borrowingcardimage = Yii::$app->request->post('borrowingcardimage');

            if($borrowingname||$borrowingmobile||$borrowingaddress||$borrowingcardcode||$borrowingcardimage){
                $borrowinginfo = serialize([0=>[
                    'borrowingname' => $borrowingname ,
                    'borrowingmobile' => $borrowingmobile ,
                    'borrowingaddress' => $borrowingaddress ,
                    'borrowingcardcode' => $borrowingcardcode ,
                    'borrowingcardimage' => $borrowingcardimage ,
                ]]);
            }else{
                unset($borrowingname);
                unset($borrowingmobile);
                unset($borrowingaddress);
                unset($borrowingcardcode);
                unset($borrowingcardimage);
                $borrowinginfo = serialize([]);
            }

            $collection = [
                'money'=>$money,
                'agencycommission'=>$agencycommission,
                'agencycommissiontype'=>$agencycommissiontype,
                'category'=>$category,
                'loan_type'=>$loan_type,
                'carbrand'=>$carbrand,
                'audi'=>$audi,
                'licenseplate'=>$licenseplate,
                'accountr'=>$accountr,
                'mortorage_has'=>$mortorage_has,
               // 'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'rate'=>$rate,
                'rate_cat'=>$rate_cat,
                'term'=>$term,
                'repaymethod'=>$repaymethod,
                'obligor'=>$obligor,
                'commitment'=>$commitment,
                'commissionperiod'=>$commissionperiod,
                'paidmoney'=>$paidmoney,
                'interestpaid'=>$interestpaid,
                'performancecontract'=>$performancecontract,
                'paymethod'=>$paymethod,
                'progress_status'=>$progress_status,
                'creditorfile'=>$creditorfile,
                'creditorinfo'=>$creditorinfo,
                'borrowinginfo'=>$borrowinginfo,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                'district_id'=>$district_id,
                'start' => $start,
				'place_province_id'=>$place_province_id,
				'place_city_id'=>$place_city_id,
				'place_district_id'=>$place_district_id,
                'token'=>$token,
            ];
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/creditorcollection'),$collection);
            die;
        }
        //$A = Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/creditorcollection'));
        //$C = Json\helpers\
            return $this->render('collection');
        
    }

    public function actionLigitation(){
        $token = Yii::$app->session->get('user_token');
        setCookie('publishCookieName',md5($token)."ligitation",time()+3600,'/');
        setCookie('fromWhere',"ligitation",time()+3600,'/');
        if(Yii::$app->request->isAjax){
            $money = Yii::$app->request->post('money');
            $agencycommissiontype = Yii::$app->request->post('agencycommissiontype');
			if($agencycommissiontype == 1){
				$agencycommission = Yii::$app->request->post('agencycommission');
			}else{
				$agencycommission = Yii::$app->request->post('agencycommissions');
			}
            
            $loan_type = Yii::$app->request->post('loan_type');
            $category = 3;
            $mortorage_has = Yii::$app->request->post('mortorage_has');
            //$mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $carbrand = Yii::$app->request->post('carbrand');
            $audi = Yii::$app->request->post('audi');
            $licenseplate = Yii::$app->request->post('licenseplate');
            $accountr = Yii::$app->request->post('accountr');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat',1);
            $term = Yii::$app->request->post('term');
            $repaymethod = Yii::$app->request->post('repaymethod');
            $obligor = Yii::$app->request->post('obligor');
            $commitment = Yii::$app->request->post('commitment');
            $commissionperiod = Yii::$app->request->post('commissionperiod');
            $paidmoney = Yii::$app->request->post('paidmoney');
            $interestpaid = Yii::$app->request->post('interestpaid');
            $performancecontract = Yii::$app->request->post('performancecontract');
			$place_province_id  = Yii::$app->request->post('place_province_id');
			$place_city_id      = Yii::$app->request->post('place_city_id');
			$place_district_id  = Yii::$app->request->post('place_district_id');
            $paymethod = Yii::$app->request->post('paymethod');
            $progress_status = Yii::$app->request->post('progress_status');
            $year= Yii::$app->request->post('year').'-'.Yii::$app->request->post('month').'-'.Yii::$app->request->post('day');
            $start = strtotime($year);
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
            $district_id = Yii::$app->request->post('district_id');
            
            //上传债权文件
            $imgnotarization = Yii::$app->request->post('imgnotarization');
            $imgcontract = Yii::$app->request->post('imgcontract');
            $imgcreditor = Yii::$app->request->post('imgcreditor');
            $imgpick = Yii::$app->request->post('imgpick');
            $imgbenjin = Yii::$app->request->post('imgbenjin');
            $imgshouju = Yii::$app->request->post('imgshouju');
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
            //债权人信息
            $creditorname = Yii::$app->request->post('creditorname');
            $creditormobile = Yii::$app->request->post('creditormobile');
            $creditoraddress = Yii::$app->request->post('creditoraddress');
            $creditorcardcode = Yii::$app->request->post('creditorcardcode');
            $creditorcardimage = Yii::$app->request->post('creditorcardimage');
            if($creditorname||$creditormobile||$creditoraddress||$creditorcardcode||$creditorcardimage){
                $creditorinfo = serialize([0=>[
                    'creditorname' => $creditorname ,
                    'creditormobile' => $creditormobile ,
                    'creditoraddress' => $creditoraddress ,
                    'creditorcardcode' => $creditorcardcode ,
                    'creditorcardimage' => $creditorcardimage ,
                ]]);
            }else{
                unset($creditorname);
                unset($creditormobile);
                unset($creditoraddress);
                unset($creditorcardcode);
                unset($creditorcardimage);
                $creditorinfo = serialize([]);
            }
            //债务人信息
            $borrowingname = Yii::$app->request->post('borrowingname');
            $borrowingmobile = Yii::$app->request->post('borrowingmobile');
            $borrowingaddress = Yii::$app->request->post('borrowingaddress');
            $borrowingcardcode = Yii::$app->request->post('borrowingcardcode');
            $borrowingcardimage = Yii::$app->request->post('borrowingcardimage');
            if($borrowingname||$borrowingmobile||$borrowingaddress||$borrowingcardcode||$borrowingcardimage){
                $borrowinginfo = serialize([0=>[
                    'borrowingname' => $borrowingname ,
                    'borrowingmobile' => $borrowingmobile ,
                    'borrowingaddress' => $borrowingaddress ,
                    'borrowingcardcode' => $borrowingcardcode ,
                    'borrowingcardimage' => $borrowingcardimage ,
                ]]);
            }else{
                unset($borrowingname);
                unset($borrowingmobile);
                unset($borrowingaddress);
                unset($borrowingcardcode);
                unset($borrowingcardimage);
                $borrowinginfo = serialize([]);
            }


            $collection = [
                'money'=>$money,
                'agencycommission'=>$agencycommission,
                'agencycommissiontype'=>$agencycommissiontype,
                'category'=>$category,
                'loan_type'=>$loan_type,
                'carbrand'=>$carbrand,
                'audi'=>$audi,
                'licenseplate'=>$licenseplate,
                'accountr'=>$accountr,
                'mortorage_has'=>$mortorage_has,
                //'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'rate'=>$rate,
                'rate_cat'=>$rate_cat,
                'term'=>$term,
                'repaymethod'=>$repaymethod,
                'obligor'=>$obligor,
                'commitment'=>$commitment,
                'commissionperiod'=>$commissionperiod,
                'paidmoney'=>$paidmoney,
                'interestpaid'=>$interestpaid,
                'performancecontract'=>$performancecontract,
                'paymethod'=>$paymethod,
                'progress_status'=>$progress_status,
                'creditorfile'=>$creditorfile,
                'creditorinfo'=>$creditorinfo,
                'borrowinginfo'=>$borrowinginfo,
                'province_id'=>$province_id,
                'district_id'=>$district_id,
                'city_id'=>$city_id,
                'start' => $start,
				'place_province_id'=>$place_province_id,
				'place_city_id'=>$place_city_id,
				'place_district_id'=>$place_district_id,
                'token'=>$token,
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/creditorcollection'),$collection);
            die;
        }

            return $this->render('ligitation');

    }

    public function actionCreditorfile(){
        $this->title = "清道夫债管家-上传文件";
        $this->keywords = "清道夫债管家-上传文件";
        $this->description = "清道夫债管家-上传文件";
        $category = Yii::$app->request->get('category',0);
        $id = Yii::$app->request->get('id',0);
        $token = Yii::$app->session->get('user_token');

        if($category == 0 && $id==0){
            return $this->render('creditorfile');
        }
        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[2,3])){
            throw new yii\web\NotFoundHttpException();
        }
        $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
        $json_arr = yii\helpers\Json::decode($json);

        if($json_arr['code']=='0000'){
            $product = $json_arr['result']['product'];
            $product['creditorfile'] = unserialize($product['creditorfile']);
        }else{
            throw new yii\web\NotFoundHttpException();
        }

        if(Yii::$app->request->isAjax){
            //上传债权文件
            $imgnotarization = Yii::$app->request->post('imgnotarization');
            $imgcontract = Yii::$app->request->post('imgcontract');
            $imgcreditor = Yii::$app->request->post('imgcreditor');
            $imgpick = Yii::$app->request->post('imgpick');
            $imgbenjin = Yii::$app->request->post('imgbenjin');
            $imgshouju = Yii::$app->request->post('imgshouju');

            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);

            $post_data = [
                'id'=>$id,
                'category'=>$category,
                'token'=>$token,
                'creditorfile'=>$creditorfile,
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/editcollection'),$post_data);
            die;
        }
        return $this->render('creditorfile',['data'=>$product['creditorinfo']]);
    }

    public function actionCreditorprofile(){
        $this->title = "清道夫债管家-债权人信息";
        $this->keywords = "清道夫债管家-债权人信息";
        $this->description = "清道夫债管家-债权人信息";
        $category = Yii::$app->request->get('category',0);
        $id = Yii::$app->request->get('id',0);
        $keynum = Yii::$app->request->get('keynum',-1);
        $token = Yii::$app->session->get('user_token');

        if($category == 0 && $id == 0){
            return $this->render('creditorprofile');
        }
        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[2,3])){
            throw new yii\web\NotFoundHttpException();
        }
        $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
        $json_arr = yii\helpers\Json::decode($json);

        if($json_arr['code']=='0000'){
            $product = $json_arr['result']['product'];
            $product['creditorinfo'] = unserialize($product['creditorinfo']);
        }else{
            throw new yii\web\NotFoundHttpException();
        }
        if(Yii::$app->request->isAjax){
            //债权人信息
            $creditorname = Yii::$app->request->post('creditorname');
            $creditormobile = Yii::$app->request->post('creditormobile');
            $creditoraddress = Yii::$app->request->post('creditoraddress');
            $creditorcardcode = Yii::$app->request->post('creditorcardcode');
            $creditorcardimage = Yii::$app->request->post('creditorcardimage');

            $creditorinfo = $product['creditorinfo'];
            if($keynum == -1 ){
                $creditorinfo[] = [
                    'creditorname' => $creditorname ,
                    'creditormobile' => $creditormobile ,
                    'creditoraddress' => $creditoraddress ,
                    'creditorcardcode' => $creditorcardcode ,
                    'creditorcardimage' => $creditorcardimage ,
                ];
            }elseif(isset($creditorinfo[$keynum])){
                $creditorinfo[$keynum] = [
                    'creditorname' => $creditorname ,
                    'creditormobile' => $creditormobile ,
                    'creditoraddress' => $creditoraddress ,
                    'creditorcardcode' => $creditorcardcode ,
                    'creditorcardimage' => $creditorcardimage ,
                ];
            }
            $post_data = [
                'id'=>$id,
                'category'=>$category,
                'token'=>$token,
                'creditorinfo'=>serialize($creditorinfo),
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/editcollection'),$post_data);
            die;
        }

        return $this->render('creditorprofile',['data'=>$product['creditorinfo']]);
    }

    public function actionUncreditorprofile(){
        $this->title = "清道夫债管家-债务人信息";
        $this->keywords = "清道夫债管家-债务人信息";
        $this->description = "清道夫债管家-债务人信息";
        $category = Yii::$app->request->get('category',0);
        $id = Yii::$app->request->get('id',0);
        $keynum = Yii::$app->request->get('keynum',-1);
        $token = Yii::$app->session->get('user_token');

        if($category == 0 && $id==0){
            return $this->render('uncreditorprofile');
        }
        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[2,3])){
            throw new yii\web\NotFoundHttpException();
        }
        $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
        $json_arr = yii\helpers\Json::decode($json);

        if($json_arr['code']=='0000'){
            $product = $json_arr['result']['product'];
            $product['borrowinginfo'] = unserialize($product['borrowinginfo']);
        }else{
            throw new yii\web\NotFoundHttpException();
        }
        if(Yii::$app->request->isAjax){
            //债务人信息
            $borrowingname = Yii::$app->request->post('borrowingname');
            $borrowingmobile = Yii::$app->request->post('borrowingmobile');
            $borrowingaddress = Yii::$app->request->post('borrowingaddress');
            $borrowingcardcode = Yii::$app->request->post('borrowingcardcode');
            $borrowingcardimage = Yii::$app->request->post('borrowingcardimage');

            $borrowinginfo = $product['borrowinginfo'];

            if($keynum == -1 ) {
                $borrowinginfo[] = [
                    'borrowingname' => $borrowingname,
                    'borrowingmobile' => $borrowingmobile,
                    'borrowingaddress' => $borrowingaddress,
                    'borrowingcardcode' => $borrowingcardcode,
                    'borrowingcardimage' => $borrowingcardimage,
                ];
            }elseif(isset($borrowinginfo[$keynum])){
                $borrowinginfo[$keynum] =  [
                    'borrowingname' => $borrowingname,
                    'borrowingmobile' => $borrowingmobile,
                    'borrowingaddress' => $borrowingaddress,
                    'borrowingcardcode' => $borrowingcardcode,
                    'borrowingcardimage' => $borrowingcardimage,
                ];
            }

            $post_data = [
                'id'=>$id,
                'category'=>$category,
                'token'=>$token,
                'borrowinginfo'=>serialize($borrowinginfo),
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/editcollection'),$post_data);
            die;
        }
        return $this->render('uncreditorprofile',['data'=>$product['borrowinginfo']]);
    }

    public function actionCreditorfileinfo(){
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');

        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
            throw new yii\web\NotFoundHttpException();
        }else{
            $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
            $json_arr = yii\helpers\Json::decode($json);

            if($json_arr['code']=='0000'){
                $product = $json_arr['result']['product'];
                $product['creditorfile'] = unserialize($product['creditorfile']);
            }else{
                throw new yii\web\NotFoundHttpException();
            }
        }
        return $this->render('creditorfileinfo',['data'=>$product['creditorfile']]);
    }

    public function actionCreditorprofileinfo(){
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');


        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
            throw new yii\web\NotFoundHttpException();
        }else{
            $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
            $json_arr = yii\helpers\Json::decode($json);

            if($json_arr['code']=='0000'){
                $product = $json_arr['result']['product'];
                $product['creditorinfo'] = unserialize($product['creditorinfo']);
            }else{
                throw new yii\web\NotFoundHttpException();
            }
        }
        return $this->render('creditorprofileinfo',['data'=>$product['creditorinfo']]);
    }

    public function actionUncreditorprofileinfo(){
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');

        if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
            throw new yii\web\NotFoundHttpException();
        }else{
            $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
            $json_arr = yii\helpers\Json::decode($json);

            if($json_arr['code']=='0000'){
                $product = $json_arr['result']['product'];
                $product['borrowinginfo'] = unserialize($product['borrowinginfo']);
            }else{
                throw new yii\web\NotFoundHttpException();
            }
        }
        return $this->render('uncreditorprofileinfo',['data'=>$product['borrowinginfo']]);
    }

    public function actionEditfinancing(){
        $this->title = "清道夫债管家";
        $this->keywords = "清道夫债管家";
        $this->description = "清道夫债管家";
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');

        setCookie('publishCookieName',md5($token)."editfinancing".$category."_".$id,time()+3600,'/');
        if(Yii::$app->request->isAjax){
            //$category = Yii::$app->request->post('category');
            //$id = Yii::$app->request->post('id');
            $money = Yii::$app->request->post('money');
            $rebate = Yii::$app->request->post('rebate');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat');
            $mortorage_has = Yii::$app->request->post('mortorage_has');
            $mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $progress_status = Yii::$app->request->post('progress_status');
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
           // $district_id = Yii::$app->request->post('district_id');


            //选填信息
            $mortgagecategory = Yii::$app->request->post('mortgagecategory');
            $status = Yii::$app->request->post('status');
            //$mortgagestatus = Yii::$app->request->post('mortgagestatus');
            $rentmoney = Yii::$app->request->post('rentmoney');
            $mortgagearea = Yii::$app->request->post('mortgagearea');
            $loanyear = Yii::$app->request->post('loanyear');
            $obligeeyear = Yii::$app->request->post('obligeeyear');

            $collection = [
                'id'=>$id,
                'money'=>$money,
                'rebate'=>$rebate,
                'rate'=>$rate,
                'category'=>1,
                'rate_cat'=>$rate_cat,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                //'district_id'=>$district_id,
                'mortorage_has'=>$mortorage_has,
                //'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'progress_status'=>$progress_status,
                'mortgagecategory'=>$mortgagecategory,
                'status'=>$status,
                'rentmoney'=>$rentmoney,
                'obligeeyear'=>$obligeeyear,
                'loanyear'=>$loanyear,
                'mortgagearea'=>$mortgagearea,
                'token'=>$token,
            ];
            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/financing'),$collection);
            die;
        }else{
            if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
                throw new yii\web\NotFoundHttpException();
            }else{
                $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
                $json_arr = yii\helpers\Json::decode($json);

                if($json_arr['code']=='0000'){
                    $product = $json_arr['result']['product'];
                }else{
                    throw new yii\web\NotFoundHttpException();
                }
            }

            return $this->render('editfinancing',['data'=>yii\helpers\Json::encode($product)]);
        }

    }

    public function actionEditcollection(){
        $this->title = "清道夫债管家-清收";
        $this->keywords = "清道夫债管家-清收";
        $this->description = "清道夫债管家-清收";
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');
        setCookie('publishCookieName',md5($token)."editcollection".$category."_".$id,time()+3600,'/');
        setCookie('fromWhere',"editcollection",time()+3600,'/');
        if(Yii::$app->request->isAjax){
            $money = Yii::$app->request->post('money');
            $agencycommissiontype = Yii::$app->request->post('agencycommissiontype',1);
			if($agencycommissiontype == 1){
				$agencycommission = Yii::$app->request->post('agencycommissions');
			}else{
				$agencycommission = Yii::$app->request->post('agencycommission');
			}
            
            $loan_type = Yii::$app->request->post('loan_type');
            $carbrand = Yii::$app->request->post('carbrand');
            $audi = Yii::$app->request->post('audi');
            $licenseplate = Yii::$app->request->post('licenseplate');
            $accountr = Yii::$app->request->post('accountr');
            $category = 2;
           // $mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat',1);
            $term = Yii::$app->request->post('term');
            $repaymethod = Yii::$app->request->post('repaymethod');
            $obligor = Yii::$app->request->post('obligor');
            $commitment = Yii::$app->request->post('commitment');
            $commissionperiod = Yii::$app->request->post('commissionperiod');
            $paidmoney = Yii::$app->request->post('paidmoney');
            $interestpaid = Yii::$app->request->post('interestpaid');
            $performancecontract = Yii::$app->request->post('performancecontract');
			$place_province_id  = Yii::$app->request->post('place_province_id');
			$place_city_id      = Yii::$app->request->post('place_city_id');
			$place_district_id  = Yii::$app->request->post('place_district_id');
            $paymethod = Yii::$app->request->post('paymethod');
            $progress_status = Yii::$app->request->post('progress_status');
            $year= Yii::$app->request->post('year').'-'.Yii::$app->request->post('month').'-'.Yii::$app->request->post('day');
            $start = strtotime($year);
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
            $district_id = Yii::$app->request->post('district_id');
            


            $collection = [
                'money'=>$money,
                'id'=>$id,
                'agencycommission'=>$agencycommission,
                'agencycommissiontype'=>$agencycommissiontype,
                'category'=>$category,
                'loan_type'=>$loan_type,
                'carbrand'=>$carbrand,
                'audi'=>$audi,
                'licenseplate'=>$licenseplate,
                'accountr'=>$accountr,
                //'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'rate'=>$rate,
                'rate_cat'=>$rate_cat,
                'term'=>$term,
                'repaymethod'=>$repaymethod,
                'obligor'=>$obligor,
                'commitment'=>$commitment,
                'commissionperiod'=>$commissionperiod,
                'paidmoney'=>$paidmoney,
                'interestpaid'=>$interestpaid,
                'performancecontract'=>$performancecontract,
                'paymethod'=>$paymethod,
                'progress_status'=>$progress_status,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                'district_id'=>$district_id,
                'start' => $start,
				'place_province_id'=>$place_province_id,
				'place_city_id'=>$place_city_id,
				'place_district_id'=>$place_district_id,
                'token'=>$token,
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/creditorcollection'),$collection);
            die;
        }else{
            if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
                throw new yii\web\NotFoundHttpException();
            }else{
                $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
                $json_arr = yii\helpers\Json::decode($json);

                if($json_arr['code']=='0000'){
                    $product = $json_arr['result']['product'];
                    $product['creditorfile'] = '';
                    $product['creditorinfo'] =  '';
                    $product['borrowinginfo'] =  '';
					unset($product['certificationdata']);
                }else{
                    throw new yii\web\NotFoundHttpException();
                }
            }
              return $this->render('editcollection',['data'=>yii\helpers\Json::encode($product)]);
            
        }
    }

    public function actionEditligitation(){
        $this->title = "清道夫债管家-诉讼";
        $this->keywords = "清道夫债管家-诉讼";
        $this->description = "清道夫债管家-诉讼";
        $id = Yii::$app->request->get('id');
        $category = Yii::$app->request->get('category');
        $token = Yii::$app->session->get('user_token');
        setCookie('publishCookieName',md5($token)."editligitation".$category."_".$id,time()+3600,'/');
        setCookie('fromWhere',"editligitation",time()+3600,'/');

        if(Yii::$app->request->isAjax){
            $money = Yii::$app->request->post('money');
            $agencycommissiontype = Yii::$app->request->post('agencycommissiontype');
            if($agencycommissiontype == 1){
				$agencycommission = Yii::$app->request->post('agencycommission');
			}else{
				$agencycommission = Yii::$app->request->post('agencycommissions');
			}
            $loan_type = Yii::$app->request->post('loan_type');/*
            $guaranteemethod = Yii::$app->request->post('guaranteemethod');
            $other = Yii::$app->request->post('other');
            $guaranteemethod = is_array($guaranteemethod)?$guaranteemethod:[];
            if($other== '' && empty($guaranteemethod )){
                $guaranteemethod = '';
            }else{
                $guaranteemethod = serialize(array_merge($guaranteemethod,['other'=>$other]));
            }*/
            $carbrand = Yii::$app->request->post('carbrand');
            $audi = Yii::$app->request->post('audi');
            $licenseplate = Yii::$app->request->post('licenseplate');
            $accountr = Yii::$app->request->post('accountr');
            $category = 3;
            $mortorage_has = Yii::$app->request->post('mortorage_has');
            //$mortorage_community = Yii::$app->request->post('mortorage_community');
            $seatmortgage = Yii::$app->request->post('seatmortgage');
            $rate = Yii::$app->request->post('rate');
            $rate_cat = Yii::$app->request->post('rate_cat',1);
            $term = Yii::$app->request->post('term');
            $repaymethod = Yii::$app->request->post('repaymethod');
            $obligor = Yii::$app->request->post('obligor');
            $commitment = Yii::$app->request->post('commitment');
            $commissionperiod = Yii::$app->request->post('commissionperiod');
            $paidmoney = Yii::$app->request->post('paidmoney');
            $interestpaid = Yii::$app->request->post('interestpaid');
            $performancecontract = Yii::$app->request->post('performancecontract');
			$place_province_id  = Yii::$app->request->post('place_province_id');
			$place_city_id      = Yii::$app->request->post('place_city_id');
			$place_district_id  = Yii::$app->request->post('place_district_id');
            $paymethod = Yii::$app->request->post('paymethod');
            $progress_status = Yii::$app->request->post('progress_status');
            $year= Yii::$app->request->post('year').'-'.Yii::$app->request->post('month').'-'.Yii::$app->request->post('day');
            $start = strtotime($year);
            $province_id = Yii::$app->request->post('province_id');
            $city_id = Yii::$app->request->post('city_id');
            $district_id = Yii::$app->request->post('district_id');


            $collection = [
                'id'=>$id,
                'money'=>$money,
                'agencycommission'=>$agencycommission,
                'agencycommissiontype'=>$agencycommissiontype,
                'category'=>$category,
                'loan_type'=>$loan_type,
                'carbrand'=>$carbrand,
                'audi'=>$audi,
                'licenseplate'=>$licenseplate,
                'accountr'=>$accountr,
                'mortorage_has'=>$mortorage_has,
                //'mortorage_community'=>$mortorage_community,
                'seatmortgage'=>$seatmortgage,
                'rate'=>$rate,
                'rate_cat'=>$rate_cat,
                'term'=>$term,
                'repaymethod'=>$repaymethod,
                'obligor'=>$obligor,
                'commitment'=>$commitment,
                'commissionperiod'=>$commissionperiod,
                'paidmoney'=>$paidmoney,
                'interestpaid'=>$interestpaid,
                'performancecontract'=>$performancecontract,
                'paymethod'=>$paymethod,
                'progress_status'=>$progress_status,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                'district_id'=>$district_id,
                'start' => $start,
				'place_province_id'=>$place_province_id,
				'place_city_id'=>$place_city_id,
				'place_district_id'=>$place_district_id,
                'token'=>$token,
            ];

            echo Func::CurlPost(yii\helpers\Url::toRoute('/wap/publish/creditorcollection'),$collection);
            die;
        }else{
            if(!Func::isInt($id)||!yii\helpers\ArrayHelper::isIn($category,[1,2,3])){
                throw new yii\web\NotFoundHttpException();
            }else{
                $json = Func::CurlPost('/wap/public/wxreleaseinformationwidget',['id'=>$id,'category'=>$category,'token'=>$token]);
                $json_arr = yii\helpers\Json::decode($json);

                if($json_arr['code']=='0000'){
                    $product = $json_arr['result']['product'];
                    $product['guaranteemethod'] = is_numeric($product['guaranteemethod'])?$product['guaranteemethod']:unserialize($product['guaranteemethod']);
                    $product['creditorfile'] = '';
                    $product['creditorinfo'] =  '';
                    $product['borrowinginfo'] =  '';
					unset($product['certificationdata']);
                }else{
                    throw new yii\web\NotFoundHttpException();
                }
            }

              return $this->render('editligitation',['data'=>yii\helpers\Json::encode($product)]);
        }
    }
    
    
    public function actionGetcity(){
        if(Yii::$app->request->isAjax){
            $pid = Yii::$app->request->post('province_id');

            $citys = Func::getCityByProvince($pid);

            $str = "";

            foreach($citys as $k=>$v){
                $str .= "<option value='{$k}'>{$v}</option>";
            }

            echo $str;
            die;
        }
    }
    //根据城市获取区域
    public function actionGetdistrict(){
        if(Yii::$app->request->isAjax){
            $pid = Yii::$app->request->post('city_id');

            $districts = Func::getDistrictByCity($pid);

            $str = "";

            foreach($districts as $k=>$v){
                $str .= "<option value='{$k}'>{$v}</option>";
            }

            echo $str;
            die;
        }
    }
	
	public function actionTimedata(){
		if(Yii::$app->request->isAjax){
			$day = Yii::$app->request->post('data');
			$html = \yii\helpers\Html::dropDownList('', '',\common\models\CreditorProduct::getDay($day));
			$html = str_replace("</select>","",$html);
			$html = trim(str_replace('<select name="">',"",$html));
			echo yii\helpers\Json::encode(['code'=>'0000','html'=>$html]);die;
		}
	}
}
