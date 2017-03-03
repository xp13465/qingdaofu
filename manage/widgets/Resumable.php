<?php
namespace manage\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Resumable extends Widget{
	public $model= '';
	public function init(){
        parent::init();
		echo '
			';
		 
    }

    public function run(){
        return 12345;
    }
	
}