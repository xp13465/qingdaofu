
$(function(){
	/*左边菜单切换*/
	$("ul li ol li").click(function(){
		$("ul li ol li.current").removeClass("current")
		$(this).addClass("current");
	});

	$('.info2 span').click( function(){
		$('#hover').stop().slideToggle(500);
		$(this).find('i').toggleClass('arrow');
	})

	/*固定费用解释切换*/
	$('.money i').hover(function() {
		$(this).siblings('span').css('display','block')
	},function(){
		$(this).siblings('span').css('display','none')
	});


	/*接单列表切换*/
	$('.title li').click(function(){
		var num=$('.jiedan table').index();
		$(this).addClass('.title current').siblings('.title li').removeClass('.title current')
		$('.jiedan table').eq($(this).index()).addClass('hide').siblings('table').removeClass('hide');
		
	})


	/*消息已读 未读切换*/
	$('.info li').click(function(){
		$(this).addClass('current').siblings('.info li').removeClass('current');
	})



	/*基本补充消息切换*/
	$('.tb_top li').click(function(){
		$(this).addClass('.tb_top current').siblings('.tb_top li').removeClass('.tb_top current');
	})
	$('#base').click(function(){
		$('.detail').addClass('hide1');
		$('#buchong').removeClass('hide1');
	})
	$('#bc').click(function(){
		$('.detail').removeClass('hide1');
		$('#buchong').addClass('hide1');
	})

	$('.list_menu span').click(function(){
		var num=$(this).index()-1;
		$(this).addClass('current').siblings('span').removeClass('current');
		$(this).find('b').addClass('caret');
		
		$(this).siblings('span').find('b').removeClass('caret');
		$(this).find('b').css('left',num*130+135+'px');
	})


	/*地图展开*/
	$('.shangla').click(function(){
		$('.quyu').stop().slideToggle(1000);
		$(this).toggleClass('xiala')
	})

	$('.jt1').click(function(){
		$('#qy').stop().slideToggle().siblings('div').hide();
		$(this).toggleClass('arrow2');
	})
	$('.jt2').click(function(){
		$('#zclx').stop().slideToggle().siblings('div').hide();
		$(this).toggleClass('arrow2 arrow3');
	})
	$('.jt3').click(function(){
		$('#zcze').stop().slideToggle().siblings('div').hide();
		$(this).toggleClass('arrow2 arrow4');
	})
	$('#zclx span').click(function() {
		if ($(this).attr('id') == '0') {
			$('.category').html('资产类型');
		} else {
			$('.category').html($(this).html());
		}

		$('.category').attr('id', $(this).attr('id'));
		$('#zclx').stop().slideToggle().siblings('div').hide();
		searchMapForm();
	});

	$('#qy span').click(function() {
		if ($(this).attr('id') == '0') {
			$('.district').html('区域');
		} else {
			$('.district').html($(this).html());
		}

		$('.district').attr('id', $(this).attr('id'));
		$('#qy').stop().slideToggle().siblings('div').hide();
		searchMapForm();
	});

	$('#zcze span').click(function() {
		if ($(this).attr('id') == '0') {
			$('.money').html('资产金额');
		} else {
			$('.money').html($(this).html());
		}

		$('.money').attr('id', $(this).attr('id'));
		$('#zcze').stop().slideToggle().siblings('div').hide();
		searchMapForm();
	});



	/*产品与服务*/
	$('.serve a').hover(function(){
		var num=$(this).index()+1
		$(this).find('img').css('src','/images/p'+num+'.png');
		$(this).addClass('current')

	}, function(){
		var num=$(this).index()+1
		$(this).find('img').css('src','/images/p1'+num+'.png');
		$(this).removeClass('current')
	})


	/*新手指引*/
	$('.guide .effect div').hide();
	$(window).scroll(function(){
		var num2=$(window).scrollTop();
		if(num2>1400){
			$('.guide3 .effect div').fadeIn(1500);
		}if(num2>900){
			$('.guide2 .effect div').fadeIn(1500);
		}if(num2>300){
			$('.guide1 .effect div').fadeIn(1500);
		}else{
			$('.guide .effect div').hide();
		}
	})
	/*点击回到顶部*/
	$(window).scrollTop(function(){
		$('.back').click(function(){
			$(window).scrollTop(0);
		})
	})


	/*合作伙伴点击按钮出现隐藏伙伴*/
	 		var ll = 0; 
            var timer=null;
        
    $('.huoban .right2').click(function(){
        
        ll++;
        if(ll>3){ll=3;}
        $('.huoban2 ul').animate({'left':'-'+ll*140+'px'})
    })
    $('.huoban .left2').click(function(){
        ll--;
        if(ll<0){ll=0;}
        $('.huoban2 ul').animate({'left':'-'+ll*140+'px'})
    })


    


})




function closeProduct(url,category,id,status){

	if(confirm('请确定是否结案')){
		$.ajax({
			url:url,
			type:'post',
			data:{category:category,id:id,status:status},
			dataType:"html",
			success:function(html){
				if(html == 1){
					alert('已结案');
				}else{
					alert('参数错误');
				}
			}
		});
	}
}




