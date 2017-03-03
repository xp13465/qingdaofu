<?php
namespace common\models;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Files;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;
    public $imageFile;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
		return Yii::$app->params['uploadRule']; 
    }
	/**
	*	 附件上传
	*	param Filetype integer  上传规则和地址识别码 
	*	param dbsave bool   是否数据库存储 
	*/
	function create_uuid($prefix = ""){    //可以指定前缀
		$str = md5(uniqid(mt_rand(), true));   
		$uuid  = substr($str,0,8) . '-';   
		$uuid .= substr($str,8,4) . '-';   
		$uuid .= substr($str,12,4) . '-';   
		$uuid .= substr($str,16,4) . '-';   
		$uuid .= substr($str,20,12);   
		return $prefix . $uuid;
	}
	public function upload($FileType=1,$dbsave=false,$isupload=true){
		$attribute = '';
		$UploadPath = Yii::$app->params['uploadPath'];
		switch($FileType){
			case 1:
				$attribute ='imageFile';
				break;
			 
			default:
				$attribute ='file';
				break;
		}
		$time =time();
		$savePath = $UploadPath[$attribute]['savePath'];
		if (!file_exists($savePath)) {
			mkdir($savePath,0777,true);
		}
		$savePath .= date("Ymd",$time)."/";
		$linkPath = $UploadPath[$attribute]['linkPath'].date("Ymd",$time)."/";
		//var_dump($this->imageFile);die;
		if ($this->$attribute && $this->validate()) {
			
			if (!file_exists($savePath)) {
				mkdir($savePath);
			}
             $filename = $FileType."_".$time.rand(1000,9999) . '.' . $this->$attribute->extension;
			     $linkUrl = $linkPath.$filename;
			     $saveAddr = $savePath.$filename;
			   if($isupload){ 
					$status = $this->$attribute->saveAs($saveAddr); 
				}else{
					$status = copy($this->$attribute->tempName,$saveAddr);
			   }			 
			if($status){
				$fileid = 0;
				if($dbsave){
					$uuid = $this->create_uuid();
					$filesModel = new Files();
					$filesModel->setAttributes([
						'uuid'=>$uuid,
						'file'=>"/site/upimages?file=".$uuid,
						'file_old'=>$linkUrl,
						'addr'=>$saveAddr,
						'name'=>$this->$attribute->name,
						'datetime'=>time(),
						'ip'=>Yii::$app->request->userIP,
						'type'=>$FileType,
					]);
					
					// var_dump($filesModel);exit;
					if($filesModel->save()){
						$fileid = $filesModel->id;
					}else{
					   echo 33;
					       //var_dump($filesModel->attributes) ;echo 33;
						//var_dump($filesModel->errors) ;
                        exit;
					} 
				}
				return array_merge((array)$this->$attribute,["url"=>$linkUrl,"url1"=>$filesModel->file,"fileid"=>$fileid]);
			}else{ 
				return array_merge(['error'=>'5001','msg'=>"上传失败！！"]);;
			}
			
        }else{
			return array_merge(['error'=>'5000','msg'=>$this->errors[$attribute]]);
		}
		
	}
	
	public function dataUrl($data){
			  $basecharArr=explode(",",$data);
			  if(count($basecharArr)!==2)return false;
			  $basecharArr1 = explode(";",$basecharArr[0]);
			  $basecharArr2 = explode("/",$basecharArr1[0]);
			  $extension = $basecharArr2[1];
			  $base64_image_content = $basecharArr[1];
			  $fileADDR="./uploads/".time().rand("1000","9999").".".$extension;
			  $img_file = base64_decode($base64_image_content);
              $status = file_put_contents($fileADDR,$img_file);
             ($pathinfo = pathinfo($fileADDR));
			 
			 $_FILES=[
              "Filedata"=>[
                "name"=>$pathinfo['basename'],
                "type"=>mime_content_type($fileADDR),
                "tmp_name"=>$fileADDR,
                "error"=>"0",
                "size"=>filesize($fileADDR),
              ] 
            ];
			return $_FILES;
	}
}