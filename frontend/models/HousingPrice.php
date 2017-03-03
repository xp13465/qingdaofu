<?php

namespace frontend\models;
use frontend\services\Func;
use Yii;

/**
 * This is the model class for table "zcb_housing_price".
 *
 * @property integer $id
 * @property string $serviceCode
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $buildingNumber
 * @property string $unitNumber
 * @property string $size
 * @property string $floor
 * @property string $maxFloor
 * @property integer $create_time
 * @property string $ip
 * @property string $code
 * @property string $msg
 * @property integer $totalPrice
 */
class HousingPrice extends \frontend\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zcb_housing_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serviceCode','city','district','address','size','buildingNumber','unitNumber','floor', 'maxFloor'], 'required'],
            [['size','userid'], 'number'],
            [['create_time', 'totalPrice'], 'integer'],
            [['serviceCode', 'city', 'district', 'address', 'buildingNumber', 'unitNumber', 'floor', 'maxFloor'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 16],
            [['code', 'msg'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serviceCode' => 'Service Code',
            'city' => '城市',
            'district' => '区域',
            'address' => '小区',
            'size' => '面积',
            'buildingNumber' => '楼栋',
            'unitNumber' => '室号',
            'floor' => '楼层',
            'maxFloor' => '总楼层',
            'create_time' => 'Create Time',
            'ip' => '请求IP',
            'code' => '错误码',
            'msg' => '错误信息',
            'totalPrice' => '总价',
            'userid' => '用户ID',
        ];
    }
	
	/**
	*	获取评估总价
	*
	*/
	public function getTotalPrice($Attributes,$userid = 0){
		if (!$userid && \Yii::$app->user->isGuest) {
			return ['errorCode'=>'UserLogin','data'=>[]];
        }
        
        $userid= $userid?$userid:Yii::$app->user->getId();
		
		$today = strtotime(date("Y-m-d",time()));
		$count = (new \yii\db\Query())
			->from('zcb_housing_price')
			->where(['userid'=>$userid])
			->andWhere(['between', 'create_time', $today, $today+86400])
			->count();
		if($count>=50){
            return ['errorCode'=>'TotalpriceLimit','data'=>[]];
		}
		$return = ['errorCode'=>'','data'=>[]];
		
		$Attributes['city']='上海';
		$Attributes['serviceCode']='S001';
		$Attributes['create_time']=time();
		$Attributes['ip']=Yii::$app->request->userIP;
		$Attributes['userid']=$userid;
		$this->setAttributes($Attributes);
		//表单内容验证
		if(!$this->validate()){
			return ['errorCode'=>'TotalpriceCheck','data'=>[]];
		}
		
        $where = [
            "district"=>$Attributes['district'],
            "address"=>$Attributes['address'],
            "size"=>$Attributes['size'],
            "buildingNumber"=>$Attributes['buildingNumber'],
            "unitNumber"=>$Attributes['unitNumber'],
            "floor"=>$Attributes['floor'],
            "maxFloor"=>$Attributes['maxFloor'],
        ];
        
        
		//历史调用结果 截止至本周一 因房价网每周日更新
		$where['code']='200';
		$zhouyi = (time()-((date('w')==0?7:date('w'))-1)*24*3600);
		$rows = (new \yii\db\Query())
				->select(['id','district','address','totalPrice','address','size','buildingNumber','unitNumber','floor','maxFloor','create_time'])
				->from('zcb_housing_price')
				->where($where)
				->andWhere(['>','create_time',$zhouyi])
				->orderBy('create_time desc')
				->one();	
		if($rows){
			$this->code="200";
			$this->msg="历史记录调用({$rows['id']})";
			$this->totalPrice=$rows['totalPrice'];
			if($this->save()){
				return ['errorCode'=>'ok','data'=>$rows];
			}else{
				return ['errorCode'=>'TotalpriceSave','data'=>[]];
			}
		}
		//获取Token
		$token = $this->getToken();
		if(empty($token)){
			return ['errorCode'=>'FangjiaToken','data'=>[]];
		}
		
		$url="http://open.fangjia.com/property/transaction";
		$url.="?token={$token}&";
		$paramStr=http_build_query($Attributes); 
		if($this->save()){	//用户请求记录写入
			// exit(12);
			$result = Func::CurlGet($url.$paramStr);
			$result = (array)(json_decode($result));
			$this->code = "".$result['code'];
			$this->msg = $result['msg'];
			if($result['code']==200){	//API调用成功
				$result['result']=(array)$result['result'];
				$this->totalPrice=$result['result']['totalPrice'];
				if($this->save()){		//更新请求结果 
					return ['errorCode'=>'ok','data'=>$this->attributes];
				}else{	//更新错误
					$this->errorMsg("TotalpriceSave");
					return ['errorCode'=>'TotalpriceSave','data'=>[]];
				}
			}else{ //API调用失败
				$this->save();
				return ['errorCode'=>'TotalpriceAPI','data'=>[]];
			} 
		}else{ //用户请求写入失败
			return ['errorCode'=>'TotalpriceSave','data'=>[]];
		}
	}
	/**
	*	获取TOKEN
	*
	*/
	public function getToken(){
		$key = 'FangjiaToken';
		$cache = Yii::$app->cache;
		$cacheToken = $cache->get($key);
		if(!empty($cacheToken)){
			return $cacheToken;
		}
		
		$url="http://open.fangjia.com/accessToken?";
		$url.="username=13918500509";
		$url.="&password=lava1012";
		$url.="&appKey=7e5b6914b50003c994213561409dda879294d2e0dd2ef0b7c232e6c4ae4ffb3d";
		$result = Func::CurlGet($url);
		$result = (array)(json_decode($result));
		
		if($result['code']=='200'){
			$result['result'] = (array)($result['result']);
			$token = $result['result']['token'];
			$cache->set($key, $token, 60*60*1);
			return $token;
		}else{
			return '';
		}
	}
}
