// JavaScript Document

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

// �����Ƿ�Ϊ��
function isEmpty(obj) {
	result = false;
	switch(typeof(obj)) {
		case 'number':if(0 == obj) {result = true;}break;
		case 'string':if(0 == obj.length) {result = true;}break;
		case 'undefined':result = true;break;
		default:if(isArray(obj) && 0 == obj.length) {result = true;}break;
	}
	return result;
}
// �ж��Ƿ�Ϊ����
function isArray(obj) {
	try {
		if(-1 == obj.constructor.toString().indexOf("function Array")) {
			return false;
		}
		return true;
	} catch(e) {return false;}
}
// �ж��Ƿ�Ϊ����
function isObject(obj) {
	try {
		if(-1 == obj.constructor.toString().indexOf("function Object")) {
			return false;
		}
		return true;
	} catch(e) {return false;}
}
// ��ȡ���ڶ���������Ϣ
function getWindowInfo() {
	// ҳ��Ŀ��Ӹ߶�
	var _h = parseInt(document.documentElement.clientHeight);
	// ҳ��Ŀ��ӿ��
	var _w = parseInt(document.documentElement.clientWidth);
	// ҳ����߱����صĿ��
	var _sL = parseInt(document.documentElement.scrollLeft);
	// ҳ�涥�������صĸ߶�
	var _sT = parseInt(document.documentElement.scrollTop);
	// ҳ������߶�
	var _mH = parseInt(document.body.clientHeight);
	// ҳ��������
	var _mW = parseInt(document.body.clientWidth);
	return {'height':_h, 'width':_w, 'maxHeight':_mH, 'maxWidth':_mW, 'scrollLeft':_sL, 'scrollTop':_sT};
}
// ��ȡָ����ʽ���Ե�ֵ
function getStyle(elem, style) {
	if(elem.currentStyle) {
		// ���ú�����replace���д���
		style = style.replace(/-([a-z])/g, function($0, $1) { return $1.toUpperCase(); });
		value = elem.currentStyle[style];
	} else if(window.getComputedStyle) {
		var css = document.defaultView.getComputedStyle(elem, null);
		value = css ? css.getPropertyValue(style) : null;
	}
	return value == 'auto' ? null : value;
}
// RGB��ɫֵת��Ϊʮ������ֵ
function rgb(r, g, b, includeHash) {
	if(includeHash == undefined) {
		includeHash = true;
	}
	r = r.toString(16);
	if(r.length == 1) {
		r = '0' + r;
	}
	g = g.toString(16);
	if(g.length == 1) {
		g = '0' + g;
	}
	b = b.toString(16);
	if(b.length == 1) {
		b = '0' + b;
	}
	return ((includeHash ? '#' : '') + r + g + b).toUpperCase();
}
/**
 * ���ñ�ǩ����ȡ����
 * @param	tagName			��ǩ���ƣ�
 * @param	targetElement	����
 */
function T(tagName, targetElement) {
	if('object' != typeof(targetElement)) {
		targetElement = document;
	}
	return targetElement.getElementsByTagName(tagName);
}
/**
 * �������Ժ������Ӧ��ֵ����ȡ����
 * @param	prototype		�������ƣ�
 * @param	value			����ֵ��
 * @param	targetElement	����
 */
function P(prototype, value, targetElement) {
	if('object' != typeof(targetElement)) {
		targetElement = document;
	}
	var children = targetElement.getElementsByTagName('*');
	var elements = [];
	var Len = children.length;
	var pv = '';
	for(var i = 0; i < Len; i ++) {
		pv = children[i][prototype] || children[i].getAttribute(prototype);
		if(-1 != (" " + pv + " ").indexOf(" " + value + " ")) {
			elements.push(children[i]);
		}
	}
	return elements;
}
// ��������ɾ��ָ�� key ֵ��Ԫ��
function arrayUnset(arr, key) {
	if(typeof(arr[key]) == 'undefined') {
		return false;
	}
	var tmp = [];
	for(var i in arr) {
		if(i == key) {
			continue;
		}
		tmp[i] = arr[i];
	}
	return tmp;
}

function spaceCategory(url,dos){var appendParent=$('append_parent');appendParent.innerHTML='';try{clearInterval(MyMar);}catch(e){}ajaxTip('���ڼ������ݣ����Ժ�...');var aj=new Ajax();aj.get(url,function(data){var footer=$('footer');var tmpDiv=document.createElement('div');var reScript=new RegExp("<script([\\s\\S]*?)>([\\s\\S]*?)<\\/script>","ig");var scripts=[];data=data.replace(reScript,function($0,$1,$2){scripts.push($0);return'';});tmpDiv.innerHTML=data;tmpDiv.style.display='none';appendParent.appendChild(tmpDiv);var newLCR=getChildNodeOfAjax(tmpDiv);var tmpFrame=[];tmpDiv.parentNode.removeChild(tmpDiv);if(isEmpty(newLCR)){spaceJsMsg(data);return;}for(var i in newLCR){tmpFrame[i]=[];tmpFrame[i]['id']=newLCR[i].id;tmpFrame[i]['className']=newLCR[i].className;tmpFrame[i]['html']=newLCR[i].innerHTML;}delNodeMenuBetweenFooter();for(var i in tmpFrame){tmpDiv=document.createElement('div');tmpDiv.id=tmpFrame[i]['id'];tmpDiv.className=tmpFrame[i]['className'];tmpDiv.innerHTML=tmpFrame[i]['html'];footer.parentNode.insertBefore(tmpDiv,footer);}try{evalscript(scripts.join(''));}catch(e){}ajaxComplete();});}function spaceJsMsg(msg){var reMsg=new RegExp("<(\\w+)([\\s\\S]*?)>([\\s\\S]*?)<\\/\\1>","i");var reEmpty=new RegExp("(\\s*)","i");var msgR=msg.replace(reMsg,function($0,$1,$2,$3){return $3;});msgR=msgR.replace(reEmpty,'');alert(msgR);ajaxComplete();}function delNodeMenuBetweenFooter(){var menuBody=(P('className','menubody')).pop();while(1){var currentNode=menuBody.nextSibling;if('footer'==currentNode.id){break;}currentNode.parentNode.removeChild(currentNode);}}function getChildNodeOfAjax(obj){var arr=[];if(obj.hasChildNodes){var nodeList=obj.childNodes;for(var i in nodeList){if(1==nodeList[i].nodeType&&'DIV'==(nodeList[i].nodeName).toUpperCase()){arr.push(nodeList[i]);}}}return arr;}function spaceView(url,dos){spaceCategory(url,dos);}function ajaxIframe(){if(!$('zhuxunIframe')){var iframe=document.createElement('div');iframe.id='zhuxunIframe';iframe.style.zIndex=998;iframe.style.display='none';iframe.style.backgroundColor="#000";iframe.style.opacity="0";iframe.style.position='absolute';iframe.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';$('append_parent')?$('append_parent').appendChild(iframe):document.body.appendChild(iframe);}var zhuxunIframe=$('zhuxunIframe');zhuxunIframe.style.top=0;zhuxunIframe.style.left=0;zhuxunIframe.style.width=document.body.clientWidth+'px';zhuxunIframe.style.height=document.body.clientHeight-470+'px';zhuxunIframe.style.display='block';}function ajaxTip(str){ajaxIframe();if(!$('zhuxunTip')){var div=document.createElement('div');div.id='zhuxunTip';div.style.zIndex=999;div.style.visibility='visible';div.style.position='absolute';$('append_parent')?$('append_parent').appendChild(div):document.body.appendChild(div);}var zhuxunTip=$('zhuxunTip');zhuxunTip.className='popupmenu_centerbox';zhuxunTip.style.top=(document.documentElement.scrollTop+((document.documentElement.clientHeight+80)/2))+'px';zhuxunTip.style.right=((document.documentElement.clientWidth-300)/2)+'px';zhuxunTip.style.width='225px';zhuxunTip.style.height='29px';zhuxunTip.style.lineHeight='29px';zhuxunTip.style.display='block';zhuxunTip.style.textAlign='center';if(isEmpty(str)){str='���ڼ������ݣ����Ժ�...';}zhuxunTip.innerHTML='<div class="lodingbg" style="width:157px;  height:29px; border:none; background: url(viewspace/img/tool/load.gif) no-repeat;">'+str+'</div>';}function ajaxComplete(){$('zhuxunTip').style.visibility='hidden';$('zhuxunIframe').style.display='none';}var jsmenu = new Array();
var ctrlobjclassName;
jsmenu['active'] = new Array();
jsmenu['timer'] = new Array();
jsmenu['iframe'] = new Array();

function horos() {
	if(!document.getElementById("horo_ul")) return false;
	if(!document.getElementById("horo_h3")) return false;
	var horo_ul = document.getElementById("horo_ul");
	var horo_ul_links = horo_ul.getElementsByTagName("a");
	var horo_h3 = document.getElementById("horo_h3");
	for(var h=0; h<horo_ul_links.length; h++) {
		if(horo_ul_links[h].getAttribute("title") == horo_h3.firstChild.nodeValue) {
			horo_ul_links[h].className = "here";
		}
	}
}

addLoadEvent(horos);