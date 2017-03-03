<div class="list_banner">
    <!-- <div class="banner_con">
        <p>清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家清道夫债管家</p>
    </div> -->
    <img src="/images/zc_list1.jpg" height="228" width="1920" alt="">
</div>
<div class="firm">
    <div class="firm_1">
        <div class="f_top">
            <p class="case"><a href="<?php echo yii\helpers\Url::to('/homepage/personal')?>">经典案例</a> > <span class="color"><?php echo isset($intr['name'])?$intr['name']:''?></span></p>
            <div class="lawyers clearfix">
                <div class="l_img fl">
                    <img src="<?php echo 'http://zichanbao.admin/'.$intr['picture']?>" height="372" width="318" alt="">
                </div>
                <div class="r_text fl">
                    <span class="color"><?php echo isset($intr['name'])?$intr['name']:''?></span>
                    <div class="jilu clearfix">
                        <span class="fl cj"><i>12</i> <br> 成交记录</span>
                        <span class="fl"><i>4.8</i> <br>星级评价</span>
                    </div>
                    <p>
                        <?php echo isset($intr['casetext'])?$intr['casetext']:''?> </p>
                </div>
            </div>
            <div class="summary">
                <ul class="l_navs clearfix">
                    <li id="xpj" class="record" class="current"><a href="javascript:;">累积评价</a></li>
                    <li class="evaluate" ><a href="javascript:;" >成交记录</a></li>
                    <li class="level"><a href="javascript:;"  >星级评价</a></li>
                </ul>
                <div class="cear">


                </div>
                <!-- 律所成交记录 -->
                <!-- 律所累积评价 -->
                <!-- 律所累积评价 -->
            <!-- 分页开始 -->
    </div>
    </div>
    </div>
    </div>
        <script type="text/javascript">
            $('.cear').load('<?php echo yii\helpers\Url::to('/homepage/record')?>');
            $(function(){
              $('.record').click(function(){
                  $('.cear').load('<?php echo yii\helpers\Url::to('/homepage/record')?>');
              });
                $('.evaluate').click(function(){
                    $('.cear').load('<?php echo yii\helpers\Url::to('/homepage/evaluate')?>');
                });
                $('.level').click(function(){
                    $('.cear').load('<?php echo yii\helpers\Url::to('/homepage/star')?>');
                })
            })
            $('.l_navs li').click(function(){
                $(this).addClass('current').siblings('li').removeClass('current');
            })
        </script>

    <!-- 内容结束-->