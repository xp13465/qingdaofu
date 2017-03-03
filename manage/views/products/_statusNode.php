<?php
use yii\helpers\Html;
// echo "<pre>";
// print_r($logGroup);die;

$pro = [ 
         0  =>['statusLabel'=>'销售进单','class'=>'default' ,"relationData"=>"progress","relationKey"=>"11",],
		 1  =>['statusLabel'=>'签订合同','class'=>'d'       ,"relationData"=>"progress","relationKey"=>"30",],
		 2  =>['statusLabel'=>'定金支付','class'=>'j'       ,"relationData"=>"progress","relationKey"=>"40",],
		 3  =>['statusLabel'=>'风控尽调','class'=>'j'       ,"relationData"=>"logGroup","relationKey"=>"21",],
		 4  =>['statusLabel'=>'风控报告','class'=>'b'       ,"relationData"=>"logGroup","relationKey"=>"22",],
		 5  =>['statusLabel'=>'理财配资','class'=>'l'       ,"relationData"=>"logGroup","relationKey"=>"23",],
		 6  =>['statusLabel'=>'运作公证','class'=>'g'       ,"relationData"=>"logGroup","relationKey"=>"24",],
		 7  =>['statusLabel'=>'运作合同','class'=>'h'       ,"relationData"=>"logGroup","relationKey"=>"25",],
		 8  =>['statusLabel'=>'运作产调','class'=>'c'       ,"relationData"=>"logGroup","relationKey"=>"26",],
		 9  =>['statusLabel'=>'财务出账','class'=>'w'       ,"relationData"=>"logGroup","relationKey"=>"27",],
		 10 =>['statusLabel'=>'财务回款','class'=>'k'           ,"relationData"=>"logGroup","relationKey"=>"28",]             
		 ];
?>
<ul>
<?php foreach($pro as $k=>$v){ 
	$relationData = $v['relationData']&&isset($$v['relationData'])?$$v['relationData']:[];
	$CurNodeData = $relationData&&isset($relationData[$v['relationKey']])?$relationData[$v['relationKey']]:[];
	if($CurNodeData){
		$type ="success";
	}else{
		$type ="fail";
	}
		
	if($v['relationData']=='logGroup'){
		// foreach($CurNodeData as $k=>$va){
			// $CurNodeData['name'] = $va['user']['username'];
			// $CurNodeData['time'] =  date('Y-m-d',$va['action_at']);
			if($k == '28'){
				$type ="wait";
			}
		}
	// }else{
		
		$name = isset($CurNodeData["name"])?$CurNodeData["name"]:'';
		$time = isset($CurNodeData["time"])?$CurNodeData["time"]:'';
	// }
	// echo $type;
	if($k>0){
			$v['class'] .= $type;
		}
?>
	<li class="<?= $v['class'] ?>">
    <i></i>
    <div class="pro">
      <p class="type"><?= $v['statusLabel'] ?></p>
      <p class="name" title="<?= isset($CurNodeData["name"])?$CurNodeData["name"]:'' ?>"><?= isset($CurNodeData["name"])?$CurNodeData["name"]:'' ?></p>
      <p class="time" title="<?= isset($CurNodeData["timeint"])?date('Y-m-d H:i:s',$CurNodeData["timeint"]):'' ?>"><?= isset($CurNodeData["time"])?$CurNodeData["time"]:'' ?></p>
    </div>
  </li>
<?php } ?>
			 
