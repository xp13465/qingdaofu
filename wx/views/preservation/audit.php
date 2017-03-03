<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'我的保全','gohtml'=>'<a href="javascript:;" onclick="reloads()">样本展示</a>'])?>
     <section>    
     	<div class="progress">
     		<div class="jin">
     			<div class="jint">
     				<div class="jintu"><img src="/images/an.png"></div>
     				<div class="jinti">
     					<p style="font-size:16px;">保全进度</p>
     					<p style="font-size:12px;color:#ddd;">本平台承诺对您的案件资料和隐私严格保密</p>
     				</div>
     			</div>
     			<div class="jind">
				<?php 
				$a1 = isset($model['status'])&&$model['status']==10?'class="jind_first01"':(in_array($model['status'],[20,30,40])?'class="jind_first02"':'');
				$a2 = isset($model['status'])&&$model['status']==20?'class="jind_second01"':(in_array($model['status'],[30,40])?'class="jind_second02"':'');
				$a3 = isset($model['status'])&&$model['status']==30?'class="jind_second01"':($model['status']==40?'class="jind_second02"':'');
				$a4 = isset($model['status'])&&$model['status']==40?'class="jind_last"':'';
				
				?>
     				<ul>
     					<li <?= $a1 ?>>
     						<p>审核通过</p>
                         </li>
     					<li <?= $a2 ?> >
     						<p>协议已签订</p>
     					</li>
     					<li <?= $a3 ?> >
     						<p>保函已出</p>
     					</li>
     					<li <?= $a4 ?>>
     						<p>完成/退保</p>
     					</li>
     				</ul>     		
     			</div>    
     		</div>
     	</div>
     	<div class="relation">     		
        <ul style="margin-top:12px;">        	 
            <li>
                <div class="lianxi">
                    <div class="kefu">                    
                        <span class="shuzi" style="font-size:18px;">保全详情</span>
                    </div>                 
                </div>
            </li>        
            <li>
                <div class="lianxi">
                    <div class="kefu">                    
                        <span class="shuzi">保全金融</span>
                        <span class="shuzi" style="float:right;padding-right:20px"><?= isset($model['account'])?round($model['account']/10000):''?>万</span>
                    </div>                 
                </div>
            </li>
            <li>
                <div class="lianxi">
                    <div class="kefu">                    
                        <span class="shuzi">管辖法院</span>
                        <span class="shuzi" style="float:right;padding-right:20px"><?= isset($model['fayuan_name'])?$model['fayuan_name']:''?></span>
                    </div>                 
                </div>
            </li>
            <li>
                <div class="lianxi">
                    <div class="kefu">                    
                        <span class="shuzi">取函方式</span>
                        <span class="shuzi" style="float:right;padding-right:20px"><?= isset($model['type'])&&$model['type'] == 1?'自取':'快递'?></span>
                    </div>                 
                </div>
            </li>
            <li>
                <div class="lianxi">
                    <div class="kefu">
                        <?php if($model['type'] == 1){?>
                            <span class="shuzi">取函地址</span>
                            <span class="shuzi"><?= isset($model['fayuan_address'])?$model['fayuan_address']:''?></span>
                        <?php }else if($model['type'] == 2){ ?>
                            <span class="shuzi">收货地址</span>
                            <span class="shuzi"><?= isset($model['address'])?$model['address']:''?></span>
                        <?php } ?>
                        
                    </div>
                </div>
            </li>
        </ul>
    </div>
     	
     	<div class="relation">     		
        <ul style="margin-top:12px;">        	 
            <li>
                <div class="lianxi">
                    <div class="kefu">                    
                        <span class="shuzi" style="font-size:18px;">上传的资料</span>
						<?php if($model['status'] == 1){?>
                        <span class="shuzi"><a href="<?= yii\helpers\Url::toRoute(['/preservation/picture','id'=>$model['id']])?>"  style="float:right;color:#0079FF;padding-right:20px">编辑</a></span>
						<?php } ?>
                    </div>                 
                </div>
            </li>    
            <?php $data = ['qisu'=>'起诉书','caichan'=>'财产保全申请书','zhengju'=>'相关证据资料','anjian'=>'案件受理通知书']?>
            <?php foreach(["qisu","caichan","zhengju","anjian"] as $val):?>
                 <?php if($model[$val]){?>
                  <?php $number = count(explode(',',$model[$val]))?>
               <li>
                <div class="lianxi" data_id="<?= $model[$val] ?>">
                    <div class="kefu">                    
                        <span class="shuzi"><?= $data[$val] ?></span>
                        <span class="shuzi" style="float:right;padding-right:20px">x<?= isset($number)?$number:''?></span>
                    </div>                 
                </div>
              </li>
            <?php }?> 
            <?php endforeach;?>             
        </ul>
    </div>
     </section>
     <script type="text/javascript">
           $(document).ready(function(){
           $('.lianxi').click(function(){
                var id = $(this).attr('data_id');
                $.ajax({
                    url:"<?= yii\helpers\Url::toRoute('/preservation/picturecategory')?>",
                    type:'post',
                    data:{id:id},
                    dataType:'json',
                    success:function(json){
                        if(json.code="0000"){
				            WeixinJSBridge.invoke('imagePreview', {  
                                  'current' : json.piclist[0],  
						          'urls' : json.piclist  
					         });
                	    }	
                    }
                     
                  })
                })
            });
            
      function reloads(){
					var wx = "<?php echo Yii::$app->params['wxs'];?>";
					var pic_list = [''+wx+'/images/baodan1.jpg',''+wx+'/images/baodan2.jpg',''+wx+'/images/baodan3.jpg']
					WeixinJSBridge.invoke('imagePreview', {  
                                      'current' : pic_list[0],  
						              'urls' : pic_list
					             });
				};
          
          
     </script>

