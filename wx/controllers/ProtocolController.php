<?php
namespace wx\controllers;
use wx\services\Func;
use yii;
class ProtocolController extends \wx\components\FrontController{
    public $enableCsrfValidation = false;

    //协议
    public function actionIndex($productid,$type="view"){
		$productid = Yii::$app->request->get('productid','');
        $token    = Yii::$app->session->get('user_token');
        $strs       = Func::CurlPost(yii\helpers\Url::toRoute('/wap/product/agreement'), ['token' => $token,'productid'=>$productid]);
        $arrs       = yii\helpers\Json::decode($strs);
		
		if($arrs['code'] == '0000'){
            $data  = $arrs['result']['data'];
			if ($data['create_by'] == Yii::$app->user->getId()) {
				//居间协议（清收委托人）
				$view="jujian";
			} else {
				//居间协议 （清收人）
				$view="jujian01";
			}
        }else{
			$this->notFound();
        }
		 
		
		
		if($type=="pdf"){
			$mpdf=new \mPDF('zh-CN','A4','','ccourierB','15','15','24','24','5','5');
			$mpdf->useAdobeCJK = true;
			$header='<table width="100%" style="">
			<tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:px;color:#AAA">页眉</td>
			<td width="20%" style="text-align: right;"></td>
			</tr> 	
			</table>';
			//设置PDF页脚内容
			$footer='<table width="100%" style=" vertical-align: bottom; font-family:黑体; font-size:12pt; color: #;"><tr style="height:px"></tr><tr>
			<td width="20%"></td>
			<td width="60%" align="center" style="font-size:10px;">{PAGENO}/{nb}</td>
			<td width="20%" style="text-align: left;"></td>
			</tr></table>';
			//添加页眉和页脚到pdf中
			$mpdf->SetHTMLHeader($this->renderPartial('pdf_header'));
			$mpdf->SetHTMLFooter($this->renderPartial('pdf_footer'));
			//$mpdf->AddPage();
			
			$mpdf->WriteHTML($this->renderPartial($view,['data'=>$data]));
			// $mpdf->WriteHTML($this->renderPartial('myview'));
			//$mpdf->AddPage();
			$mpdf->Output('MyPDF.pdf', "I");//I查看D下载
			exit;
		}else{
			return $this->render($view,['data'=>$data]);
		}
		
		
		
		
		
		
		
        if($arrs['code'] == '0000'){
            $data  = $arrs['result']['data'];
			
			if ($data['create_by'] == Yii::$app->user->getId()) {
				//居间协议（清收委托人）
				echo $this->render('jujian',['data'=>$data]);
			} else {
				//居间协议 （清收人）
				echo $this->render('jujian01',['data'=>$data]);
			}
            // return $this->render('mediacys',['data'=>$data]);
        }else{
            // return $this->render('mediacys');
        }
    }


}