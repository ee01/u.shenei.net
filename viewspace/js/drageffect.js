/**
* Ϊ��ʹ�ص������ܼ�ʱ������ק�Ķ����������ڿ�ʼ�϶����϶����ͷ�ʱ�������˻ص��������Ա��ڴ���
*/

/**
* @param:dragBody ���϶��Ķ����ID��
* @param:dragHead �����϶���λ�ã�
* @param:dragRange ���Ա��϶��ķ�Χ��
* @param:dragDirection ���϶��ķ���(ˮƽ����vertical, ��ֱ����horizontal)��
* @param:dragCallback �ص�������
* @param:dragClick �Ƿ���Ӧ���ĵ����¼�(һ�������϶�ʱ�޶���Χʱʹ��)��
*/
function drag(dragBody, dragHead, dragRange, dragDirection, dragCallback, dragClick) {
	// �Բ��������ж�
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
	// �Ƿ���Ӧ��굥��ʱ��
	this.dragClick = dragClick;
	// �ص�����
	this.callback = dragCallback;
	// �϶�����
	this.direction = dragDirection;
	// �϶���Χ����
	this.range = this.$(dragRange);
	// �������ʱ�����϶��Ķ���
	this.head = this.$(dragHead);
	// �������Ա��϶��Ķ���
	this.body = this.$(dragBody);
	// �϶�ʱ�ı�ʶ
	this.element = false;
	this.elementInfo = [];
	// �϶�ʱ�������ʽ
	this.cursor = 'move';
	// �������϶������ onmousedown �¼�
	this.head.onmousedown = this.ready.bindAsEventListener(this);
	// �������϶������ onmouseover �¼�
	this.head.onmouseover = this.over.bindAsEventListener(this);
	// ������϶���Χ������Ӧ��굥���¼�ʱ��������϶���Χ����� onmousedown �¼�
	if(this.range && this.dragClick) {
		this.range.onmousedown = this.rangeClick.bindAsEventListener(this);
	}
}
// ��ȡ���󣬲�����
drag.prototype.$ = function(id) {
	if("object" == typeof(id)) {
		return id;
	}
	return document.getElementById(id);
}
// �����϶���Χ�� onmousedown �¼�
drag.prototype.rangeClick = function(event) {
	// ��ȡ event ����
	event = event || window.event;
	// ��ȡ��Χ����� left��top ֵ
	var _LT = fetchOffset(this.range);
	// ����Ҫ�϶��Ĳ�����ĵ��Ƶ���굥���ĵ���
	x = parseInt(event.clientX) + document.documentElement.scrollLeft - _LT['left'] - (this.body.offsetWidth / 2);
	y = parseInt(event.clientY) + document.documentElement.scrollTop - _LT['top'] - (this.body.offsetHeight / 2);
	this.setLeftTop(this.body, x, y);
	// ��ʼ���϶��¼�
	this.ready(event);
	// �ƶ����϶�����
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
// ��ȡ CSS ����
drag.prototype.getStyle = function(elem, style) {
	if(elem.currentStyle) {
		// ���ú�����replace���д���
		style = style.replace(/-([a-z])/g, function($0, $1){return $1.toUpperCase();});
		value = elem.currentStyle[style];
	} else if(window.getComputedStyle) {
		var css = document.defaultView.getComputedStyle(elem, null);
		value = css ? css.getPropertyValue(style) : null;
	}
	return value == 'auto' ? null : value;
}
// ��ʼ���϶��¼�
drag.prototype.ready = function(event) {
	// Ϊ��ǰ�����ȡһ������
	var __method = this;
	event = event || window.event;
	// �ѵ�ǰ�ı��϶�����ֵ�� this.element
	this.element = this.body;
	// ��ȡ��굥������������Ͻǵ�����
	this.oldx = this.mx = this.mouseX = parseInt(event.clientX);
	this.oldy = this.my = this.mouseY = parseInt(event.clientY);
	// ��ȡ���϶���������Ͻ�����
	this.leftTop = fetchOffset(this.element);
	// ��ȡ��ǰ���ڵ������Ϣ
	this.windowInfo = this.getWindowInfo();
	// �趨���϶��������� CSS ����
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
	// ������϶������
	this.element.style.position = 'absolute';
	//this.setLeftTop(this.element, this.leftTop['left'], this.leftTop['top']);
	// �������һ���������϶��Ļ������ȡ������������Ϣ
	if(this.range) {
		this.rangeLT = fetchOffset(this.range);
		this.rangeLT['width'] = this.range.offsetWidth;
		this.rangeLT['height'] = this.range.offsetHeight;
	}
	// ����ҳ��� onmousemove �¼�
	document.onmousemove = this.move.bindAsEventListener(this);
	// ����ҳ��� onmouseup �¼�
	document.onmouseup = this.stop.bindAsEventListener(this);
	/**
	 ��ΪFirefoxĬ�ϵľ�����겶��״̬������ֻ�ж�IE�����⴦����У�
	 ��ʱ������������������ڱ��������ģ�������Ƴ����������ʱ����ֻ�ǵ�������-1������һ���������ֵ��
	*/
	if(is_ie) {
		document.documentElement.setCapture();
	} else if(window.captureEvents) {
		window.captureEvents(event.MOUSEMOVE | event.MOUSEUP);
	}
	// Ϊ�˼��� Firefox �ж�ͼƬ���϶������ĵ���¼������´��ݣ�����ֹ��˵�� javascript ���¼�ð��
	doane(event);
	// ִ�лص�����
	if(this.callback) {
		this.callback(__method);
	}
}
// ����������������¼�������׼ȷ���϶�������
drag.prototype.scrollMove = function(event, x, y) {
	var _T = null, _L = null;
	// ��������޶���ĳһ������
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
	// ���������ҳ���϶�������ֻ�����˴�ֱ���꣬ˮƽ�Ŀ��ܻ��ǻ�������......
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
// �����϶��¼�
drag.prototype.move = function(event) {
	var __method = this;
	event = event || window.event;
	// �ж�dragElement�Ƿ�Ϊnull�����Ƿ�Ϊ��ק״̬
	if(this.element) {
		var x, y;
		this.oldx = this.mx;
		this.oldy = this.my;
		// ��ȡ��ǰ��������
		this.mx = parseInt(event.clientX);
		this.my = parseInt(event.clientY);
		// ���㵱ǰ�ƶ��������
		x = this.mx - this.mouseX + this.leftTop['left'];
		y = this.my - this.mouseY + this.leftTop['top'];
		// ����׼ȷ������λ��
		xyLT = this.scrollMove(event, x, y);
		// �ı�dragElement��λ�ã�������Ҫ�ж��Ƿ�ֻ�����û���ĳһ���������϶�
		this.setLeftTop(this.element, xyLT.x, xyLT.y, xyLT.L, xyLT.T);
		// ��ҳ��� onmousemove �¼��ÿ�
		document.onmousemove = null;
		// ���� setTimeout ���������°�ҳ��� onmousemove �¼����������Խ�ʡ�ͻ�����Դ
		setTimeout(function() {
			// ����ҳ��� onmousemove �¼�
			document.onmousemove = __method.move.bindAsEventListener(__method);
		}, 40);
		
		if(is_ie) {
			// ȡ��IE�е�ѡ��״̬
			document.selection.empty();
		} else {
			// ȡ��FireFox�е�ѡ��״̬
			window.getSelection().removeAllRanges();
		}
		// ִ�лص�����
		if(this.callback) {
			this.callback(__method);
		}
	}
}
// ���û��ɿ������ʱ����ֹͣ������϶�
drag.prototype.stop = function(event) {
	var __method = this;
	// ����϶����󲻴���
	if(false == this.element) {
		return true;
	}
	// �����������ҳ���϶�������Ҫ���¼����϶����λ�ã���ֹ�û��Ѷ����ϵ���Ļ����
	if(!this.range) {
		// ��ȡ�϶����������
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
	// ��ԭ��С
	if(this.elementInfo['height']) {
		this.element.style.height = this.elementInfo['height'] + 'px';
	}
	if(this.elementInfo['width']) {
		this.element.style.width = this.elementInfo['width'] + 'px';
	}
	this.element = false;
	// ��ΪFirefoxĬ�ϵľͻ��ͷ���겶��״̬������ֻ�ж�IE�����⴦�����
	if(is_ie) {
		document.documentElement.releaseCapture();
	} else if(window.captureEvents) {
		window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
	}
	// ִ�лص�����
	if(this.callback) {
		this.callback(__method);
	}
}
// �ı������ʽ
drag.prototype.over = function(event) {
	this.head.style.cursor = this.cursor;
}
// ��ȡ���ڶ���������Ϣ
drag.prototype.getWindowInfo = function() {
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