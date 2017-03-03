<?php 
$data = (new \yii\db\Query())
		->select('id,file')
		->from('zcb_files')
		->where(["id"=>explode(",",$model->cardimgimg)])
		->limit(2)
		->all();
?>
<div class="rz_cons">
    <ul class="rz_top">
        <li class="<?php if($type == 1){echo 'current';}?> li_11 active" id="1" >
            <span class="personal" style="<?php if($type == 1){echo 'background:url(/images/personal-s.png) no-repeat';}else{echo 'background:url(/images/personal02.png) no-repeat';}?>"></span>
            <span>个人</span>
        </li>
        <li class="<?php if($type == 2){echo 'current';}?> li_22" id="2" >
            <span class="firm "  style="<?php if($type == 2){echo 'background:url(/images/firm02.png) no-repeat';}else{echo 'background:url(/images/firm.png) no-repeat';}?>"></span>
            <span>律所</span>
        </li>
        <li class="<?php if($type == 3){echo 'current';}?> li_33" id="3" >
            <span class="gongsi"  style="<?php if($type == 3){echo 'background:url(/images/company02.png) no-repeat';}else{echo 'background:url(/images/company.png) no-repeat';}?>"></span>
            <span>公司</span>
        </li>
    </ul>
    <div class="qh">
        <form name="cerone">
            <div class="inp inp2 <?php if($type == 1){echo 'current';}?>" >
                <ul style="padding-top:0px;">
				    <h3>基本信息</h3>
                    <li>
                        <span><i class="red">*</i>姓名</span>
                        <input type="text" placeholder="请输入您的姓名" name="name" value="<?php echo isset($model->category)&&$model->category == 1?$model->name:'' ?>"/> <i></i>
                    </li>
                    <li>
                        <span><i class="red">*</i>身份证</span>
                        <input type="text" placeholder="请输入您的身份证号"  name="cardno" value="<?php echo isset($model->category)&&$model->category == 1?$model->cardno:'' ?>"/> <i></i>
                    </li>


                     <li class='pic'>
					    <span style="vertical-align:middle; float:left;"><i class="red">*</i>身份证</span>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimgId1',isset($model->cardimgimg)?$model->cardimgimg:'',['cardimgId'=>'cardimgId1','class'=>'car']);?>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimg1',isset($model->cardimg)?$model->cardimg:'',['id'=>'cardimg1','cardimg'=>'cardimg1','class'=>'cars']);?>
				        <?php if($type == 1&&$data){?>
							<?php  $i=1;$b=1;?>
							<?php foreach($data as $k=>$v){ ?>
							<sapn style="margin-left:20px;">
								<i class="closebutton" data-type='<?php echo $type; ?>' style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><img src="<?php echo isset($v['file'])?Yii::$app->params['www'].$v['file']:''?>" style="display:inline-block;" class="add_tu_0<?php echo $b++?>" inputName="cardimg" cardimgId="cardimgId" limit="2" data_type='<?php echo $i++?>' data_bid="<?php echo $v['id']; ?>" width="120" height="120"/>
							</sapn>
							<?php } ?>
							<?php if(count($data) == 1){ ?>
							<span style="margin-left:20px;"><img src="/images/pian.jpg" inputName='cardimg1' class='add_tu_02' limit = '5' cardimgId="cardimgId1" width="120" height="120" data_type='2'></span>
							<?php } ?>
			
						<?php }else{ ?>
							<span style="margin-left:20px;"><img src="/images/pian.jpg" inputName='cardimg1' class='add_tu_01' limit = '5' cardimgId="cardimgId1" width="120" height="120" data_type='1'></span>
							<span style="margin-left:20px;"><img src="/images/pian.jpg" inputName='cardimg1' class='add_tu_02' limit = '5' cardimgId="cardimgId1" width="120" height="120" data_type='2'></span>
                            <div style="float:right;margin-right:220px;color:#686868;">
                                <p>请上传手持身份证正反面</p>
                                <p>文件小于2M</p>
                                <p>支持JPG/PNG/BMP等格式图片 </p>
                            </div>
                           
						<?php } ?>
					</li>

					
                    <li>
                        <span>邮箱</span>
                        <input type="text" placeholder="请输入您的常用邮箱"  name="email" value="<?php echo isset($model->category)&&$model->category == 1?$model->email:'' ?>"/> <i></i>
                    </li>
                </ul>
                <?php echo \yii\helpers\Html::hiddenInput('types',1);?>
                <a href="javascript:void(0)" class="renz">认证</a>
            </div>
        </form>
        <form name="certwo">
        <div class="inp inp2 <?php if($type == 2){echo 'current';}?>">
            <ul style="padding-top:0px;">
			    <h3>基本信息</h3>
                <li>
                    <span><i class="red">*</i>律所名称</span>
                    <input type="text" placeholder="请输入律所名称" name="name" value="<?php echo isset($model->category)&&$model->category == 2?$model->name:'' ?>" /> <i></i>
                </li>
                <li class="sc_mid">
                    <span><i class="red">*</i>执业证号</span>
                    <input type="text" placeholder="请输入律所的执业证号" name="cardno" value="<?php echo isset($model->category)&&$model->category == 2?$model->cardno:'' ?>"/><i></i>
                </li>

                <li class='pic'>
					    <span style="vertical-align:middle;float:left;"><i class="red">*</i>上传图片</span>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimgId2',isset($model->cardimgimg)?$model->cardimgimg:'',['cardimgId'=>'cardimgId2','class'=>'car']);?>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimg2',isset($model->cardimg)?$model->cardimg:'',['id'=>'cardimg2','cardimg'=>'cardimg2','class'=>'cars']);?>
				        <?php if($type == 2&&$data){?>
						   <?php  $i=1;$b=1;?>
							<?php foreach($data as $k=>$v){ ?>
								<sapn style="margin-left:100px;">
									<i class="closebutton" data-type='<?php echo $type; ?>' style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><img src="<?php echo isset($v['file'])?Yii::$app->params['www'].$v['file']:''?>" style="display:inline-block;" class="add_tu_01"  inputName="cardimg" cardimgId="cardimgId" limit="2"  data_bid="<?php echo $v['id']; ?>" width="120" height="120"/>
								</sapn>
							<?php } ?>
							<?php if(count($data) == 1){ ?>
							<!--<span style="margin-left:100px;"><img src="/images/pian.jpg" inputName='cardimg1' class='add_tu_02' limit = '5'  cardimgId="cardimgId1" width="120" height="120" data_type='2'></span>-->
							<?php } ?>
						<?php }else{ ?>
							<span style="margin-left:100px;"><img src="/images/pian.jpg" inputName='cardimg2' class='add_tu_01' limit = '5' cardimgId="cardimgId2" width="120" height="120" data_type='1'></span>
							<!--<span style="margin-left:100px;"><img src="/images/pian.jpg" inputName='cardimg2' class='add_tu_02'  limit = '5' cardimgId="cardimgId2" width="120" height="120" data_type='2'></span>-->
						    <div style="float:right;margin-right:220px;color:#686868;">
                                <p>请上传手持身份证正反面</p>
                                <p>文件小于2M</p>
                                <p>支持JPG/PNG/BMP等格式图片 </p>
                            </div>
						
						<?php } ?>
					</li>
					
					
                <li class="sh_c">
                    <span><i class="red">*</i>联系人</span>
                    <input type="text" placeholder="请输入律所联系人的名称"  name="contact" value="<?php echo isset($model->category)&&$model->category == 2?$model->contact:'' ?>"/> <i></i>
                </li>
                <li>
                    <span><i class="red">*</i>联系方式</span>
                    <input type="text" placeholder="请输入律所联系人的联系方式"  name="mobile" value="<?php echo isset($model->category)&&$model->category == 2?$model->mobile:'' ?>"/> <i></i>
                </li>
				<h3>业务信息</h3>
                <li>
                    <span>邮箱</span>
                    <input type="text" placeholder="请输入律所的常用邮箱"  name="email" value="<?php echo isset($model->category)&&$model->category == 2?$model->email:'' ?>"/> <i></i>
                </li>
                <li class="anli">
                    <span>经典案例</span>
					<textarea  cols="30" rows="10"  name="casedesc" placeholder="关于律所在诉讼，清收等方面的成功案例，将作为展示宣传之用，有利于发布方更加青睐你噢！" ><?php echo isset($model->category)&&$model->category == 2?$model->casedesc:'' ?></textarea> <i></i>
                </li>
            </ul>
            <?php echo \yii\helpers\Html::hiddenInput('types',2);?>
            <a href="javascript:void(0)" class="renz">认证</a>
        </div>
        </form>
        <form name="certhree">
        <div class="inp inp2 <?php if($type == 3){echo 'current';}?>">
            <ul>
                <h3>基本信息</h3>
                <li>
                    <span><i class="red">*</i>企业名称</span>
                    <input type="text" placeholder="请输入公司名称"   name="name"  value="<?php echo isset($model->category)&&$model->category == 3?$model->name:'' ?>"/> <i></i>
                </li>
                <li class="sc_mid">
                    <span><i class="red">*</i>营业执照号</span>
                    <input type="text" placeholder="请输入公司的营业执照号"   name="cardno" value="<?php echo isset($model->category)&&$model->category == 3?$model->cardno:'' ?>"/> <i></i>
                </li>
				
				
					<li class='pic'>
					    <span style="vertical-align:middle;float:left;"><i class="red">*</i>上传图片</span>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimgId3',isset($model->cardimgimg)?$model->cardimgimg:'',['cardimgId'=>'cardimgId3','class'=>'car']);?>
					    <?php echo \yii\helpers\Html::hiddenInput('cardimg3',isset($model->cardimg)?$model->cardimg:'',['id'=>'cardimg3','cardimg'=>'cardimg3','class'=>'cars']);?>
				        <?php if($type == 3&&$data){?>
						    <?php  $i=1;$b=1;?>
							<?php foreach($data as $k=>$v){ ?>
							<sapn style="margin-left:100px;">
								<i class="closebutton"  data-type='<?php echo $type; ?>' style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><img src="<?php echo isset($v['file'])?Yii::$app->params['www'].$v['file']:''?>" style="display:inline-block;" class="add_tu_01" inputName="cardimg" cardimgId="cardimgId" limit="2"  data_bid="<?php echo $v['id']; ?>" width="120" height="120"/>
							</sapn>
							<?php } ?>
							<?php if(count($data) == 1){ ?>
							 <!--<span style="margin-left:100px;"><img src="/images/pian.jpg" inputName='cardimg1' class='add_tu_02' limit = '5' cardimgId="cardimgId1" width="120" height="120" data_type='2'></span>-->
							<?php } ?>
						<?php }else{ ?>
							<span style="margin-left:100px;"><img src="/images/pian.jpg" inputName='cardimg3' class='add_tu_01' limit = '5' cardimgId="cardimgId3" width="120" height="120" data_type='1'></span>
							<!-- <span style="margin-left:20px;"><img src="/images/pian.jpg" inputName='cardimg3' class='add_tu_02' limit = '5' cardimgId="cardimgId3" width="120" height="120" data_type='2'></span>-->
						   <div style="float:right;margin-right:220px;color:#686868;">
                                <p>请上传手持身份证正反面</p>
                                <p>文件小于2M</p>
                                <p>支持JPG/PNG/BMP等格式图片 </p>
                            </div>
						
						<?php } ?>
					</li>
					  
					  
                <li class="sh_c">
                    <span><i class="red">*</i>联系人</span>
                    <input type="text" placeholder="请输入公司联系人的名称"   name="contact" value="<?php echo isset($model->category)&&$model->category == 3?$model->contact:'' ?>"/> <i></i>
                </li>
                <li>
                    <span><i class="red">*</i>联系方式</span>
                    <input type="text" placeholder="请输入公司联系人的联系方式"   name="mobile" value="<?php echo isset($model->category)&&$model->category == 3?$model->mobile:'' ?>"/> <i></i>
                </li>
                <li>
                    <span>企业邮箱</span>
                    <input type="text" placeholder="请输入公司的常用邮箱"   name="email" value="<?php echo isset($model->category)&&$model->category == 3?$model->email:'' ?>"/> <i></i>
                </li>
                <h3>业务信息</h3>
                <li>
                    <span>企业经营地址</span>
                    <input type="text" placeholder="请输入公司经营地址"   name="address" value="<?php echo isset($model->category)&&$model->category == 3?$model->address:'' ?>"/> <i></i>
                </li>
                <li>
                    <span>公司网站</span>
                    <input type="text" placeholder="请输入公司网站地址"   name="enterprisewebsite" value="<?php echo isset($model->category)&&$model->category == 3?$model->enterprisewebsite:'' ?>"/> <i></i>
                </li>
                <li class="anli">
                    <span>经典案例</span>
					<textarea  cols="30" rows="10"  name="casedesc" placeholder="关于公司在诉讼，清收等方面的成功案例，将作为展示宣传之用，有利于发布方更加青睐你噢！"><?php echo isset($model->category)&&$model->category == 3?$model->casedesc:'' ?></textarea> <i></i>
                </li>
            </ul>
            <?php echo \yii\helpers\Html::hiddenInput('types',3);?>
            <a href="javascript:void(0)" class="renz">认证</a>
        </div>
        </form>
    </div>
</div>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<input  style='display:none' type="file" name="Filedata" id='id_photos' value="" />
<script type="text/javascript">
    $(function(){
        var pic_list_init = ["url(/images/personal02.png)","url(/images/firm.png)","url(/images/company.png)"];
        var pic_list = ["url(/images/personal-s.png)","url(/images/firm02.png)","url(/images/company02.png)"];
        var type = <?php echo $type;?>;
        var next_position = $('.rz_top li.current').index();
        var currentIndex = $('.rz_top li.current').index();
         $('.rz_top li').click(function (event){
			 var category = "<?php echo $model->category;?>";
			 var modify = "<?php echo Yii::$app->request->get('modify');?>";
			 if(!category || modify == 3){
				 if(currentIndex == $(this).index())return false;
                 currentIndex = $(this).index();
                 $(this).children().first().css("background", pic_list[$(this).index()]);
                 $('.rz_top').children().eq(next_position).children().first().css("background", pic_list_init[next_position]);
                 $(this).addClass('current').siblings().removeClass('current');
                 $('.qh .inp').hide();
                 $('.qh .inp').eq($(this).index()).show();
                 next_position = $(this).index();
			 }
             
         });

        function printError(inputs,textareas,type){
            var vildateFlag = true;
            var validateCore = [['name','cardno','email','casedesc'],['name','cardno','contact','mobile','email','casedesc'],['name','cardno','contact','mobile','email','enterprisewebsite','casedesc']];
            for(var i = 0 ;i < inputs.length ;i++){
                if($.inArray($(inputs[i]).attr('name'),validateCore[type-1])>-1){
                    if(type==1){
                        switch ($(inputs[i]).attr('name')){
                            case 'name':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('用户姓名必填');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'cardno':if(!idCardNoUtil.checkIdCardNo($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('身份证号码错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                           // case 'cardimg[1]':if($.trim($(inputs[i]).val())==''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('身份证图片未上传');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                           // case 'email':if(!validateCheck.isEmail($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('email地址错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }else if(type == 2){
                        switch ($(inputs[i]).attr('name')){
                            case 'name':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('律所名称必填');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'cardno':if(!validateCheck.isZhiyezhenghao($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('执业证号错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'cardimg':if($.trim($(inputs[i]).val())==''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('执业证图片未上传');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            //case 'contact':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('联系人必填');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'mobile':if(!validateCheck.isTel($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('联系人手机号错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                           // case 'email':if(!validateCheck.isEmail($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('email地址错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }else if(type == 3){
                        switch ($(inputs[i]).attr('name')){
                            case 'name':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('企业名称必填');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'cardno':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('营业执照号错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            //case 'cardimg':if($.trim($(inputs[i]).val())==''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('营业执照图片未上传');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'contact':if($.trim($(inputs[i]).val()) == ''){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('联系人必填');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'mobile':if(!validateCheck.isTel($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('联系人手机号错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                            case 'enterprisewebsite':if(!validateCheck.isNet($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('网址错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                           // case 'email':if(!validateCheck.isEmail($(inputs[i]).val())){$(inputs[i]).next(i).addClass('error').removeClass('yesshow').html('email地址错误');vildateFlag = false;}else{$(inputs[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }
                }
            }

            for(var i = 0 ;i < textareas.length ;i++){
                if($.inArray($(textareas[i]).attr('name'),validateCore[type-1])>-1){
                    if(type==1){
                        switch ($(textareas[i]).attr('name')){
                            case 'casedesc':if($.trim($(textareas[i]).val()) == ''||$.trim($(textareas[i]).val()) == '关于公司在诉讼，清收等方面的成功案例，将作为展示宣传之用，有利于发布方更加青睐你噢！'){$(textareas[i]).next(i).addClass('error').removeClass('yesshow').html('');}else{$(textareas[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }else if(type==2){
                        switch ($(textareas[i]).attr('name')){
                            case 'casedesc':if($.trim($(textareas[i]).val()) == ''||$.trim($(textareas[i]).val()) == '关于公司在诉讼，清收等方面的成功案例，将作为展示宣传之用，有利于发布方更加青睐你噢！'){$(textareas[i]).next(i).addClass('error').removeClass('yesshow').html('');}else{$(textareas[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }else if(type==3){
                        switch ($(textareas[i]).attr('name')){
                            case 'casedesc':if($.trim($(textareas[i]).val()) == ''||$.trim($(textareas[i]).val()) == '关于公司在诉讼，清收等方面的成功案例，将作为展示宣传之用，有利于发布方更加青睐你噢！'){$(textareas[i]).next(i).addClass('error').removeClass('yesshow').html('');}else{$(textareas[i]).next(i).removeClass('error').addClass('yesshow').html('');}break;
                        }
                    }
                }
            }

            return vildateFlag;
        }


        $('textarea').focusout(
            function(){
                var type = $(this).parent().parent().parent('div').children('input[type="hidden"]').val();
                var inputs = $(this).parent().parent('ul').children().children('input');
                var textareas =$(this).parent().parent('ul').children().children('textarea');
                printError(inputs,textareas,type);
            }
        );

        var validateCheck = {
            isEmail:function(email){
                var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                return myreg.test(email);
            },
            isMobile:function(mobile){
                var myreg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
                return myreg.test(mobile);
            },
            isTel:function(mobile){
                var myreg = /^\d{1,}$/;
                return myreg.test(mobile);
            },
            isNet:function(net){
                var myreg = /^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/;
                return !net||myreg.test(net);
            },
            isZhiyezhenghao:function(zhiyezhenghao){
                var myreg = /^\d{17}$/;
                return myreg.test(zhiyezhenghao);
            },
        };
        $('.renz').click(function(){
            var type = $(this).prev('input[type="hidden"]').val();
            var inputs = $(this).parent().parent('form').children().children().children().children('input');
            var textareas =$(this).parent().parent('form').children().children().children().children('textarea');
            if(!printError(inputs,textareas,type))return false;
            var uid ="<?php echo isset($model->uid)&&$model->uid ?>";
            if(uid){
                var modifyUrl ="<?php echo \yii\helpers\Url::toRoute('/certification/perfect')?>";
            }else{
                var modifyUrl = "<?php echo \yii\helpers\Url::toRoute('/certification/add')?>";
            }
            $.ajax({
                type:'post',
                url:modifyUrl,
                data:$(this).parent().parent('form').serialize(),types:type,
                dataType:'json',
                success:function(json){
                    if(json.code != '0000')alert(json.msg);
                    else{
                        window.location = "<?php echo \yii\helpers\Url::toRoute('/certification/index')?>";
                    }
                }
            });
        });
	})
</script>
<script>
$(document).ready(function(){

	//照片异步上传
	$(document).on("click",".closebutton",function(){
		        var wx = "<?php echo Yii::$app->params['www'];?>";
				var cardimgId = $(this).parents('.pic').children('.car').attr('cardimgId');
				var cardimg = $(this).parents('.pic').children('.cars').attr('cardimg');
                var id = $(this).parents().children('input[name='+cardimgId+']').val();
                var bid = $(this).next().attr('data_bid');
				var data_type = $(this).next().attr('data_type');
				//alert(types);
                var temp='';
                var ids =id.split(',');
                for(i in ids){
                    		if(ids[i]==bid){
                    			continue;
                    		}
                    		temp+=temp?","+ids[i]:ids[i];
                    	}
				var src = $(this).next('img').attr('src').replace(wx,'');
				var img = $(this).parents().children('input[name='+cardimg+']').val();
                            img = img.replace(src,"");
                            img = img.replace(",''","");
                            img = img.replace("'',","");
                            img = img.replace("''","");
                            $('input[name='+cardimg+']').val(img);			
               	$(this).parents().children('input[name='+cardimgId+']').val(temp);
				var type = $(this).attr('data-type');
				if(type == 1){
					if(data_type == 1){
					 var imgs = '<img src="/images/pian.jpg" inputName="'+cardimg+'" class="add_tu_01" limit = "2" cardimgId="'+cardimgId+'" width="120" height="120" data_type="1">';
				     $(this).parent().html(imgs);
		
					}else{
						var imgs = '<img src="/images/pian.jpg" inputName="'+cardimg+'" class="add_tu_02" limit = "2" cardimgId="'+cardimgId+'" width="120" height="120" data_type="2">';
						$(this).parent().html(imgs);
		
					}
				}else{
					 var imgs = '<img src="/images/pian.jpg" inputName="'+cardimg+'" class="add_tu_01" limit = "2" cardimgId="'+cardimgId+'" width="120" height="120" data_type="1">';
				     $(this).parent().html(imgs);
				}
				
    });
	 
	 //照片异步上传
	$(document).on('click',".add_tu_01",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		var cardimgId = $(this).attr('cardimgId')?$(this).attr('cardimgId'):'';
		var data_type = $(this).attr('data_type');
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"cardimgId":cardimgId,"limit":limit,'data_type':data_type}).click();
	})
	
	$(document).on('click',".add_tu_02",function(){
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var inputName = $(this).attr('inputName')?$(this).attr('inputName'):'';
		var cardimgId = $(this).attr('cardimgId')?$(this).attr('cardimgId'):'';
		var data_type = $(this).attr('data_type');
		if(!inputName)return false;
		$("#id_photos").attr({"inputName":inputName,"cardimgId":cardimgId,"limit":limit,'data_type':data_type}).click();
	})
	$(document).on("change",'#id_photos',function(){ //此处用了change事件，当选择好图片打开，关闭窗口时触发此事件
		var index = layer.load(1, {
		  shade: [0.4,'#fff'] //0.1透明度的白色背景
		});
		var inputName = $(this).attr('inputName');
		var cardimgId = $(this).attr('cardimgId');
		var limit = $(this).attr('limit')?$(this).attr('limit'):2;
		var aa = $("#"+inputName).val();
		var bb = $('input[name='+cardimgId+']:hidden').val();
		// if(limit&&aa.split(",").length>=limit){
			// layer.close(index)
			// layer.alert("最多上传"+limit+"张图片");
			// $("#"+inputName).parents(".pic").hide()
			// return false;
		// } 
		if(!inputName)return false;
		$.ajaxFileUpload({
			url:"<?php echo yii\helpers\Url::toRoute(['/site/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
			type: "POST",
			secureuri: false,
			fileElementId: 'id_photos',
			data: {'_csrf':'<?=Yii::$app->getRequest()->getCsrfToken()?>'},
			textareas:{},
			dataType: "json",
			success: function (data) {
				layer.close(index) 
				var wx = "<?php echo Yii::$app->params['www'];?>";
				if(data.error == '0'&&data.fileid){					
					var aa = $("#"+inputName).val();
                    var bb = $('input[name='+cardimgId+']:hidden').val();					
					if(limit&&aa.split(",").length>=limit){
						layer.alert("最多上传"+limit+"张图片");
						$("#"+inputName).parents(".pic").hide()
						return false;
					}   				
                    $("input[name='" + cardimgId + "']").val((bb ? (bb + ",") : '')+data.fileid).trigger("change");										
					$('input[name=' + inputName + ']').val((aa ? (aa + ",") : '')+"'"+data.url+"'").trigger("change"); 
					if($('input[name=Filedata]').attr('data_type')==1){
							 var div = '<i class="closebutton" style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><img src="'+wx+data.url+'"  data_bid="'+data.fileid+'" style="display:inline-block;" class="add_tu_06" inputName="cardimg" cardimgId="cardimgId" limit="2" data_type="1" width="120" height="120"/>';	
							 var spandiv = $("input[name='" + inputName + "']").next().html(div);
						}else{
							var div = '<i class="closebutton" style="position:absolute;"><img src="/images/hint33.png" width="35" height="35"></i><img src="'+wx+data.url+'"  data_bid="'+data.fileid+'" style="display:inline-block;" class="add_tu_06" inputName="cardimg" cardimgId="cardimgId"  limit="2" data_type="2" width="120" height="120"/>';	
							var spandiv = $("input[name='" + inputName + "']").next().next().html(div);
						}
				}else if(data.msg){
					layer.alert(""+data.msg)
				}
			},
			error:function(){
				layer.close(index)
			}
		}); 
	});
	$(document).on("click",".add_tu_06",function(){
		var   photojson = {
			"status": 1,
			"msg": "查看",
			"title": "查看",
			"id": 0,
			"start": 0,
			"data": []
		};
		photojson.data.push({
			"alt": "",
			"pid": 0,
			"src": $(this).attr("src"),
			"thumb": ""
		})
		 
		layer.photos({
			area: ['auto', '80%'],
			photos: photojson
		});
    })
});
</script>	