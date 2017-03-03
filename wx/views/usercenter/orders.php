<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'我的接单','gohtml'=>''])?>
<script type="text/javascript">
    $(document).ready(function(){
        function release(progress_status){
            location.href = "<?php echo yii\helpers\Url::toRoute('/usercenter/orders')?>?progress_status="+progress_status;
        }
        $('.cp_s ul').delegate('li','click',function(){
            release($(this).attr('id'));
        })
        $('.type').click(function(){
            var id = $(this).attr('data-id');
            var category = $(this).attr('data-category');
            location.href = "<?php echo yii\helpers\Url::toRoute('/releases/index')?>?id="+id+'&category='+category;
        })
    })
</script>
<style>
.num{background:#e2e4e5;}
.num p{font-size:16px;line-height:30px;padding-left:20px;}
</style>
<section>
    <div class="cp_s">
        <ul>
            <li id="0"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('progress_status') == 0){echo 'class="current"';}?> >全部</a></li>
            <li id="1"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('progress_status') == 1){echo 'class="current"';}?> >申请中</a></li>
            <li id="2"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('progress_status') == 2){echo 'class="current"';}?> >处理中</a></li>
            <li id="3"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('progress_status') == 3){echo 'class="current"';}?> >终止</a></li>
            <li id="4"><a href="javascript:void(0);" <?php if(\Yii::$app->request->get('progress_status') == 4){echo 'class="current"';}?> >结案</a></li>
        </ul>
    </div>
    <?php if($rows){ ?>
    <?php foreach($rows as $value){ ?>
    <div class="type" data-id = "<?php echo $value['id']?>" data-category="<?php echo $value['category']?>">
        <ul>
            <li>
                <div class="rongzi">
                    <div class="over">
                        <div class="flo_l" style="width:60%;float:left;">
                            <?php if($value['category'] == 1){?>
                                <span class="rz_ig"></span>
                            <?php }else if($value['category'] == 2){?>
                                <span class="rz_ig01"></span>
                            <?php }else if($value['category'] == 3){?>
                                <span class="rz_ig02"></span>
                            <?php } ?>
                            <span class="code"><?php echo isset($value['code'])?$value['code']:''?></span>
                        </div>
                        <?php if($value['app_id'] == 0 && $value['progress_status'] == 1){?>
                            <span class="flo_r">申请中</span>
                        <?php }else if($value['app_id'] == 1 && $value['progress_status']==2){?>
                            <span class="flo_r">处理中</span>
                        <?php }else if($value['app_id'] == 2 && $value['progress_status'] == 1){?>
                            <span class="flo_r">收藏中</span>
                        <?php }else if($value['progress_status'] == 3){ ?>
                            <span class="flo_r">已终止</span>
                        <?php }else if($value['progress_status'] == 4){ ?>
                            <span class="flo_r">已结案</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="num">
                <p>借款本金:<?php echo isset($value['money'])?$value['money']:''?>万</p>
				<p>
				<?php if(isset($value['category'])&&$value['category'] == 2){ ?>	
                        <?php echo isset($value['agencycommissiontype'])&&$value['agencycommissiontype']==1?'服务佣金:':'固定费用:'?>
                           <?php if($value['agencycommissiontype']==1){ ?>
                               <?php echo isset($value['agencycommission'])?$value['agencycommission'].'%':'暂无' ?>
                           <?php }else{ ?>
                               <?php echo isset($value["agencycommission"])?$value["agencycommission"].'万':'暂无';?>
                           <?php } ?>
                    <?php }else{ ?>
                        <?php echo isset($value['agencycommission'])&&$value['agencycommissiontype']==1?'固定费用:':'风险费率:'?>
                        <?php if($value['agencycommissiontype']==1){ ?>
                            <?php echo isset($value["agencycommission"])?$value["agencycommission"].'万':'暂无';?>
                        <?php }else{ ?>
                            <?php echo isset($value["agencycommission"])?$value["agencycommission"].'%':'暂无';?>
                        <?php } ?>
                    <?php } ?>
				    </p>
					<?php if($value['loan_type'] == 1){ ?>
					 <p>抵押物地址:
					<?php if($value['category'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                            <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                    <?php } else if($value['loan_type'] == 1){?>
                            <?php echo \frontend\services\Func::getCityNameById($value['city_id']);?>
                            <?php echo \frontend\services\Func::getAreaNameById($value['district_id']);?>
                            <?php echo isset($value['seatmortgage'])?\frontend\services\Func::getSubstrs($value['seatmortgage']):'';?>
                    <?php }else{echo '';}?></p>
					<?php } ?>
                 
                </div>
                <?php if($value['app_id'] == 1 && $value['progress_status'] == 2){ ?>
                <div class="sup_sq">
                    <div class="tip_l">
                        <?php if($value['category'] == 1){ ?>
                            <?php if($value['rate_cat'] == 1){ ?>
							    <?php $a = isset($value['term'])?$value['term']:1  ?>
                                <span class="tip_jl">截止日期:<?php echo date("Y-m-d",strtotime(date("Y-m-d",$value['agree_time']).' + '.$a." month "))?></span>
                            <?php }else{ ?>
							 <?php $a = isset($value['term'])?$value['term']:30  ?>
                                <span class="tip_jl">截止日期:<?php echo date("Y-m-d",strtotime(date("Y-m-d",$value['agree_time']).' + '.$a." days "))?></span>
                            <?php } ?>
                        <?php }else{ ?>
						     <?php $a = isset($value['term'])?$value['term']:1  ?>
                            <span class="tip_jl">截止日期:<?php echo date("Y-m-d",strtotime(date("Y-m-d",$value['agree_time']).' + '.$a." month "))?></span>
                        <?php } ?>

                    </div>
                    <div class="tip_r">
                        <a href="<?php echo yii\helpers\Url::toRoute(['/releases/speedo','id'=>$value['id'],'category'=>$value['category']])?>" class="current">填写进度</a>
                    </div>
                </div>
                 <?php }?>
                <?php if($value['progress_status'] == 4){ ?>
                    <?php if($creditor[$value['id'].'_'.$value['category']] == 1 || $creditor[$value['id'].'_'.$value['category']] >= 3){ ?>
                        <div class="sup_sq">
                            <div class="tip_r">
                                <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/evaluatelists','id'=>$value['id'],'category'=>$value['category']])?>" class="current">查看评价</a>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="sup_sq">
                            <div class="tip_r">
                                <a href="<?php echo yii\helpers\Url::toRoute(['/releases/addevaluation','id'=>$value['id'],'category'=>$value['category']])?>" class="current">去评价</a>
                            </div>
                        </div>
                    <?php } ?>
                 <?php } ?>
            </li>

        </ul>
    </div>
        <?php } ?>
    <?php } ?>
</section>

