<?php

namespace backend\controllers;
use Yii;
use app\models\Property;
use yii\data\Pagination;
use app\models\PropertySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\BackController;
 
use common\models\Areas;
use common\models\Cdcon;
use common\models\Express;
use common\models\Openid;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class PropertyController extends BackController
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
     * Lists all Property models.
     * @return mixed
     */
    public function actionScriptLocal($offset=0,$limit=500,$auto="0",$type=1)
    {
       (new Property)->scriptLocal($offset,$limit,$auto,$type);
    }
	
	
    /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Property model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$data = $this->findModel($id);
		$result = $data->cid?Cdcon::find()->asArray()->where(['cid'=>$data->cid])->one():[];
		
        return $this->render('view', [
            'model' => $data,
            'result' => $result,
        ]);
    }

    /**
     * Updates an existing Property model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	//仟房
	public function actionSetqf(){
		$id = Yii::$app->request->post('id');
		$data = Property::findOne($id);
		if($id && $data){
			if($data->lock =='1'){
				$info['status'] = 0;
				$info['info'] = '仟房正在操作中。。。请稍后再试！';
				return json_encode($info);
			}
			if($data->status !='-1'){
				$info['status'] = 0;
				$info['info'] = '已操作过，请刷新后重试！';
				return json_encode($info);
			}
			$do = $data->updateAll(['lock'=>'1'],["id"=>$data->id,'lock'=>'0','status'=>'-1']);
			
			if($do==0){
				$info['status'] = 0;
				$info['info'] = '操作失败，请刷新后重试！';
				return json_encode($info);
			}
			
			$areas = Areas::findOne($data['city']);
			$address = $data['address'];
			if($data['name']){
				$address .="(产权人姓名：".$data['name'].")";
			}
			$area = $areas['name'];
			$timestamp = time();
			 
			$appid = '9f1727c84a';
			$token = '9efa016b0b46ef9e0dab906931cbca19';
			$parm = 'address='.str_replace(' ','',$address).'&appid=9f1727c84a&area='.$area.'&mobile=15021180031&timestamp='.$timestamp;
			$sign = strtolower(md5($parm.$token));
			$url = 'http://www.1001fang.com/cdapi/submit?'.$parm.'&sign='.$sign;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output  = curl_exec($ch);
			curl_close($ch);
			$con = json_decode($output,true); 
			
			if($con){
				if($con['code'] == 1){
					$data->cid = $con['response']['id'];
					$data->status = 1;
					$data->lock = '0';
					$data->save();
					$info['status'] = 1;
					$info['info'] = '仟房申请成功';
				}else{
					$data->lock = '0';
					$data->save();
					$info['info'] = $con['error'];
					$info['appid'] = $appid;
					$info['url'] = $url;
					$info['status'] = 0;
					$info['info'] = '仟房申请失败，请联系管理员！';
				}
			}else{
				$data->lock = '0';
				$data->save();
				$info['status'] = 0;
				$info['info'] = '仟房接口调用失败，请联系管理员！';
			}
		}else{
			$info['status'] = 0;
			$info['info'] = '数据有误...';
		}
		return json_encode($info);
	}
	//本地
	public function actionSetbd(){
		$this->header = "本地产调";
		$od = Yii::$app->request->get('od');
		$data = Property::find()->where(['orderId'=>$od])->one();
		if($od && $data){
			if($data->lock =='1'){
				return '仟房正在操作中。。。请稍后再试！...';
			}
			if(!in_array($data->status ,["-1","3"])){
				return '已操作过，请刷新后重试！...';
			}
			if($data->status==3){
				$result = $data->cid?Cdcon::find()->asArray()->where(['cid'=>$data->cid])->one():[];
				$d['result'] = $result;
			}
			$areas = Areas::findOne($data['city']);
			$d['status'] = $data['status'];
			$d['address'] = $data['address'];
			$d['id'] = $data['id'];
			$d['cid'] = $data['cid'];
			$d['phone'] = $data['phone'];
			$d['orderId'] = $data['orderId'];
			$d['area'] = $areas['name'];
			return $this->render('setbd',['data'=>$d]);
		}else{
			return '数据有误...';
		}
	}
	//快递单号
	public function actionExpress(){
		$this->header = "快递单号";
		$id = Yii::$app->request->get('id');
		$data = Property::findOne($id);
		if(! empty($data)){
			$areas = Areas::findOne($data['city']);
			$d['address'] = $data['address'];
			$Express = Express::find()->where(['jid'=>$id])->one();
			if(! empty($Express)){
				$d['addre'] = $Express->toArray()['address'];
				$d['phone'] = $Express->toArray()['phone'];
				$d['name'] = $Express->toArray()['name'];
				$d['id'] = $Express->toArray()['id'];
			}
			$d['area'] = $areas['name'];
			return $this->render('express',['data'=>$d]);
		}else{
			return '数据有误...';
		}
	}
	//提交快递单号
	public function actionPexp(){
		$id = Yii::$app->request->post('id');
		$phone = Yii::$app->request->post('phone');
		$orderId = Yii::$app->request->post('orderId');
		$express = Express::findOne($id);
		$express->orderId = $orderId;
		$express->uptime = time();
		if($express->save()){ 
			$info['status'] = 1;
			
			$post_data = array(
				'mobile' => $phone,
				'msg' => '您的快递(单号为'.$orderId.')已经寄出,请注意查收或进入公众号查看具体进度'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$post_data = http_build_query($post_data);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
			curl_setopt($ch, CURLOPT_URL, 'http://www.zcb2016.com/admin/wx/sendmsg');
			$output  = curl_exec($ch);
			curl_close($ch);
			
			
			$Property = Property::findOne($id);
			if($Property->userOpenid){
				$openid = $Property->userOpenid->openid;
				// $openid = 'oNZOTszvLxTU9zaLKyvfXu--ynqQ';
				$WxMsg = new \frontend\services\WxMsg("WXMSG");
				$time = $Property->time;
				$ordersid = date("Ymd",$time).str_pad($Property->id,6,"0",STR_PAD_LEFT);
				// $WXurl ="http://wx.zcb2016.com/property/index";
				$u = urlencode(base64_encode($Property->cid .','.$Property->id));
				$WXurl ="http://wx.zcb2016.com/property/view?id=".$u;
				$template = array(
					'touser' => $openid,
					'template_id' => '-WZphstKbZt3XoWu001cQk0zqHzPUgf7zranCGiwPFc',
					"url"=> $WXurl,
					"topcolor"=> "#FF0000",
					'data' => array(
						'first' => ["value" => "报告尊上：您查阅的产调已寄出","color" => "#0065b3"],
						'OrderSn' => ["value" => $ordersid,"color" => "#0065b3"],
						'OrderStatus' => ["value" => "已寄出","color" => "#0065b3"],
						'remark' => ["value" => "快递单号为".$express->orderId,"color" => "#0065b3"],
					)
				); 
				$status = $WxMsg->sendMB($template);
				// $openid = 'oNZOTs-OesEpM5DiovEI1zKOaQ30';
				// $template['touser'] = $openid;
				// $status = $WxMsg->sendMB($template);
			}
			
		}else{
			$info['status'] = 0;
		}
		return json_encode($info);
	}
	//本地提交
	public function actionDosd(){
		$area = Yii::$app->request->post('area');
		$address = Yii::$app->request->post('address');
		$refund_fee = Yii::$app->request->post('refund_fee');
		$refund_msg = Yii::$app->request->post('refund_msg');
		$pics = Yii::$app->request->post('pics');
		$phone = Yii::$app->request->post('phone');
		$id = Yii::$app->request->post('id');
		$cid = Yii::$app->request->post('cid');
		$pic = array();
		
		$Property = Property::findOne($id);
		if($id && $Property){
			if($Property->lock =='1'){
				$info['status'] = 0;
				$info['info'] = '仟房正在操作中。。。请稍后再试！';
				return json_encode($info);
			}
			if(!in_array($Property->status ,["-1","3"])){
				$info['status'] = 0;
				$info['info'] = '已操作过，请刷新后重试！';
				return json_encode($info);
			}
		}else{
			$info['status'] = 0;
			$info['info'] = '参数有误';
			return json_encode($info);
		}
		
		if(strpos($pics,',') !== false){
			foreach(explode(',',$pics) as $k => $v){
				$pic[$k] = '/Public/upload/' . $v;
			}
		}else{
			$pic[0] = '/Public/upload/' . $pics;
		}
		if($cid){
			$one = Cdcon::find()->where(['cid'=>$cid])->one();
			if(!$one){
				$one = Cdcon::find()->where(['offlineId'=>$id])->one();
			}
		}else{
			$one = Cdcon::find()->where(['offlineId'=>$id])->one();
		}
		if($cid &&! empty($one)){
			$cdcon = $one ;//Cdcon::findOne($one['id']);
		}else if($one){
			$cdcon = $one ;//Cdcon::findOne($one['id']);
		}else{
			$cdcon = new Cdcon();
		}
		$cdcon->area = $area;
		$cdcon->address = $address;
		$cdcon->refund_fee = $refund_fee;
		$cdcon->refund_msg = $refund_msg;
		
		$cdcon->images = serialize($pic);
		$cdcon->offlineId = $id;
		if($cdcon->save()){
			// var_dump($cdcon);exit;
			
			$Property->uptime = time();
			if($refund_fee > 0){
				$Property->status = 4;
				$status = $Property->updateAll(['status'=>$Property->status,'uptime'=>$Property->uptime],["id"=>$Property->id,'status'=>['-1','3']]);
			}else{
				$Property->status = 2;
				$status = $Property->updateAll(['status'=>$Property->status,'uptime'=>$Property->uptime],["id"=>$Property->id,'status'=>['-1','3']]);
			}
			// $Property->save();
			if($status==0){
				$info['status'] = 0;
				$info['info'] = "本地操作失败！！！";
				return json_encode($info);
			}
			$info['status'] = 1;
			$info['info'] = "本地操作成功";
			
			$opids = Openid::find()->where(['uid'=>$Property['uid']])->one();
			if($opids['openid']){
				// $con = '您的订单已经处理完毕,请查询,感谢您的支持.';
				// $this->sendM($opids['openid'],$con);
			}	
			if($Property->userOpenid){
				// $openid = $opids['openid'];
				$openid = $Property->userOpenid->openid;
				// $openid = 'oNZOTszvLxTU9zaLKyvfXu--ynqQ';
				$WxMsg = new \frontend\services\WxMsg("WXMSG");
				$time = $Property->time;
				$ordersid = date("Ymd",$time).str_pad($Property->id,6,"0",STR_PAD_LEFT);
				if($cdcon->refund_fee>0){
					$first = "报告尊上：您查阅的产调已退款";
					$remark = "退款原因: ".$refund_msg;
					$OrderStatus = "已退款";
				}else{
					$first = "报告尊上：您查阅的产调结果已出";
					$remark = '';
					$OrderStatus = "已处理";
				}
				$u = urlencode(base64_encode($Property->cid .','.$Property->id));
				$WXurl ="http://wx.zcb2016.com/property/view?id=".$u;
				$template = array(
					'touser' => $openid,
					'template_id' => '-WZphstKbZt3XoWu001cQk0zqHzPUgf7zranCGiwPFc',
					"url"=> $WXurl,
					"topcolor"=> "#FF0000",
					'data' => array(
						'first' => ["value" => $first,"color" => "#0065b3"],
						'OrderSn' => ["value" => $ordersid,"color" => "#0065b3"],
						'OrderStatus' => ["value" => $OrderStatus,"color" => "#0065b3"],
						'remark' => ["value" => $remark,"color" => "#0065b3"],
					)
				); 
				$status = $WxMsg->sendMB($template);
				// $openid = 'oNZOTs-OesEpM5DiovEI1zKOaQ30';
				// $template['touser'] = $openid;
				// $status = $WxMsg->sendMB($template);
			}
				
				
			
			
			
			$post_data = array(
				'mobile' => $phone,
				'msg' => '您申请的产调('.$area.$address.')已经处理完毕，请进入公众号查看'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$post_data = http_build_query($post_data);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
			curl_setopt($ch, CURLOPT_URL, 'http://www.zcb2016.com/admin/wx/sendmsg');
			$output  = curl_exec($ch);
			curl_close($ch);
			//curl(2,$sms);
		}else{
			$info['status'] = 0;
			$info['info'] = "本地操作错误！";
		}
		return json_encode($info);
	}
	public function actionOutput(){
		$s = Yii::$app->request->get('s');
		$e = Yii::$app->request->get('e');
		$query = Property::find();
		$query->where(['between','time', strtotime($s),strtotime($e)+86400]);
		$result = $query->orderBy('time DESC')->all();
        $arr = [];
        $ic = 1;
		$status = ['0'=>'未付款','-1'=>'已付款','1'=>'审核中','2'=>'成功','3'=>'退款中','4'=>'已退款'];
        foreach($result as $r) {
            $r = $r->toArray();
            $r['id'] = $ic++;
            $r['time'] = date('Y/m/d H:i',$r['time']);
			if($r['uptime']){
				$r['uptime'] = date('Y/m/d H:i',$r['uptime']);
			}else{
				$r['uptime'] = '无';
			}
			$area = Areas::findOne($r['city']);
			$r['city'] = $area['name'];
			$r['address'] = $r['address'];
			$r['phone'] = $r['phone'];
			$r['orderId'] = $r['orderId'];
			if($r['status'] == 3 || $r['status'] == 4){
				$cdcon = Cdcon::find()->where(['cid'=>$r['cid']])->one();
				if(! empty($cdcon)){
					$r['refund_msg'] = $cdcon->toArray()['refund_msg'];
				}else{
					$r['refund_msg'] = '无';
				}
			}else{
				$r['refund_msg'] = '无';
			}
			$local = Cdcon::find()->where(['offlineId'=>$r['id']])->one();
			if($local){
				$r['local'] = '本地';
			}else if($r['cid']){
				if($r['status'] == 0 || $r['status'] == -1 || $r['status'] == 1){
					$r['local'] = '无';
				}else{
					$r['local'] = '仟房';
				}
			}else{
				if($r['status'] == 0 || $r['status'] == -1 || $r['status'] == 1){
					$r['local'] = '无';
				}else{
					$r['local'] = '本地异常';
				}
			}
			$r['status'] = $status[$r['status']];
            $arr[] = $r;
        }
        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['id','city','address','time','uptime','phone','orderId','status','refund_msg','local'],
            'headers'=>['id'=>'序号','city'=>'区域','address'=>'地址','time'=>'提交时间','uptime'=>'结束时间','phone'=>'联系方式','orderId'=>'订单号','status'=>'状态','refund_msg'=>'退款原因','local'=>'银行/房管']
        ]);
    }
	
	//微信
	//推送模板消息
	private function sendM($openid,$con){
        $template = array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array(
				'content' => $con
			)
        );
        $json_template = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $this->getToken();
        $dataRes = $this->request_post($url, urldecode($json_template));
        /* if ($dataRes['errcode'] == 0) {
            return true;
        } else {
            return false;
        } */
	}
	/**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    private function request_post($url = '', $param = ''){
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
	/**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    private function request_get($url = ''){
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
	/**
     * @param $appid
     * @param $appsecret
     * @return mixed
     * 获取token
     */
    private function getToken($appid='wxb31437c7395f1399', $appsecret='8c777e91602c930f443015952db70d16'){
        if (isset($_COOKIE[$appid])) {
            $access_token = $_COOKIE[$appid];
        } else {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
            $token = $this->request_get($url);
            $token = json_decode(stripslashes($token));
            $arr = json_decode(json_encode($token), true);
            $access_token = $arr['access_token'];
            setcookie($appid, $access_token, time()+720);
        }
        return $access_token;
    }
	public function actionKlist(){
		$this->header = "产调快递";
		$status = Yii::$app->request->get('status');
		$phone = Yii::$app->request->get('phone');
		$query = Express::find();
		$pagination = new Pagination([
			'defaultPageSize' => 20,
			'totalCount' => $query->count(),
		]);
		$kd = $query->orderBy('time DESC')->offset($pagination->offset)->limit($pagination->limit)->all();
		foreach($kd as $k => $v){
			$jid = $v->toArray()['jid'];
			$properties = Property::findOne($jid);
			$s[$k] = $properties->toArray();
			$area = Areas::findOne($s[$k]['city']);
			$s[$k]['city'] = $area['name'];
			$s[$k]['kdOrderOd'] = $v['orderId'];
		} 
		return $this->render('klist',['pagination'=>$pagination,'data'=>$s]);
	}
	public function actionIndexold(){
		$this->header = "产调管理";
		$status = Yii::$app->request->get('status');
		$phone = Yii::$app->request->get('phone');
		$query = Property::find();
		if($status != ''){
			$query->andFilterWhere(['status' => $status]);
		}
		if($phone != ''){
			// if(preg_match("/^1[34578]\d{9}$/", $phone)){
				// $query->andFilterWhere(['phone|address' => $phone]);
			// }else{
				$query->andFilterWhere(["or",['like', 'phone', $phone],['like', 'address', $phone],['like', 'orderId', $phone]]);
			// }
		}
		$pagination = new Pagination([
			'defaultPageSize' => 18,
			'totalCount' => $query->count(),
		]);
		$properties = $query->orderBy('time DESC')->offset($pagination->offset)->limit($pagination->limit)->all();
		$proArr = [];
		foreach($properties as $k => $v){
			$v = $v->toArray();
			$area = Areas::findOne($v['city']);
			$Express = Express::find()->where(['jid'=>$v['id']])->one();
			$cdcon = Cdcon::find()->where(['cid'=>$v['cid']])->one();
			if(! empty($Express)){
				$v['orderd'] = $Express->toArray()['id'];
				$v['eid'] = $Express->toArray()['orderId'];
			}else{
				$v['orderd'] = '';
				$v['eid'] = '';
			}
			if(! empty($cdcon)){
				$v['refund_msg'] = $cdcon->toArray()['refund_msg'];
			}else{
				$v['refund_msg'] = '';
			}
			$v['city'] = $area['name'];
			$proArr[] = $v;
		}
		return $this->render('index_old',['pagination'=>$pagination,'data'=>$proArr,'status'=>$status,'phone'=>$phone]);
	}
	
	  /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionOutputnew()
    {
		set_time_limit(0);
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		// var_dump(Yii::$app->request->queryParams);
		// echo "<pre>";
		// print_r(Yii::$app->request->queryParams['sort']);
		
		// print_r($dataProvider->sort);
		// exit;
		$sort = Yii::$app->request->queryParams['sort']?Yii::$app->request->queryParams['sort']:'-time';
		
		if($sort{0}=="-"){
			$orderBy = substr($sort,1)."  desc";
		}else{
			$orderBy = " {$sort} ASC";
		}
		$result = $dataProvider->query->orderBy($orderBy)->asArray()->all();
		// var_dump($result);exit;
        $arr = [];
        $ic = 1;
		$status = ['0'=>'未付款','-1'=>'已付款','1'=>'审核中','2'=>'成功','3'=>'退款中','4'=>'已退款'];
        foreach($result as $r) {
            // $r = $r->toArray();
            $r['item'] = $ic++;
            $r['time'] = date('Y/m/d H:i',$r['time']);
			if($r['uptime']){
				$r['uptime'] = date('Y/m/d H:i',$r['uptime']);
			}else{
				$r['uptime'] = '无';
			}
			$area = Areas::findOne($r['city']);
			$r['city'] = $area['name'];
			$r['address'] = $r['address'];
			$r['phone'] = $r['phone'];
			$r['orderId'] = $r['orderId'];
			if($r['status'] == 3 || $r['status'] == 4){
				if($r['cid']){
					$cdcon = Cdcon::find()->where(['cid'=>$r['cid']])->asArray()->one();
				}else{
					$cdcon = Cdcon::find()->where(['offlineId'=>$r['id']])->asArray()->one();
				}
				if(! empty($cdcon)){
					$r['refund_msg'] = $cdcon['refund_msg'];
					$r['refund_fee'] = $cdcon['refund_fee'];
				}else{
					$r['refund_msg'] = '无';
					$r['refund_fee'] = '无';
				}
			}else{
				$r['refund_msg'] = '无';
				$r['refund_fee'] = '无';
			}
			$local = Cdcon::find()->where(['offlineId'=>$r['id']])->one();
			if($local){
				if($r['cid']){
					$r['local'] = '仟房后本地';
				}else{
					$r['local'] = '本地';
				}
				
			}else if($r['cid']){
				if($r['status'] == 0 || $r['status'] == -1 || $r['status'] == 1){
					$r['local'] = '无';
				}else{
					$r['local'] = '仟房';
				}
			}else{
				if($r['status'] == 0 || $r['status'] == -1 || $r['status'] == 1){
					$r['local'] = '无';
				}else{
					$r['local'] = '本地异常';
				}
			}
			$r['status'] = $status[$r['status']];
            $arr[] = $r;
        }
        \moonland\phpexcel\Excel::export([
            'models' => $arr,
            'columns' => ['item','city','address','time','uptime','phone','orderId','status','money','refund_msg','refund_fee','local','cid'],
            'headers'=>['item'=>'序号','city'=>'区域','address'=>'地址','time'=>'提交时间','uptime'=>'结束时间','phone'=>'联系方式','orderId'=>'订单号','status'=>'状态','money'=>'付款金额','refund_msg'=>'退款原因','refund_fee'=>'退款金额','local'=>'本地/仟房','cid'=>'仟房ID']
        ]);
    }
	
	public function actionResultdetail()
    {	
		$this->layout=false;
		$id = Yii::$app->request->get('id');
		
        $model = new Property;
		// $data = $model::find()->where(['id'=>$id,'uid'=>$uid])->one();
		 
		 
		// var_dump($ids);exit;
		$ones = $model::find()->where(['id'=>$id])->one();
		
		if(!$ones){
			$this->errorMsg("ParamsCheck","");  
		}
		$one = $ones->attributes;
		$images = (new \yii\db\Query())
						->select(['images'])
						->from('zcb_cdcon') 
						->where(['offlineId'=>$ones['id']]) 
						->one();
		$city = (new \yii\db\Query())
						->select(['name'])
						->from('zcb_areas')
						->where(['id'=>$ones['city']]) 
						->one();
		$one['cityname']=$city['name'];
		$data = []; 
		$success_msg = '';
		if($images){
			$data = unserialize($images['images']);
		}else if($ones){
			$appid ='9f1727c84a' ;
			$token = '9efa016b0b46ef9e0dab906931cbca19';
			$timestamp = time();
			$parm = "appid={$appid}&id={$ones['cid']}&timestamp={$timestamp}";
			$sign = strtolower(md5($parm.$token));
			$url = 'http://www.1001fang.com/cdapi/query?'.$parm.'&sign='.$sign;
		
			$con = json_decode(\frontend\services\Func::CurlGet($url),true);
			if($con&&$con['code']==1){
				$data = $con['response']['images'];
				$success_msg = $con['response']['success_msg'];
				if($success_msg == '改过地址'){
					$success_msg = '<br><font color=red>重要提示:'.$success_msg.'</font>';
				}else{
					$success_msg = '';
				}
			}
		}
		// var_dump($ones);exit;
		return $this->render('resultdetail',['data'=>$data,'ones'=>$one,'success_msg'=>$success_msg]);
    }
}
