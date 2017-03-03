<?php
use yii\helpers\Html;
use wx\widget\wxHeaderWidget;
use \common\models\CreditorProduct;
use \common\models\FinanceProduct;
?>
<?=wxHeaderWidget::widget(['title'=>'发布融资','gohtml'=>''])?>


<form name = "creditor" id = "creditor">
<section>
    <div class="information">
        <span></span>
        <span>基本信息</span>
    </div>
    <ul class="infor">
        <li>
            <div class="infor_l">
                <span>金额</span>
                <?php echo Html::input('text','money','',['placeholder'=>'填写您希望融资的金额','id'=>'txt'])?>
                <span>万元</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
                <span>返点</span>
                <?php echo Html::input('text','rebate','',['placeholder'=>'能够给到中介的返点'])?>
                <span>%</span>
            </div>
        </li>
        <li class="li_infor">
            <div class="infor_l">
                <span class="infor_loan_l">利息</span>
                <?php echo Html::input('text','rate','',['placeholder'=>''])?>
                <span>
				<div class="select-area infor_arrow">
                    % <i class="select-value"></i>
                    <?php echo Html::dropDownList('rate_cat','',CreditorProduct::$ratedatecategory)?>
                </div>
			</span>
            </div>
        </li>
        <?php echo Html::hiddenInput('mortorage_has',1)?>
        <!--<li>
            <div class="infor_l">
                <span>有无抵押物</span>
				<span>
					<div class="select-area infor_arrow">
                        <i class="select-value"></i>
                        <?php /*echo Html::dropDownList('mortorage_has','',[''=>'请选择','0'=>'有','1'=>'无'])*/?>
                    </div>
				</span>
            </div>
        </li>-->
        <li class="infor_li infor_li01" style="border-bottom:1px solid #ddd;">
            <div class="infor_l ">
                <span>抵押物地址</span>
                <?php echo Html::hiddenInput('province_id','',['id'=>'province_id'])?>
                <?php echo Html::hiddenInput('city_id','',['id'=>'city_id'])?>
                <?php echo Html::hiddenInput('district_id','',['id'=>'district_id'])?>
                <?php echo Html::textarea('mortorage_community','',['placeholder'=>'小区/写字楼/商铺等','class'=>'community','id'=>'mortorage_community','onClick'=>'communitOnClick();'])?>
            </div>
        </li>
        <li class="infor_li">
            <div class="infor_l">
                <span></span>
                <?php echo Html::textarea('seatmortgage','',['placeholder'=>'详细地址','style'=>'padding-top:10px;','id'=>'seatmortgage'])?>
            </div>
        </li>
    </ul>
</section>
<section class="sup">
    <div class="information">
        <span></span>
        <span>补充信息</span>
    </div>
    <ul class="supplement infor">
        <li>
            <div class="infor_l">
            <span>抵押物类型</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('mortgagecategory','',FinanceProduct::$mortgagecategory)?>
                </div>
			</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
            <span>状态</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('status','',FinanceProduct::$status)?>
                </div>
			</span>
            </div>
        </li>
        <li class="bot_line rentmoney" style="display:none">
            <div class="infor_l">
            <span>租金</span>
                <?php echo Html::input('text','rentmoney','',['placeholder'=>''])?>
            <span>元</span>
            </div>
        </li>
        <li class="bot_line">
            <div class="infor_l">
                <span>抵押物面积</span>
                <?php echo Html::input('text','mortgagearea','',['placeholder'=>''])?>
                <span> ㎡</span>
            </div>
        </li>
        <li class="bot_line">
            <div class="infor_l">
            <span>借款人年龄</span>
                <?php echo Html::input('text','loanyear','',['placeholder'=>''])?>
            <span>岁</span>
            </div>
        </li>
        <li>
            <div class="infor_l">
                <span>权利人年龄</span>
			<span>
				<div class="select-area infor_arrow">
                    <i class="select-value"></i>
                    <?php echo html::dropDownList('obligeeyear','',FinanceProduct::$obligeeyear);?>
                </div>
			</span>
            </div>
        </li>
    </ul>
</section>
<section>
    <div class="back">
        <div class="back_n">
            <p>
                <span class="bc_img"></span>
                <span class="bctips">收回补充信息(选填)</span>
            </p>
            <span class="back_text">信息越完善越容易获得接单方的青睐</span>
        </div>
    </div>
</section>
<footer>
    <ul class="hold">
        <li class="saveCls">
            <span class="hold_ig01"></span>
            <?php echo Html::hiddenInput('progress_status','1')?>
            <?php echo Html::hiddenInput('type','1')?>
            <span>保存</span>
        </li>
        <li class="publishCls">
            <span class="hold_ig02"></span>
            <span >立即发布</span>
        </li>
    </ul>
</footer>
</form>

<script>
    $(function(){
        $('input').keyup(
            function(){
                if($(this).val() == '')
                    this.style.fontSize = "12px";
                else this.style.fontSize = "18px";

            }
        );
        setDefaultValue();
        if($("input").val() !=  '') $("input").attr('style',"font-size:18px;");
        $(".select-area .select-value").each(function(){
            if( $(this).next("select").find("option:selected").length != 0 ){
                $(this).text( $(this).next("select").find("option:selected").text() );
            }
        });
        $(".select-area select").change(function(){
            var value = $(this).find("option:selected").text();
            $(this).parent(".select-area").find(".select-value").text(value);
            if($(this).children('option:selected').val() == 2){
                $('.rentmoney').show();
            }else{
                $('.rentmoney').hide();
            }
        });

        /*$('.community').click(function(){
            window.open("<?php echo \yii\helpers\Url::toRoute('/common/searchcommunity')?>");
        });*/

        $('.back').click(function(event) {
            $('.sup').slideToggle();
            if($('.bctips').html() == '收回补充信息(选填)'){
                $('.bctips').html('展开补充信息(选填)');
            }else{
                $('.bctips').html('收回补充信息(选填)');
            }

            // $(this).find('.bc_img').css('background-position','-381px -73px');
        });

        $('.infor_loan_r a').click(function(event) {
            $(this).addClass('current').siblings().removeClass('current');
            $("input[name='loan_type']").val($(this).attr('id'));
            saveCookie();
        });



        function setDefaultValue(){
            if(getCookie(getCookie('publishCookieName')) == null || getCookie(getCookie('publishCookieName')) == '' ){
                return false;
            }
            var all = formUnserialize(getCookie(getCookie('publishCookieName')));

            for(cname in all){
                var v = decodeURIComponent(all[cname]);
                if(cname == 'loan_type'){
                    $('[name="'+cname+'"]').parent().children().each(function(){if($(this).attr('id') == v){
                        $(this).addClass('current').siblings().removeClass('current');
                    }});
                }
                $('[name="'+cname+'"]').val(v);
            }
        }

        $('input').keyup(function(){saveCookie();});
        $('textarea').keyup(function(){saveCookie();});
        $('select').change(function(){saveCookie();});


        $('.saveCls').click(function(){
            $('input[name="progress_status"]').val(0);
            var type = $('input[name=type]:hidden').val();
            saveCookie();
            save(type);
        });
        $('.publishCls').click(function(){
            $('input[name="progress_status"]').val(1);
            saveCookie();
            save();
        });

        function save(type){
            $.ajax({
                url:"<?php echo \yii\helpers\Url::toRoute('/publish/financing')?>",
                type:'post',
                data:getCookie(getCookie('publishCookieName')),
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        delCookie(getCookie('publishCookieName'));
                        if(type == 1){
                            alert("保存成功");
                            window.location = "<?php echo \yii\helpers\Url::toRoute('/usercenter/preservation')?>";
                        }else{
                            alert("发布成功");
                            window.location = "<?php echo \yii\helpers\Url::toRoute('/usercenter/release')?>";
                        }
                    }else{
                        alert(json.msg);
                    }
                }
            })
        }

    });

    function saveCookie(){
        var sc = setCookie(getCookie('publishCookieName'),$('form').serialize(),'h1');
    }

    function communitOnClick(){
        window.location = "<?php echo \yii\helpers\Url::toRoute('/common/searchcommunity')?>";
    }
</script>
