<?php
use \yii\helpers\html;
use \common\models\CreditorProduct;
use \yii\helpers\Url;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post','options'=>['enctype'=>"multipart/form-data"],])?>
    <ul class="tb_top mr clearfix">
        <li class="current" onclick='change(this,0);'><a href="#">基本信息</a><span>(必填)</span></li>
        <li onclick='change(this,1);'><a href="#">补充信息</a><span>(选填)</span></li>
    </ul>

    <div class="detail pl">
        <ul>

            <li>
                <label for="">借款本金 : <?php echo html::input('text','money',$model->money)?> 万元<i></i></label>
            </li>
            <li>
                <label for="">借款期限 : <?php echo html::input('text','term',$model->term)?> 月 <i></i></label>
            </li>
            <li class="clearfix">
                <div style="display: inline-block;float: left;">借款利率 : <?php echo html::input('text','rate',$model->rate,['class'=>"tian"])?> % <i></i></div>
                <div style="display: inline-block;float: left;">月<?php echo Html::hiddenInput('rate_cat','2')?><i></i></div>
            
            　　　　还款方式 : <?php echo html::dropDownList('repaymethod',$model->repaymethod,CreditorProduct::$repaymethod)?>
            </li>
            <li >
                <?php
                    $guaranteemethod = unserialize($model->guaranteemethod);
                $ma = '';
                $mb = [];
                    if(is_array($guaranteemethod)){
                        $ma = isset($guaranteemethod['ohter'])?$guaranteemethod['ohter']:'';
                        unset($guaranteemethod['ohter']);
                        $mb = $guaranteemethod;
                    }
                $judicialstatusA = unserialize($model->judicialstatusA);
                $judicialstatusA = is_array($judicialstatusA)?$judicialstatusA:[];
                ?>
                抵押 : <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(1,$mb),['value'=>1])?>住房</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(2,$mb),['value'=>2])?>别墅</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(3,$mb),['value'=>3])?> 写字楼</label> <label for=""><?php echo html::checkbox('guaranteemethod[]',in_array(4,$mb),['value'=>4])?>商铺 其他 <?php echo html::input('text','other',$ma,['class'=>'tian'])?> </label><i></i>
            </li>
            <li class="mr">担保物 (抵押物) 所在地 <?php echo  html::dropDownList('city_id','310100',['310100'=>'上海市']);?> <label><?php echo  html::dropDownList('district_id',$model->district_id,\frontend\services\Func::getDistrictByCity('310100'));?> <i></i></label><label><?php echo html::input('text','seatmortgage',$model->seatmortgage)?><i></i></label></li>
            <li><label for="">司法现状:(1)<?php echo html::checkbox('judicialstatusA[]',in_array(1,$judicialstatusA),['value'=>1])?>诉讼</label> <label for=""><?php echo html::checkbox('judicialstatusA[]',in_array(2,$judicialstatusA),['value'=>2])?>仲裁</label> <label for=""><?php echo html::checkbox('judicialstatusA[]',in_array(3,$judicialstatusA),['value'=>3])?>强制执行公证</label> <label for=""><?php echo html::checkbox('judicialstatusA[]',in_array(4,$judicialstatusA),['value'=>4])?>强制执行</label><i></i></li>
            <li>　　　　 <label for="">(2)债务人是否能正常联系: <?php echo html::radio('judicialstatusB',$model->judicialstatusB==1,['value'=>1])?>可联</label> <label for=""><?php echo html::radio('judicialstatusB',$model->judicialstatusB == 2,['value'=>2])?>失联</label><i></i>
            </li>
            <li>
                <label for="">债务人主体:<?php echo html::radio('obligor',$model->obligor == 1,['value'=>1])?>自然人 </label> <label for=""><?php echo html::radio('obligor',$model->obligor == 2,['value'=>2])?>法人</label> <label for=""><?php echo html::radio('obligor',$model->obligor == 3,['value'=>3])?> 其他(未成年,外籍等)</label><i></i>
            </li>
            <li>
                委托事项 : <?php echo html::dropDownList('commitment',$model->commitment,CreditorProduct::$commitment)?>
            </li>
            <li>
                委托期限 : <?php echo html::dropDownList('commissionperiod',$model->commissionperiod,CreditorProduct::$commissionperiod)?> 月
            </li>
            <li class="money">
                <b>
                    代理费用 :(1) 固定<?php echo html::input('text','agencycommissionf',$model->agencycommissiontype == 1?$model->agencycommission:'',['class'=>"tian"])?>元                     <i class="wenhao"></i>
                                <span class="wenhao">
                                    固定指固定收费代理，是指律师服务费作为收取的固定费用，
                                    收取的费用与案件的处理结果无关。<em></em>
                                </span>
                </b>
                　　
                <b>
                    　　　(2) 风险费率  <?php echo html::input('text','agencycommissions',$model->agencycommissiontype == 2?$model->agencycommission:'',['class'=>"tian"])?>%
                    <i></i>
                                <span>
                                    固定指固定收费代理，是指律师服务费作为收取的固定费用，
                                    收取的费用与案件的处理结果无关。<em></em>
                                </span>
                </b>
                <i style="width:270px;display: inline-block;background: none;position: relative;margin:0px;padding: 0px;left:10px;"></i>
            </li>
            <li>
                付款方式 : <?php echo html::dropDownList('paymethod',$model->paymethod,CreditorProduct::$paymethod)?>
            </li>
            <li>
                <?php echo html::input('hidden','progress_status',1,['id'=>'progress_status'])?>
                <input type="button" value="保存" onclick="SaveForm()">
                <input type="button" value="提交" onclick="PublishForm()">
            </li>
        </ul>
    </div>

    <div class="buchong pl">
        <ul>
            <li class="ul_top">
                <label for="">已付本金 : <?php echo html::input('text','paidmoney',$model->paidmoney)?> 元</label>
                <label for="">已付利息 : <?php echo html::input('text','interestpaid',$model->interestpaid)?> 元</label>
                <label for="">合同履行地 : <?php echo html::input('text','performancecontract',$model->performancecontract)?> </label>
            </li>
            <?php
            $files = unserialize($model->creditorfile);
            if(empty($files)){
                ?>
            <li>
                <ul>
                    <li class="ml">债权文件 : 上传凭证</li>
                    <li>《公证书》　<input type="file" accept = 'image/*' name="CreditorProduct[imgnotarization]"><span class="color">上传 +</span>　<em name="imgnotarization"></em></li>
                    <li>《接款合同》<input type="file" accept = 'image/*' name="CreditorProduct[imgcontract]"><span class="color">上传 +</span>　<em name="imgcontract"></em></li>
                    <li>《他项权证》<input type="file" accept = 'image/*' name="CreditorProduct[imgcreditor]"><span class="color">上传 +</span>　<em name="imgcreditor"></em></li>
                    <li>付款凭证　　<input type="file" accept = 'image/*' name="CreditorProduct[imgpick]"><span class="color">上传 +</span>　<em name="imgpick"></em></li>
                    <li>收据　　　　<input type="file" accept = 'image/*' name="CreditorProduct[imgshouju]"><span class="color">上传 +</span>　<em name="imgshouju"></em></li>
                    <li>还款凭证(本金、利息)　<input type="file" accept = 'image/*' name = "CreditorProduct[imgbenjin]"><span class="color">上传 +</span>　<em name="imgbenjin"></em></li>
                    <li><p class="color ml">备注: 发布信息后3日内需提供所选债权文件原件供审核</p></li>
                </ul>
            </li>
            <?php }
              else{
                      ?>
                      <li>
                          <ul>
                              <li class="ml">债权文件 : 上传凭证</li>
                              <li>《公证书》　<input type="file" accept = 'image/*' name="CreditorProduct[imgnotarization]"><span class="color">上传 +</span><em name="imgnotarization"></em>　<?php if($files['imgnotarization']){echo '<span class="suolve" status="0">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li>《借款合同》<input type="file" accept = 'image/*' name="CreditorProduct[imgcontract]"><span class="color">上传 +</span><em name="imgcontract"></em>　<?php if($files['imgcontract']){echo '<span class="suolve" status="1">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li>《他项权证》<input type="file" accept = 'image/*' name="CreditorProduct[imgcreditor]"><span class="color">上传 +</span><em name="imgcreditor"></em>　<?php if($files['imgcreditor']){echo '<span class="suolve" status="2">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li>付款凭证　　<input type="file" accept = 'image/*' name="CreditorProduct[imgpick]"><span class="color">上传 +</span><em name="imgpick"></em>　<?php if($files['imgpick']){echo '<span class="suolve" status="3">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li>收据　　　　<input type="file" accept = 'image/*' name="CreditorProduct[imgshouju]"><span class="color">上传 +</span><em name="imgshouju"></em><?php if($files['imgshouju']){echo '<span class="suolve" status="4">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li>还款凭证(本金、利息)　<input type="file" accept = 'image/*' name = "CreditorProduct[imgbenjin]"><span class="color">上传 +</span><em name="imgbenjin"></em>　<?php if($files['imgbenjin']){echo '<span class="suolve" status="5">详情 <i class="box"></i></span>';}else{echo '';}?></li>
                              <li><p class="color ml">备注: 发布信息后3日内需提供所选债权文件原件供审核</p></li>
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
                        <li class="ml">债权方信息 : </li>
                        <li><label for="">姓　　名 : <?php echo html::input('text','creditorname[]','')?></label></li>
                        <li><label for="">联系方式 : <?php echo html::input('text','creditormobile[]','')?></label></li>
                        <li><label for="">联系地址 : <?php echo html::input('text','creditoraddress[]','')?></label></li>
                        <li><label for="">证 件 号 : <?php echo html::input('text','creditorcardcode[]','')?></label>
                            <?php echo html::input('file','CreditorProduct[creditorcardimage][]','',['accept'=>'image/*']);?>
                            <span class="color display">上传</span>　<em name="creditorcardimage"></em></li>
                        <?php
                    }
                    else{
                        foreach($creditorinfo as $ci)
                        {
                        ?>
                        <li class="ml">债权方信息 : </li>
                        <li><label for="">姓　　名 : <?php echo html::input('text','creditorname[]',isset($ci['creditorname'])?$ci['creditorname']:'')?></label></li>
                        <li><label for="">联系方式 : <?php echo html::input('text','creditormobile[]',isset($ci['creditorname'])?$ci['creditormobile']:'')?></label></li>
                        <li><label for="">联系地址 : <?php echo html::input('text','creditoraddress[]',isset($ci['creditorname'])?$ci['creditoraddress']:'')?></label></li>
                        <li><label for="">证 件 号 : <?php echo html::input('text','creditorcardcode[]',isset($ci['creditorname'])?$ci['creditorcardcode']:'')?></label>
                            <?php echo html::input('file','CreditorProduct[creditorcardimage][]','',['accept'=>'image/*']);?><span class="color display">上传</span><em name="creditorcardimage"></em>
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
                    <li class="ml">欠款方信息 : </li>
                    <li><label for="">姓　　名 : <?php echo html::input('text','borrowingname[]','')?></label></li>
                    <li><label for="">联系方式 : <?php echo html::input('text','borrowingmobile[]','')?></label></li>
                    <li><label for="">联系地址 : <?php echo html::input('text','borrowingaddress[]','')?></label></li>
                    <li><label for="">证 件 号 : <?php echo html::input('text','borrowingcardcode[]','')?></label>
                        <?php echo html::input('file','CreditorProduct[borrowingcardimage][]','',['accept'=>'image/*'])?><span class="color display">上传 </span><em name="borrowingcardimage"></em></li>
                <?php
                }
                else{
                foreach($borrowinginfo as $ci)
                {
                ?> <li class="ml">欠款方信息 : </li>
                    <li><label for="">姓　　名 : <?php echo html::input('text','borrowingname[]',isset($ci['borrowingname'])?$ci['borrowingname']:'')?></label></li>
                    <li><label for="">联系方式 : <?php echo html::input('text','borrowingmobile[]',isset($ci['borrowingmobile'])?$ci['borrowingmobile']:'')?></label></li>
                    <li><label for="">联系地址 : <?php echo html::input('text','borrowingaddress[]',isset($ci['borrowingaddress'])?$ci['borrowingaddress']:'')?></label></li>
                    <li><label for="">证 件 号 : <?php echo html::input('text','borrowingcardcode[]',isset($ci['borrowingcardcode'])?$ci['borrowingcardcode']:'')?></label> <?php echo html::input('file','CreditorProduct[borrowingcardimage][]','',['accept'=>'image/*'])?><span class="color display">上传 </span><em name="borrowingcardimage"></em>
                    </li>

                    <?php
                }
                }
                ?>
                    <li><a href="javascript:void(0)" class="color borrowingadd">添加欠款方信息 + </a></li>
                </ul>
            </li>
            <li>
                <input type="button" value="保存" onclick="SaveForm()">
                <input type="button" value="提交" onclick="PublishForm()">
            </li>
        </ul>

        <div id = "creditor" style="display: none">
            <li><label for="">姓　　名 : <?php echo html::input('text','creditorname[]','')?></label></li>
            <li><label for="">联系方式 : <?php echo html::input('text','creditormobile[]','')?></label></li>
            <li><label for="">联系地址 : <?php echo html::input('text','creditoraddress[]','')?></label></li>
            <li><label for="">证 件 号 : <?php echo html::input('text','creditorcardcode[]','')?></label> <?php echo html::input('file','CreditorProduct[creditorcardimage][]','')?><span class="color display">上传</span></li>
        </div>
        <div id = "borrowing"  style="display: none">
            <li><label for="">姓　　名 : <?php echo html::input('text','borrowingname[]','')?></label></li>
            <li><label for="">联系方式 : <?php echo html::input('text','borrowingmobile[]','')?></label></li>
            <li><label for="">联系地址 : <?php echo html::input('text','borrowingaddress[]','')?></label></li>
            <li><label for="">证 件 号 : <?php echo html::input('text','borrowingcardcode[]','')?></label> <?php echo html::input('file','CreditorProduct[borrowingcardimage][]','')?><span class="color display">上传 </span></li>
        </div>

        <script>
            $(function(){
                $('.pl').hide();
                change('',0);
            });
            function change(ob,num){
                $('.pl').hide();
                $('.pl').eq(num).show();
                $(ob).addClass('current').siblings('li').removeClass('current');
            }
            $.validator.addMethod('atLeastOneChecked', function(value, element) {
                var checkedCount = 0;
                $("input[name='judicialstatusA']").each(function() {
                    if ($(this).attr('checked')) { checkedCount++; }
                });
                return checkedCount>0;

            },"请选择至少一项");

            $(document).ready(function() {
                $('.borrowingadd').click(function(){
                    $(this).parent().prepend("<hr />"+$('#borrowing').html());
                });
                $('.creditoradd').click(function(){
                    $(this).parent().prepend("<hr />"+$('#creditor').html());
                });
                $("#financing").validate({
                    ignore: "",
                    rules: {
                        money: "required",
                        rebate: "required",
                        rate: "required",
                        rate_cat: "required",
                        term: "required",
                        repaymethod: "required",
                        fundstime: "required",
                        mortgagearea: "required",
                        district_id: "required",
                        'guaranteemethod[]': {
                            required: {
                                depends:function(){ //二选一
                                    if($('input[name=other]').val()== '') return true;
                                    else return false;
                                }
                            }
                        },
                        seatmortgage: "required",
                        'judicialstatusA[]': "required",
                        judicialstatusB: "required",
                        obligor: "required",
                        paymethod: "required",
                        commissionperiod: "required",
                        agencycommissionf:{
                            required: {
                                depends:function(){ //二选一
                                    if($('input[name=agencycommissions]').val()== '') return true;
                                    else return false;
                                }
                            }
                        },
                        commitment: "required",
                    },

                    messages: {
                        money: "金额必填",
                        rebate: "返点必填",
                        rate: "利息必填",
                        rate_cat: "",
                        term: "期限必填",
                        repaymethod: "还款方式必选",
                        fundstime: "资金到账日必填",
                        mortgagearea: "抵押物面积必填",
                        district_id: "",
                        'guaranteemethod[]': "抵押物选择或者填写其他",
                        seatmortgage: "抵押物地址必填",
                        'judicialstatusA[]': "司法现状必选",
                        judicialstatusB: "债务人是否可联系必选",
                        obligor: "债务人主体必选",
                        paymethod: "",
                        commissionperiod: "",
                        agencycommissionf: "代理费用填写固定金额或者填写费率",
                        commitment: "",
                    },
                    errorPlacement: function(error, element) {
                        if (element.is(':radio') || element.is(':checkbox')){
                            element.parent().parent().children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
                        }else if(element.attr('name') == 'agencycommissionf'||element.attr('name') == 'agencycommissions'){
                            element.parent().parent().children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
                        }else{
                            element.parent().children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
                        }
                    },
                    success: function (element) {
                        element.parent().children('i').html('').removeClass('error');
                        if($('input[name=other]').val()!= '') {$('input[name=other]').parent().parent().children('i').html('').removeClass('error');}
                        if($('input[name=agencycommissions]').val()!= '') {$('input[name=agencycommissions]').parent().parent().children('i').html('').removeClass('error');}
                    },


                });
            });

            function PublishForm(){
                $('#progress_status').val('1');
                var r = $('#financing').valid()
                if(r){
                    $(".detail select").removeAttr("disabled");
                    $('#financing').submit();
                }else{
                    if($('.pl').eq(0).css('display') == 'none'){
                        alert('请切换到必填项填写完成')
                    }
                }
            }

            function SaveForm(){
                $('#progress_status').val('0');

                var r = $('#financing').valid()
                if(r){
                    $(".detail select").removeAttr("disabled");
                    $('#financing').submit();
                }else{
                    if($('.pl').eq(0).css('display') == 'none'){
                        alert('请切换到必填项填写完成')
                    }
                }
            }
            $(function() {
                $(':file').click(function () {
                    $('input[name="CreditorProduct[imgnotarization]"]').change(function (a) {
                        if (a) {
                            $('em[name=imgnotarization]').css('color', 'red');
                            $('em[name=imgnotarization]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[imgcontract]"]').change(function (b) {
                        if (b) {
                            $('em[name=imgcontract]').css('color', 'red');
                            $('em[name=imgcontract]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[imgcreditor]"]').change(function (c) {
                        if (c) {
                            $('em[name=imgcreditor]').css('color', 'red');
                            $('em[name=imgcreditor]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[imgpick]"]').change(function (d) {
                        if (d) {
                            $('em[name=imgpick]').css('color', 'red');
                            $('em[name=imgpick]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[imgshouju]"]').change(function (e) {
                        if (e) {
                            $('em[name=imgshouju]').css('color', 'red');
                            $('em[name=imgshouju]').html('已上传');
                        }
                    })
                    $('input[name = "CreditorProduct[imgbenjin]"]').change(function (f) {
                        if (f) {
                            $('em[name=imgbenjin]').css('color', 'red');
                            $('em[name=imgbenjin]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[creditorcardimage][]"]').change(function(g){
                        if(g){
                            $('em[name=creditorcardimage]').css('color', 'red');
                            $('em[name=creditorcardimage]').html('已上传');
                        }
                    })
                    $('input[name="CreditorProduct[borrowingcardimage][]"]').change(function(h){
                        if(h){
                            $('em[name=borrowingcardimage]').css('color', 'red');
                            $('em[name=borrowingcardimage]').html('已上传');
                        }
                    })
                })
            })
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


            $('.detail input').attr('readonly',true);
            $('.detail select').attr('disabled',true);
            $('.detail input').attr('onclick','return false;');
        </script>
    </div>
<?php \yii\widgets\ActiveForm::end();?>