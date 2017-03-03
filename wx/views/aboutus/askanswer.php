<style>
    .arrow1{
        width: 14px;
        height: 8px;
        margin: 22px 0 0;
        background: url(/images/arrow.png) no-repeat 0 -8px;
        background-size: 14px;
        display: inline-block;
        float: right;
        margin-right: 10px;
    }
</style>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'常见问答','gohtml'=>''])?>
<section>
    <div class="wenda">
        <ul>
            <li>
                <div class="float">
                    <div class="line-a line-a1">
                        <span class="wen">问</span>
                        <span class="juli">我发布的信息如何吸引更多接单方查看？</span>
                    </div>
                    <div class="arrow"></div>
                </div>
                <ul class="detail_a1" style="display:none">
                    <li>
                        <span class="da">答</span>
						<span class="juli">
							如果接单方浏览到您所发布的信息且有服务意向，就会向您申请服务。您可以看到所有申请服务的处置方的详细资料（包括联络方式），联系处置方，从中选择一家合适的处置方后，在平台上点击“确认申请”，进行确认。
						</span>
                    </li>
                </ul>
            </li>
            <li>
                <div class="float">
                    <div class="line-a line-a1">
                        <span class="wen">问</span>
                        <span class="juli">发布方如何与处置方联系？</span>
                    </div>
                    <div class="arrow"></div>
                </div>
                <ul class="detail_a1" style="display:none">
                    <li>
                        <span class="da">答</span>
						<span class="juli">
							如果接单方浏览到您所发布的信息且有服务意向，就会向您申请服务。您可以看到所有申请服务的处置方的详细资料（包括联络方式），联系处置方，从中选择一家合适的处置方后，在平台上点击“确认申请”，进行确认。
						</span>
                    </li>
                </ul>
            </li>
            <li>
                <div class="float">
                    <div class="line-a line-a1">
                        <span class="wen">问</span>
                        <span class="juli">发布方可以修改已填写的"债权信息"吗？</span>
                    </div>
                    <div class="arrow"></div>
                </div>
                <ul class="detail_a1" style="display:none">
                    <li>
                        <span class="da">答</span>
						<span class="juli">
							如果已经填写“债权信息”，处于待发布状态，可以进行修改。但是如果您的“债权信息”已经发布并有处置方申请了服务，您就不能再对“债权信息”进行修改。
						</span>
                    </li>
                </ul>
            </li>

            <li>
                <div class="float">
                    <div class="line-a line-a1">
                        <span class="wen">问</span>
                        <span class="juli">如何在平台上注册，需要交会员费吗？</span>
                    </div>
                    <div class="arrow"></div>
                </div>
                <ul class="detail_a1" style="display:none">
                    <li>
                        <span class="da">答</span>
						<span class="juli">
							在清道夫债管家平台，您既可以发布信息，也可以处置信息。用户点击“注册”，进入注册流程。在注册时，请按照注册页面的提示完整真实地填写各项信息。清道夫债管家作为第三方信息发布平台，根据“清道夫债管家网站服务协议”为用户提供服务，平台注册不收取任何费用。
						</span>
                    </li>
                </ul>
            </li>
            <li>
                <div class="float">
                    <div class="line-a line-a1">
                        <span class="wen">问</span>
                        <span class="juli">发布方及接单方的综合评价是如何形成的?</span>
                    </div>
                    <div class="arrow"></div>
                </div>
                <ul class="detail_a1" style="display:none">
                    <li>
                        <span class="da">答</span>
						<span class="juli">
							平台专业人员基于两方项目资料真实性为前提，着重分析债权金额、项目来源、借款时间、逾期情况、担保方式等重要因素后，对各因素对债权风险的影响程度进行量化、设置权重，最后通过加权法得出的结果，仅供发布方和处置方参考。
						</span>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.wenda ul li').bind("click",function(){
            $(this).children().children('.arrow').toggleClass('arrow1').parent().next().toggle();
            $(this).siblings().children().children('.arrow').attr('class','arrow').parent().siblings().hide();
        })
    })

</script>
