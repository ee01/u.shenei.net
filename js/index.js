/**
 *来自VOONE首页的脚本
 */
function t$(id){
	return "string"==typeof id?document.getElementById(id):id;
}
function getCls(obj,tn){
	var a=new Array();
	var cn=obj.childNodes;
	for(var i=0;i<cn.length;i++){
		var c=cn[i];
		if(!!c.tagName&&c.tagName.toLowerCase()==tn){
		  a.push(c);
		}
	}
	return a;
}
var getInfo=function(o){//获取对象相对于body的位置
	var to=new Object();
	to.left=to.right=to.top=to.bottom=0;
	var twidth=o.offsetWidth;
	var theight=o.offsetHeight;
	while(o!=document.body){
		try{
		  to.left+=o.offsetLeft;
		  to.top+=o.offsetTop;
		  o=o.offsetParent;
		}
		catch(e){
		  break;
		}
	}
	to.right=to.left+twidth;
	to.bottom=to.top+theight;
	return to;
}
var CancelBubble=function(e){//取消冒泡
	e=e?e:window.event;
	if(!!(window.attachEvent&&!window.opera)){
		e.cancelBubble=true;
	}else {
		e.stopPropagation();
	}
	return e;
}
var CancelSelect=function(e){//取消选择
	if(!!(window.attachEvent&&!window.opera)){
		document.onselectstart=function(event){
			var e=event;
			e=e?e:window.event;
			e.returnValue=false;
		}
	}
	else {
		document.body.style.MozUserSelect="none";
	}
};
var UnCancelSelect=function(){
	if(!!(window.attachEvent&&!window.opera)){
		document.onselectstart=new Function();
	}
	else {
		document.body.style.MozUserSelect="text";
	}
}


function CB(){
var moveObj,scrollObj,p,pW;
var mDown=function(e){e=e?e:window.event;
CancelSelect(e);CancelBubble(e);pW=parseInt(moveObj.offsetWidth);p=(136-20)/(pW-149.33);var place=getInfo(scrollObj);scrollObj.lastX=e.clientX-place.left;
var self=this;document.onmousemove=mMove;document.onmouseup=function(){document.onmousemove=new Function();document.onmouseup=new Function();UnCancelSelect()}};
var mMove=function(e){e=e?e:window.event;CancelBubble(e);
var cx=e.clientX;
var place=getInfo(scrollObj.parentNode);


var left=cx-place.left-scrollObj.lastX;left=left+20>136?136-20:left;left=left<0?0:left;scrollObj.style.left=left+"px";
var tl=parseInt(left);
var tl=parseInt(left/p);moveObj.style.left="-"+tl+"px"};var mOut=function(e){};
var init=function(){var obj=$("CBPeople");

var cn=getCls(obj,"div");if(cn.length!=2)return;moveObj=cn[0];scrollObj=cn[1];var s=getCls(scrollObj,"div")[0];s.onmousedown=mDown;scrollObj=s};init()}
function buyMenu(n){for(var i=1;i<=3;i++){$('buyM_'+i).className='hidden';$('buy_box'+i).className='hidden';}$('buy_box'+n).className='no_hidden';$('buyM_'+n).className='y_buy_button no_hidden';}



//页面初始化调用
window.onload=function()
{
	CB();
}




