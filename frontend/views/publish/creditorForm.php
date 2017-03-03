<?php
use yii\helpers\Html;
use \common\models\CreditorProduct;
use yii\helpers\Url;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post','options'=>['enctype'=>"multipart/form-data"],])?>
    <div class="title_rz clearfix" style="background-color:#fff;">
        <span class="base fl current" onclick='change(this,0);' style="padding-left:0;">　基本信息</span>
        <span class="supplement fr" onclick='change(this,1);' style="padding-left:0;">　　补充信息</span>
    </div>
    <div class="prompt">
        <p class="color">
            温馨提示：<br>
            　　1、根据我们的长期跟踪统计，填写信息完整度越高，越容易获得接单方的青睐！<br>
            　　2、为了保证您的信息能顺利发布，请您确认发布的信息：真实，有效，合法！<br>
            　　3、基本信息为基础信息，不能留空。补充信息接单方也很看重呢。<br>
        </p>
    </div>
    <div class="detail pl">
        <?php
        $AplaceHolder = '填写您借款时的本金';
        $bplaceHolder = '当时约定的借款期限';

        ?>
        <ul>
            <li>
                <span>债权类型</span>
                <label for=""><?php echo html::radio('loan_type',$model->loan_type == 1,['value'=>1 ,'class'=>'private']) ?>房产抵押</label>　
                <label for=""><?php echo html::radio('loan_type',$model->loan_type == 3,['value'=>3,'class'=>'cars']) ?>机动车抵押</label>
                <label for=""><?php echo html::radio('loan_type',$model->loan_type == 2,['value'=>2,'class'=>'accountrs']) ?>应收账款</label>
                <label for=""><?php echo html::radio('loan_type',$model->loan_type == 4,['value'=>4,'class'=>'accounts']) ?>无抵押</label>
                <i></i>
            </li>
                <?php if(is_numeric($model['guaranteemethod'])){ ?>
                <?php }else{?>
                    <li class="guaran">
                    <?php
                    $guaranteemethod = unserialize($model->guaranteemethod);
                    $ma = '';
                    $mb = [];
                    if(is_array($guaranteemethod)){
                        $ma = isset($guaranteemethod['ohter'])?$guaranteemethod['ohter']:'';
                        unset($guaranteemethod['ohter']);
                        $mb = $guaranteemethod;
                    }
                    ?>
                    <span>抵押 </span><label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(1,$mb),['value'=>1])?>住房</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(2,$mb),['value'=>2])?>别墅</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(3,$mb),['value'=>3])?> 写字楼</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(4,$mb),['value'=>4])?>商铺</label><?php echo html::checkbox('guaranteemethod[]',in_array(5,$mb),['value'=>5])?> 其他</label> <i></i>
                 </li>
                <?php } ?>

            <li class="mr guaran">
                <span>担保物 (抵押物) 所在地 </span>
                <?php echo  html::dropDownList('province_id',$model->province_id?$model->province_id:'310000',\frontend\services\Func::getProvince());?> <i></i>
                <?php echo  html::dropDownList('city_id',$model->city_id?$model->city_id:'310100',$model->province_id=='310000'||$model->province_id==''?\frontend\services\Func::getCityByProvince('310000'):\frontend\services\Func::getCityByProvince($model->province_id));?> <i></i>
                <?php echo  html::dropDownList('district_id',$model->district_id,$model->city_id=='310100'||$model->city_id==''?\frontend\services\Func::getDistrictByCity('310100'):\frontend\services\Func::getDistrictByCity($model->city_id));?> <i></i>
            </li>
            <li class="w300 guaran">
                <span>详细地址</span><?php echo html::input('text','seatmortgage',$model->seatmortgage)?><i></i>
            </li>

            <li class="car">
                <span>机动车品牌</span>
                <?php echo  html::dropDownList('carbrand',$model->carbrand?$model->carbrand:'请选择车品牌',\frontend\services\Func::getBrand());?> <i></i>
                <?php echo  html::dropDownList('audi',$model->audi?$model->audi:'请选择车系',$model->carbrand==''|| $model->carbrand==''?\frontend\services\Func::getAudiByBrand('1'):\frontend\services\Func::getAudiByBrand($model->carbrand),['style'=>'width: 120px;margin-left:20px;background:#E8E9EE;color:#878787','disabled'=>'disabled']);?> <i></i>
            </li>
            <li class="car">
                <span>车牌类型</span>
                <label for=""><?php echo html::radio('licenseplate',$model->licenseplate == 1,['value'=>1]) ?>沪牌</label>
                <label for=""><?php echo html::radio('licenseplate',$model->licenseplate == 2,['value'=>2]) ?>非沪牌</label>
                <i></i>
            </li>

            <li class="accountr">
                <span>应收账款</span><?php echo html::input('text','accountr',$model->accountr,['style'=>'width:365px'])?><i></i>
            </li>

            <li>
                <span>借款本金</span><?php echo html::input('text','money',$model->money,['placeholder'=>$AplaceHolder,'style'=>'width:365px;','class'=>'number'])?> 万元<i></i>
            </li>
            <li>
                <span>借款期限</span><?php echo html::input('text','term',$model->term,['placeholder'=>$bplaceHolder,'style'=>'width:365px;'])?> 月 <i></i>
            </li>
            <li>
                <span>还款方式</span><?php echo html::dropDownList('repaymethod',$model->repaymethod,CreditorProduct::$repaymethod)?><i></i>
            </li>



            <li>
                <span>债务人主体</span><label for=""><?php echo html::radio('obligor',$model->obligor == 1,['value'=>1])?>自然人</label><label for=""><?php echo html::radio('obligor',$model->obligor == 2,['value'=>2])?>法人<?php echo html::radio('obligor',$model->obligor == 3,['value'=>3])?> 其他(未成年,外籍等)</label><i></i>
            </li>
            
            <li>
                <?php if(isset($coll)&&$coll){?>
                    <?php echo '';?>
                <?php }else{?>
                    <span>委托事项</span><?php echo html::dropDownList('commitment',$model->commitment,CreditorProduct::$commitment);?><i></i>
                <?php }?>
            </li>
            <li>
                <span>委托代理期限</span><?php echo html::dropDownList('commissionperiod',$model->commissionperiod,CreditorProduct::$commissionperiod)?> 月<i></i>
            </li>
            <?php if(isset($coll)&&$coll){ ?>
                <li class="money">
                    <b>
                        　　代理费用　(1) 固定费用<?php echo html::input('text','agencycommissionf',$model->agencycommissiontype == 2?$model->agencycommission:'',['class'=>"tian"])?><em style='color:red'>万</em>                  <!--<i class="wenhao"></i>-->
                        <!--<span class="wenhao">
                            固定指固定收费代理，是指律师服务费作为收取的固定费用，
                            收取的费用与案件的处理结果无关。<em></em>
                        </span>-->
                    </b>
                    　　
                    <b class="fx">
                        　　　(2) 服务佣金 <?php echo html::input('text','agencycommissions',$model->agencycommissiontype == 1?$model->agencycommission:'',['class'=>"tian"])?><em style='color:red'>%</em>
                        <!--<i></i>
                                <span>
                                    风险指风险收费代理，是指律师服务费与案件的最终处理结果挂钩，
                                    以案件最终处理结果的一定比例支付律师服务费用。<em></em>
                                </span>-->
                    </b>
                    <i style="width:100px;background: none;position: relative;margin:0px;padding: 0px;left:10px;"></i>
                </li>
            <?php } else{ ?>
            <li class="money">
                <b>
                    　　代理费用　(1) 固定<?php echo html::input('text','agencycommissionf',$model->agencycommissiontype == 1?$model->agencycommission:'',['class'=>"tian"])?><em style='color:red'>万</em>                  <i class="wenhao"></i>
                                <span class="wenhao">
                                    固定指固定收费代理，是指律师服务费作为收取的固定费用，
                                    收取的费用与案件的处理结果无关。<em></em>
                                </span>
                </b>
                　　
                <b class="fx">
                    　　　(2) 风险费率  <?php echo html::input('text','agencycommissions',$model->agencycommissiontype == 2?$model->agencycommission:'',['class'=>"tian"])?><em style='color:red'>%</em>
                    <i></i>
                                <span>
                                    风险指风险收费代理，是指律师服务费与案件的最终处理结果挂钩，
                                    以案件最终处理结果的一定比例支付律师服务费用。<em></em>
                                </span>
                </b>
                <i style="width:100px;background: none;position: relative;margin:0px;padding: 0px;left:10px;"></i>
            </li>
            <?php } ?>
            <li>
                <?php echo html::input('hidden','progress_status',1,['id'=>'progress_status'])?>
                <input type="button" value="保存" onclick="SaveForm()">
                <input type="button" value="发布" onclick="PublishForm()">
            </li>
        </ul>
    </div>

    <div class="buchong pl">
        <ul>
            <li class="ul_top">
                <span>已付本金</span><?php echo html::input('text','paidmoney',$model->paidmoney)?> 元
            </li>
            <li class="lixi">
                <span>已付利息</span><?php echo html::input('text','interestpaid',$model->interestpaid)?> 元
            </li>

            <li class="ht">
                <span>合同履行地</span><?php echo html::input('text','performancecontract',$model->performancecontract)?>
            </li>

            <li class="type1">
                <span>借款利率</span><?php echo html::input('text','rate',$model->rate,['class'=>"tian"])?> % <i></i>
                月<?php echo Html::hiddenInput('rate_cat','2')?><i></i>
            </li>
            <?php
            $files = unserialize($model->creditorfile);
            if(empty($files)){
                ?>
                <li>
                    <ul>
                        <li class="ml">债权文件 : 上传凭证</li>
                        <li>《公证书》　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgnotarization')?><span class="color uploadChakan">查看</span><em name="imgnotarization"></em></li>
                        <li>《借款合同》<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgcontract')?><span class="color uploadChakan">查看</span><em name="imgcontract"></em></li>
                        <li>《他项权证》<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgcreditor')?><span class="color uploadChakan">查看</span><em name="imgcreditor"></em></li>
                        <li> 付款凭证　　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgpick')?><span class="color uploadChakan">查看</span><em name="imgpick"></em></li>
                        <li> 收据　　　　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgshouju')?><span class="color uploadChakan">查看</span><em name="imgshouju"></em></li>
                        <li> 还款凭证(本金、利息)　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgbenjin')?><span class="color uploadChakan">查看</span><em name="imgbenjin"></em></li>
                        <li><p class="color ml">友情提示 : 请您放心，涉及您和欠款方的信息，平台将做模糊处理。</p></li>
                    </ul>
                </li>
            <?php }
            else{
                ?>
                <li>
                    <ul>
                        <li class="ml">债权文件 : 上传凭证</li>
                        <li>《公证书》　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgnotarization',$files['imgnotarization'])?><span class="color uploadChakan">查看</span><em name="imgnotarization"></em></li>
                        <li>《借款合同》<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgcontract',$files['imgcontract'])?><span class="color uploadChakan">查看</span><em name="imgcontract"></em></li>
                        <li>《他项权证》<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgcreditor',$files['imgcreditor'])?><span class="color uploadChakan">查看</span><em name="imgcreditor"></em></li>
                        <li> 付款凭证　　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgpick',$files['imgpick'])?><span class="color uploadChakan">查看</span><em name="imgpick"></em></li>
                        <li> 收据　　　　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgshouju',$files['imgshouju'])?><span class="color uploadChakan">查看</span><em name="imgshouju"></em></li>
                        <li> 还款凭证(本金、利息)　<span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('imgbenjin',$files['imgbenjin'])?><span class="color uploadChakan">查看</span>　<em name="imgbenjin"></em></li>
                        <li><p class="color ml">友情提示 : 请您放心，涉及您和欠款方的信息，平台将做模糊处理。</p></li>
                    </ul>
                </li>
                <?php
            }
            ?>

            <li class="zq">
                <ul>
                    <?php
                    $creditorinfo = unserialize($model->creditorinfo);
                    if(empty($creditorinfo)){
                        ?>
                        <li class="ml">债权方信息（被欠方）</li>
                        <li><label for="">债权方名称 </label><?php echo html::input('text','creditorname[]','',['placeholder'=>'被欠方的企业或个人名称'])?></li>
                        <li><label for="">联系方式 </label><?php echo html::input('text','creditormobile[]','')?></li>
                        <li><label for="">联系地址 </label><?php echo html::input('text','creditoraddress[]','')?></li>
                        <li><label for="">证 件 号 </label><?php echo html::input('text','creditorcardcode[]','')?>
                            <span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('creditorcardimage[0]')?><span class="color uploadChakan">查看</span>　<em name="creditorcardimage"></em></li>
                        <?php
                    }
                    else{
                        foreach($creditorinfo as $k=>$ci)
                        {
                            $creditoradd = $k;
                            ?>
                            <li class="ml">债权方信息（被欠方）</li>
                            <li><label for="">债权方名称</label><?php echo html::input('text','creditorname[]',isset($ci['creditorname'])?$ci['creditorname']:'',['placeholder'=>'被欠方的企业或个人名称'])?></li>
                            <li><label for="">联系方式 </label><?php echo html::input('text','creditormobile[]',isset($ci['creditorname'])?$ci['creditormobile']:'')?></li>
                            <li><label for="">联系地址 </label><?php echo html::input('text','creditoraddress[]',isset($ci['creditorname'])?$ci['creditoraddress']:'')?></li>
                            <li><label for="">证 件 号 </label><?php echo html::input('text','creditorcardcode[]',isset($ci['creditorname'])?$ci['creditorcardcode']:'')?>
                                <span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput("creditorcardimage[$k]",$ci['creditorcardimage'])?><span class="color uploadChakan">查看</span>　<em name="creditorcardimage"></em>
                            </li>
                            <?php
                        }
                    }
                    ?>



                    <li><a href="javascript:void(0)" class="color creditoradd">添加债权方信息 + </a></li>
                </ul>
            </li>

            <li class="qk">
                <ul>
                    <?php
                    $borrowinginfo = unserialize($model->borrowinginfo);
                    if(empty($borrowinginfo)){
                        ?>
                        <li class="ml">债务方信息（欠款方）</li>
                        <li><label for="">债务方名称</label><?php echo html::input('text','borrowingname[]','',['placeholder'=>'欠款方的企业或个人名称'])?></li>
                        <li><label for="">联系方式 </label><?php echo html::input('text','borrowingmobile[]','')?></li>
                        <li><label for="">联系地址 </label><?php echo html::input('text','borrowingaddress[]','')?></li>
                        <li><label for="">证 件 号 </label><?php echo html::input('text','borrowingcardcode[]','')?>
                            <span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput('borrowingcardimage[0]')?><span class="color uploadChakan">查看</span><em name="borrowingcardimage"></em></li>
                        <?php
                    }
                    else{
                        foreach($borrowinginfo as $k=>$ci)
                        {
                            $borrowingadd =$k;
                            ?> <li class="ml">债务方信息（欠款方）</li>
                            <li><label for="">债务方名称</label><?php echo html::input('text','borrowingname[]',isset($ci['borrowingname'])?$ci['borrowingname']:'',['placeholder'=>'欠款方的企业或个人名称'])?></li>
                            <li><label for="">联系方式 </label><?php echo html::input('text','borrowingmobile[]',isset($ci['borrowingmobile'])?$ci['borrowingmobile']:'')?></li>
                            <li><label for="">联系地址 </label><?php echo html::input('text','borrowingaddress[]',isset($ci['borrowingaddress'])?$ci['borrowingaddress']:'')?></li>
                            <li><label for="">证 件 号 </label><?php echo html::input('text','borrowingcardcode[]',isset($ci['borrowingcardcode'])?$ci['borrowingcardcode']:'')?>
                                <span class="color uploadSpan">上传 +</span>　<?php echo Html::hiddenInput("borrowingcardimage[$k]",isset($ci['borrowingcardimage'])?$ci['borrowingcardimage']:'')?><span class="color uploadChakan">查看</span><em name="borrowingcardimage"></em>
                            </li>

                            <?php
                        }
                    }
                    ?>
                    <li><a href="javascript:void(0)" class="color borrowingadd">添加债务人信息 + </a></li>
                </ul>
            </li>
            <li>
                <input type="button" value="保存" onclick="SaveForm()">
                <input type="button" value="发布" onclick="PublishForm()">
            </li>
        </ul>
        <div style="display:none;" class="tanchu">
            <div class="rz_alert2c" style="font-size: 16px;">
                <p class="art" >补充信息您还没有填呢，信息越完善越容易获得接单发的青睐!</p>
                <div>
                    <a onclick="tops();"style="background:#c3c3c3;">直接发布</a>　　　<a onclick = "perfect();">去完善</a>
                </div>
            </div>
        </div>
        <script>
            $(function(){
                $('.pl').hide();
                change('',0);
            });
            function change(ob,num){
                $('.pl').hide();
                $('.pl').eq(num).show();
                $(ob).addClass('current').siblings().removeClass('current');
            }
            $('.guaran').css('display','none');
            $('.car').css('display','none');
            $('.accountr').css('display','none');
            $('.accounts ').click(function(){
                $('.guaran').css('display','none');
                $('.car').css('display','none');
                $('.accountr').css('display','none');
            });
            $('.private').click(function(){
                $('.guaran').css('display','block');
                $('.car').css('display','none');
                $('.accountr').css('display','none');
            })
            $('.cars').click(function(){
                $('.car').css('display','block');
                $('.guaran').css('display','none');
                $('.accountr').css('display','none');
            })
            $('.accountrs').click(function(){
                $('.accountr').css('display','block');
                $('.car').css('display','none');
                $('.guaran').css('display','none');
            });
            $(document).ready(function() {
                $("select[name='province_id']").change(
                    function () {
                        var pid = this.value;
                        $.ajax({
                            url:'<?php echo \yii\helpers\Url::toRoute('/func/getcity')?>',
                            type:"post",
                            data:{province_id:pid},
                            dataType:'html',
                            success:function(html){
                                $("select[name='city_id']").html(html);
                            }
                        });
                    }
                );
                $("select[name='city_id']").change(
                    function () {
                        var pid = this.value;
                        $.ajax({
                            url:'<?php echo \yii\helpers\Url::toRoute('/func/getdistrict')?>',
                            type:"post",
                            data:{city_id:pid},
                            dataType:'html',
                            success:function(html){
                                $("select[name='district_id']").html(html);
                            }
                        });
                    }
                );
                $("select[name='carbrand']").change(
                    function () {
                        var pid = this.value;
                        $.ajax({
                            url:'<?php echo \yii\helpers\Url::toRoute('/func/getaudi')?>',
                            type:"post",
                            data:{carbrand:pid},
                            dataType:'html',
                            success:function(html){
                                /*if(pid){$('.msgbox_bg').hide();}else{$('.msgbox_bg').show();}*/
                                if(pid){$('select[name="audi"]').removeAttr('disabled').css({'background':'','color':''})}else{$('select[name="audi"]').attr('disabled','disabled').css({
                                    "background":"#E8E9EE","color":"#878787"
                                })}
                                $("select[name='audi']").html(html);
                            }
                        });
                    }
                );


                var borrowingadd = <?php echo isset($borrowingadd)?$borrowingadd:0;?>+1;
                var creditoradd = <?php echo isset($creditoradd)?$creditoradd:0;?>+1;
                $('.creditoradd').click(function(){
                    $(this).parent().prepend("<hr />"+"<li><label for=''>债权方名称</label>"+'<?php echo html::input('text','creditorname[]','')?>'+"</li><li><label for=''>联系方式 </label>"+'<?php echo html::input('text','creditormobile[]','')?>'+"</li><li><label for=''>联系地址 </label>"+'<?php echo html::input('text','creditoraddress[]','')?>'+"</li><li><label for=''>证 件 号 </label>"+'<?php echo html::input('text','creditorcardcode[]','')?>'+"<span class='color display uploadSpan'>上传</span><input type = 'hidden' name = 'creditorcardimage["+(creditoradd++)+"]'> <span class='color uploadChakan'>查看</span></li>");
                });
                $('.borrowingadd').click(function(){
                    $(this).parent().prepend("<hr />"+"<li><label for=''>债务方名称</label>"+'<?php echo html::input('text','borrowingname[]','')?>'+"</li><li><label for=''>联系方式 </label>"+'<?php echo html::input('text','borrowingmobile[]','')?>'+"</li><li><label for=''>联系地址 </label>"+'<?php echo html::input('text','borrowingaddress[]','')?>'+"</li><li><label for=''>证 件 号 </label>"+'<?php echo html::input('text','borrowingcardcode[]','')?>'+"<span class='color display uploadSpan'>上传 </span><input type = 'hidden' name = 'borrowingcardimage["+(borrowingadd++)+"]'> <span class='color uploadChakan'>查看</span></li>");
                });

                /*jQuery.validator.addMethod("isRate", function(value, element) {
                    return this.optional(element) || isValidate();
                }, "");*/

                $("#financing").validate({
                    ignore: "",
                    rules: {
                        money: "required",
                        rebate: "required",
                        loan_type:"required",
                        /*rate:{
                            required:true,
                           // isRate:true
                        },
                        rate_cat: "required",*/
                        term:{
                            required:true,
                            //max:360
                        },
                        agencycommissionf:{
                            required: {
                                depends:function(){ //二选一
                                    if($('input[name=agencycommissions]').val()== '') return true;
                                    else return false;
                                }
                            }
                        },
                        agencycommission:"required",
                        repaymethod: "required",
                        fundstime: "required",
                        mortgagearea: "required",
                        /*province_id: "required",
                        city_id: "required",
                        district_id: "required",
                        'guaranteemethod[]': {
                            required: {
                                depends:function(){ //二选一
                                    if($('input[name=other]').val()== '') return true;
                                    else return false;
                                }
                            }
                        },
                        seatmortgage: "required",*/
                        obligor: "required",
                        commissionperiod: "required",
                        commitment: "required",
                    },

                    messages: {
                        money: "忘记填写金额啦",
                        rebate: "忘记填写返点啦",
                        loan_type:"忘记填写借款类型啦",
                        /*rate: {
                            required:'忘记填写利率啦',

                        },
                        rate_cat: "",*/
                        term:{
                            required: "忘记选择期限啦",
                           // max:"最大不超过360"
                        },
                        repaymethod: "忘记选择还款方式啦",
                        fundstime: "忘记填写资金到账日啦",
                        mortgagearea: "忘记填写抵押物面积啦",
                       /* province_id: " ",
                        city_id: " ",
                        district_id: " ",
                        'guaranteemethod[]': "忘记选择抵押物或者填写其他啦",
                        seatmortgage: "忘记填写抵押物地址啦",*/
                        obligor: "忘记选择债务人主体啦",
                        commissionperiod: "委托期限必选",
                        agencycommissionf: "请填写固定金额或费率",
                        agencycommission:"忘记填写代理费用啦",
                        commitment: "",
                    },
                    errorPlacement: function(error, element) {
                        var arr = ["guaranteemethod[]","loan_type","obligor",'agencycommissionf'];

                        if(error.html() == ''){
                            if ($.inArray(element.attr('name'), arr)>-1){
                                element.parent().parent().children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('yesshow').removeClass('error');
                            }else{
                                element.next('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('yesshow').removeClass('error');
                            }
                        }else{
                            if ($.inArray(element.attr('name'), arr)>-1){
                                element.parent().parent('li').children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error').removeClass('yesshow');
                            }else{
                                element.next('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error').removeClass('yesshow');
                            }

                        }
                    },
                    success: function (obj) {
                        obj.next('i').html('').addClass('yesshow').removeClass('error');

                        if($('input[name=other]').val()!= '') {$('input[name=other]').parent().parent().children('i').html('').removeClass('error');}
                    },


                });
            });

            function PublishForm(){
                $('#progress_status').val('1');
                var r = $('#financing').valid();
                //if(!isValidate())return false;
                if(r){
                    if($('.pl').eq(1).css('display') == 'none') {
                        $.msgbox({
                            closeImg: '/images/close1.png',
                            async: false,
                            height: 190,
                            width: 340,
                            content: $('.tanchu').html(),
                        });
                    }else{
                        registerProtocol($("input[name='money']").val(),$("select[name='commissionperiod'] option:selected").val());
                    }
                }else{
                    if($('.pl').eq(0).css('display') == 'none'){
                        alert('请切换到必填项填写完成')
                    }
                }
            }

            function tops(){
                $('.msgbox_wrapper').hide();
                $('.msgbox_bg').hide();
                // $('#jMsgboxBox').add().css('display','none').siblings().remove().css('display','none');
                registerProtocol($("input[name='money']").val(),$("select[name='commissionperiod'] option:selected").val());
            }
            function perfect(){
                $('.msgbox_wrapper').hide();
                $('.msgbox_bg').hide();
                if($('.pl').eq(1).css('display') == 'none'){
                    change('.fr',1);
                }
            }

            function SaveForm(){
                $('#progress_status').val('0');

                var r = $('#financing').valid()

                //if(!isValidate())return false;
                if(r){
                    registerSave($("input[name='money']").val(),$("select[name='commissionperiod'] option:selected").val());
                }else{

                    if($('.pl').eq(0).css('display') == 'none'){
                        alert('请切换到必填项填写完成')
                    }
                }
            }
            function subEvent(){
                $('#financing').submit();
            }
            function registerSave(money,commissionperiod){
                $.msgbox({
                    height:190,
                    width:360,
                    content:'<p>您保存的<?php if(isset($coll)&&$coll){echo '清收';}else{echo'诉讼';}?>信息如下:</p><p><?php if(isset($coll)&&$coll){echo '清收';}else{echo'诉讼';}?>金额：' +money+
                    '万元</p><p>委托代理期限：' +commissionperiod+
                    '个月</p><p>温馨提示：请确认信息后再保存。</p>',
                    type :'confirm',
                    onClose:function(v){
                        if(v){
                            subEvent();
                        }else{

                        }
                    }
                });
            }
            function registerProtocol(money,commissionperiod){
                $.msgbox({
                    height:210,
                    width:360,
                    content:'<p>您发布的<?php if(isset($coll)&&$coll){echo '清收';}else{echo'诉讼';}?>信息如下:</p><p><?php if(isset($coll)&&$coll){echo '清收';}else{echo'诉讼';}?>金额：' +money+
                    '万元</p><p>委托代理期限：' +commissionperiod+'个月</p><p>温馨提示：请先确认后再发布。</p>',
                    type :'confirm',
                    onClose:function(v){
                        if(v){
                            subEvent();
                        }else{

                        }
                    }
                });
            }

            $(function(){
                $('.box').hide();
                $('.suolve').hover(function(){
                    var img ={
                        0:"<?php echo $files['imgnotarization']?>",
                        1:"<?php echo $files['imgcontract']?>",
                        2:"<?php echo $files['imgcreditor']?>",
                        3:"<?php echo $files['imgpick']?>",
                        4:"<?php echo $files['imgshouju']?>",
                        5:"<?php echo $files['imgbenjin']?>",
                    };
                    var img_index = $(this).attr('status');
                    $('i.box').html('<img src="/'+img[img_index]+'" >');
                    /*$('i.box').html('<img src="/g.jpg" >');*/
                    $('i.box').find('img').each(function(){
                        var $imgH = $('i.box').outerHeight();
                        var $imgW = $('i.box').outerWidth();
                        $('i.box').find('img').css({
                            "width":$imgW,
                            "height":$imgH
                        })

                    })
                    $(this).find('i').stop().fadeToggle(500);
                });
            });
                $('input[name=agencycommissionf]').click(function(){
                    var i = $('input[name=agencycommissions]').val();
                    if(i){
                        $("input[name=agencycommissions]").val('');
                        alert('请二选一');
                    }
                })
                $('input[name=agencycommissions]').click(function(){
                    var i = $("input[name=agencycommissionf]").val()
                    if(i){
                        $("input[name=agencycommissionf]").val('')
                        alert('请二选一');
                    }
                })
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
            $(document).delegate('.uploadSpan','click',function(){
                var name = $(this).next('input:hidden').attr("name");
                $.msgbox({
                    closeImg: '/images/close.png',
                    async: false,
                    height:530,
                    width:630,
                    title:'请选择图片',
                    content:"<?php echo Url::toRoute(["/func/uploadsimg"])?>/?type="+name,
                    type:'ajax'
                });
            });
            $(document).delegate('.uploadChakan','click',function(){
                var typeName = $(this).prev('input:hidden').attr("name");
                var name = $(this).prev('input:hidden').val();
                $.msgbox({
                    closeImg: '/images/close.png',
                    async: false,
                    height:530,
                    width:630,
                    title:'显示照片',
                    content:"<?php echo Url::toRoute(["/func/viewimages"])?>/?name="+name+"&typeName="+typeName,
                    type:'ajax'
                });
            });
        </script>
    </div>

<?php \yii\widgets\ActiveForm::end();?>