<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/13
 * Time: 14:22
 */
namespace frontend\widget;

use frontend\services\Func;
use yii;
use yii\base;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class HomepagerecommendWidget extends Widget{
    public $products;

    public function init(){
        parent::init();
		$sql = "
				select a.id ,a.category ,a.modify_time,a.create_time,a.uid,b.pid from zcb_finance_product a left join zcb_user b on b.id = a.uid  where progress_status != 0 
				union 
				select a.id ,a.category ,a.modify_time,a.create_time,a.uid,b.pid from zcb_creditor_product a left join zcb_user b on b.id = a.uid where progress_status != 0 order by create_time desc limit  8
		";
        // $finance = Yii::$app->db->createCommand("select id ,category ,modify_time from zcb_finance_product   where progress_status != 0 union select id ,category,modify_time from zcb_creditor_product  where progress_status != 0 order by modify_time desc limit 6 ")->queryAll();
        $finance = Yii::$app->db->createCommand($sql)->queryAll();
        $this->products = $finance;
    }

    public function run(){
        return $this->render('homepagerecommend',['products'=>$this->products]);
    }
}