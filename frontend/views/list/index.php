<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="kuang">
    <div class="fa01">
       <!-- <a href="<?php echo yii\helpers\Url::toRoute('/publish/publish');?>"> <img src="/images/fa.png" height="43" width="140" alt="" class="fa01_img" /></a>  -->
       <p class="fa01_img">
            <span class="jiedan">我的发布 </span>(共 <?php echo isset($pagination->totalCount)?$pagination->totalCount:0?>
            条信息)<!-- ,其中<span class="x_jiedan">新的处理信息 </span><?php echo Yii::$app->user->getId()?Yii::$app->db->createCommand("select count(`id`) from zcb_apply  where `app_id` = 0 and `uid` =".Yii::$app->user->getId())->queryScalar():0;?> 个 ) -->
        </p>
        <div style="position:relative;">
        <a href="<?php echo yii\helpers\Url::toRoute('/publish/publish')?>"><img src="/images/publish.png"  style="position:absolute;right:180px;top:16px;"></a>
            <form>
                <a class="btn-select" id="btn_select">
                    <span class="cur-select"><?php $a = ['0'=>'全部','1'=>'待发布','2'=>'已发布','3'=>'处理中','4'=>'已终止','5'=>'已结案'];echo isset($a[Yii::$app->request->get('status')+1])?$a[Yii::$app->request->get('status')+1]:'全部'?></span>
                   <select name="status" onchange="changestatus(this.value)">
                        <option value="0" <?php if(Yii::$app->request->get('status')==''||Yii::$app->request->get('status') == -1){echo "selected = 'selected'";}?>>全部</option>
                        <option value="1" <?php if(Yii::$app->request->get('status') == 0){echo "selected = 'selected'";}?>>待发布</option>
                        <option value="2" <?php if(Yii::$app->request->get('status') == 1){echo "selected = 'selected'";}?>>已发布</option>
                        <option value="3" <?php if(Yii::$app->request->get('status') == 2){echo "selected = 'selected'";}?>>处理中</option>
                        <option value="4" <?php if(Yii::$app->request->get('status') == 3){echo "selected = 'selected'";}?>>已终止</option>
                        <option value="5" <?php if(Yii::$app->request->get('status') == 4){echo "selected = 'selected'";}?>>已结案</option>
                   </select>
                </a>
            </form>
        </div>
    </div>
    <div class="tm_table">
        <table style="width:800px;text-align:center;" cellpadding="0" cellspacing="0">
            <thead>
            <th>区域</th>
            <th>类型</th>
            <th>抵押物地址</th>
            <th>编号</th>
            <th>发布时间</th>
            <th>金额(万元)</th>
            <th>申请记录(次)</th>
            <th>状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            <?php foreach($creditor as $v):?>
                <tr>
                    <td><?php echo \frontend\services\Func::getProvinceNameById($v['province_id']);?></td>
                    <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
                    <?php if($v['category'] == 1){?>
                    <td style="position:relative;" class="sh">
                        <span>
                            <?php echo \frontend\services\Func::getCityNameById($v['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($v['district_id']);?>
							<?php echo isset($v['seatmortgage'])?\frontend\services\Func::getSubstrs($v['seatmortgage']):'';?>
                            <?php //isset($v['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($v['seatmortgage'],'*',4,0):'';?>
                         </span>
                        <div  class="dizhi">
                            <span style="position:relative;white-space:nowrap;padding:0 4px;">
                                <?php echo \frontend\services\Func::getCityNameById($v['city_id']);?>
                                <?php echo \frontend\services\Func::getAreaNameById($v['district_id']);?>
                                <?php if($v['uid'] == \Yii::$app->user->getId() || $v['progress_status'] == 2 ){
                                    echo isset($v['seatmortgage'])?$v['seatmortgage']:'';
                                }else{
									echo isset($v['seatmortgage'])?\frontend\services\Func::getSubstrs($v['seatmortgage']):'';
                                   // echo isset($v['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($v['seatmortgage'],'*',4,0):'';
                                }?></span>
                            <img src="/images/arrowe.png" alt="" style="position:absolute;bottom:-6px;left:10px;">
                        </div>
                    </td>
                    <?php }else if($v['loan_type'] == 1){?>
                    <td style="position:relative;" class="sh">
                        <span>
                            <?php echo \frontend\services\Func::getCityNameById($v['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($v['district_id']);?>
                            <?php echo isset($v['seatmortgage'])?\frontend\services\Func::getSubstrs($v['seatmortgage']):'';?>
                         </span>
                        <div  class="dizhi">
                            <span style="position:relative;white-space:nowrap;padding:0 4px;">
                                <?php echo \frontend\services\Func::getCityNameById($v['city_id']);?>
                                <?php echo \frontend\services\Func::getAreaNameById($v['district_id']);?>
                                <?php if($v['uid'] == \Yii::$app->user->getId() || $v['progress_status'] == 2 ){
                                    echo isset($v['seatmortgage'])?$v['seatmortgage']:'';
                                }else{
									echo isset($v['seatmortgage'])?\frontend\services\Func::getSubstrs($v['seatmortgage']):'';
                                    //echo isset($v['seatmortgage'])?\frontend\services\Func::HideStrRepalceByChar($v['seatmortgage'],'*',4,0):'';
                                }?></span>
                            <img src="/images/arrowe.png" alt="" style="position:absolute;bottom:-6px;left:10px;">
                        </div>
                    </td>
                    <?php }else{echo '<td>无</td>';}?>

                    <td><?php echo isset($v['code'])?$v['code']:'';?></td>
                    <td><?php echo isset($v['create_time'])?date('Y-m-d H:i',$v['create_time']):''?></td>
                    <td class="jin"><?php echo isset($v['money'])?$v['money']:''?></td>
                    <td>
                        <a href="<?php echo yii\helpers\Url::toRoute(['/list/chakan','category'=>$v['category'],'id'=>$v['id']])?>#cd1"><?php echo Yii::$app->db->createCommand("select count(*) from zcb_apply where category = {$v['category']} and product_id = {$v['id']} and app_id=0")->queryScalar(); ?></a>
                    </td>
                    <td class="chuli"><?php echo \frontend\services\Func::$listMenu[$v['progress_status']];?></td>
                    <td class="td_color">
                        <a href="javascript:void(0)" onclick="check('<?php echo $v['category']?>','<?php echo $v['id']?>');">查看</a>
                        <?php if($v['progress_status'] >= 1){
                            if($v['progress_status'] <= 2){
                            ?>
                                <?php if($v['progress_status'] < 2){?>
                        <a href="javascript:void(0);" onclick="editUserInfo('<?php echo $v['id']?>','<?php echo $v['category']?>');">编辑</a>
                                    <?php } ?>
                        <a href="<?php echo Url::toRoute(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>3])?>">终止</a>
                        <?php }}?>
                        <?php if($v['progress_status'] == 2){?>

                            <?php if(!$v['applyclose']){?>
                                <a href="<?php echo Url::toRoute(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>4])?>">结案</a>

                            <?php }else{
                                if($v['applyclosefrom'] == Yii::$app->user->getId()){?>
                                    <?php if($v['applyclose']==4){ ?>
                                    <a href="<?php echo Url::toRoute(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>">结案申请中</a>
                                    <?php } ?>
                                <?php }
                                else{?>
                                    <?php if($v['applyclose']==4){ ?>
                                    <a href="<?php echo Url::toRoute(["/list/chakan","category"=>$v['category'],"id"=>$v['id'],'status'=>$v['applyclose']])?>">结案待确认</a>
                                     <?php } ?>
                                    <?php }
                            }?>
                        <?php }?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <div class="fenye clearfix" style="top:20px">
            <?php if($pagination->totalCount>$pagination->defaultPageSize){?>
                <?php echo '<span class="fenyes" style="font-size:12px;margin:0px 50px -42px;">'.'共'.(isset($pagination->totalCount)?$pagination->totalCount:0).
                    '条消息'.'第'.(Yii::$app->request->get('page')?Yii::$app->request->get('page'):1).'/'.(isset($pagination->totalCount)?ceil($pagination->totalCount/$pagination->defaultPageSize):0)
                    .'页'. '</span>';?>
                <?= \yii\widgets\LinkPager::widget([
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'pagination' => $pagination,
                    'maxButtonCount'=>4,
                ]);?>
            <?php }else{ echo '';} ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function inquire(id,uid) {
        var user_url = '';
        if(uid == '1'){
            user_url = "<?php echo Url::toRoute('/apply/treatmentfinancing')?>";
        } else if(uid == '2'|| uid == '3'){
            user_url = "<?php echo Url::toRoute('/apply/treatmentcreditor')?>";
        } else {
            user_url = FALSE;
        }
        if(user_url) {
            location.href = user_url+'?id='+id;
        } else {
            alert('链接有问题！');
        }
    }

    function editUserInfo(id,category){
        if(category == '1'){
            location.href  = "<?php echo Url::toRoute('/publish/afterfinancing')?>/?id="+id;
        } else if(category == '2'|| category == '3'){
            location.href  = "<?php echo Url::toRoute('/publish/aftercreditor')?>/?id="+id+'&category='+category;
        }
    }



    /*function closeProduct(category,id,status){
        if(confirm(status == 3?"请确定是否终止":"请确定是否结案")){
            $.ajax({
                url:"<?php /*echo \yii\helpers\Url::toRoute('/apply/closeproduct')*/?>",
                type:'post',
                data:{category:category,id:id,status:status},
                dataType:"html",
                success:function(html){
                    if(html == 1){
                        location.reload();
                    }else{
                        alert('参数错误');
                    }
                }
            });
        }
    }

    function closeProductAgree(category,id,status){
        var url = "<?php /*echo \yii\helpers\Url::toRoute('/apply/closeproductagree')*/?>";
        var tips = "请确定是否同意终止";
        if(status == 4)tips = "请确定是否同意结案";
        if(confirm(tips)){
            $.ajax({
                url:url,
                type:'post',
                data:{category:category,id:id},
                dataType:"html",
                success:function(html){
                    if(html == 1){
                        location.reload();
                    }else{
                        alert('参数错误');
                    }
                }
            });
        }
    }*/
    function changestatus(status){
        location.href = "<?php echo Url::toRoute(['/list/index/'])?>?status="+(status-1);
    }

    function check(category,id){
        var url = "<?php echo Url::toRoute("/list/attestation")?>";
        $.post(url,{category:category,id:id},function(i){
            if(i.status == 0 ){
                location.href="<?php echo Url::toRoute("/list/chakan");?>?category="+category+"&id="+id;
            }else if(i.status == 1){
                $.msgbox({
                    height: 140,
                    width: 280,
                    content: '<p style="text-align:center">您还未认证,请进行身份认证!</p>',
                    type: 'confirm',
                    onClose: function (v) {
                        if (v) {
                            location.href = "<?php echo Url::toRoute("/certification/index");?>";
                        } else {

                        }
                    }
                });
            }
        },'json');
    }
</script>

<script>
    $(function(){
        $('.sh span').mouseover(function(event) {
           $(this).parent().find('.dizhi').css('display','block');
        });
         $('.sh span').mouseout(function(event) {
           $(this).parent().find('.dizhi').css('display','none');
        });
    })
</script>