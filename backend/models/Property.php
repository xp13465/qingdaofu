<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%property}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $province
 * @property string $city
 * @property string $address
 * @property integer $cid
 * @property string $phone
 * @property string $money
 * @property integer $time
 * @property integer $uptime
 * @property integer $status
 * @property string $orderId
 */
class Property extends \yii\db\ActiveRecord
{
	public static $status = [
		'0'=>"未付款",
		'-1'=>"已付款",
		'1'=>"审核中",
		'2'=>"成功",
		'3'=>"退款中", 
		'4'=>"已退款", 
		'6'=>"成功并申请快递", 
		'7'=>"成功并已发快递", 
	];
	public static $type = [
		'1'=>"交易中心版",
		'2'=>"电子版", 
	];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%property}}';
    }
	
	public $kdorder;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'cid', 'time', 'uptime', 'status', 'lock'], 'integer'],
            [['money'], 'number'],
            [['province', 'city'], 'string', 'max' => 4],
            [['address'], 'string', 'max' => 225],
            [['phone'], 'string', 'max' => 11],
            [['orderId'], 'string', 'max' => 32],
            [['orderId'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单ID',
            'uid' => '申请帐号',
            'province' => '市',
            'city' => '区',
            'address' => '地址',
            'cid' => '第三方产调ID',
            'type' => '产调类型',
            'name' => '产权人姓名',
            'phone' => '联系方式',
            'money' => '付款费用',
            'time' => '申请时间',
            'uptime' => '产调成功时间',
            'status' => '状态',
            'orderId' => '仟房单号',
            'kdorder' => '快递单号',
            'lock' => '锁定状态',
        ];
    }
	
	public function getUsername(){
		return $this->hasOne(\common\models\User::className(), ['id'=>'uid'])->select(["id","username"]);
	}
	
	public function getUserOpenid(){
		return $this->hasOne(\common\models\Openid::className(), ['uid'=>'uid']);
	}
	
	public function getProvincename(){
		return $this->hasOne(\common\models\Areas::className(), ['id'=>'province'])->alias("provincename");
	}
	public function getCityname(){
		return $this->hasOne(\common\models\Areas::className(), ['id'=>'city'])->alias("cityname");
	}
	public function getExpressdata(){
		return $this->hasOne(\common\models\Express::className(), ['jid'=>'id'])->alias("expressdata");
	}
	public function getCdcondata(){
		return $this->hasOne(\common\models\Cdcon::className(), ['cid'=>'cid'])->alias("cdcondata")->onCondition("cdcondata.cid != 0");
	}
	public function getLocaldata(){
		return $this->hasOne(\common\models\Cdcon::className(), ['offlineId'=>'id'])->alias("localdata")->onCondition("localdata.offlineId != 0");
	}
	public function getSaveLocal(){
		return $this->hasOne(PropertyLocal::className(), ['pid'=>'id'])->alias("saveLocal");
	}
	public function scriptLocal($offset=0,$limit=500,$auto,$type=1){
		set_time_limit(0);
		
		//ob_start(); 
		//ob_end_flush();
		//ob_implicit_flush(1);
		$num = 0;
		if($type==2){
			$where = ['property.status'=>2,"saveLocal.status"=>2] ;
		}else{
			$where = ['property.status'=>2,"saveLocal.pid"=>NULL] ;
		}
		
		
		$joinWith = ["cdcondata","localdata","saveLocal"];
		$count = self::find()->alias("property")
			->where($where)
			->joinWith($joinWith)
			->orderBy("property.id desc")
			->offset($offset)
			->limit($limit)
			->asArray()->count();
		
		while($count){
			$num++;
			$data = self::find()->alias("property")
			->where($where)
			->joinWith($joinWith)
			->orderBy("property.id desc")
			->offset($offset)
			->limit($limit)
			->asArray()->one();
			
			if($data){
				echo "第{$num}条，ID：{$data['id']},剩{$count}条";
				$this->localFiles($data);
				print (str_repeat(" ", 4096));
				echo "
				<script>
					var body = document.body;
					body.scrollTop = body.scrollHeight;
				</script>
				";
				
				ob_flush(); 
				flush();
				
			}
			$count = self::find()->alias("property")
			->where($where)
			->joinWith($joinWith)
			->orderBy("property.id desc")
			->offset($offset)
			->limit($limit)
			->asArray()->count();
			if($auto!=1){
				$count = 0;
			}
			
		}
		
		
		
		
	}
	
	public static function scriptLocalupdate($offset=0,$limit=500){
		
	}
	
	public function localFiles($d,$path ="./upload/property"){
			$PropertyLocal = (PropertyLocal::find()->where(["pid"=>$d['id']])->one())?:new PropertyLocal;
			if($PropertyLocal->status==="0")return false;
			$PropertyLocal->pid = $d['id'];
			$PropertyLocal->status = 0;
			$status = $PropertyLocal->save();
			if(!$status)return false;
			
			$images = [];
			if($d['localdata']){
				$images=$d['localdata']['images']?unserialize($d['localdata']['images']):[];
				$baseUrl = "http://admin.zcb2016.com";
				$baseUrl = "http://m.zcb2016.com/";
				$area = $d['localdata']['area'];
				$address = $d['localdata']['address'];
				$PropertyLocal->type = 1 ;
			}elseif($d['cdcondata']||$d["cid"]){
				$images=$d['cdcondata']['images']?unserialize($d['cdcondata']['images']):[];
				$baseUrl = "";
				$area = $d['cdcondata']['area'];
				$address = $d['cdcondata']['address'];
				
				/***** 最新仟房*******/
				$appid ='9f1727c84a' ;
				$token = '9efa016b0b46ef9e0dab906931cbca19';
				$timestamp = time();
				$parm = "appid={$appid}&id={$d['cid']}&timestamp={$timestamp}";
				$sign = strtolower(md5($parm.$token));
				$url = 'http://www.1001fang.com/cdapi/query?'.$parm.'&sign='.$sign;
				$con = json_decode(\frontend\services\Func::CurlGet($url),true);
				if($con&&$con['code']==1){
					$data = $con['response']['images'];
					$images = $data;
					$area = $con['response']['area'];
					$address = $con['response']['address'];
					// $success_msg = $con['response']['success_msg'];
					// if($success_msg == '改过地址'){
						// $success_msg = '<br><font color=red>重要提示:'.$success_msg.'</font>';
					// }else{
						// $success_msg = '';
					// }
					// var_dump($con);exit;
				}
				/**************/
				
				$PropertyLocal->type = 2 ;
			}else{
				$PropertyLocal->memo = "异常";
				$PropertyLocal->status = "4";
				$PropertyLocal->save();
				
				var_dump($d);
				var_dump("异常");
				return false;
			}
			$savePath = $path."/".$area."/".$address."";
			
			if($images){
				// var_dump($savePath);
				
				// var_dump($images);
				if(PATH_SEPARATOR!=':'){
					$savePath = iconv('utf-8', 'gbk', $savePath);
				}
				if(!is_dir($savePath))mkdir($savePath,0777,true);
				
				$count = count($images);
				$num =0;
				$fileStr ="";
				$errorStr = "";
				foreach($images as $key=>$imagesURL){
					$fileItem=$count>1?("_".($key+1)):"";
					$file = ((explode('.',basename($imagesURL))));
					$extension = $file[count($file)-1];
					$filename = $address.$fileItem.".".$extension;
					$filename= preg_replace("/[\/]|[\\\]|[:]|[?]|[\"]|[<]|[>]|[\|]/","`",$filename);
					if(PATH_SEPARATOR!=':'){
						$filename = iconv('utf-8', 'gbk', $filename);
					}
					$filename = $savePath."/".$filename;
					$fileContent= @file_get_contents($baseUrl.$imagesURL);
					// var_dump( $fileContent);exit;
					if($fileContent){
						$status = file_put_contents($filename,$fileContent );
						// var_dump($status);
						if($status){
							$fileStr .=$fileStr?(",".$filename):$filename;
							$num ++ ;
						}else{
							$errorStr .=$errorStr?(",下载失败".$baseUrl.$imagesURL):("下载失败".$baseUrl.$imagesURL);
						}
					}else{
						if($fileContent!==false)$num++;
						$errorStr .=$errorStr?(",空".($baseUrl.$imagesURL)):("空".$baseUrl.$imagesURL);
					}
					
				}
				if(PATH_SEPARATOR!=':'){
					$fileStr = mb_convert_encoding ( $fileStr,"UTF-8",'gbk');
				}
				
				
				$PropertyLocal->file = $fileStr;
				if($num==$count){
					$PropertyLocal->memo = "成功".$errorStr;
					$PropertyLocal->status = "1";
					$PropertyLocal->save();
					// var_dump($PropertyLocal->errors);
					// var_dump($PropertyLocal->attributes);
					var_dump($area.$address."本地化成功");
				}else if($num==0){
					$PropertyLocal->memo = "爬取失败";
					$PropertyLocal->status = "3";
					$PropertyLocal->save();
					var_dump($baseUrl.$imagesURL);
					var_dump($area.$address."本地化失败爬取失败");
				}else{
					$PropertyLocal->memo = "爬取内容不全".$num."|".$count."__".$errorStr;
					$PropertyLocal->status = "2";
					$PropertyLocal->save();
					var_dump($area.$address."本地化失败爬取内容不全");
				}
				
			}else{
				$PropertyLocal->memo = "无图片";
				$PropertyLocal->status = "3";
				$PropertyLocal->save();
				var_dump($area.$address."无图片");
			}
		
	}
}
