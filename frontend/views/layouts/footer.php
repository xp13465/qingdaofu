<!-- 底部 -->
<script src="http://kxlogo.knet.cn/seallogo.dll?sn=e15041733010058386nqve000000&size=0"></script>
<?php  /* 
<script language="javascript" src="http://float2006.shifa.tq.cn/floatcard?adminid=9709839&sort=2"></script> */?>

<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?3f0b4afa6d4cd724a56071512216551a";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

<div class="general-adbox">
    <div class="general-small-ad">	    
        <a id="gen-arrow-l"><img src="/images/over.png"></a>
    </div>
	
    <div class="general-big-ad">
        <div class="general">
		   <div class="pic">
		   <ul>
		      <li>
			  <p class="tit">关注微信公众号</p>
			  <p class="tpc"><img src="/bate2.0/images/wx.png"></p>
			  </li>
			  <li>
			  <p class="tit">手机扫描下载</p>
			  <p class="tpc"><img src="/bate2.0/images/app.png"></p>
			  </li>
		      <li>
			  <div class="an">
				  <p class="btn"><a target="_blank" href="https://itunes.apple.com/cn/app/id1116869191?ls=1&mt=8">iphone下载</a></p>
				  <p class="btn"><a target="_blank" href="http://sj.qq.com/myapp/detail.htm?apkName=com.zhaiguanjia ">android下载</a></p>
	          </div>
			  </li>
		   </ul>
		   <div class="clear"></div>
		   </div>
		   <div class="pnumber">
		   <p>客服热线</p>
		   <p>400-855-7022</p>
		   </div>
		   <div class="clear"></div>
		</div>
        <div class="icon general-close" id="general-close"></div>
	</div>
</div>

<script type="text/javascript">

function setCookie(name,value)
{
	var Days = 30;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() + ';path=/';
}

        $(function(){
			$(".general-small-ad").animate({
                    right:"0px"
                },500);
            $("#gen-arrow-l").click(function(){
                $(this).parent(".general-small-ad").animate({
                    right:"-100%"
                },500);
                setTimeout(function(){
					setCookie('Teldiv',0)
                    $(".general-big-ad").animate({
                        width:"100%",
                        right:"0px"
                    },500);
                },500);
            });
            $("#general-close").click(function(){
                $(this).parent(".general-big-ad").animate({
                   right:"-100%"
                },500);
                setTimeout(function(){
					setCookie('Teldiv',1)
                    $(".general-small-ad").animate({
                        right:"0px"
                    },500);
                },500);

            });
			<?php if(isset($_COOKIE["Teldiv"])&&$_COOKIE["Teldiv"]==1){?>
				$(".general-small-ad").animate({
                    right:"0px"
                },1000);
			<?php }else{ ?>
			$("#gen-arrow-l").trigger("click");
			<?php } ?>

        });
    </script> 




<div class="foot">
  <div class="footer">
    <div class="clearfix" style="height:195px;">
      <div class="foot_m">
        <p style="text-indent:15px;">移动平台</p>
       <div class="mg">
       <p><img src="/bate2.0/images/wx.png" width="120" height="120"></p>
       <p>微信公众号</p>
       </div>
      <div class="mg">
       <p><img src="/bate2.0/images/app.png" width="120" height="120"></p>
       <p>APP下载</p>
       </div>
      </div>
      <div class="foot_l fl">
            <div class="lj">
              <p>友情链接　</br></br>
                 <a href="http://www.zcb2016.com/">不良资产处置</a>
                 &nbsp;&nbsp;<a href="http://www.zcb2016.com/">投融资平台</a> 
              　 <a href="http://www.zcb2016.com/">上海清债公司</a>
              </p>
            </div>
      </div>

      <div class="foot_r fr">
        <p class="font16">联系我们</p>
        <p class="font14"><span>客服热线：</span>400-855-7022（周一到周五9:00-18:00）</p>
        <p class="font14"><span>公司地址：</span>上海市浦东南路855号世界广场34楼A座</p>
      </div>
    </div>
    <div class="gsjy">
    <div class="yq">
        <ul class="foot_top clearfix">
			<li class="gssm"><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/intro')?>" target ="_blank">公司简介　|　</a></li>
            <li><a href="<?php echo yii\helpers\Url::toRoute('/homepage/business')?>" target ="_blank">业务流程　|　</a></li>
            <li class="fwxy"><a href="<?php echo yii\helpers\Url::toRoute('/aboutus/serviceclause')?>" target ="_blank">服务协议　|　</a></li>
            <li class="fwxy"><a href="<?php echo yii\helpers\Url::toRoute('/site/help')?>" target ="_blank">新手指引　|　</a></li>
            <li><a href="<?php echo yii\helpers\Url::toRoute('/protocol/falvdeclaration')?>" target ="_blank">法律声明　|　</a></li>
			 <li><a href="http://tongji.baidu.com/web/welcome/login" style="color:#a8abae" rel="nofollow" target="_blank">站长统计</a></li>
		  
		  
        </ul>
        <div class="clearfix">
          <p class="bq fl"> 版权所有：Copyright©2015-2016  直向资产管理有限公司　沪ICP备15055061号-1 </p>
        </div>
    </div>
    <div class="ty">
    <ul>
	<li><a href="http://gsxt.saic.gov.cn/" target ="_blank" rel="nofollow" ><img src="/images/search1.jpg" height="30" width="155" alt="" ></a></li>
    <li><a href="http://www.court.gov.cn/zgcpwsw/" target ="_blank" rel="nofollow"><img src="/images/search2.jpg" height="30" width="155" alt=""></a></li>
    <li><a href="http://shixin.court.gov.cn/" target ="_blank" rel="nofollow"><img src="/images/search3.jpg" height="30" width="155" alt=""></a></li>
    <li><a href="http://zhixing.court.gov.cn/search/" target ="_blank" rel="nofollow"><img src="/images/search4.jpg" height="30" width="155" alt="" ></a></li>
    <li><a href="http://www.nacao.org.cn/" target ="_blank" rel="nofollow"><img src="/images/search5.jpg" height="30" width="155" alt=""></a></li>
    <li class="kexin"><script src="http://kxlogo.knet.cn/seallogo.dll?sn=e16021531011762446tbbq000000&size=0"></script></li>
    <!--<li><li><img src="/bate2.0/images/search1.jpg"></li>
    <li><img src="/bate2.0/images/search2.jpg"></li>
    <li><img src="/bate2.0/images/search3.jpg"></li>
    <li><img src="/bate2.0/images/search4.jpg"></li>
    <li><img src="/bate2.0/images/search5.jpg"></li>
    <li><img src="/bate2.0/images/search1.jpg"></li>-->
    </ul>
    </div>
   </div>
  </div>
</div>
</body>
</html>

