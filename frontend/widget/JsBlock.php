<?php
namespace frontend\widget;

use yii\web\View ;
use yii\widgets\Block ;

class JsBlock extends Block
{

	public $key = null;
	public $pos = View::POS_END ;

	public function run()
	{
		$block = ob_get_clean();
		if ($this->renderInPlace) {
			throw new \Exception("not implemented yet ! ");
		}
		$block = trim($block) ;
		$jsBlockPattern  = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
		if(preg_match($jsBlockPattern,$block,$matches)){
			$block =  $matches['block_content'];
		}

		$this->view->registerJs($block, $this->pos,$this->key) ;
	}
}
