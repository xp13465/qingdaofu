<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
use yii\helpers\ArrayHelper;
?>
<?=wxHeaderWidget::widget(['title'=>'发布诉讼','gohtml'=>'<a href="javascript:void(0)" class="publishCls">保存</a>'])?>

<form name = "creditor" id = "creditor">
<section>
<?php $datas = yii\helpers\Json::decode($data);?>
<?php echo Html::hiddenInput('id',isset($datas['id'])?$datas['id']:'')?>
<?php echo Html::hiddenInput('category',isset($datas['category'])?$datas['category']:'')?>
<?php echo Html::hiddenInput('progress_status','1')?>
    <div class="information">
        <span></span>
        <span>基本信息</span>
    </div>
    <ul class="infor">
         <li>
            <div class="infor_l">
                <span>借款本金</span>
                <?php echo Html::input('text','money',isset($datas['money'])?$datas['money']:'',['placeholder'=>'逾期未还的债权本金'])?>
                <span>万</span>
            </div>
        </li>
		
		
		<li>
        <div class="infor_l">
         <span>费用类型</span>
          <div class="select-area"> 
          <i class="select-value" style="color:#000;font-size:16px;width:60%;">请选择</i>
		  <?php echo Html::dropDownList('agencycommissiontype',isset($datas['agencycommissiontype'])?$datas['agencycommissiontype']:'',[''=>'请选择','1'=>'固定费用','2'=>'风险费率'],['style'=>"width:65%;left:35%;height:50px;"])?>
          </div>
          </span> 
        </div>
      </li>
       <li class="selected03" style="display:none;" >
        <div class="infor_l">
         <span>固定费用</span>
		  <?php echo Html::input('text','agencycommission',isset($datas['agencycommissiontype'])&&$datas['agencycommissiontype']==1?$datas['agencycommission']:'',['placeholder'=>'请输入费用','style'=>'width:55%;'])?>
          <i style="font-size:16px;color:#0065b3;margin-right:15px;float:right;"></i> <span>
          </span> 
        </div>
      </li>
        <li class="selected04" style="display:none;" >
        <div class="infor_l">
         <span>风险费率</span>
          <?php echo Html::input('text','agencycommissions',isset($datas['agencycommissiontype'])&&$datas['agencycommissiontype']==2?$datas['agencycommission']:'',['placeholder'=>'请输入费用','style'=>'width:55%;'])?>
          <i style="font-size:16px;color:#0065b3;margin-right:15px;float:right;"></i> <span>
          </span> 
        </div>
      </li>

        <li class="li_infor">
        <div class="infor_l">
         <span class="infor_loan_l">债权类型</span>
		  <?php echo Html::dropDownList('loan_type',isset($datas['loan_type'])?$datas['loan_type']:'',[''=>'请选择','1'=>'房产抵押','3'=>'机动车抵押','2'=>'应收账款','4'=>'无抵押'],['style'=>"width:65%;left:35%;height:50px;background:#fff;-webkit-appearance:none;"])?>
        </div>
      </li>
        <li class="infor_li infor_li01 guaran" style="border-bottom:1px solid #ddd; display: none;" >
            <div class="infor_l">
                <span>抵押物地址</span>
                <?php echo  html::dropDownList('province_id',$datas['province_id']?$datas['province_id']:'310000',\wx\services\Func::getProvince(),["class"=>"selects selects_three"]);?> <i></i>
                <?php echo  html::dropDownList('city_id',$datas['city_id']?$datas['city_id']:'310100',$datas['province_id']=='310000'||$datas['province_id']==''?\wx\services\Func::getCityByProvince('310000'):\wx\services\Func::getCityByProvince($datas['province_id']),["class"=>"selects selects_three"]);?> 
                <?php echo  html::dropDownList('district_id',$datas['district_id'],$datas['city_id']=='310100'||$datas['city_id']==''?\wx\services\Func::getDistrictByCity('310100'):\wx\services\Func::getDistrictByCity($datas['city_id']),["class"=>"selects selects_three"]);?>
            </div>
        </li>
        <li class="infor_li guaran" style="border-bottom:1px solid #ddd;display: none;">
            <div class="infor_l">
                <span></span>
                <?php echo Html::textarea('seatmortgage',isset($datas['seatmortgage'])?$datas['seatmortgage']:'',['placeholder'=>'详细地址','style'=>'padding-top:5px;height:29px','id'=>'seatmortgage'])?>
            </div>
        </li>
        <li class="car infor_l " style="display: none;">
            <span>机动车品牌</span>
            <select name="carbrand" class="brand-cls selects selects_two">
                <option value="">请选择车品牌</option>

                <?php
                $json = \wx\services\Func::CurlPost(yii\helpers\Url::toRoute(['/wap/common/brand']));
                $jsonArr = \yii\helpers\Json::decode($json);
                foreach ($jsonArr as $k=>$v) {
                    echo "<option value='".$k."'>{$v}</option>";
                }
                ?>
            </select>
            <select name="audi" class="brand-child-cls selects selects_two">
                <option value="">请选择车系</option>

            </select>
        </li>
        <li class="car infor_l" style="display: none;font-size: 12px;">
            <span>车牌类型</span>
            <input type="radio" name="licenseplate" value="1" style="width: 6%;">沪牌
            <input type="radio" name="licenseplate" value="2" style="width: 6%;">非沪牌
        </li>
        <li class="accountr" style="display: none;">
            <span>应收账款</span>
            <input type="text" name="accountr" placeholder="应收账款"><span>万元</span>
        </li>
    </ul>
</section>
<script>
    $(document).ready(function(){
       $("select[name='loan_type']").change(function(){
			if($(this).val()==''){
				$('.guaran').css('display','none');
				$('.car').css('display','none');
				$('.accountr').css('display','none');
				$('.wudiya').css('display','none');
			}else if($(this).val()==1){
				$('.guaran').css('display','block');
				$('.car').css('display','none');
				$('.accountr').css('display','none');
				$('.wudiya').css('display','none');
			}else if($(this).val()==3){
				$('.car').css('display','block');
				$('.guaran').css('display','none');
				$('.accountr').css('display','none');
				$('.wudiya').css('display','none');
			}else if($(this).val()==2){
				$('.accountr').css('display','block');
				$('.guaran').css('display','none');
				$('.car').css('display','none');
				$('.wudiya').css('display','none');
			}else if($(this).val()==4){
				$('.wudiya').css('display','block');
				$('.car').css('display','none');
				$('.guaran').css('display','none');
				$('.accountr').css('display','none');
				};
		});	
		
		switch($("select[name='loan_type']").val()){
			case '1':
			 $('.guaran').css('display','block');
			break;
			case '3':
			$('.car').css('display','block');
			break;
			case '2':
			$('.accountr').css('display','block');
			break;
			
		}
		$("select[name='agencycommissiontype']").change(function(){
			
			if($("select[name='agencycommissiontype']").val()==1){
				$(".selected03").show();
			    $(".selected04").hide();
				$("input[name='agencycommission']").next("i").html("万")
			}else if($("select[name='agencycommissiontype']").val()==2){
				$(".selected03").hide();
			    $(".selected04").show();
				$("input[name='agencycommissions']").next("i").html("%")
				
			}else{
				$(".selected03").hide();
			    $(".selected04").hide();
				$("input[name='agencycommission']").next("i").html("")
			}
		})
      
	 
        switch($("select[name='agencycommissiontype']").val()){
			case '1':
			    $(".selected03").show();
				$("input[name='agencycommission']").next("i").html("万");
				break;
			case '2':
			    $(".selected04").show();
				$("input[name='agencycommissions']").next("i").html("%")
				break;
		}	 
});
</script>
<section class="sup" style="display:none;">
    <div class="information">
        <span></span>
        <span>补充信息</span>
    </div>
    <ul class="supplement infor">
        <li>
            <div class="infor_l">
            <span>借款利率(%)</span>
            <?php echo Html::input('text','rate','',['placeholder'=>''])?>
			<span>
				<div class="select-area">
                    <i class="select-value"></i>
                    月<?php echo Html::hiddenInput('rate_cat','2')?><i></i>
                </div>
			</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
            <span>借款期限</span>
                <?php echo Html::input('text','term','',['placeholder'=>''])?>
			<span>月
			</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
            <span>还款方式</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('repaymethod','',CreditorProduct::$repaymethod)?>
                </div>
			</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
            <span>债务人主体</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('obligor','',[''=>'请选择',1=>'自然人',2=>'法人',3=>'其他(未成年,外籍等)'])?>
                </div>
			</span>
            </div>
        </li>
        <li>
        
        <span>逾期开始日期</span>
		<?php if(date('Y',$datas['start']) > date('Y',time())){
			           $v = (date('Y',time())-date('Y',$datas['start']));
					   $a = (20-$v)+1;
				  }else if(date('Y',$datas['start']) < date('Y',time())){
					  $a = date('Y',time())-date('Y',$datas['start']);
				  }else{
					  $a = '0';
				  }
		?>
		<?php echo html::dropDownList('years',$a,CreditorProduct::getStart(time(),10))?>
		<?php echo html::hiddenInput('year',date('Y',$datas['start']))?>
		<?php echo html::dropDownList('months',date('m',$datas['start'])-1,CreditorProduct::getMonth())?>
		<?php echo html::hiddenInput('month',date('m',$datas['start']))?>
		<?php echo Html::dropDownList('days',1,[""=>"1"], 
								[
									'id'=>'days',
									'placeholder'=>"1",
									'data-required'=>"true",
								]
						   );
						   ?>
		<?php echo html::hiddenInput('day',date('d',$datas['start']))?>          
        </li>
             
        </li>
        <li>
            <div class="infor_l">
            <span>委托事项</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('commitment','',CreditorProduct::$commitment);?>
                </div>
			</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
            <span style="width:43%;">委托代理期限(月)</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('commissionperiod','',CreditorProduct::$commissionperiod)?>
                </div>
			</span>
            </div>
        </li>
        <li class="bot_line">
            <div class="infor_l">
            <span>已付本金</span>
                <?php echo Html::input('text','paidmoney','',['placeholder'=>''])?>
            <span>元</span>
            </div>
        </li>
        <li class="bot_line">
            <div class="infor_l">
            <span>已付利息</span>
                <?php echo Html::input('text','interestpaid','',['placeholder'=>''])?>
            <span>元</span>
            </div>
        </li>
        <li class="bot_line">
            <div class="infor_l">
            <span>合同履行地</span>
			    <?php echo  html::dropDownList('place_province_id',$datas['place_province_id']?$datas['place_province_id']:'310000',\wx\services\Func::getProvince(),["class"=>"selects selects_three"]);?> <i></i>
                <?php echo  html::dropDownList('place_city_id',$datas['place_city_id']?$datas['place_city_id']:'310100',$datas['place_province_id']=='310000'||$datas['place_province_id']==''?\wx\services\Func::getCityByProvince('310000'):\wx\services\Func::getCityByProvince($datas['place_province_id']),["class"=>"selects selects_three"]);?> 
                <?php echo  html::dropDownList('place_district_id',$datas['place_district_id'],$datas['place_city_id']=='310100'||$datas['place_city_id']==''?\wx\services\Func::getDistrictByCity('310100'):\wx\services\Func::getDistrictByCity($datas['place_city_id']),["class"=>"selects selects_three"]);?>
            <span></span>
            </div>
        </li>
        <li class="bot_line">
            <span>债权文件</span>
			<span>
				<div class="select-area infor_arrow creditorfile">
                    <i class="select-value"></i>
                    上传
                </div>
			</span>
        </li>
        <li class="bot_line">
            <span>债权人信息</span>
			<span>
				<div class="select-area infor_arrow creditorprofile">
                    <i class="select-value"></i>
                    完善
                </div>
			</span>
        </li>
        <li>
            <span>债务人信息</span>
			<span>
				<div class="select-area infor_arrow uncreditorprofile">
                    <i class="select-value"></i>
                    完善
                </div>
			</span>
        </li>
    </ul>
</section>
<section>
    <div class="back">
      <div class="back_n" style="background:#0065b3;">
        <p> <span class="bc_img"></span> <span class="bctips">展开补充信息(选填)</span> </p>
        <span class="back_text">信息越完善越容易获得接单方的青睐</span> </div>
    </div>
  </section>
<!--<footer>
    <ul class="hold">
        <li class="saveCls">
            <span class="hold_ig01"></span>
            <?php //echo Html::hiddenInput('progress_status','1')?>
            <?php //echo Html::hiddenInput('type','1')?>
            <span>保存</span>
        </li>
        <li class="publishCls">
            <span class="hold_ig02"></span>
            <span >立即发布</span>
        </li>
    </ul>
</footer>-->
</form>
<script>
$(document).ready(function(){
	
	 var brandchild = '';
        $('input').keyup(
            function(){
                if($(this).val() == '')
                    this.style.fontSize = "12px";
                else this.style.fontSize = "18px";

                if($(this).attr('name')== 'other'){
                    $(this).css('width','50px');
                }
            }
        );
		
		if(getCookie(getCookie('publishCookieName')+'isSecond') == null || getCookie(getCookie('publishCookieName')+'isSecond') == '' ){
            setOnceDefaultValue();
            setCookie(getCookie('publishCookieName')+'isSecond','1','h1');
            if($("input[name='money']").val() != ''){$("input").attr('style',"font-size:18px;");$('input[type=radio]').attr('style','width:6%')}
            $('input[name="other"]').css('width','50px');
        }else{
            setDefaultValue();
            if($("input[name='money']").val() != ''){$("input").attr('style',"font-size:18px;");$('input[type=radio]').attr('style','width:6%')}
            if($("input").val() !=  '') $("input").attr('style',"font-size:18px;");
            $('input[name="other"]').css('width','50px');
        }
		
		$(".select-area .select-value").each(function(){
            if($(this).next("select").find("option:selected").length != 0 ){
                $(this).text($(this).next("select").find("option:selected").text());
            }
        });
        $(".select-area select").change(function(){
            var value = $(this).find("option:selected").text();
            $(this).parent(".select-area").find(".select-value").text(value);
        });
		$('.brand-cls').change(function () {
            var pid = $(this).val();
            if(pid){
                $.ajax({
                    url:'<?php echo \yii\helpers\Url::toRoute('/common/getbrandchild')?>',
                    data:{pid:pid},
                    type:'post',
                    success:function(html){
                        $('.brand-child-cls').html(html);
                        if(brandchild){
                            $('.brand-child-cls').val(brandchild);
                            saveCookie();
                        }
                    }
                });
            }
        });
		$("select[name='province_id']").change(
                function () {
                    var pid = this.value;
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute('/publish/getcity')?>',
                        type: "post",
                        data: {province_id: pid},
                        dataType: 'html',
                        success: function (html) {
                            $("select[name='city_id']").html(html);
                        }
                    });
                }
            );
		$("select[name='city_id']").change(
                function () {
                    var pid = this.value;
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute('/publish/getdistrict')?>',
                        type: "post",
                        data: {city_id: pid},
                        dataType: 'html',
                        success: function (html) {
                            $("select[name='district_id']").html(html);
                        }
                    });
                }
            );
		$("select[name='place_province_id']").change(
                function () {
                    var pid = this.value;
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute('/publish/getcity')?>',
                        type: "post",
                        data: {province_id: pid},
                        dataType: 'html',
                        success: function (html) {
                            $("select[name='place_city_id']").html(html);
                        }
                    });
                }
            );
		$("select[name='place_city_id']").change(
                function () {
                    var pid = this.value;
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute('/publish/getdistrict')?>',
                        type: "post",
                        data: {city_id: pid},
                        dataType: 'html',
                        success: function (html) {
                            $("select[name='place_district_id']").html(html);
                        }
                    });
                }
            );
			$('.brand-cls').change();
        $('.brand-child-cls').change(function(){
            saveCookie();
        });
        switch ($('input[name="loan_type"]').val()){
            case '1':$('.private').click();break;
            case '3':$('.cars').click();break;
            case '2':$('.accountrs').click();break;
            case '4':$('.accounts').click();break;
            default :$('.private').click();break;
        }

        $('.back').click(function(event) {
            $('.sup').slideToggle();
            if($('.bctips').html() == '收回补充信息(选填)'){
                $('.bctips').html('展开补充信息(选填)');
				$(this).find('.bc_img').css('background-position','-381px -73px');				
            }else{
                $('.bctips').html('收回补充信息(选填)');
				$(this).find('.bc_img').css('background-position','-363px -73px');
            }

           
        });

        $('.infor_loan_r a').click(function(event) {
            $(this).addClass('current').siblings().removeClass('current');
            $("input[name='loan_type']").val($(this).attr('id'));
            saveCookie();
        });

        $('.creditorfile').click(function(){
            saveCookie();
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/publish/creditorfileinfo','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>";
        });

        $('.creditorprofile').click(function(){
            saveCookie();
             window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/publish/creditorprofileinfo','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>";
        });

        $('.uncreditorprofile').click(function(){
            saveCookie();
            window.location.href = "<?php echo \yii\helpers\Url::toRoute(['/publish/uncreditorprofileinfo','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>";
        });
		
		
		$('input').keyup(function(){saveCookie();});
        $('input[type="radio"]').change(function(){saveCookie();});
        $('input[type="checkbox"]').change(function(){saveCookie();});
        $('textarea').keyup(function(){saveCookie();});
        $('select').change(function(){saveCookie();});

        saveCookie();
		
		//$('.saveCls').click(function(){
       //     $('input[name="progress_status"]').val(0);
        //    var type = $('input[name=type]:hidden').val();
        //    saveCookie();
       //     save(type);
       // });
        $('.publishCls').click(function(){
            $('input[name="progress_status"]').val(1);
		    var category = $('input[name="category"]').val();
            var id = $('input[name=id]:hidden').val();
            saveCookie();
            save(id,category);
        });
		
		$('.icon-back').click(function () {
            window.location = "<?php echo \yii\helpers\Url::toRoute(['/releases/index','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')]);?>";
        });
		
		$($('select[name=months]')).change(function(){
			$(this).next('input').val($(this).find("option:selected").text()); 
			var data = $(this).find("option:selected").text();
	        $.ajax({
				url:"<?php echo yii\helpers\Url::toRoute('/publish/timedata')?>",
				type:'post',
				data:{data:data},
				dataType:'json',
				success:function(json){
					if(json['code']=='0000'){
							$('#days').html(json['html']);
					}
					
				}
			})
	   });
});

function saveCookie(){
            var sc = setCookie(getCookie('publishCookieName'),$('form').serialize(),'h1');
        };
		
function setDefaultValue(){
            if(getCookie(getCookie('publishCookieName')) == null || getCookie(getCookie('publishCookieName')) == '' ){
                return false;
            }
            var all = formUnserialize(getCookie(getCookie('publishCookieName')));
            for(cname in all){
                var v = decodeURIComponent(all[cname]);
                if(cname == 'loan_type'){
                    $('[name="'+cname+'"]').val(v);
                    $('[name="'+cname+'"]').parent().children().each(function(){if($(this).attr('id') == v){
                        $(this).addClass('current').siblings().removeClass('current');
                    }});
                }else if(cname == 'audi'){
                    brandchild = v;
                }else if(cname == 'licenseplate'){
                    $("input[name='licenseplate'][value='"+v+"']").attr("checked",true);
                }else if(cname == 'guaranteemethod'){
                    v.split(',').forEach(function(param){
                        $("input[name='guaranteemethod[]'][value='"+param+"']").attr("checked",true);
                    });

                }else{
                    $('[name="'+cname+'"]').val(v);
                }
            }
        };
	
function setOnceDefaultValue(){
            var arr = eval( '(' + '<?php echo $data?>' + ')' );

            for(cname in arr){
                var v = decodeURIComponent(arr[cname]);

                if(v == 'null' ) v = '';
                if(cname == 'loan_type'){
                    $('[name="'+cname+'"]').val(v);
                    $('[name="'+cname+'"]').parent().children().each(function(){if($(this).attr('id') == v){
                        $(this).addClass('current').siblings().removeClass('current');
                    }});
                }else if(cname == 'audi'){
                    brandchild = v;
                }else if(cname == 'licenseplate'){
                    $("input[name='licenseplate'][value='"+v+"']").attr("checked",true);
                }else if(cname == 'guaranteemethod'){
                    v.split(',').forEach(function(param){
                        $("input[name='guaranteemethod[]'][value='"+param+"']").attr("checked",true);
                    });

                }else{
                    $('[name="'+cname+'"]').val(v);
                }
            }
        };
function save(id,category){
            $.ajax({
                url:"<?php echo \yii\helpers\Url::toRoute(['/publish/editligitation','id'=>Yii::$app->request->get('id'),'category'=>Yii::$app->request->get('category')])?>",
                type:'post',
                data:getCookie(getCookie('publishCookieName'))+'&'+getCookie(getCookie('publishCookieName')+'creditorprofile')+'&'+getCookie(getCookie('publishCookieName')+'creditorfile')+'&'+getCookie(getCookie('publishCookieName')+'borrowingprofile'),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        delCookie(getCookie('publishCookieName'));
                        delCookie(getCookie('publishCookieName')+'creditorprofile');
                        delCookie(getCookie('publishCookieName')+'creditorfile');
                        delCookie(getCookie('publishCookieName')+'borrowingprofile');
                        delCookie(getCookie('publishCookieName')+'isSecond');
						alert("保存成功");
						window.location = "<?php echo \yii\helpers\Url::toRoute('/releases/index')?>?id="+id+"&category="+category;
                        /*if(type == 1){
                            alert("保存成功");
                            window.location = "<?php echo \yii\helpers\Url::toRoute('/usercenter/preservation')?>";
                        }else{
                            alert("发布成功");
                            window.location = "<?php echo \yii\helpers\Url::toRoute('/usercenter/release')?>";
                        }*/
                    }else{
                        alert(json.msg);
                    }
                }
            })
        };		
    function communitOnClick(){
        window.location = "<?php echo \yii\helpers\Url::toRoute('/common/searchcommunity')?>";
    };
	$($('select[name=years]')).change(function(){
			$(this).next('input').val($(this).find("option:selected").text()); 
			 });
    $($('select[name=days]')).change(function(){
			$(this).next('input').val($(this).find("option:selected").text()); 
			 });
</script>