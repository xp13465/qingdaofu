<?php
use yii\helpers\Html;
use \common\models\FinanceProduct;
?>
<div class="content_right">
    <div class="rongzi">
        <?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post'])?>
        <h3>融资发布</h3>
        <div class="title_rz clearfix" style="background-color:#fff;">
            <span class="base fl current" onclick='change(this,0);' style="padding-left:0;">　基本信息</span>
            <span class="supplement fr" onclick='change(this,1);' style="padding-left:0;">　　补充信息</span>
        </div>
        <div class="prompt">
            <p class="color">
                温馨提示：<br>
                　　1、根据我们的长期跟踪统计，填写信息完整度越高，越容易获得接单方的青睐！<br>
                　　2、为了保证您的信息能顺利发布，请您确认发布的信息：真实，有效，合法！<br>
                　　3、必填信息为基本信息，不能留空。补充信息接单方也很看重呢。<br>
            </p>
        </div>
        <div class="bitian pl">
            <ul>
                <li class="wh1">
                    <label for="">金额</label><?php echo html::input('text','money',$model->money,['placeholder'=>'填写您希望融资的金额','style'=>'width:170px;','class'=>'number'])?> 万元 <i></i>
                    　　　　　　
                </li>
                <li class="wh1">
                    <label for="">返点</label><?php echo html::input('text','rebate',$model->rebate,['placeholder'=>'能够给到中介的返点','style'=>'width:170px;'])?> %<i></i>
                </li>
                <li class="clearfix wh3">
                    <label for="">利息</label><?php echo html::input('text','rate',$model->rate,['class'=>"tian",'id'=>"rate"])?> %<i></i><?php echo html::dropDownList('rate_cat',$model->rate_cat,FinanceProduct::$ratedatecategory,['onchange'=>'ratecatchange(this.value);'])?><i></i><!--<em style="font-size:14px">(注：日利率不得超过0.067%或者月利率不得超过2%)</em>--></li>
                <li class="wh2">
                    <label for="">借款期限</label><?php echo html::input('text','term',$model->term,['class'=>"tian"])?> <i></i>　<?php echo html::dropDownList('rate_cat',$model->rate_cat,FinanceProduct::$ratedatecategory,['onchange'=>'ratecatchange(this.value);;'])?><i></i>
                </li>
                <li class="wh"><label for="">抵押物面积</label><?php echo html::input('text','mortgagearea',$model->mortgagearea,['class'=>'number'])?> ㎡ <i></i></li>

                <!-- <li><label for="">预期资金到账</label><?php /*echo html::input('text','fundstime',$model->fundstime?date("Y-m-d",$model->fundstime):'',['onclick'=>'WdatePicker()'])*/?><i></i>-->

                <li class="clearfix wh4">
                    <label for="">抵押物地址</label><?php echo  html::dropDownList('province_id',$model->province_id?$model->province_id:'310000',\frontend\services\Func::getProvince());?>
                    <?php echo  html::dropDownList('city_id',$model->city_id?$model->city_id:'310100',$model->province_id=='310000'||$model->province_id==''?\frontend\services\Func::getCityByProvince('310000'):\frontend\services\Func::getCityByProvince($model->province_id));?>
                    <?php echo  html::dropDownList('district_id',$model->district_id,$model->city_id=='310100'||$model->city_id==''?\frontend\services\Func::getDistrictByCity('310100'):\frontend\services\Func::getDistrictByCity($model->city_id));?>
                    <?php echo html::input('text','seatmortgage',$model->seatmortgage,['placeholder'=>'门牌 小区名 楼层'])?><i></i>

                </li>
                <li>
                    <input type="button" value="保存" onclick="SaveForm()">
                    <input type="button" value="发布" onclick="PublishForm()">
                </li>
            </ul>
        </div>

        <div class="chosen pl">
            <ul>
                <li>
                    <label for="">抵押物类型</label><?php echo html::dropDownList('mortgagecategory',$model->mortgagecategory,FinanceProduct::$mortgagecategory)?>
                <li><label for="">状态</label><?php echo html::dropDownList('status',$model->status,FinanceProduct::$status,['onchange'=>'changestatus(this.value)'])?>
                <li><label for="">抵押状况</label><?php echo html::dropDownList('mortgagestatus',$model->mortgagestatus,FinanceProduct::$mortgagestatus)?>
                </li>
                <li style="display: none">
                    <label for="">租金</label><?php echo html::input('text','rentmoney',$model->rentmoney)?> 元
                </li>
                <li>
                    <label for="">借款人年龄</label><?php echo html::input('text','loanyear',$model->loanyear)?> 岁</li></li>
                <li><label for="">权利人年龄</label><?php echo html::dropDownList('obligeeyear',$model->obligeeyear,FinanceProduct::$obligeeyear)?>
                </li>

                <li>
                    <?php echo html::input('hidden','progress_status',$model->progress_status,['id'=>'progress_status'])?>
                    <input type="button" value="保存" onclick="SaveForm()">
                    <input type="button" value="发布" onclick="PublishForm()">
                </li>
            </ul>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
    <div style="display:none;" class="tanchu">
        <div class="rz_alert2c" style="font-size: 16px;">
            <p class="art" >补充信息您还没有填呢，信息越完善越容易获得接单发的青睐!</p>
            <div>
                <a onclick="tops();"style="background:#c3c3c3;">直接发布</a>　　　<a onclick = "perfect();">去完善</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.pl').hide();
            change('',0);
        });
        function change(ob,num){
            $('.pl').hide();
            $('.pl').eq(num).show();
            $(ob).addClass('current').siblings().removeClass('current');
        }
        $(document).ready(function() {
            var bcflag = false;
            $("select[name='province_id']").change(
                function () {
                    var pid = this.value;
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute('/func/getcity')?>',
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
                        url: '<?php echo \yii\helpers\Url::toRoute('/func/getdistrict')?>',
                        type: "post",
                        data: {city_id: pid},
                        dataType: 'html',
                        success: function (html) {
                            $("select[name='district_id']").html(html);
                        }
                    });
                }
            );

            changestatus(0);
            /* jQuery.validator.addMethod("isRate", function (value, element) {
             return this.optional(element) || isValidate();

             }, "");*/
            $("#financing").validate({
                rules: {
                    money: "required",
                    rebate: {
                        required: true,
                        max: 6,
                    },
                    rate: {
                        required: true,
                    },
                    rate_cat: {
                        required: true,
                        //isRate:true
                    },
                    term: {
                        required: true,
                        max: 360
                    },
                    // fundstime: "required",
                    mortgagearea: "required",
                    province_id: "required",
                    city_id: "required",
                    district_id: "required",
                    seatmortgage: "required"
                },

                messages: {
                    money: "忘记填写金额啦",
                    rebate: {
                        required: "忘记填写返点啦",
                        max: "返点不大于6个点"
                    },
                    rate:'忘记填写利息啦',
                    rate_cat: '',

                    term: {
                        required: "忘记填写期限啦",
                        max: "最大不超过360"
                    },
                    // fundstime: "忘记填写资金到账日啦",
                    mortgagearea: "忘记填写抵押物面积啦",
                    district_id: "忘记填写区域啦",
                    seatmortgage: "忘记填写抵押物地址啦"
                },
                errorPlacement: function (error, element) {
                    if(error.html() == ''){
                        if(element.attr('name') != ''){
                            element.next('i').html('&nbsp;' + error.html() + '&nbsp;').addClass('yesshow').removeClass('error');
                        }
                    }
                    else element.next('i').html('&nbsp;' + error.html() + '&nbsp;').addClass('error').removeClass('yesshow');
                },

                success: function (element) {
                    element.parent().children('i').html('').removeClass('error').addClass('yesshow');
                }
            });
        });
        /*function isValidate(){
         if($('select[name="rate_cat"]').val() == 1){
         if($("input[name='rate']").val() >0.067){
         $("input[name='rate']").next('i').html("利率填写错误").addClass('error');
         return false;
         }else{
         $("input[name='rate']").next('i').html("").removeClass('error');
         return true;
         }
         }else if($('select[name="rate_cat"]').val() == 2){
         if($("input[name='rate']").val()>2){
         $("input[name='rate']").next('i').html("利率填写错误").addClass('error');
         return false;
         }else{
         $("input[name='rate']").next('i').html("").removeClass('error');
         return true;
         }
         }

         return false;
         }*/

        $('.number').change(function(){
            if(!/^(-?\d+)(\.\d+)?$/.test(this.value)){
                $("input[name='money']").next('i').html("只能输入数字").addClass('error');
                this.value='';
            }
        })

        function PublishForm(){
            var r = $('#financing').valid()
            if(!r)return false;
            $('#progress_status').val('1');
            if($('select[name="rate_cat"]').val()  == 1)var aaa = '日';
            if($('select[name="rate_cat"]').val()  == 2)var aaa = '月';
            if($('.pl').eq(1).css('display') == 'none') {
                $.msgbox({
                    closeImg: '/images/close1.png',
                    async: false,
                    height: 190,
                    width: 340,
                    content: $('.tanchu').html(),
                });
            }else{
                registerProtocol($("input[name='money']").val(),$("input[name='term']").val(),$("select[name=rate_cat]").val());
            }

            //registerProtocol($("input[name='money']").val(),$("input[name='term']").val());}
        }

        function tops(){
            $('#jMsgboxBox').css('display','none').hide();
            $('#jMsgboxBg').hide();
            registerProtocol($("input[name='money']").val(),$("input[name='term']").val(),$("select[name=rate_cat]").val());
        }
        function perfect(){
            $('#jMsgboxBox').hide();
            $('#jMsgboxBg').hide();
            if($('.pl').eq(1).css('display') == 'none'){
                change('.fr',1);
            }
        }

        function subEvent(){
            $('#financing').submit();
        }

        function SaveForm(){
            var r = $('#financing').valid();
            if(!r)return false;
            $('#progress_status').val('0');
            registerSave($("input[name='money']").val(),$("input[name='term']").val(),$("select[name=rate_cat]").val());

        }

        function ratecatchange(value){
            $("select[name='rate_cat']").val(value);
        }

        function registerSave(money,term,day){
            $.msgbox({
                height:190,
                width:350,
                title:' ',
                closeImg:false,
                content:'<p>您保存的融资信息如下:</p><p>融资金额：' +money+
                '万元</p><p>融资期限：' +term+(day==1?'天':'月')+
                '</p><p>温馨提示：请确认信息后再保存。</p>',
                type :'confirm',
                onClose:function(v){
                    if(v){
                        subEvent();
                    }else{

                    }
                }
            });
        }

        function registerProtocol(money,term,day){
            $.msgbox({
                height:190,
                width:350,
                content:'<p>您发布的融资信息如下:</p><p>融资金额：' +money+
                '万元</p><p>融资期限：' +term+(day==1?'天':'月')+
                '</p> <p>温馨提示：请您先确认后再发布。</p>',
                type :'confirm',
                onClose:function(v){
                    if(v){
                        subEvent();
                    }else{

                    }
                }
            });
        }

        function changestatus(status){
            if(status == 2){
                $("input[name='rentmoney']").parent().css('display','block');
            }else{
                $("input[name='rentmoney']").parent().css('display','none');
            }
        }

    </script>
</div>


