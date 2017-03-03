<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 2016/4/13
 * Time: 14:22
 */
namespace wx\widget;

use yii;
use yii\base;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class wxHeaderWidget extends Widget{
    public $title;
    public $backurl=true;
    public $homebtn=true;
    public $reload;
    public $gohtml;
	public $fork = false;

    public function init(){
        parent::init();
        if($this->title == null){
            $this->title = '清道夫债管家';
        }

    }

    public function run(){
        return $this->render('wxHeaderWidget',['title'=>$this->title,'gohtml'=>$this->gohtml,'backurl'=>$this->backurl,'homebtn'=>$this->homebtn,'reload'=>$this->reload,'fork'=>$this->fork]);
    }
}