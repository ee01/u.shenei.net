var IE = navigator.userAgent.indexOf('MSIE') > -1 ? true : false;
var Opera = navigator.userAgent.indexOf('Opera') > -1 ? true : false;
var IE7 = navigator.userAgent.indexOf('MSIE 7') > -1 ? true : false;
var Firefox = navigator.userAgent.indexOf('Firefox') > -1 ? true : false;
var xp = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
var getHost = function(url) {
        var host = "null";
        if(typeof url == "undefined" || null == url) url = window.location.href;
        var regex = /.*\:\/\/([^\/]*).*/;
        var match = url.match(regex);
        if(typeof match != "undefined"&& null != match)  host = match[1];
        return host;
}
var HOST = 'http://' + getHost();
function $(_sId){return document.getElementById(_sId);}
String.prototype.resetBlank = function(){return this.replace(/\s+/g," ");}
String.prototype.LTrim = function(){return this.replace(/^\s+/g,"");} 
String.prototype.trim = function(){return this.replace(/(^\s+)|(\s+$)/g,""); }
String.prototype.getNum = function(){return this.replace(/[^\d]/g,"");}
String.prototype.getEn = function(){return this.replace(/[^A-Za-z]/g,""); }
String.prototype.getCn = function(){return this.replace(/[^\u4e00-\u9fa5\uf900-\ufa2d]/g,"");}
String.prototype.strlen = function(){return this.replace(/[^\x00-\xff]/g,"--").length;}
String.prototype.left = function(n){return this.slice(0,n);}
String.prototype.right = function(n){return this.slice(this.length-n);}
String.prototype.HTMLEncode = function(){var re = this;var q1 = [/\x26/g,/\x3C/g,/\x3E/g,/\x20/g];var q2 = ["&amp;","&lt;","&gt;","&nbsp;"];for(var i=0;i<q1.length;i++)re = re.replace(q1[i],q2[i]);return re;}
String.prototype.ascW = function(){var strText = "";for (var i=0; i<this.length; i++) strText += "&#" + this.charCodeAt(i) + ";";return strText;}
String.prototype.stripTags = function(){return this.replace(/<\/?[^>]+>/gi, '');};
String.prototype.inArray = function(arr){for(var i=0;i<arr.length;i++){if(this==arr[i]) return true;};return false;};
Array.prototype.unset = function(str){var _o = this; var len = _o.length;var temp = []; for(var i=0;i<len;i++){if(_o[i] != str) temp[temp.length] = _o[i];}; return temp;};
Array.prototype.inArray = function(str){var _o = this; var len = _o.length;for(var i=0;i<len;i++){if(_o[i] == str) return true;};return false;};
function domReady(func){if (!window.__load_events){var init = function(){if (arguments.callee.done) return;arguments.callee.done = true;if (window.__load_timer){clearInterval(window.__load_timer);window.__load_timer = null;};for (var i=0;i < window.__load_events.length;i++){window.__load_events[i]();};window.__load_events = null;};if (document.addEventListener){document.addEventListener("DOMContentLoaded", init, false);};if (/WebKit/i.test(navigator.userAgent)){window.__load_timer = setInterval(function(){if (/loaded|complete/.test(document.readyState)){init();};}, 10);};window.onload = init;window.__load_events = [];};window.__load_events.push(func);}
var base = new Object();
var bases = function(){
	var _o = this;
	var _t = '';
	this.sure = -1;
	this.eventIndex = -1;
	this.divIndex = 0;
	this.delObj = new Object();
	this.getElementsByClassName = function(ele, className){
		if(document.all){var children = ele.all;}else{var children = ele.getElementsByTagName('*');}
		var elements = new Array();
		for (var i = 0; i < children.length; i++){
			var child = children[i];
			var classNames = child.className.split(' ');
			for (var j = 0; j < classNames.length; j++) {
				if (classNames[j].trim() == className) {
					elements[elements.length] = child;
					break;
				}
			}
		}
		return elements;
	}
	this.evalscript = function(s){
		if(s.indexOf('<script') == -1) return s;
		var p = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/ig;
		var arr = new Array();
		while(arr = p.exec(s)) appendscript(arr[1], '', arr[2], arr[3]);
		p = /<script (?!src)[^\>]*?( reload=\"1\")?>([^\x00]+?)<\/script>/ig;
		while(arr = p.exec(s)) appendscript('', arr[2], arr[1]);
		return s;
	};
	this.encode = function(string){return encodeURIComponent(string);};
	this.getFile = function(url){var index = url.lastIndexOf('/');return url.substr(index+1);};
	this.evaljson = function(resp){var json; try{eval("json = " + resp);}catch(e){var l = resp.indexOf('{'); var r = resp.lastIndexOf('}'); eval("json = " + resp.substr(l, r-l+1));};return json;};
	this.preloadImg = function(arr){var imgs= new Array();for(var i=0;i<arr.length;i++){imgs[i]=new Image();imgs[i].src=arr[i];}};
	this.backIframeDis = function(){if(!IE) return false; var shield = document.createElement("DIV");shield.id = "iframeTop" ;shield.style.position = "absolute";shield.style.left = "0px";shield.style.top = "0px";shield.style.width = "100%";shield.style.height = document.body.clientHeight + 'px';shield.style.zIndex = "499";shield.style.opacity = 0;shield.style.filter = "alpha(opacity=0)";document.body.appendChild(shield);shield.innerHTML= '<iframe src="about:blank" frameBorder=0 scrolling=no style="width:100%;height:100%;"></iframe>';};
	this.backGroundDis = function(){var shield = document.createElement("DIV");shield.id = "shield";shield.style.position = "absolute";shield.style.left = "0px";shield.style.top = "0px";shield.style.width = "100%";shield.style.height = document.body.clientHeight + 'px';shield.style.background = "#000000";shield.style.textAlign = "center";shield.style.zIndex = "500";shield.style.filter = "alpha(opacity=50)";shield.style.opacity = 0.5;document.body.appendChild(shield);};
	this.backGroundDisColor = function(_s,_e){var _opac = parseFloat($('shield').style.opacity);if(_opac >= _e) return true;_opac = _opac+0.05;$('shield').style.filter = "alpha(opacity="+(_opac*100)+")";$('shield').style.opacity = _opac;this._t = setTimeout(function(){_o.backGroundDisColor(_s,_e);}, 50);};
	this.backGroundDisClose = function(){if($('shield') != null){clearTimeout(this._t);document.body.removeChild($('shield'));};if($('iframeTop')!= null){$('iframeTop').removeChild($('iframeTop').getElementsByTagName('iframe')[0]);document.body.removeChild($('iframeTop'));};};
	this.getElementsByName = function(name, tag){var _o = document.getElementsByName(name);if(_o.length > 0) return _o;var temp = [];tag = tag || 'DIV';var _o = document.getElementsByTagName(tag.toUpperCase());for(var i=0;i<_o.length;i++){if(_o[i].getAttribute('name') == name){temp[temp.length] = _o[i];}};return temp;};
	this.closeDoDiv = function(){if($('dialog-advanced') == null) return false;document.body.removeChild($('dialog-advanced'));window.onscroll=null;window.onresize=null;_o.backGroundDisClose();return false;};
	this.closeDiv = function(){base.closeDoDiv();return false;};
	this.bgChangeColor = function(_id, _type, fun){var fun = fun || null;this.obj = typeof(_id)=='object' ? _id : $(_id);this._type = _type || 'up';this.start = this._type == 'up' ? 0 : 1;this.end = this._type == 'up' ? 1 : 0;if(this.obj == null) return false;this.obj.style.background = 'yellow';this.obj.style.filter = "alpha(opacity="+(this.start*100)+")";this.obj.style.opacity = this.start;this.stat = 0;var _t = this;this.timer = window.setTimeout(function(){_t.change(fun);},1);this.getStep = function(op){return op <= 0.15 ? 0.05 : 0.2;};this.change = function(fun){var op = parseFloat(this.obj.style.opacity);var step = _t.getStep(op);if(_t._type == 'up'){if(op<=_t.end){this.obj.style.opacity = op + step;this.obj.style.filter = "alpha(opacity="+(op+step)*100+")";window.setTimeout(function(){_t.change(fun);},25);}else{clearTimeout(_t.timer);fun();}}else{if(op>=_t.end){this.obj.style.opacity = op - step;this.obj.style.filter = "alpha(opacity="+(op-step)*100+")";window.setTimeout(function(){_t.change(fun);},25);}else{clearTimeout(_t.timer);fun();}}};};
	this.makeSure = function(obj,title,delObj){if(delObj && $('delMakeSure') != null){_o.sure = -1;document.body.removeChild($('delMakeSure'));setTimeout(function(){_o.makeSure(obj, title, delObj);},100);return false;};if($('delMakeSure') == null){if(delObj == undefined) return false;_o.delObj = delObj;var _div = document.createElement('DIV');_div.id = 'delMakeSure';_div.style.cssText = 'visibility:visible;position:absolute;display:block;z-index: 900;';_div.innerHTML = "<div class='dialog-1' style='position: relative;'><div style='visibility: inherit;' class='hd'/><div style='visibility: inherit;' class='bd'><h3>确定删除该<span id='delSureTitle'>"+title+"</span>吗？</h3></div><div style='visibility: inherit;' class='ft'><p class='act'><input type='button' onclick='base.sure=1;' class='f-button btn-confirm' value='确定' id='make_sure_act'/><input type='button' onclick='base.sure=0;' class='f-button f-alt btn-cancel' value='取消'/></p><div class='decor'><img src='img/pmsg_dialog.gif'/></div></div></div>";_div.style.top  = base.getPos(delObj, 0) - 83 +'px';_div.style.left  = base.getPos(delObj, 1) -200+'px';document.body.appendChild(_div);}if(window.eventList==null) window.eventList=new Array();_o.eventIndex=-1;for(var i=0;i<window.eventList.length;i++){if(window.eventList[i]==null){window.eventList[i]=obj;_o.eventIndex=i;break;}};if(_o.eventIndex==-1){ind=window.eventList.length;window.eventList[_o.eventIndex]=obj;};setTimeout("base.GoOn(" + _o.eventIndex + ",'"+title+"')",50);};
	this.delSure = function(obj, title, id){_o.makeSure(this,title,obj);this.NextStep=function(){
		if(base.sure == 1){
			if(!id) top.location = obj.getAttribute('rel');
			else{
				var url = obj.getAttribute('rel');
				AJAX.request(url,{method: 'post',parameters:'myFormAc=delete',onComplete: function(resp){
					var json = base.evaljson(resp);
					if(json[0] == 200) base.bgChangeColor(id, 'down', function(){base.removeSure();$(id).parentNode.removeChild($(id));});
					else{base.removeSure();base.alert('出错了！','<p>'+json[1]+'</p>');}
				}});
			}
		}
	}};
	this.GoOn = function(ind,title){var obj=window.eventList[ind];window.eventList[ind]=null;if(_o.sure == -1) _o.makeSure(obj,title);else{if(_o.sure==1){$('delSureTitle').parentNode.innerHTML='<img src="img/loading.gif" />数据处理中,请等待';$('make_sure_act').className='hidden';}else{document.body.removeChild($('delMakeSure'));}; if(obj.NextStep) obj.NextStep();else obj();_o.sure = -1;_o.delObj.style.zIndex = _o.divIndex;}};
	this.removeSure = function(){$('delMakeSure')!=null && document.body.removeChild($('delMakeSure'));};
	this.alert = function(title, message){
		_o.dialog(title, message);
		_o.setOk(false);
	};
	this.setOk = function(_type,_value, _function){
		if($('dialog-submit') == null) return false;
		var obj = $('dialog-submit');
		if(_type == true){
			obj.className = obj.className.replace('hidden','').trim();
		}else{
			obj.className = obj.className.replace('hidden','').trim() + ' hidden';
		}
		if(_value) $('dialog-submit').value = _value;
		if(_function) $('dialog-submit').onclick = _function;
	};
	this.dialog = function(title, message){
		if($('dialog-advanced') != null) _o.closeDoDiv();
		_o.backGroundDis();
		_o.backIframeDis();
		var _html = "<div class='hd' id='dialog-title'><h3>"+title+"</h3></div><div class='bd' id='dialog-content'>"+message+"</div><div class='ft' id='dialog-ft'><input type='button' id='dialog-submit' class='f-button' value='确定'/><input type='button' id='dialog-cancel' onclick='return base.closeDiv()' class='f-button' value='取消'/><div class='dialog-close'><a href='#' onclick='return base.closeDiv()'>关闭</a></div></div><div class='underlay' id='dialog-underlay' style='width:500px;height:120px;'></div>";
		var _div = document.createElement('DIV');
		_div.className = 'dialog-advanced';
		_div.id = 'dialog-advanced';
		_div.innerHTML = _html;
		_div.style.top = 80 + document.documentElement.scrollTop + 'px';
		document.body.appendChild(_div);
		this.listen();
	};
	this.listen = function(){
		if($('dialog-advanced') == null) return false;
		var extend = (IE && !IE7) ? 2 : 0;
		$('dialog-underlay').style.width = ($('dialog-advanced').offsetWidth).toString() + 'px';
		$('dialog-underlay').style.height = ($('dialog-advanced').offsetHeight + extend).toString() + 'px';
		setTimeout(_o.listen, 200);
	};
	this.showResult = function(title, url)
	{
		_o.alert(title,'<p class="loading">数据加载中，请等待......</p>');
		_o.showResultAjax(url);
	};
	this.showResultAjax = function(url)
	{
		AJAX.request(url,{method: 'get',onComplete	: function(resp){
				if($('dialog-content') == null) return false;
				$('dialog-content').innerHTML = resp;
				_o.evalscript('dialog-content');
		}});
	};
	this.ajaxGet = function(url, callBack){
		AJAX.request(url,{method: 'get',onComplete: function(resp){
			if(callBack) callBack(resp);
		}});
	};
	this.textCounter = function(obj, limit, id){
		var len = obj.value.trim().length;
		if(len > limit){
			obj.value = obj.value.trim().substr(0, limit);
		}else{
			$(id).innerHTML = limit - len;
		}
	};
	this.doSubmit = function(e, id){
		var e=e||event;
		if(e.keyCode==13 && e.ctrlKey == true){
			$(id).onclick();
		}
	};
	this.checkAll = function(obj, name){
		var elements = obj.getElementsByTagName('input');
		var flag = $('chkall').checked;
		for(var i=0; i<elements.length; i++){
			if(elements[i].type.toLowerCase() == 'checkbox' && elements[i].disabled == false && elements[i].name.indexOf(name) === 0){
				elements[i].checked = flag;
			}
		}
	};
};
var base = new bases();
var AJAX =
{
	activeRequestCount: 0,
	_getTransport: function(){
		if (window.ActiveXObject && !window.XMLHttpRequest){
			var msxmls = ['Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'];
			for (var i = 0; i < msxmls.length; i++){try{return new window.ActiveXObject(msxmls[i]);}catch (e){}};return null;
		}else{
			return new XMLHttpRequest();
		}
	},
	request: function(url, options){
		if(AJAX.activeRequestCount>0) return setTimeout(function(){AJAX.request(url, options);} ,50);
		AJAX.activeRequestCount++;
		this.transport	= this._getTransport();
		this.url = url;
		this.options	={
	    	method:	'POST',
	    	asynchronous:	true,
	    	contentType:	'application/x-www-form-urlencoded',
	    	parameters:		'',
			onLoading:		function(){},
			onLoaded:		function(){},
			onInteractive:	function(){},
			onComplete:		function(){}
		};
		this.requestHeaders = ['X-Requested-With', 'XMLHttpRequest','Accept', 'text/javascript, text/html, application/xml, text/xml, */*'];
		options		= options || {};
		Object.extend(this.options, options);
		if (this.options.method.toUpperCase() == 'GET' && this.options.parameters.length > 0){
			this.url += (this.url.match(/\?/) ? '&' : '?') + this.options.parameters;
		};
		this.transport.open(this.options.method.toUpperCase(), this.url, this.options.asynchronous);
		if (this.options.asynchronous){
			var self = this;
	        	this.transport.onreadystatechange = function(){
				switch(self.transport.readyState){
					case 1:
						self.options.onLoading(self.transport);
					break;
					case 2:
						self.options.onLoaded(self.transport);
					break;
					case 3:
						self.options.onInteractive(self.transport);
					break;
					case 4:
						if(self.transport.getResponseHeader('x-json')!=null && self.transport.getResponseHeader('x-json')!=''){
							var json;
							eval('json =' + self.transport.getResponseHeader('x-json'));
							self.options.onComplete(json);
						}else{
							try{self.options.onComplete(self.transport.responseXML.lastChild.firstChild.nodeValue);}catch(e){self.options.onComplete(self.transport.responseText)}
						}
						AJAX.activeRequestCount--;
					break;
				}
			};
		}
		if (this.options.method.toUpperCase() == 'POST')
		{
			this.requestHeaders.push('Content-type', this.options.contentType);
			if (this.transport.overrideMimeType)
			{
				this.requestHeaders.push('Connection', 'close');
			}
		}
		this.requestHeaders.push('Referer', location.href);
		this.requestHeaders.push('Charset', 'utf-8');
		if (this.options.requestHeaders)
		{
			this.requestHeaders.push.apply(this.requestHeaders, this.options.requestHeaders);
		}
		for (var j = 0; j < this.requestHeaders.length; j += 2)
		{
			this.transport.setRequestHeader(this.requestHeaders[j], this.requestHeaders[j+1]);
		}
		this.transport.send(this.options.method.toUpperCase() == 'POST' ? this.options.parameters : null);
		if (!this.options.asynchronous)
		{
			this.options.onComplete(this.transport);
		}
	}
};
Object.extend = function(destination, source) 
{
	for (var property in source)
	{
		destination[property] = source[property];
	}
	return destination;
};
var Form = {
		serialize:function(name){
			var obj = document.forms[name];
			var para = "";
			var arr = obj.elements;
			var elem = {};
			for(var i=0,j; j=arr[i]; i++){
				if(j.disabled || !j.name){continue;}
				if(j.type && j.type.toLowerCase().inArray(["radio","checkbox"]) && !j.checked){continue;}
				var na = j.name;
				if(typeof elem[na] == "undefined"){
					elem[na] = [];
				}
				elem[na].push(encodeURIComponent(j.value));
			}
			var para = [];
			for(var name in elem){
				for(var i=0; i<elem[name].length; i++){
					para[para.length] = name+"="+elem[name][i];
				}
			}
			return para.join("&");
		},
		submitDo:function(name){
			var obj = document.forms[name];
			base.setOk(false);			
			var _url = obj.getAttribute('action');
			var _method = obj.getAttribute("method");
			var _parameters = Form.serialize(name);
			$('dialog-content').innerHTML = '<p class="loading">数据提交中，请等待......</p>';
			AJAX.request(_url,{method: _method,parameters:_parameters,onComplete: function(resp){
				try{
					var json = base.evaljson(resp);
					$('dialog-content').innerHTML = '<p>'+json[1]+'</p>';
					base.evalscript('dialog-content');
				}catch(e){};
				setTimeout(function(){base.bgChangeColor('dialog-advanced', 'down', base.closeDoDiv);}, 1000);
			}});
		}
};
function domReady(func){if (!window.__load_events){var init = function(){if (arguments.callee.done) return;arguments.callee.done = true;if (window.__load_timer){clearInterval(window.__load_timer);window.__load_timer = null;};for (var i=0;i < window.__load_events.length;i++){window.__load_events[i]();};window.__load_events = null;};if (document.addEventListener){document.addEventListener("DOMContentLoaded", init, false);};if (/WebKit/i.test(navigator.userAgent)){window.__load_timer = setInterval(function(){if (/loaded|complete/.test(document.readyState)){init();};}, 10);};window.onload = init;window.__load_events = [];};window.__load_events.push(func);}