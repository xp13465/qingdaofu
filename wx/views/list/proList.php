<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
?>
<?php if($type == 1){ ?>
<?=wxHeaderWidget::widget(['title'=>'债权文件','gohtml'=>''])?>
<?php }else if($type == 2){ ?>
<?=wxHeaderWidget::widget(['title'=>'债权人信息','gohtml'=>''])?>
<?php }else{ ?>
<?=wxHeaderWidget::widget(['title'=>'债务人信息','gohtml'=>''])?>
<?php } ?>
<?php $credis = isset($credi['creditorfile'])?unserialize($credi['creditorfile']):''?>
<?php $credic = isset($credi['creditorinfo'])?unserialize($credi['creditorinfo']):''?>
<?php $credib = isset($credi['borrowinginfo'])?unserialize($credi['borrowinginfo']):''?>
<form>
    <section>
        <?php if($type == 1){ ?>
            <section>
                <div class="debt_file">
                    <ul>
                        <li>
                            <div class="file_l">
                                <span class="debt01"></span>
                                <span>公证书</span>
                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgnotarization',isset($credis['imgnotarization'])?$credis['imgnotarization']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                        <li>
                            <div class="file_l">
                                <span class="debt02"></span>
                                <span>借款合同</span>
                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgcontract',isset($credis['imgcontract'])?$credis['imgcontract']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                        <li>
                            <div class="file_l">
                                <span class="debt03"></span>
                                <span>他项权证</span>

                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgcreditor',isset($credis['imgcreditor'])?$credis['imgcreditor']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                        <li>
                            <div class="file_l">
                                <span class="debt04"></span>
                                <span>付款凭证</span>
                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgpick',isset($credis['imgpick'])?$credis['imgpick']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                        <li>
                            <div class="file_l">
                                <span class="debt05"></span>
                                <span>收据</span>
                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgshouju',isset($credis['imgshouju'])?$credis['imgshouju']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                        <li>
                            <div class="file_l">
                                <span class="debt06"></span>
                                <span>备注</span>
                            </div>
                            <?php echo \yii\helpers\Html::hiddenInput('imgbenjin',isset($credis['imgbenjin'])?$credis['imgbenjin']:'');?>
                            <span class="file_look">查看</span>
                        </li>
                    </ul>
                </div>
            </section>
        <?php }else if($type == 2 && !empty($credic)){ ?>
            <?php foreach($credic as $value){ ?>
                <?php if(!empty($value['creditorname'])){ ?>
                <ul class="add_xi">
                    <li>
                        <span>姓名</span>
                        <span><?php echo isset($value['creditorname'])?$value['creditorname']:'暂无'?></span>
                    </li>
                    <li>
                        <span>联系方式</span>
                        <span><?php echo isset($value['creditormobile'])?$value['creditormobile']:'暂无'?></span>
                    </li>
                    <li>
                        <span>联系地址</span>
                        <span><?php echo isset($value['creditoraddress'])?$value['creditoraddress']:'暂无'?></span>
                    </li>
                    <li>
                        <span>证件号</span>
                        <span><?php echo isset($value['creditorcardcode'])?$value['creditorcardcode']:'暂无'?></span>
                    </li>
					<li>
                    <?php if(!empty($value['creditorcardimage'])){ ?>
                        <?php $cardimg = explode(',',$value['creditorcardimage']);?>
                            <div class="add_file">
                                <?php foreach($cardimg as $v){ ?>
                                <div class="add_file_l"><img src="<?php echo isset($v)?Yii::$app->params['wx'].substr($v,1,-1):''?>" style="height:50px; width:50px;"/></div>
                                <?php } ?>
								<!--<?php foreach($cardimg as $v){ ?>
                                <span class="add_file_r" style="width:8%;margin:10px"><img src="<?php echo isset($v)?Yii::$app->params['wx'].substr($v,1,-1):''?>" style="height:50px; width:50px;"/></span>
                                <?php } ?>-->
							</div>
                    <?php } ?>
					</li>
                </ul>
            <?php } ?>
           <?php }?>
        <?php }else if($type == 3 && !empty($credib)){ ?>
            <?php foreach($credib as $value){ ?>
                <?php if(!empty($value['borrowingname'])){ ?>
                <ul class="add_xi">
                    <li>
                        <span>姓名</span>
                        <span><?php echo isset($value['borrowingname'])?$value['borrowingname']:'暂无'?></span>
                    </li>
                    <li>
                        <span>联系方式</span>
                        <span><?php echo isset($value['borrowingmobile'])?$value['borrowingmobile']:'暂无'?></span>
                    </li>
                    <li>
                        <span>联系地址</span>
                        <span><?php echo isset($value['borrowingaddress'])?$value['borrowingaddress']:'暂无'?></span>
                    </li>
                    <li>
                        <span>证件号</span>
                        <span><?php echo isset($value['borrowingcardcode'])?$value['borrowingcardcode']:'暂无'?></span>
                    </li>
					<li>
                    <?php if(!empty($value['borrowingcardimage'])){ ?>
                        <?php $cardimg = explode(',',$value['borrowingcardimage']);?>
                            <div class="add_file">
                            	<?php foreach($cardimg as $v){ ?>
                                <div class="add_file_l"><img src="<?php echo isset($v)?Yii::$app->params['wx'].substr($v,1,-1):''?>" style="height:50px; width:50px;"/></div>
                                <?php } ?>
								<!--<?php foreach($cardimg as $v){ ?>
                                <span class="add_file_r" style="width:8%;margin:10px"><img src="<?php echo isset($v)?Yii::$app->params['wx'].substr($v,1,-1):''?>" style="height:50px; width:50px;"/></span>
                                 <?php } ?>-->
							</div>
                    <?php } ?>
					</li>
                </ul>
                    <?php } ?>
            <?php } ?>
        <?php } else{ ?>
            <ul class="add_xi">
                <li>
                    <span>姓名</span>
                    <span>暂无</span>
                </li>
                <li>
                    <span>联系方式</span>
                    <span>暂无</span>
                </li>
                <li>
                    <span>联系地址</span>
                    <span>暂无</span>
                </li>
                <li>
                    <span>证件号</span>
                    <span>暂无</span>
                </li>
            </ul>
        <?php } ?>
    </section>
</form>
<script>
    $(document).ready(function(){
        var w = document.body.clientWidth;
        var h = document.body.clientHeight;
    $(document).delegate('.file_look', 'click', function () {
        var typeName = $(this).prev('input:hidden').attr("name");
        var name = $(this).prev('input:hidden').val();
        $.msgbox({
            closeImg: '/images/close.png',
            async: false,
            width: w * 0.7,
            height: h * 0.6,
            title: '显示照片',
            content: "<?php echo \yii\helpers\Url::toRoute(["/common/viewimages"])?>/?name=" + name + "&typeName=" + typeName,
            type: 'ajax'
        });
    });
    })
</script>

