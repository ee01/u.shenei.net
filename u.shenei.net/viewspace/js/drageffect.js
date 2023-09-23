/**
* 为了使回调函数能及时处理拖拽的动作，所以在开始拖动、拖动、释放时都调用了回调函数，以便于处理
*/

/**
* @param:dragBody 被拖动的对象的ID；
* @param:dragHead 可以拖动的位置；
* @param:dragRange 可以被拖动的范围；
* @param:dragDirection 可拖动的方向(水平方向：vertical, 垂直方向：horizontal)；
* @param:dragCallback 回调函数；
* @param:dragClick 是否响应鼠标的单击事件(一般用在拖动时限定范围时使用)；
*/
function drag(dragBody, dragHead, dragRange, dragDirection, dragCallback, dragClick) {
	// 对参数进行判断
	if('undefined' == typeof(dragBody) || '' == dragBody) {
		return false;
	}
	if('undefined' == typeof(dragHead) || '' == dragHead) {
		dragHead = dragBody;
	}
	if('undefined' == typeof(dragRange) || '' == dragRange) {
		dragRange = null;
	}
	if('undefined' == typeof(dragDirection) || '' == dragDirection) {
		dragDirection = null;
	}
	if('undefined' == typeof(dragCallback) || '' == dragCallback) {
		dragCallback = null;
	}
	if('undefined' == typeof(dragClick) || '' == dragClick) {
		dragClick = null;
	}
	// 是否响应鼠标单击时间
	this.dragClick = dragClick;
	// 回调函数
	this.callback = dragCallback;
	// 拖动方向
	this.direction = dragDirection;
	// 拖动范围对象
	this.range = this.$(dragRange);
	// 按下鼠标时可以拖动的对象
	this.head = this.$(dragHead);
	// 整个可以被拖动的对象
	this.body = this.$(dragBody);
	// 拖动时的标识
	this.element = false;
	this.elementInfo = [];
	// 拖动时的鼠标样式
	this.cursor = 'move';
	// 监听可拖动区域的 onmousedown 事件
	this.head.onmousedown = this.ready.bindAsEventListener(this);
	// 监听可拖动区域的 onmouseover 事件
	this.head.onmouseover = this.over.bindAsEventListener(this);
	// 如果有拖动范围并且响应鼠标单击事件时，则监听拖动范围对象的 onmousedown 事件
	if(this.range && this.dragClick) {
		this.range.onmousedown = this.rangeClick.bindAsEventListener(this);
	}
}
// 读取对象，并返回
drag.prototype.$ = function(id) {
	if("object" == typeof(id)) {
		return id;
	}
	return document.getElementById(id);
}
// 处理拖动范围的 onmousedown 事件
drag.prototype.rangeClick = function(event) {
	// 获取 event 对象
	event = event || window.event;
	// 读取范围对象的 left、top 值
	var _LT = fetchOffset(this.range);
	// 把需要拖动的层的中心点移到鼠标单击的点上
	x = parseInt(event.clientX) + document.documentElement.scrollLeft - _LT['left'] - (this.body.offsetWidth / 2);
	y = parseInt(event.clientY) + document.documentElement.scrollTop - _LT['top'] - (this.body.offsetHeight / 2);
	this.setLeftTop(this.body, x, y);
	// 初始化拖动事件
	this.ready(event);
	// 移动被拖动对象
	this.move(event);
}
drag.prototype.setLeftTop = function(obj, x, y, L, T) {
	if('vertical' != this.direction) {
		obj.style.left = x + 'px';
	}
	if('horizontal' != this.direction) {
		obj.style.top = y + 'px';
	}
	if('undefined' != typeof(L) && null != L) {
		document.documentElement.scrollLeft = L;
	}
	if('undefined' != typeof(T) && null != T) {
		document.documentElement.scrollTop = T;
	}
}
// 获取 CSS 内容
drag.prototype.getStyle = function(elem, style) {
	if(elem.currentStyle) {
		// 利用函数对replace进行处理
		style = style.replace(/-([a-z])/g, function($0, $1){return $1.toUpperCase();});
		value = elem.currentStyle[style];
	} else if(window.getComputedStyle) {
		var css = document.defaultView.getComputedStyle(elem, null);
		value = css ? css.getPropertyValue(style) : null;
	}
	return value == 'auto' ? null : value;
}
// 初始化拖动事件
drag.prototype.ready = function(event) {
	// 为当前类对象取一个别名
	var __method = this;
	event = event || window.event;
	// 把当前的被拖动对象赋值给 this.element
	this.element = this.body;
	// 获取鼠标单击点相对于左上角的坐标
	this.oldx = this.mx = this.mouseX = parseInt(event.clientX);
	this.oldy = this.my = this.mouseY = parseInt(event.clientY);
	// 获取被拖动对象的左上角坐标
	this.leftTop = fetchOffset(this.element);
	// 获取当前窗口的相关信息
	this.windowInfo = this.getWindowInfo();
	// 设定被拖动对象的相关 CSS 属性
	var _BL = parseInt(this.getStyle(this.element, 'border-left-width'));
	var _BR = parseInt(this.getStyle(this.element, 'border-right-width'));
	_BL = isNaN(_BL) ? 0 : _BL;
	_BR = isNaN(_BR) ? 0 : _BR;
	this.element.style.width = (this.element.offsetWidth - _BL - _BR) + 'px';
	if(this.element.offsetWidth > this.windowInfo['width']) {
		this.elementInfo['width'] = this.element.offsetWidth - _BL - _BR;
		this.element.style.width = (this.windowInfo['width'] - 100) + 'px';
	}
	/*if(this.element.offsetHeight > this.windowInfo['height']) {
		this.elementInfo['height'] = this.element.offsetHeight;
		this.element.style.height = (this.windowInfo['height'] - 100) + 'px';
	}*/
	// 如果被拖动层大于
	this.element.style.position = 'absolute';
	//this.setLeftTop(this.element, this.leftTop['left'], this.leftTop['top']);
	// 如果是在一个区域内拖动的话，则读取该区域的相关信息
	if(this.range) {
		this.rangeLT = fetchOffset(this.range);
		this.rangeLT['width'] = this.range.offsetWidth;
		this.rangeLT['height'] = this.range.offsetHeight;
	}
	// 监听页面的 onmousemove 事件
	document.onmousemove = this.move.bindAsEventListener(this);
	// 监听页面的 onmouseup 事件
	document.onmouseup = this.stop.bindAsEventListener(this);
	/**
	 因为Firefox默认的就是鼠标捕获状态，所以只有对IE做特殊处理就行；
	 此时捕获的鼠标坐标是相对于被捕获对象的，在鼠标移出被捕获对象时，不只是单纯返回-1，而是一个具体的数值。
	*/
	if(is_ie) {
		document.documentElement.setCapture();
	} else if(window.captureEvents) {
		window.captureEvents(event.MOUSEMOVE | event.MOUSEUP);
	}
	// 为了兼容 Firefox 中对图片的拖动；鼠标的点击事件不向下传递，即防止传说中 javascript 的事件冒泡
	doane(event);
	// 执行回调函数
	if(this.callback) {
		this.callback(__method);
	}
}
// 处理滚动条滚动的事件，计算准确的拖动层坐标
drag.prototype.scrollMove = function(event, x, y) {
	var _T = null, _L = null;
	// 如果对象被限定在某一个区域
	if(this.range) {
		if(x < this.rangeLT['left']) {
			x = 0;
		} else if(x > this.rangeLT['left'] + this.rangeLT['width'] - this.element.offsetWidth) {
			x = this.rangeLT['width'] - this.element.offsetWidth;
		} else {
			x = parseInt(event.clientX) + document.documentElement.scrollLeft - this.rangeLT['left'] - (this.element.offsetWidth / 2);
		}
		if(y < this.rangeLT['top']) {
			y = 0;
		} else if(y > this.rangeLT['top'] + this.rangeLT['height'] - this.element.offsetHeight) {
			y = this.rangeLT['height'] - this.element.offsetHeight;
		} else {
			y = parseInt(event.clientY) + document.documentElement.scrollTop - this.rangeLT['top'] - (this.element.offsetHeight / 2);
		}
		return {x:x,y:y};
	}
	// 如果在整个页面拖动，这里只计算了垂直坐标，水平的可能还是会有问题......
	if(document.body.clientHeight > y + parseInt(this.element.offsetHeight)) {
		if(y < this.windowInfo['scrollTop'] && 0 <= y && this.my < this.oldy) {
			_sT = this.windowInfo['scrollTop'] - y;
			_T = this.windowInfo['scrollTop'] = y;
			if(0 < _sT) {
				this.leftTop['top'] -= _sT;
				y -= _sT;
			} else {
				_T = this.windowInfo['scrollTop'] = 0;
			}
			//document.documentElement.scrollTop = this.windowInfo['scrollTop'];
		} else if(this.windowInfo['height'] + this.windowInfo['scrollTop'] < y + parseInt(this.element.offsetHeight) && this.windowInfo['maxHeight'] >= y + parseInt(this.element.offsetHeight) && this.my > this.oldy) {
			_sT = y + parseInt(this.element.offsetHeight) - this.windowInfo['height'] - this.windowInfo['scrollTop'];
			_T = this.windowInfo['scrollTop'] += _sT;
			if(this.windowInfo['maxHeight'] >= y + parseInt(this.element.offsetHeight)) {
				this.leftTop['top'] += _sT;
				y += _sT;
			} else {
				_T = this.windowInfo['scrollTop'] = this.windowInfo['maxHeight'] - this.windowInfo['height'];
			}
			//document.documentElement.scrollTop = this.windowInfo['scrollTop'];
		}
	}
	
	return {x:x,y:y,L:_L,T:_T};
}
// 处理拖动事件
drag.prototype.move = function(event) {
	var __method = this;
	event = event || window.event;
	// 判断dragElement是否为null，即是否为拖拽状态
	if(this.element) {
		var x, y;
		this.oldx = this.mx;
		this.oldy = this.my;
		// 获取当前鼠标的坐标
		this.mx = parseInt(event.clientX);
		this.my = parseInt(event.clientY);
		// 计算当前移动层的坐标
		x = this.mx - this.mouseX + this.leftTop['left'];
		y = this.my - this.mouseY + this.leftTop['top'];
		// 计算准确的坐标位置
		xyLT = this.scrollMove(event, x, y);
		// 改变dragElement的位置，这里需要判断是否只运行用户在某一个方向上拖动
		this.setLeftTop(this.element, xyLT.x, xyLT.y, xyLT.L, xyLT.T);
		// 把页面的 onmousemove 事件置空
		document.onmousemove = null;
		// 利用 setTimeout 方法，重新绑定页面的 onmousemove 事件，这样可以节省客户端资源
		setTimeout(function() {
			// 监听页面的 onmousemove 事件
			document.onmousemove = __method.move.bindAsEventListener(__method);
		}, 40);
		
		if(is_ie) {
			// 取消IE中的选中状态
			document.selection.empty();
		} else {
			// 取消FireFox中的选中状态
			window.getSelection().removeAllRanges();
		}
		// 执行回调函数
		if(this.callback) {
			this.callback(__method);
		}
	}
}
// 当用户松开了鼠标时，即停止对象的拖动
drag.prototype.stop = function(event) {
	var __method = this;
	// 如果拖动对象不存在
	if(false == this.element) {
		return true;
	}
	// 如果是在整个页面拖动，则需要重新计算拖动层的位置，防止用户把对象拖到屏幕外面
	if(!this.range) {
		// 获取拖动对象的坐标
		var LT = fetchOffset(this.element);
		if(0 > LT['left']) {
			this.element.style.left = '0px';
		}
		if(0 > LT['top']) {
			this.element.style.top = '0px';
		}
		if(LT['left'] + parseInt(this.element.offsetWidth) > this.windowInfo['maxWidth']) {
			this.element.style.left = (this.windowInfo['maxWidth'] - this.element.offsetWidth) + 'px';
		}
	}
	// 还原大小
	if(this.elementInfo['height']) {
		this.element.style.height = this.elementInfo['height'] + 'px';
	}
	if(this.elementInfo['width']) {
		this.element.style.width = this.elementInfo['width'] + 'px';
	}
	this.element = false;
	// 因为Firefox默认的就会释放鼠标捕获状态，所以只有对IE做特殊处理就行
	if(is_ie) {
		document.documentElement.releaseCapture();
	} else if(window.captureEvents) {
		window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
	}
	// 执行回调函数
	if(this.callback) {
		this.callback(__method);
	}
}
// 改变鼠标样式
drag.prototype.over = function(event) {
	this.head.style.cursor = this.cursor;
}
// 获取窗口对象的相关信息
drag.prototype.getWindowInfo = function() {
	// 页面的可视高度
	var _h = parseInt(document.documentElement.clientHeight);
	// 页面的可视宽度
	var _w = parseInt(document.documentElement.clientWidth);
	// 页面左边被隐藏的宽度
	var _sL = parseInt(document.documentElement.scrollLeft);
	// 页面顶部被隐藏的高度
	var _sT = parseInt(document.documentElement.scrollTop);
	// 页面的最大高度
	var _mH = parseInt(document.body.clientHeight);
	// 页面的最大宽度
	var _mW = parseInt(document.body.clientWidth);
	return {'height':_h, 'width':_w, 'maxHeight':_mH, 'maxWidth':_mW, 'scrollLeft':_sL, 'scrollTop':_sT};
}