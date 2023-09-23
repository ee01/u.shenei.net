/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: script_common.js 13025 2009-08-06 02:05:30Z zhengqingpeng $
*/


//iframe包含

function $(id) {
	return document.getElementById(id);
}

//头部菜单 
function suboptwo(obj,id){

	var tLeft = obj.offsetLeft;
	var tTop = obj.offsetTop + 32;
	while(obj=obj.offsetParent) {
		tLeft+=obj.offsetLeft;
		tTop+=obj.offsetTop;
	}
	if($(id).style.display == 'none'){
		$(id).style.cssText = 'display:block; left:' + tLeft + 'px; top:' + tTop + 'px;';
	}
	else{
		$(id).style.cssText = 'display:none;';
	}
}


/**
*   头部导航二级菜单。
*/
function openSub(obj, target, isclick) {
	var tSub = $('sub' + target);
	if (tSub.style.display != 'block') {
		closeSub();
		/*var tMenu = obj.previousSibling;
		var tMenuP = obj.parentNode;
			tMenuP.id = 'subopen';
		var tLeft = tMenu.offsetLeft;
		var tTop = tMenu.offsetTop + 20;
		while(tMenu=tMenu.offsetParent) {
			tLeft+=tMenu.offsetLeft;
			tTop+=tMenu.offsetTop;
		}
		tSub.style.left = tLeft + 'px';
		tSub.style.top = tTop + 'px';*/
		//event.clientX, event.clientY
		//alert(event.clientX + '==' + event.clientY);
		
		tSub.style.left = event.clientX - 30;
		
		tSub.style.display = 'block';
		//tMenuP.className = 'active';
		obj.blur();
	} else {
		closeSub();
	}
	
	if(isclick) {
		var clickId = 0;
		document.body.onclick = function() {
			clickId = clickId + 1;
			if( clickId > 1 ) {
				closeSub();
			}
		}
	} else {
		document.body.onclick = function() {
			closeSub();
		}
	}
}

/**
*   头部导航二级菜单关闭。
*/
function closeSub() {
	var evt = getEvent();
	if(evt != null) {
		var srcElement = evt.srcElement || evt.target;
		if (srcElement.rel != 'menufocus') {
			var subMenu = $('submenu').getElementsByTagName('div');
			if($('subopen')){
				$('subopen').className = '';
				$('subopen').id = '';
			}
			for (var i=0; i<subMenu.length; i++) {
				subMenu[i].style.display = 'none';

			}
			
		}
	}
}

