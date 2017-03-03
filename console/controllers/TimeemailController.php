<?php
namespace console\controllers;

use services\FuncServices;
use yii;
use yii\console\Controller;

class TimeemailController extends Controller
{
    public function actionNonpickup()
    {
        $wherel = ' 1 and ap.app_id is null  and progress_status = 1 and zfp.create_time < '.(time()-86400).'';
        $wherer = ' 1 and ap.app_id is null  and progress_status = 1 and  zcp.create_time < '.(time()-86400).'';
        $sql ="select zfp.id as id,province_id,zfp.category as category,zfp.city_id,zfp.district_id,zfp.seatmortgage,zfp.uid,code,zfp.create_time,zfp.modify_time,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id
           from zcb_finance_product zfp left JOIN zcb_apply as ap  on (ap.product_id = zfp.id)
           where {$wherel}  and ap.category  = 1 union
           select zcp.id as id, province_id,zcp.category as category,zcp.city_id,zcp.district_id,zcp.seatmortgage,zcp.uid,code,zcp.create_time,zcp.modify_time,money,progress_status,ap.uid as fuid,ap.category,applyclose,applyclosefrom,ap.app_id
           from zcb_creditor_product zcp left JOIN zcb_apply as ap  on (ap.product_id = zcp.id)
           where {$wherer}  and ap.category in(2,3)  order by case app_id when 2 then 1 when 0 then 2 else 3 end ,progress_status asc, modify_time desc";

        $data = \Yii::$app->db->createCommand($sql)->queryAll();

        $str = $this->render('nonpickup',['data'=>$data]);

        FuncServices::sendMail('未接单(超过24h邮件给我，每天下午16:00)',$str,Yii::$app->params['adminEmail']);
    }

    public function actionPublish(){
        $where = " progress_status > 0 and create_time between ".(time()-86400)." and ".time();
        $sql = "select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom from zcb_finance_product where {$where} union
               select id,uid,seatmortgage,province_id,city_id,district_id,category,code,create_time,modify_time,money,progress_status,applyclose,applyclosefrom from zcb_creditor_product where {$where} order by progress_status asc,modify_time desc";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $str = $this->render('publish',['data'=>$data]);
        FuncServices::sendMail('发布邮件(每天15:00)',$str,Yii::$app->params['adminEmail']);
    }

    //注册邮件发送：
    public function actionRegistered(){
       $where = "created_at between ".(time()-86400)." and ".time();
       $sql = " select username ,id , mobile , created_at ,pid from zcb_user where {$where}";
       $data = \Yii::$app->db->createCommand($sql)->queryAll();
       $registered = $this->render('registered', ['data' => $data]);
       FuncServices::sendMail('发布邮件(每天17:00)', $registered, Yii::$app->params['adminEmail']);
    }
}