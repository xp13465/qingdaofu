function delCookie(name)
{
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null)
		document.cookie= name + "="+cval+";expires="+exp.toGMTString()+";path=/";
}

//如果需要设定自定义过期时间
//那么把上面的setCookie　函数换成下面两个函数就ok;
//程序代码
function setCookie(name,value,time)
{
	var strsec = getsec(time);
	var exp = new Date();
	exp.setTime(exp.getTime() + strsec*1);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";path=/";
}
function getsec(str)
{
	var str1=str.substring(1,str.length)*1;
	var str2=str.substring(0,1);
	if (str2=="s")
	{
		return str1*1000;
	}
	else if (str2=="h")
	{
		return str1*60*60*1000;
	}
	else if (str2=="d")
	{
		return str1*24*60*60*1000;
	}
}

function getCookie(name)
{
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}
//这是有设定过期时间的使用示例：
//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30

function formUnserialize(seriliazie){

	var arr = [];
	seriliazie.split('&').forEach(function(param){
		param = param.split('=');
		arr[param[0]] = param[1];
		if(param[0] == 'guaranteemethod%5B%5D')
			arr['guaranteemethod'] = arr['guaranteemethod']?(arr['guaranteemethod']+','+param[1]):param[1];
	});
	return arr;
}

function arrayserializa(arr){
	var str = '';
	for(a in arr){
		str += str==''?(a+"="+arr[a]):("&"+a+"="+arr[a])
	}
	return str;
}