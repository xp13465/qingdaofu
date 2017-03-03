<?php

namespace frontend\modules\wap\controllers;

use common\models\CreditorProduct;
use common\models\FinanceProduct;
use common\models\User;
use frontend\modules\wap\components\WapController;
use \frontend\modules\wap\services\Func;
use yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * 用户操作控制器
 */
class PublishController extends WapController
{
    /**
     *
     */
    public function actionFinancing(){
        //必填信息
        $id = Yii::$app->request->post('id');
        $money = Yii::$app->request->post('money');
        $rebate = Yii::$app->request->post('rebate');
        $rate = Yii::$app->request->post('rate');
        $category = Yii::$app->request->post('category');
        $term = Yii::$app->request->post('term');
        $rate_cat = Yii::$app->request->post('rate_cat');
        $mortorage_has = Yii::$app->request->post('mortorage_has');
        $mortorage_community = Yii::$app->request->post('mortorage_community');
        $seatmortgage = Yii::$app->request->post('seatmortgage');
        $progress_status = Yii::$app->request->post('progress_status');
        $province_id = Yii::$app->request->post('province_id');
        $city_id = Yii::$app->request->post('city_id');
        //$district_id = Yii::$app->request->post('district_id');

        //选填信息
        $mortgagecategory = Yii::$app->request->post('mortgagecategory');
        $status = Yii::$app->request->post('status');
        //$mortgagestatus = Yii::$app->request->post('mortgagestatus');
        $rentmoney = Yii::$app->request->post('rentmoney');
        $mortgagearea = Yii::$app->request->post('mortgagearea');
        $loanyear = Yii::$app->request->post('loanyear');
        $obligeeyear = Yii::$app->request->post('obligeeyear');

        //必填项
        if($category != 1){
            echo Json::encode(['code'=>'1008','msg'=>'产品类型必须为1']);die;
        }
        if(trim($money)==''||!Func::isDecimal($money)){
            echo Json::encode(['code'=>'1001','msg'=>'金额必须是实数']);die;
        }
        if(trim($rebate)==''||!Func::isDecimal($rebate)){
            echo Json::encode(['code'=>'1002','msg'=>'返点必须是实数']);die;
        }
        if(trim($rate)==''||!Func::isDecimal($rate)){
            echo Json::encode(['code'=>'1003','msg'=>'利率必须是实数']);die;
        }
        if(trim($rate_cat)==''|| !Func::isInt($rate_cat)||!ArrayHelper::keyExists($rate_cat,CreditorProduct::$ratedatecategory)){
            echo Json::encode(['code'=>'1004','msg'=>'利率单位为天或者月']);die;
        }
        if(trim($progress_status)==''||!Func::isInt($progress_status)||!ArrayHelper::isIn($progress_status,[1,0])){
            echo Json::encode(['code'=>'1007','msg'=>'产品进度不正确']);die;
        }
        if(trim($mortorage_community)==''){
            echo Json::encode(['code'=>'1005','msg'=>'抵押物小区名不可为空']);die;
        }
        if(trim($seatmortgage)==''){
            echo Json::encode(['code'=>'1005','msg'=>'抵押物地址不可为空']);die;
        }


        if(trim($mortgagecategory) && (!Func::isInt($mortgagecategory)||!ArrayHelper::keyExists($mortgagecategory,FinanceProduct::$mortgagecategory))){
            echo Json::encode(['code'=>'1010','msg'=>'请选择抵押物类型']);die;
        }
        if(trim($status) && (!Func::isInt($status)||!ArrayHelper::keyExists($status,FinanceProduct::$status))){
            echo Json::encode(['code'=>'1010','msg'=>'请选择状态']);die;
        }
        if(trim($rentmoney)&&!Func::isDecimal($rentmoney)){
            echo Json::encode(['code'=>'1012','msg'=>'租金为实数']);die;
        }
        if(trim($mortgagearea)&&!Func::isDecimal($mortgagearea)){
            echo Json::encode(['code'=>'1012','msg'=>'抵押物面积为实数']);die;
        }
        if(trim($loanyear)&&!Func::isInt($loanyear)){
            echo Json::encode(['code'=>'1012','msg'=>'借款人年龄为整数']);die;
        }
        if(trim($obligeeyear) && (!Func::isInt($obligeeyear)||!ArrayHelper::keyExists($obligeeyear,FinanceProduct::$obligeeyear))){
            echo Json::encode(['code'=>'1010','msg'=>'请选择权利人年龄']);die;
        }

        if($province_id)$province_id = Yii::$app->db->createCommand("select provinceID from zcb_province where province = '{$province_id}'")->queryScalar();
        if($city_id)$city_id = Yii::$app->db->createCommand("select cityID from zcb_city where city = '{$city_id}' and fatherID = '{$province_id}'")->queryScalar();
        if($district_id)$district_id = Yii::$app->db->createCommand("select areaID from zcb_area where area = '{$district_id}' and fatherID = '{$city_id}'")->queryScalar();

        if(Func::isInt($id)&&ArrayHelper::isIn($category,[1,2,3])){
            $model_old = Func::getProduct($category,$id,$this->user->id);

        }

        $model= isset($model_old)&&$model_old?$model_old:new FinanceProduct();
        $model->category = $category;
        $model->code = $model->isNewRecord?Func::createCatCode($category):$model->code;
        $model->money = $money;
        $model->rebate = $rebate;
        $model->term = $term;
        $model->rate = $rate;
        $model->rate_cat = $rate_cat;
        $model->mortorage_has = $mortorage_has;
        $model->mortorage_community = $mortorage_community;
        $model->seatmortgage = $seatmortgage;
        $model->mortgagecategory = $mortgagecategory;
        $model->status = $status;
        $model->rentmoney = $rentmoney;
        $model->mortgagearea = $mortgagearea;
        $model->loanyear = $loanyear;
        $model->obligeeyear = $obligeeyear;
        $model->is_del = 0;
        $model->province_id = $province_id?$province_id:0;
        $model->city_id = $city_id?$city_id:0;
        $model->district_id = $district_id?$district_id:0;
        $model->progress_status = $progress_status;
        $model->create_time = $model->isNewRecord?time():$model->create_time;
        $model->modify_time = time();
        $model->uid = $this->user->id;

        $s = $model->save();
        if($s){
            Func::addMessagesPerType('融资发布',"融资编号:".$model->code.",发布信息越详细，处置效率越高噢。",2,serialize(['id' => $model->id,'category' => $model->category]));
            echo Json::encode(['code'=>'0000','msg'=>'插入或者保存数据成功']);die;
        }else{
            echo Json::encode(['code'=>'4001','msg'=>$model->errors]);die;
        }
    }

    public function actionEditcollection(){
        $id =  Yii::$app->request->post('id');
        $category =  Yii::$app->request->post('category');
        $uid = $this->user->id;
        if(Func::isInt($id)&&ArrayHelper::isIn($category,[1,2,3])){
            $model = Func::getProduct($category,$id,$uid);
        }
        if(!$model||!is_object($model)){
            echo Json::encode(['code'=>'4001','msg'=>'你无权限或者参数错误']);
        }

        if(Yii::$app->request->post('creditorinfos')){
            $creditorinfo = Func::getDataprocessings(Yii::$app->request->post('creditorinfos'));
            $creditorinfo = serialize($creditorinfo);
        }else if(Yii::$app->request->post('creditorinfoss')){
            $creditorinfo = Func::getDataprocessing(Yii::$app->request->post('creditorinfoss'));
            $creditorinfo = serialize($creditorinfo);
        }else{
            $creditorinfo = Yii::$app->request->post('creditorinfo');
        }

        if(Yii::$app->request->post('borrowinginfos')){
            $borrowinginfo = Func::getDataprocessings(Yii::$app->request->post('borrowinginfos'));
            $borrowinginfo = serialize($borrowinginfo);
        }else if(Yii::$app->request->post('borrowinginfoss')){
            $borrowinginfo = Func::getDataprocessing(Yii::$app->request->post('borrowinginfoss'));
            $borrowinginfo = serialize($borrowinginfo);
        }else{
            $borrowinginfo = Yii::$app->request->post('borrowinginfo');
        }

        if(Yii::$app->request->post('imgnotarizations') || Yii::$app->request->post('imgcontracts') || Yii::$app->request->post('imgcreditors')||Yii::$app->request->post('imgpicks')||Yii::$app->request->post('imgbenjins')||Yii::$app->request->post('imgshoujus')){
            $imgnotarization = Yii::$app->request->post('imgnotarizations')?Func::getCreditor(Yii::$app->request->post('imgnotarizations')):'';
            $imgcontract     = Yii::$app->request->post('imgcontracts')?Func::getCreditor(Yii::$app->request->post('imgcontracts')):'';
            $imgcreditor     = Yii::$app->request->post('imgcreditors')?Func::getCreditor(Yii::$app->request->post('imgcreditors')):'';
            $imgpick         = Yii::$app->request->post('imgpicks')?Func::getCreditor(Yii::$app->request->post('imgpicks')):'';
            $imgbenjin       = Yii::$app->request->post('imgbenjins')?Func::getCreditor(Yii::$app->request->post('imgbenjins')):'';
            $imgshouju       = Yii::$app->request->post('imgshoujus')?Func::getCreditor(Yii::$app->request->post('imgshoujus')):'';
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
        }else if(Yii::$app->request->post('imgnotarizationss') || Yii::$app->request->post('imgcontractss') || Yii::$app->request->post('imgcreditorss')||Yii::$app->request->post('imgpickss')||Yii::$app->request->post('imgbenjinss')||Yii::$app->request->post('imgshoujuss')){
            $imgnotarization = Yii::$app->request->post('imgnotarizationss')?Func::getCreditors(Yii::$app->request->post('imgnotarizationss')):'';
            $imgcontract     = Yii::$app->request->post('imgcontractss')?Func::getCreditors(Yii::$app->request->post('imgcontractss')):'';
            $imgcreditor     = Yii::$app->request->post('imgcreditorss')?Func::getCreditors(Yii::$app->request->post('imgcreditorss')):'';
            $imgpick         = Yii::$app->request->post('imgpickss')?Func::getCreditors(Yii::$app->request->post('imgpickss')):'';
            $imgbenjin       = Yii::$app->request->post('imgbenjinss')?Func::getCreditors(Yii::$app->request->post('imgbenjinss')):'';
            $imgshouju       = Yii::$app->request->post('imgshoujuss')?Func::getCreditors(Yii::$app->request->post('imgshoujuss')):'';
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
        }else{
            $creditorfile = Yii::$app->request->post('creditorfile');
        }



        $model->creditorfile = $creditorfile;
        $model->creditorinfo = $creditorinfo?$creditorinfo:$model->creditorinfo;
        $model->borrowinginfo = $borrowinginfo?$borrowinginfo:$model->borrowinginfo;

        $model->modify_time = time();
        $s = $model->save();

        if($s){
            echo Json::encode(['code'=>'0000','msg'=>'插入或者保存数据成功']);die;
        }else{
            echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
        }
    }

    public function actionCreditorcollection(){
        $id = Yii::$app->request->post('id');
        $money = Yii::$app->request->post('money');
        $category = Yii::$app->request->post('category');
        $agencycommissiontype = Yii::$app->request->post('agencycommissiontype');
        $agencycommission = Yii::$app->request->post('agencycommission');
        $loan_type = Yii::$app->request->post('loan_type');
        //$mortorage_community = Yii::$app->request->post('mortorage_community');
        $seatmortgage = Yii::$app->request->post('seatmortgage');
        $carbrand = Yii::$app->request->post('carbrand');
        $audi = Yii::$app->request->post('audi');
        $licenseplate = Yii::$app->request->post('licenseplate');
        $accountr = Yii::$app->request->post('accountr');
        $rate = Yii::$app->request->post('rate');
        $rate_cat = Yii::$app->request->post('rate_cat');
        $term = Yii::$app->request->post('term');
        $repaymethod = Yii::$app->request->post('repaymethod');
        $obligor = Yii::$app->request->post('obligor');
        $commitment = Yii::$app->request->post('commitment');
        $commissionperiod = Yii::$app->request->post('commissionperiod');
        $paidmoney = Yii::$app->request->post('paidmoney');
        $interestpaid = Yii::$app->request->post('interestpaid');
        $performancecontract = Yii::$app->request->post('performancecontract');
		$place_province_id   = Yii::$app->request->post('place_province_id');
		$place_city_id       = Yii::$app->request->post('place_city_id');
		$place_district_id   = Yii::$app->request->post('place_district_id');
        $paymethod = Yii::$app->request->post('paymethod');
        $progress_status = Yii::$app->request->post('progress_status');
        $province_id = Yii::$app->request->post('province_id');
        $city_id = Yii::$app->request->post('city_id');
        $district_id = Yii::$app->request->post('district_id');
        $start = Yii::$app->request->post('start')?Yii::$app->request->post('start'):'0';
        $end   = Yii::$app->request->post('end')?Yii::$app->request->post('end'):'0';
        //ios债权文件
        if(Yii::$app->request->post('imgnotarizations') || Yii::$app->request->post('imgcontracts') || Yii::$app->request->post('imgcreditors')||Yii::$app->request->post('imgpicks')||Yii::$app->request->post('imgbenjins')||Yii::$app->request->post('imgshoujus')){
            $imgnotarization = Yii::$app->request->post('imgnotarizations')?Func::getCreditor(Yii::$app->request->post('imgnotarizations')):'';
            $imgcontract     = Yii::$app->request->post('imgcontracts')?Func::getCreditor(Yii::$app->request->post('imgcontracts')):'';
            $imgcreditor     = Yii::$app->request->post('imgcreditors')?Func::getCreditor(Yii::$app->request->post('imgcreditors')):'';
            $imgpick         = Yii::$app->request->post('imgpicks')?Func::getCreditor(Yii::$app->request->post('imgpicks')):'';
            $imgbenjin       = Yii::$app->request->post('imgbenjins')?Func::getCreditor(Yii::$app->request->post('imgbenjins')):'';
            $imgshouju       = Yii::$app->request->post('imgshoujus')?Func::getCreditor(Yii::$app->request->post('imgshoujus')):'';
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
        }else if(Yii::$app->request->post('imgnotarizationss') || Yii::$app->request->post('imgcontractss') || Yii::$app->request->post('imgcreditorss')||Yii::$app->request->post('imgpickss')||Yii::$app->request->post('imgbenjinss')||Yii::$app->request->post('imgshoujuss')){
            $imgnotarization = Yii::$app->request->post('imgnotarizationss')?Func::getCreditors(Yii::$app->request->post('imgnotarizationss')):'';
            $imgcontract     = Yii::$app->request->post('imgcontractss')?Func::getCreditors(Yii::$app->request->post('imgcontractss')):'';
            $imgcreditor     = Yii::$app->request->post('imgcreditorss')?Func::getCreditors(Yii::$app->request->post('imgcreditorss')):'';
            $imgpick         = Yii::$app->request->post('imgpickss')?Func::getCreditors(Yii::$app->request->post('imgpickss')):'';
            $imgbenjin       = Yii::$app->request->post('imgbenjinss')?Func::getCreditors(Yii::$app->request->post('imgbenjinss')):'';
            $imgshouju       = Yii::$app->request->post('imgshoujuss')?Func::getCreditors(Yii::$app->request->post('imgshoujuss')):'';
            $creditorfile = serialize([
                'imgnotarization' => $imgnotarization ,
                'imgcontract' => $imgcontract ,
                'imgcreditor' => $imgcreditor ,
                'imgpick' => $imgpick ,
                'imgshouju' => $imgbenjin ,
                'imgbenjin' => $imgshouju,
            ]);
        }else{
            $creditorfile = Yii::$app->request->post('creditorfile');
        }


        if(Yii::$app->request->post('creditorinfos')){
            $creditorinfo = Func::getDataprocessings(Yii::$app->request->post('creditorinfos'));
            $creditorinfo = serialize($creditorinfo);
        }else if(Yii::$app->request->post('creditorinfoss')){
            $creditorinfo = Func::getDataprocessing(Yii::$app->request->post('creditorinfoss'));
            $creditorinfo = serialize($creditorinfo);
        }else{
            $creditorinfo = Yii::$app->request->post('creditorinfo');
        }

        if(Yii::$app->request->post('borrowinginfos')){
            $borrowinginfo = Func::getDataprocessings(Yii::$app->request->post('borrowinginfos'));
            $borrowinginfo = serialize($borrowinginfo);
        }else if(Yii::$app->request->post('borrowinginfoss')){
            $borrowinginfo = Func::getDataprocessing(Yii::$app->request->post('borrowinginfoss'));
            $borrowinginfo = serialize($borrowinginfo);
        }else{
            $borrowinginfo = Yii::$app->request->post('borrowinginfo');
        }
        //必填项
        if(trim($money)==''||!Func::isDecimal($money)){
            echo Json::encode(['code'=>'1001','msg'=>'金额必须是实数']);die;
        }
     
        if (trim($agencycommissiontype) == '') {
                echo Json::encode(['code' => '1014', 'msg' => '请选择费用类型']);
                die;
            }else if(!Func::isInt($agencycommissiontype) || !ArrayHelper::isIn($agencycommissiontype, [1, 2])){
				echo Json::encode(['code' => '1014', 'msg' => '费用类型不正确']);
                die;
			}
	if($category == 2) {
			if(trim($agencycommissiontype)==1){
				if(trim($agencycommission)==''){
                   echo Json::encode(['code'=>'1002','msg'=>'服务佣金不能为空']);die;
              }else if(!Func::isDecimal($agencycommission)){
				  echo Json::encode(['code'=>'1002','msg'=>'服务佣金必须为实数']);die;
			  }
			}else{
				if(trim($agencycommission)==''){
                   echo Json::encode(['code'=>'1002','msg'=>'固定费用不能为空']);die;
              }else if(!Func::isDecimal($agencycommission)){
				  echo Json::encode(['code'=>'1002','msg'=>'固定费用必须为实数']);die;
			  }
			}

        }else{
			if(trim($agencycommissiontype)==1){
				if(trim($agencycommission)==''){
                   echo Json::encode(['code'=>'1002','msg'=>'固定费用不能为空']);die;
              }else if(!Func::isDecimal($agencycommission)){
				  echo Json::encode(['code'=>'1002','msg'=>'固定费用必须为实数']);die;
			  }
			}else{
				if(trim($agencycommission)==''){
                   echo Json::encode(['code'=>'1002','msg'=>'风险费率不能为空']);die;
              }else if(!Func::isDecimal($agencycommission)){
				  echo Json::encode(['code'=>'1002','msg'=>'风险费率必须为实数']);die;
			  }
			}
		}
        
        if(trim($loan_type)==''){
            echo Json::encode(['code'=>'1003','msg'=>'请选择债权类型']);die;
        }else if(!Func::isInt($loan_type)||!ArrayHelper::isIn($loan_type,[1,2,3,4])){
			echo Json::encode(['code'=>'1003','msg'=>'债权类型不正确']);die;
		}
        switch($loan_type){
            case 1:
                if(trim($province_id)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'请选择您所在的省份']);die;
                }
                if(trim($city_id)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'请选择您所在的城市']);die;
                }
                if(trim($district_id)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'请选择您所在的区域']);die;
                }
                if(trim($seatmortgage)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'抵押物地址不可为空']);die;
                }
                break;
            case 3:
                if(trim($carbrand)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'车品牌不可为空']);die;
                }
                if(trim($audi)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'车系不可为空']);die;
                }
                if(trim($licenseplate)==''){
                    echo Json::encode(['code'=>'1005','msg'=>'车牌类型不可为空']);die;
                }
                break;
            case 2:
                    if(trim($accountr)==''){
                        echo Json::encode(['code'=>'1005','msg'=>'应收账款不可为空']);die;
                    }
                break;
        }


        if(trim($progress_status)==''||!Func::isInt($progress_status)||!ArrayHelper::isIn($progress_status,[1,0])){
            echo Json::encode(['code'=>'1012','msg'=>'产品进度不正确']);die;
        }
        if(trim($category)==''||!Func::isInt($category)||!ArrayHelper::isIn($category,[3,2])){
            echo Json::encode(['code'=>'1014','msg'=>'产品类型不正确']);die;
        }
        if(trim($term)&&!Func::isInt($term)){
            echo Json::encode(['code'=>'1013','msg'=>'借款期限不正确']);die;
        }
        if(trim($rate_cat)&&( !Func::isInt($rate_cat)||!ArrayHelper::keyExists($rate_cat,CreditorProduct::$ratedatecategory))){
            echo Json::encode(['code'=>'1007','msg'=>'借款单位为天或者月']);die;
        }
        //选填项
        if(trim($rate)&&!Func::isDecimal($rate)){
            echo Json::encode(['code'=>'1006','msg'=>'借款利率为实数']);die;
        }

        if(trim($repaymethod)&& (!Func::isInt($repaymethod)||!ArrayHelper::keyExists($repaymethod,CreditorProduct::$repaymethod))){
            echo Json::encode(['code'=>'1008','msg'=>'请选择还款方式']);die;
        }
        if(trim($obligor)&& (!Func::isInt($obligor)||!ArrayHelper::keyExists($obligor,CreditorProduct::$obligor))){
            echo Json::encode(['code'=>'1009','msg'=>'请选择债务人主体']);die;
        }
        if(trim($commitment)&& (!Func::isInt($commitment)||!ArrayHelper::keyExists($commitment,CreditorProduct::$commitment))){
            echo Json::encode(['code'=>'1010','msg'=>'请选择委托事项']);die;
        }
        if(trim($commissionperiod)&& (!Func::isInt($commissionperiod)||!ArrayHelper::keyExists($commissionperiod,CreditorProduct::$commissionperiod))){
            echo Json::encode(['code'=>'1011','msg'=>'请选择委托代理期限(月)']);die;
        }
        if(trim($paidmoney)&&!Func::isDecimal($paidmoney)){
            echo Json::encode(['code'=>'1012','msg'=>'已付本金为实数']);die;
        }
        if(trim($interestpaid)&&!Func::isDecimal($interestpaid)){
            echo Json::encode(['code'=>'1013','msg'=>'已付利息为实数']);die;
        }

        if(trim($paymethod)&& (!Func::isInt($paymethod)||!ArrayHelper::keyExists($paymethod,CreditorProduct::$paymethod))){
            echo Json::encode(['code'=>'1011','msg'=>'请选择付款方式']);die;
        }

       // if($province_id)$province_id = Yii::$app->db->createCommand("select provinceID from zcb_province where province = '{$province_id}'")->queryScalar();
        //if($city_id)$city_id = Yii::$app->db->createCommand("select cityID from zcb_city where city = '{$city_id}' and fatherID = '{$province_id}'")->queryScalar();
       // if($district_id)$district_id = Yii::$app->db->createCommand("select areaID from zcb_area where area = '{$district_id}' and fatherID = '{$city_id}'")->queryScalar();

        if(Func::isInt($id)&&ArrayHelper::isIn($category,[1,2,3])){
            $model_old = Func::getProduct($category,$id,$this->user->id);
        }
        $model= isset($model_old)&&$model_old?$model_old:new CreditorProduct();
        $model->code = $model->isNewRecord?Func::createCatCode($category):$model->code;
        $model->money = $money;
        $model->agencycommission = $agencycommission;
        $model->agencycommissiontype = $agencycommissiontype;
        $model->loan_type = $loan_type;
        switch($loan_type){
            case 1:
            //    $model->mortorage_community = $mortorage_community;
                $model->seatmortgage = $seatmortgage;

                break;
            case 3:

                $model->carbrand = $carbrand;
                $model->audi = $audi;
                $model->licenseplate = $licenseplate;

                break;
            case 2:
                $model->accountr = $accountr;

                break;
        }
        $model->rate = $rate;
        $model->rate_cat = $rate_cat;
        $model->term = $term;
        $model->repaymethod = $repaymethod;
        $model->obligor = $obligor;
        $model->commitment = $commitment;
        $model->commissionperiod = $commissionperiod;
        $model->paidmoney = $paidmoney;
        $model->interestpaid = $interestpaid;
        $model->performancecontract = $performancecontract;
        $model->paymethod = $paymethod;
        $model->progress_status = $progress_status;
        $model->creditorfile = $creditorfile;
        $model->creditorinfo = $creditorinfo?$creditorinfo:$model->creditorinfo;
        $model->borrowinginfo = $borrowinginfo?$borrowinginfo:$model->borrowinginfo;
        $model->province_id = $province_id?$province_id:0;
        $model->city_id = $city_id?$city_id:0;
        $model->district_id = $district_id?$district_id:0;
        $model->category = $category;
        $model->is_del = 0;
        $model->start = $start;
        $model->create_time = $model->isNewRecord?time():$model->create_time;
        $model->modify_time = time();
        $model->uid = $this->user->id;
		$model->place_province_id = $place_province_id;
		$model->place_city_id = $place_city_id;
		$model->place_district_id = $place_district_id;
     //   var_dump($model);die;
        //echo Json::encode(['code'=>'1014','msg'=>$model.'--'.$rate]);die;
        $s = $model->save();

        if($s){
            if($model->category == 2)Func::addMessagesPerType('清收发布',"清收编号:".$model->code.",发布信息越详细，处置效率越高噢。",2,serialize(['id' => $model->id,'category' => $model->category]));
            else Func::addMessagesPerType('诉讼发布',"诉讼编号:".$model->code.",发布信息越详细，处置效率越高噢。",2,serialize(['id' => $model->id,'category' => $model->category]));
            echo Json::encode(['code'=>'0000','msg'=>'插入或者保存数据成功']);die;
        }else{
            echo Json::encode(['code'=>'1014','msg'=>$model->errors]);die;
        }
    }

}
