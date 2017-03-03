<?php
use yii\helpers\Html;
use \common\models\FinanceProduct;
?>
<div class="content_right">
    <?php \yii\widgets\ActiveForm::begin(['id'=>'financing','method'=>'post'])?>
    <h3>融资发布</h3>
    <div class="bitian pl">
        <p class="jiantou">必填<em></em></p>
        <ul>
            <li>
                <label for="">金额 : <?php echo html::input('text','money',$model->money)?> 元<i></i></label>
                <label for="">返点 : <?php echo html::input('text','rebate',$model->rebate)?><i></i> </label>
            </li>
            <li>
                <label for=""><div style="display: inline-block;float: left;">利息 : <?php echo html::input('text','rate',$model->rate,['class'=>"tian"])?> % <i></i></div>
                        <div style="display: inline-block;float: left;"><?php echo html::dropDownList('rate_cat',$model->rate_cat,FinanceProduct::$ratedatecategory,['onchange'=>'ratecatchange(this.value)'])?><i></i></div>
                </label>
                <label for=""><div style="display: inline-block;float: left;">期限 : <?php echo html::input('text','term',$model->term,['class'=>"tian"])?> <i></i></div><div style="display: inline-block;float: left;"><?php echo html::dropDownList('rate_cat',$model->rate_cat,FinanceProduct::$ratedatecategory,['onchange'=>'ratecatchange(this.value)'])?><i></i></div>

                </label>

            </li>
            <li><label for="">资金到账日 : <?php echo html::input('text','fundstime',$model->fundstime)?><i></i></label>
                <label for="">　　　　　　抵押物面积 : <?php echo html::input('text','mortgagearea',$model->mortgagearea)?> m2 <i></i></label></li>
            <li><label for=""><div style="display: inline-block;float: left;">抵押物地址 : <?php echo  html::dropDownList('city_id','310100',['310100'=>'上海市']);?> <i></i></div><div style="display: inline-block;float: left;"><?php echo  html::dropDownList('district_id',$model->district_id,array_merge([''=>'请选择'],\frontend\services\Func::getDistrictByCity('310100')));?> <i></i></div><div style="display: inline-block;float: left;"><?php echo html::input('text','seatmortgage',$model->seatmortgage,['placeholder'=>'门牌 小区名 楼层'])?><i></i></div></label></li>   </ul>
    </div>
    <div class="chosen pl">
        <p class="jiantou">选填<em></em></p>
        <ul>
            <li>
                <label for="">抵押物类型 : <?php echo html::dropDownList('mortgagecategory',$model->mortgagecategory,FinanceProduct::$mortgagecategory)?></label>
            </li>
            <li>
                <label for="">租金 :<?php echo html::input('text','rentmoney',$model->rentmoney)?> 元</label>
            </li>

            <li>
                <label for="">状态 : <?php echo html::dropDownList('status',$model->status,FinanceProduct::$status)?></label>
            </li>
            <li>
                <label for="">抵押物状况 :<?php echo html::dropDownList('mortgagestatus',$model->mortgagestatus,FinanceProduct::$mortgagestatus)?></label>
            </li>
            <li>
                <label for="">借款年龄 : <?php echo html::input('text','loanyear',$model->loanyear)?> 岁</label>
            </li>
            <li>
                <label for="">权利人年龄 : <?php echo html::dropDownList('obligeeyear',$model->obligeeyear,FinanceProduct::$obligeeyear)?></label>
            </li>
            <li>注:信息填写越完整,将会吸引更多接单方与您联系!</li>
            <li>
                <?php echo html::input('hidden','progress_status',$model->progress_status,['id'=>'progress_status'])?>
                <input type="button" value="保存" onclick="SaveForm()">
                <input type="button" value="提交" onclick="PublishForm()">
            </li>
        </ul>
    </div>

    <?php \yii\widgets\ActiveForm::end();?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#financing").validate({
                rules: {
                    money: "required",
                    rebate: "required",
                    rate: "required",
                    rate_cat: "required",
                    term: "required",
                    fundstime: "required",
                    mortgagearea: "required",
                    district_id: "required",
                    seatmortgage: "required",
                },

                messages: {
                    money: "金额必填",
                    rebate: "返点必填",
                    rate: "利息必填",
                    rate_cat: "",
                    term: "期限必填",
                    fundstime: "资金到账日必填",
                    mortgagearea: "抵押物面积必填",
                    district_id: "区域必填",
                    seatmortgage: "抵押物地址必填",
                },
                errorPlacement: function(error, element) {
                    element.parent().children('i').html('&nbsp;'+error.html()+'&nbsp;').addClass('error');
                },
                success: function (element) {
                    element.parent().children('i').html('').removeClass('error');
                }

            });
        });

        function PublishForm(){
            $('#progress_status').val(2);
            $('#financing').submit();
        }

        function SaveForm(){
            $('#progress_status').val(1);
            $('#financing').submit();
        }

        function ratecatchange(value){
            $("select[name='rate_cat']").val(value);
        }
    </script>
</div>
    