<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?> 
<p>
		产调编号:<font><?=date('YmdHis',$ones['time'])?></font><br>
		产调地址:<?=$ones['cityname']?><?=$ones['address']?>
		<?=$success_msg?>
	</p>
	<div class="banner">
	<?php foreach($data as $v):
	if(strpos($v, 'http')===false)$v='http://m.zcb2016.com/'.$v;
	?>
	<img src="<?=$v?>" width='100%' />
	<?php endforeach;?>
	</div>
 
