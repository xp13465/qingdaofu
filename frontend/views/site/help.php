<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>清道夫债管家-新手帮助</title>
<meta content="" name="keywords">
<meta content="" name="description">
<link rel="shortcut icon" type="image/ico" href="/images/favicon.png">
<link rel="stylesheet" href="/bate2.0/css/help.css">
<script src="/bate2.0/js/jquery-1.11.1.js"></script>
</head>
<body>
<div class="bg">
  <div class="bg1"></div>
  <div class="bg2"></div>
  <div class="bg3"></div>
  <div class="bg4"></div>
  <div class="bg5"></div>
  <div class="bg6"></div>
  <div class="bg7"></div>
  <div class="bg8"></div>
  <div class="bg9"></div>
  <div class="bg10"></div>
</div>
<div class="cont">
  <div class="content">
    <div class="main">
      <div class="logo"></div>
      <div class="control">
        <div class="left"></div>
        <div class="middle"></div>
        <div class="right"></div>
      </div>
      <div class="service">
        <div class="one"></div>
        <div class="two"></div>
        <div class="three"></div>
        <div class="four"></div>
      </div>
      <div class="process">
        <div class="cicle cicle1">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>债权发布</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle2">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>接单面谈</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle3">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>尽职调查</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle4">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>制定解决方案</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle5">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>签约</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle6">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>跟进处置流程</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle7">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>完成处置</p>
          <div class="next"></div>
        </div>
        <div class="cicle cicle8">
          <div class="bar bar-left">
            <div class="bar-left-an"></div>
          </div>
          <div class="bar bar-right">
            <div  class="bar-right-an"></div>
          </div>
          <p>回款</p>
        </div>
      </div>
      <div class="fish">
        <div class="flying"></div>
      </div>
      <div class="line"></div>
      <div class="bottom"> <a href="/product/create" class="lf">发布债权</a> <a href="/product/index" class="rg">申请处置</a> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
	  $(".logo").addClass("logoshow");
       $(window).scroll(function() {
			var showHight=Math.min(450,$(window).height());
		    // if( $(this).scrollTop()>=0-showHight && $(this).scrollTop()<=0+showHight){
			 	 // $(".logo").addClass("logoshow");
			// }else{
				// $(".logo").removeClass("logoshow");
			// }

			var showHight=Math.min(430,$(window).height());
		    if(!$(".control").hasClass("controlshow")&& $(this).scrollTop()>=955-showHight && $(this).scrollTop()<=955+showHight){
			 	 $(".control").addClass(" controlshow");
			}else{
				// $(".control").removeClass(" controlshow");
			}
			var showHight=Math.min(3255,$(window).height());
		   if( $(this).scrollTop()>=3255-showHight && $(this).scrollTop()<=3255+showHight){
			 	 $(".line").addClass(" lineshow");
			}else{
				$(".line").removeClass(" lineshow");
			}

        });
	})

</script>
</body>
</html>