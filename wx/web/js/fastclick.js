var myScroll,
	pullDownEl,
	pullDownOffset,
	pullUpEl,
	pullUpOffset,
	generatedCount = 0;

function loaded() {
	$("#scroller").next("div").remove()
	pullDownEl = document.getElementById('pullDown');
	pullDownOffset = pullDownEl.offsetHeight;
	pullUpEl = document.getElementById('pullUp');
	pullUpOffset = 10;
	pullUpOffset = pullUpEl.offsetHeight;
	var catr = $('#thelist').attr('data-catr');
		myScroll = new iScroll('wrapper', {
		useTransition: true,
		topOffset: pullDownOffset,
		onRefresh: function () {
			if (pullDownEl.className.match('loading')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';}
			if (pullUpEl.className.match('loading')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
			}
			document.getElementById("pullDown").style.display="none";
			document.getElementById("pullUp").style.display="none";
			document.getElementById("show").innerHTML="onRefresh: up["+pullUpEl.className+"],down["+pullDownEl.className+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
		},
		onScrollMove: function () {
			document.getElementById("show").innerHTML="onScrollMove: up["+pullUpEl.className+"],down["+pullDownEl.className+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
			if (this.y > 0) {
				document.getElementById("pullDown").style.display="";
				pullDownEl.className = 'flip';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
				this.minScrollY = 0;
			}
			if (this.y < 0 && pullDownEl.className.match('flip')) {
				document.getElementById("pullDown").style.display="none";
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
				this.minScrollY = -pullDownOffset;
			}

			if ( this.scrollerH < this.wrapperH && this.y < (this.minScrollY-pullUpOffset) || this.scrollerH > this.wrapperH && this.y < (this.maxScrollY - pullUpOffset) ) {
				if(!catr)return false;
				if(catr < 10){
					document.getElementById("pullUp").style.display="";
					pullUpEl.className = 'flip';
					pullUpEl.querySelector('.pullUpLabel').innerHTML = '没有数据';
				}else{
					document.getElementById("pullUp").style.display="";
					pullUpEl.className = 'flip';
					pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
				}

			}
			if (this.scrollerH < this.wrapperH && this.y > (this.minScrollY-pullUpOffset) && pullUpEl.className.match('flip') || this.scrollerH > this.wrapperH && this.y > (this.maxScrollY - pullUpOffset) && pullUpEl.className.match('flip')) {
				document.getElementById("pullUp").style.display="none";
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
			}
		},
		onScrollEnd: function () {
			
			document.getElementById("show").innerHTML="onScrollEnd: up["+pullUpEl.className+"],down["+pullDownEl.className+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
			if (pullDownEl.className.match('flip')) {
				pullDownEl.className = 'loading';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
				// pullDownAction();	// Execute custom function (ajax call?)
				if(typeof pullDownAction === 'function' ){
					pullDownAction();
				}else{
					pullDownActionAjax();
				}
			}
			
			catr = $('#thelist').attr('data-catr');
			
			if(catr < 10) {
				if (pullUpEl.className.match('flip')) {
					pullUpEl.className = 'loading';
					pullUpEl.querySelector('.pullUpLabel').innerHTML = '没有数据...';
					// pullUpAction();	// Execute custom function (ajax call?)
					if(typeof pullUpAction === 'function' ){
						pullUpAction();
					}else{
						pullUpActionAjax();
					}
				}
			}else{
				if (pullUpEl.className.match('flip')) {
					pullUpEl.className = 'loading';
					pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
					if(typeof pullUpAction === 'function' ){
						pullUpAction();
					}else{
						pullUpActionAjax();
					}
				}
			}
			
		}
	});

	//setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
}
var page = 1;
var datalistClass = "#scroller ul.thelist";
function pullDownActionAjax () {
        var el, li, i;
        el = document.getElementById('thelist');
		page = 1;
		var url = $("#thelist").attr("data-url");
		
		if(url){
			if(url.indexOf("?")==-1){
				url = url +"?page="+page;
			}else{
				url = url +"&page="+page;
			}
			window.location = url;
		}else{
			window.location.reload()
		}
        myScroll.refresh();
		layer.load(1, {
			time:2000,
			shade: [0.4,'#fff'] //0.1透明度的白色背景
		});	
}

function pullUpActionAjax () {
	
    var catr = $('#thelist').attr('data-catr');
    if(catr<10){
            var el, li, i;
            el = document.getElementById('thelist');
            myScroll.refresh();
    }else{
            var el, li, i;
            el = document.getElementById('thelist');
            page  ++;
			var url = $("#thelist").attr("data-url");
			if(!url){
				myScroll.refresh();
				return false;
			}
			
			if(url.indexOf("?")==-1){
				url = url +"?page="+page;
			}else{
				url = url +"&page="+page;
			}
			$.ajax({ 
				async : false,
				type : "POST",
				url : url,
				dataType : "html",
				success: function(data) { 
					// alert(data)
					// var num = 0;
					$(data).find(datalistClass).each(function(){
							$("#thelist").append($(this))
							// num++;
					})
					// console.log(num)
					var num = $(data).find("#thelist").attr('data-catr');
					$('#thelist').attr('data-catr',num);
						myScroll.refresh();
					}
			})
    }
}
// document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

 document.addEventListener('DOMContentLoaded', function () {
	setTimeout(loaded, 200); 
	if($("#scroller").length){ $("body").css("overflow","hidden"); } 
 }, false);