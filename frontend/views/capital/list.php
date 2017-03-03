<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="banners_1">
    <?php if(Yii::$app->request->get('cat')==0){ ?>
        <div <?php if(in_array(Yii::$app->request->get('cat'),[0])){echo "class='b_susong'";}?>>
            <img src="/images/banner-cuishou.png" height="176" width="1920" alt="">
        </div>
    <?php }else if(Yii::$app->request->get('cat') == 2){ ?>

        <div <?php if(in_array(Yii::$app->request->get('cat'),[2])){echo "class='b_susong'";}?>>
            <img src="/images/banner-cuishou.png" height="176" width="1920" alt="">
        </div>
    <?php }else if(Yii::$app->request->get('cat') == 3){ ?>
        <div <?php if(in_array(Yii::$app->request->get('cat'),[3])){echo "class='b_susong'";}?>>
            <img src="/images/banner-susong.png" height="176" width="1920" alt="">
        </div>
    <?php }else{ ?>
        <div class='b_susong'>
            <img src="/images/banner-rongzi.png" height="176" width="1920" alt="">
        </div>
    <?php }?>
</div>
<!-- banner 结束-->
<!-- 内容开始-->
<div class="list_con">
    <div class="list_con1">
        <div class="list_menu">
            <span id = '0' <?php if(in_array(Yii::$app->request->get('cat'),[0])){echo "class='current'";}?>>不限</span>
            <!--<span id = '1' <?php /*if(in_array(Yii::$app->request->get('cat'),[1])){echo "class='current'";}*/?>><i class="display rz"></i><b></b>融资</span>-->
            <span id = '2' <?php if(in_array(Yii::$app->request->get('cat'),[2])){echo "class='current'";}?>><i class="display cs"></i><b></b>清收</span>
            <span id = '3' <?php if(in_array(Yii::$app->request->get('cat'),[3])){echo "class='current'";}?>><i class="display ss"></i><b></b>诉讼</span>
        </div>
        <ul class="area">
            <li class="one">
                <span>区域:　</span>
                <div class="slect_a"><?php echo  html::dropDownList('province_id',Yii::$app->request->get('province_id')?Yii::$app->request->get('province_id'):'',\frontend\services\Func::getProvince(),['class'=>'root']);?></div>
                <div class="slect_a"><?php echo  html::dropDownList('city_id',Yii::$app->request->get('city_id'),\frontend\services\Func::getCityByProvince(Yii::$app->request->get('province_id')?Yii::$app->request->get('province_id'):'310000'));?></div>
                <div class="slect_a"><?php echo  html::dropDownList('district_id',Yii::$app->request->get('district_id'),\frontend\services\Func::getDistrictByCity(Yii::$app->request->get('city_id')));?> <i></i></div> 

            </li>

            <li class="four">
                <span>状态:　</span><a href="#" id = '0' <?php if(in_array(Yii::$app->request->get('progress'),[0])){echo "class='current'";}?>>不限</a>　
                <a href="javascript:;" id='1'<?php if(in_array(Yii::$app->request->get('progress'),[1])){echo "class='current'";}?>>发布中</a>
                <a href="javascript:;" id='2'<?php if(in_array(Yii::$app->request->get('progress'),[2])){echo "class='current'";}?>>处理中</a>
                <a href="javascript:;" id='3,4'<?php if(in_array(Yii::$app->request->get('progress'),[3,4])){echo "class='current'";}?>>已结案</a>
            </li>
            <li class="two">
                <span>金额:　</span><a href="#" id = '0'<?php if(in_array(Yii::$app->request->get('money'),[0])){echo "class='current'";}?>>不限</a>　
                <a href="javascript:;"  id = '1' <?php if(in_array(Yii::$app->request->get('money'),[1])){echo "class='current'";}?>>30万以下</a>　
                <a href="javascript:;" id = '2' <?php if(in_array(Yii::$app->request->get('money'),[2])){echo "class='current'";}?>>30-100万</a>　
                <a href="javascript:;" id = '3' <?php if(in_array(Yii::$app->request->get('money'),[3])){echo "class='current'";}?>>100-500万</a>　
                <a href="javascript:;" id = '4' <?php if(in_array(Yii::$app->request->get('money'),[4])){echo "class='current'";}?>>500万以上</a>
            </li>
        </ul>
    </div>
    <?php foreach($creditor as $value):?>
        <?php $read = \common\models\Statistics::findOne(['cid'=>$value['id'],'category'=>$value['category'],'uid'=>\Yii::$app->user->getId()])?>
        <?php $apply = \common\models\Apply::findOne(['product_id'=>$value['id'],'category'=>$value['category'],'app_id'=>1]); ?>
        <div  <?php if(in_array($value['progress_status'],[2,3,4])){echo 'class="bianhao current "';}else{ ?> class="bianhao current bian" <?php } ?> data-category = "<?php echo $value['category'];?>"  data-id = "<?php echo $value['id'];?>" >
            <?php echo Html::hiddenInput('id',$value['id'])?>
            <?php echo Html::hiddenInput('category',$value['category'])?>
            <?php echo Html::hiddenInput('uid',$value['uid'])?>
            <?php if(in_array($value['progress_status'],[2,3,4])){echo '';}else{ ?>
            <?php if($read['status'] === 0){?>
            <i class="yck"></i>
            <?php }else if($read['status'] === 1){?>
            <i class="ysq"></i>
            <?php }else{ echo'';}?>
            <?php }?>

            <div class="bianh">
                <div class="myleft clearfix ">
                <div class="left1">
                    <p class="left_t">【<b><?php if($value['category'] == 1){echo '融资';}else if($value['category'] == 2){echo'清收';}else{echo'诉讼';}?></b>】<?php echo isset($value['code'])?$value['code']:'';?></p>

                    <?php if($value['category'] == 1){?>
                    <p class="diy">抵押物地址：
                        <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                        <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
						<?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                        <?php //echo \frontend\services\Func::Substr($value['seatmortgage'],'4',"*");?>
                    </p>
                    <?php } else if($value['loan_type'] == 1){?>
                    <p class="diy">    抵押物地址：
                        <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                        <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
						<?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                        <?php //echo \frontend\services\Func::Substr($value['seatmortgage'],'4',"*");?>
                    </p>
                     <?php }else{echo '';}?>

                    <p class="lefts"><span style="margin-left:-37px;font-size:28px;"><?php echo isset($value['money'])?$value['money']:'';?></span>
                        <span style="color:#0065b3;font-size:28px;">
                            <?php if($value['category'] == 1){ ?>
                                <?php echo isset($value['rebate'])?$value['rebate'].'%':'';?>
                            <?php }else if($value['category'] == 2){?>
                                <?php echo isset($value['rentmoney'])&&$value['rentmoney']==1?$value['mortgagemoney'].'%':$value['mortgagemoney'].'万';?>
                            <?php }else if($value['category'] == 3){?>
                                <?php if($value['rentmoney']==1){ ?>
                                    <?php echo isset($value['mortgagemoney'])?$value['mortgagemoney'].'万':''?>
                                <?php }else{ ?>
                                    <?php echo isset($value['mortgagemoney'])?$value['mortgagemoney'].'%':''?>
                                <?php } ?>
                            <?php } ?>
                            </span><span style="color:#666;">
                            <?php if($value['category'] == 1){ ?>
                                <?php echo isset($value['rate'])?$value['rate']:'';?>%(<?php echo isset($value['rate_cat'])?\common\models\FinanceProduct::$ratedatecategory[$value['rate_cat']]:''?>)
                            <?php }else{ ?>
                                <?php echo isset($value['loan_type'])&&$value['loan_type']==0 ? '无':common\models\CreditorProduct::$loan_types[$value['loan_type']]?>
                            <?php } ?>
                            </span></p>
                    <p class="left_q">
                        <span style="margin-left:-40px;">借款本金(万元)</span>
                        <span><?php if($value['category'] == 1){ echo '返点';}
                            else if($value['category'] == 2){echo isset($value['rentmoney'])&&$value['rentmoney']==1?'服务佣金':'固定费用';}
                            else if($value['category'] == 3){ ?>
                                <?php echo isset($value['mortgagemoney'])?\common\models\CreditorProduct::$agencycommissiontype[$value['rentmoney']]:'';?>
                            <?php } ?>
                        </span>
                        <span><?php if($value['category'] == 1){ echo '借款利率';}else{echo'债权类型';}?></span>
                    </p>
                </div>
                <div class="right1">
                <?php if($value['progress_status'] == 2){
                    echo "<a class='see nowapply' style='width:205px; height: 33px; margin:70px 25px 0 40px; background:#b5b5b5;'>已申请</a>";
                }else if($value['progress_status'] == 3){
                    echo "<a class='see nowapply' style='width:205px; height: 33px; margin:70px 25px 0 40px; background:#b5b5b5;'>已终止</a>";
                }else if($value['progress_status'] == 4){
                    echo "<a class='see nowapply' style='width:205px; height: 33px; margin:70px 25px 0 40px; background:#b5b5b5;'>已结案</a>";
                }else if($read['status'] === 0 && empty($apply) && $apply['app_id'] == 2){
					echo '<a href="javascript:void(0);" style="width:205px; height: 33px; line-height: 32px; text-align: center; color: #fff; font-size: 16px;background: #0065b3; border-radius: 4px; border:none; cursor: pointer;margin-top: 40px; float: right; margin:70px 25px 0 40px;" class="browseproduct see"  data-id=" '.$value["id"].'"  data-category = "'.$value["category"].'" >立即申请</a>';	
                }else if($read['status'] === 1){
					echo '<a href="javascript:void(0);" class="sees" style="background:#0284e8 " data-id=" '.$value["id"].'"  data-category = "'.$value["category"].'" >点击查看</a>';
				}else{
                    echo '<a href="javascript:void(0);" class="sees" data-id=" '.$value["id"].'"  data-category = "'.$value["category"].'" >立即查看</a>';
                }?>
                </div>
                </div>
                <div style="margin-bottom:20px;overflow:hidden;" Class="gre">
                        <div class="progress" style="width:150px;height:4px;background-color:#dbe1e6;overflow:hidden;float:left;margin-top:8px;">
                            <span class="progr" style="width:30%;height:4px;display:block;background-color: #0065b3;"></span>
                            <?php echo Html::hiddenInput('progress_status',$value['progress_status'])?>
                            <?php echo Html::hiddenInput('agree_time',isset($apply['agree_time'])?\frontend\services\Func::agree($apply['agree_time']):'')?>
                           
                        </div>
                        <span class="ress" style="float:left;padding-left:8px;">30%</span>
                </div>
            </div>
        </div>
    <?php endforeach;?>

    <!-- 分页开始 -->
    <div class="pages clearfix ">
        <div class="fenye" style="margin-top:30px">
            <?php if($pagination->totalCount>$pagination->defaultPageSize){ ?>
                <?php echo '<span class="fenyes" style="font-size:16px; margin:0 -407px 0 405px">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条记录'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
                <?= linkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
            <?php }else if($pagination->totalCount){ ?>
                <?php echo '<span class="fenyes" style="font-size:16px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条记录'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
            <?php }else{ echo '';} ?>
        </div>
    </div>
    <!-- 分页开始 -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        function searchForm(cat,district_id,progress,money,province_id,city_id){
            window.location = "<?php echo \yii\helpers\Url::toRoute('/capital/list/')?>"+"?cat="+cat+"&&district_id="+district_id+"&&progress="+progress+"&&money="+money+"&&province_id="+province_id+"&&city_id="+city_id;
        }
        $('.list_menu span').click(function(){
            searchForm($(this).attr('id'),($("select[name='district_id']").val()?$("select[name='district_id']").val():0),$('.area .four a.current').attr('id'),$('.area .two a.current').attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
        });
        $('.area .four a').click(function(){
            searchForm($('.list_menu span.current ').attr('id'),($("select[name='district_id']").val()?$("select[name='district_id']").val():0),$(this).attr('id'),$('.area .two a.current').attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
        })
        $('.area .two a').click(function(){
            searchForm($('.list_menu span.current ').attr('id'),($("select[name='district_id']").val()?$("select[name='district_id']").val():0),$('.area .four a.current').attr('id'),$(this).attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
        });
        $("select[name='province_id']").change(
            function () {
                searchForm($('.list_menu span.current').attr('id'),$("select[name='district_id']").val(),$('.area .four a.current').attr('id'),$('.area .two a.current').attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
            }
        );
        $("select[name='city_id']").change(
            function () {
                searchForm($('.list_menu span.current').attr('id'),$("select[name='district_id']").val(),$('.area .four a.current').attr('id'),$('.area .two a.current').attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
            }
        );
        $("select[name='district_id']").change(
            function () {
                searchForm($('.list_menu span.current').attr('id'),$(this).val(),$('.area .four a.current').attr('id'),$('.area .two a.current').attr('id'),($("select[name='province_id']").val()?$("select[name='province_id']").val():0),($("select[name='city_id']").val()?$("select[name='city_id']").val():0))
            }
        );
        $('.gre').children().children('input[name="progress_status"]').each(function(){
              var pro = $(this).val();
              var pros = $(this).next().val();
           // alert(pros);
            if(pro == 2 &&  pros == 4 || pro == 2 && pros ==''){
                $(this).prev().css('width','60%');
                $(this).parent().next().html('60%');
            }else if(pro == 2 && pros == 1){
                $(this).prev().css('width','70%');
                $(this).parent().next().html('70%');
            }else if(pro == 2 && pros == 2){
                $(this).prev().css('width',' 80%');
                $(this).parent().next().html('80%');
            }else if(pro == 2 && pros == 3){
                $(this).prev().css('width','90%');
                $(this).parent().next().html('90%');
            }else if(pro  == 3 || pro  == 4){
                $(this).prev().css('width','100%');
                $(this).parent().next().html('100%');
            }
            
        });
    });

    $(function(){
        $('.search').click(function(){
            var seek = $('input[name=seek]').val();
            var url = "<?php echo \yii\helpers\Url::toRoute('/capital/list/')?>";
            $.post(url,{seek:seek},function(v){
                if(v.status==0){
                }
            })
        })
    });


    $('.bian').click(function(){
        var category = $(this).attr('data-category');
        var id = $(this).attr('data-id');
        url ='<?php echo Url::toRoute("/capital/ischakan")?>';
        $.ajax({
            url:url,
            type:'post',
            data:{category:category,id:id},
            dataType:'json',
            success:function(json){
                if(json.code == '0000'){
                    location.href = "<?php echo Url::toRoute('/capital/applyorder/'); ?>?id=" + id + "&category=" + category;
                }else{
                    switch (json.code){
                        case '1001':showMsg(json.msg,category,id,'<?php echo Url::toRoute('/site/login')?>');break;
                        case '1002':showMsg(json.msg,category,id,'<?php echo Url::toRoute('/certification/index')?>');break;
                        case '1006':showMsg(json.msg,category,id,'1');break;
                        default :showMsg(json.msg,category,id,'');break;
                    }
                }
            }
        });
    });
    $('.nowapply').click(function(){
        return false;
    });
    $('.browseproduct').click(function(){
        var category = $(this).attr('data-category');
        var id = $(this).attr('data-id');
        var url ='<?php echo Url::toRoute("/capital/isapply")?>';
        $.ajax({
            url:url,
            type:'post',
            data:{category:category,id:id},
            dataType:'json',
            success:function(json){
                if(json.code == '0000'){
                    location.href = "<?php echo Url::toRoute('/capital/applyorder/'); ?>?id=" + id + "&category=" + category;
                }else{
                    switch (json.code){
                        case '1001':showMsg(json.msg,category,id,'<?php echo Url::toRoute('/site/login')?>');break;
                        case '1002':showMsg(json.msg,category,id,'<?php echo Url::toRoute('/certification/index')?>');break;
                        case '1006':showMsg(json.msg,category,id,'1');break;
                        default :showMsg(json.msg,category,id,'');break;
                    }
                }
            }
        });


        return false;
    });
    function showMsg(msg,category,id,url){
        var content = '<p style="text-align:center;margin-top:28px;background:url(/images/iconss.png) left center no-repeat;line-height:40px;margin-left:47px;">'+msg+'</p>';
        $.msgbox({
            title:"<span style='color:#333;font-weight:normal;line-height:30px;font-size:16px;'>申请接单提示</span>",
            closeImg: '/images/closess.png',
            height: 170,
            width: 340,
            content: content,
            type: 'confirmyes',
            onClose: function (v) {
                if (v) {
                    if(url=='1'){
                        var urlapp = "<?php echo Url::toRoute('/capital/read')?>";
                        $.post(urlapp,{category:category,id:id},function (iv){
                            if (iv == 1){
                                location.href = "<?php echo Url::toRoute('/capital/list')?>";
                            }
                        }, 'json');
                    }else if(url) location.href = url;
                }
            }
        });
    }

    /*function check(id, category) {
        var url = "<?php echo Url::toRoute("/capital/certification"); ?>";
        $.post(url, {id: id, category: category}, function (v) {
            if (v.status == 0) {
                $.msgbox({
                    height: 190,
                    width: 330,
                    content: '<p style="text-align:center">您还未登录,请先<a href="<?php echo Url::toRoute('/site/login')?>" style="color:#FF9700">登录</a></p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute('/site/login')?>";
                        }
                    }
                });
            } else if (v.status == 1) {
                location.href = "<?php echo Url::toRoute('/capital/applyorder/'); ?>?id=" + id + "&category=" + category;
            } else if (v.status == 2) {
                $.msgbox({
                    height: 190,
                    width: 330,
                    content: '<p style="text-align:center">您还未认证,请进行身份认证!</p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute('/certification/index')?>";
                        }
                    }
                });
            }
        }, 'json');
        return true;
    }
*/
    $('.sees').click(function(){
        browser(this,"<?php echo Url::toRoute('/capital/isapply')?>");return false;
    });
//        $('.seesssss').bind("click",function(){
//           var category = $(this).parent().parent('.bianhao').children('input[name="category"]').val();
//            var id = $(this).parent().parent('.bianhao').children('input[name="id"]').val();
//            var uid =  $(this).parent().parent('.bianhao').children('input[name="uid"]').val();
//        var url = "<?php //echo Url::toRoute('/capital/applysuccessful')?>//";
//        $.post(url, {category: category, id: id, uid: uid}, function (i) {
//            if (i.status == 0) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">您还未登录,请先<a href="<?php //echo Url::toRoute('/site/login')?>//" style="color:#FF9700">登录</a></p>',
//                    type: 'confirm',
//                    onClose: function (v) {
//                        if (v) {
//                            location.href = "<?php //echo Url::toRoute('/site/login')?>//";
//                        } else {
//
//                        }
//                    }
//                });
//            } else if (i.status == 1) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">请不要申请自己发布的数据。',
//                    type: 'confirm',
//                    onClose: function (v) {
//                    }
//                });
//            } else if (i.status == 2) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">请不要重复申请。',
//                    type: 'confirm',
//                    onClose: function (v) {
//                        if (v) {
//                            location.href = "<?php //echo Url::toRoute('/order/index')?>//";
//                        }
//                    }
//                });
//            } else if (i.status == 3) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">个人用户只能发布数据',
//                    type: 'confirm',
//                    onClose: function (v) {
//                    }
//                });
//            } else if (i.status == 4) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">律所账号只能代理诉讼、清收',
//                    type: 'confirm',
//                    onClose: function (v) {
//                    }
//                });
//            } else if (i.status == 5) {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">公司账号只能代理融资、清收',
//                    type: 'confirm',
//                    onClose: function (v) {
//                        if (v) {
//                            location.href = "<?php //echo Url::toRoute('/order/index')?>//";
//                        }
//                    }
//                });
//            } else {
//                $.msgbox({
//                    height: 190,
//                    width: 330,
//                    content: '<p style="text-align:center">是否申请?',
//                    type: 'confirm',
//                    onClose: function (v) {
//                        if (v) {
//                            var url = "<?php //echo Url::toRoute('/capital/read')?>//";
//                            $.post(url,{category:category,id:id,uid:uid},function (iv){
//                                if (iv == 1){
//                                    location.href = "<?php //echo Url::toRoute('/capital/list')?>//";
//                                }
//                            }, 'json');
//                        }
//                    }
//                });
//
//            }
//        }, 'json');
//         return false;
//        })
</script>
