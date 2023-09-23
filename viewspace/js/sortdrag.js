/**
* @param:body ���ƶ�ģ���CSS��ʽ className ֵ��
* @param:head ���϶���λ�ã�Ҳ�� className ֵ��
* @param:content ����ƶ��ö�����ʱ����ʾһ�����ֲ㣬Ҳ�� className ֵ��
*/
sortDrag = function(body, head, content) {
	var __method = this;
	if("undefined" == typeof(body) || '' == body) {
		return false;
	}
	if('undefined' == typeof(head) || '' == head) {
		head = body;
	}
	if('undefined' == typeof(content) || '' == content) {
		content = null;
	}
	// ���ֲ����
	this.transfer = $('transferReady');
	this.body = body;
	this.head = head;
	this.content = content;
	//  �϶��������ض��󼯺�
	this.bodyHead = {};
	this.bodyHead[body] = [];
	this.bodyHead[head] = [];
	// �Ƿ������϶�
	this.isDrag = false;
	// ��ʱ�����
	// this.tmpDiv = false;
	// �ƶ�ʱ�������ڸ��е���ʱ��
	this.goDiv = false;
	var actionDiv = [body, head];
	if(content) {
		this.bodyHead[content] = [];
		this.array_push(actionDiv, content);
	}
	// �϶���������鼯��
	this.drags = [];
	// ���ж�Ӧ��ģ�����
	this.posObj = [];
	// ���ж���
	this.LCR = [];
	// ���϶�ģ����󵽸��еĶ�������
	this.key2mod = [];
	// ��ǰ������
	this.curLCR = null;
	// �����С���жϱ�־
	this.smallMax = 'max';
	// ��ҳ�����������������Ķ���
	this.getByClass(document.body, actionDiv);
	// �����п��϶�������г�ʼ��
	for(var i in this.bodyHead[body]) {
		this.drags[i] = new drag(this.bodyHead[body][i], '', '', '', function(obj) {
			// �ú���Ϊ�ص�����
			if(false == obj.element) {
				__method.isDrag = false;
				// ���϶��Ĳ�ŵ��ʺϵ�λ��
				__method.back(obj);
				return false;
			}
			if('none' != __method.transfer.style.display) {
				//__method.transfer.style.display = 'none';
			}
			__method.isDrag = true;
			// �������϶�ʱ��λ��
			__method.go(obj);
		});
		// ����Ƶ��ò�ʱ����̬����һ���㣬�Ա����޸�����ʽ
		this.bodyHead[content][i].onmouseover = this.transferReady.bindAsEventListener(this, this.drags[i]);
	}
}
// ��ȡ���еĿ��϶�ģ�������Ϣ
sortDrag.prototype.getLCR = function() {
	// �жϸ��м�ģ����������Ƿ��Ѿ�����
	if(0 < this.LCR.length || 0 < this.posObj.length) {
		return true;
	}
	for(var i in this.bodyHead[this.body]) {
		_pObj = this.bodyHead[this.body][i].parentNode;
		this.LCR[_pObj.id] = _pObj;
		// �������ģ�����鲻���ڣ����ʼ��һ������
		if("undefined" == typeof(this.posObj[_pObj.id])) {
			this.posObj[_pObj.id] = new Array();
		}
		this.key2mod[i] = _pObj.id;
		this.posObj[_pObj.id][i] = this.bodyHead[this.body][i];
	}
}
sortDrag.prototype.insertAfter = function(newNode, targetNode) {
	var parentNode = targetNode.parentNode;
	if(targetNode.lastChild == parentNode) {
		parentNode.appendChild(newNode);
	} else {
		parentNode.insertBefore(newNode, targetNode.nextSibling);
	}
}
sortDrag.prototype.changeSize = function(smallMax, obj) {
	if('max' == this.smallMax && 'max' == smallMax) {
		return false;
	}
	if('small' == this.smallMax && 'small' == smallMax) {
		return false;
	}
	for(var i in this.bodyHead[this.content]) {
		this.bodyHead[this.content][i].style.display = 'none';
	}
	this.smallMax = 'small';
	this.transfer.style.display = 'none';
	// ����Ҫ�϶��Ĳ�����ĵ��Ƶ���굥���ĵ���
	obj.windowInfo['scrollTop'] = document.documentElement.scrollTop;
	obj.windowInfo['scrollLeft'] = document.documentElement.scrollLeft;
	obj.leftTop['left'] = obj.mouseX + obj.windowInfo['scrollLeft'] - (obj.body.offsetWidth / 2);
	obj.leftTop['top'] = obj.mouseY + obj.windowInfo['scrollTop'] - (obj.body.offsetHeight / 2);
	obj.body.style.left = obj.leftTop['left'] + 'px';
	obj.body.style.top = obj.leftTop['top'] + 'px';
}
// �϶�ģ����󣬼��㵱ǰģ��Ӧ�ò����ĸ�λ��
sortDrag.prototype.go = function(obj) {
	// ģ�����������������
	var _CL = null;
	// ��һ������
	var _CF = null;
	// ��ǰģ�鴦���ĸ�ģ��֮ǰ
	var _CT = null;
	// ����� left��topֵ
	var _LT = null;
	// �����صĸ߶�
	var scrollTop = document.documentElement.scrollTop;
	this.changeSize('small', obj);
	
	// ��ȡ���еĿ��϶�ģ����Ϣ������
	this.getLCR();
	
	// ����Ǵ����϶�״̬�У������ʱ��ŵ����϶��������
		/*
	if('absolute' == this.transfer.style.position) {
		this.insertAfter(this.transfer, obj.body);
		if(obj.body.parentNode.lastChild)
		obj.body.parentNode.insertBefore(this.transfer, obj.body);
		this.transfer.style.position = 'static';
	}
		*/
	// ��ȡ���϶�ģ����������
	if(null == this.curLCR) {
		for(var i in this.bodyHead[this.body]) {
			if(obj.body == this.bodyHead[this.body][i]) {
				this.curLCR = this.key2mod[i];
			}
		}
	}
	// ��ȡ��ǰ���λ�ô����ĸ���Ŀ
	for(var i in this.LCR) {
		_LT = fetchOffset(this.LCR[i]);
		if(null == _CF || _CF > _LT['left']) {
			_CF = i;
		}
		if(obj.mx > _LT['left']) {
			_CL = i;
		}
	}
	if(null == _CL) {
		_CL = _CF;
	}
	// ��ȡ�û��Ĵ�ֱλ��
	var _minTop = null;
	var _curTop = null;
	var tmp = 0;
	for(var i in this.posObj[_CL]) {
		_LT = fetchOffset(this.posObj[_CL][i]);
		tmp = _LT['top'] + (this.posObj[_CL][i].offsetHeight / 2);
		if(obj.my + scrollTop < tmp && (null == _curTop || _curTop > tmp) && obj.body != this.posObj[_CL][i]) {
			_CT = i;
			_curTop = tmp;
		}
	}
	// �����ʱ�㲻����
	if(false == this.goDiv) {
		this.goDiv = document.createElement('div');
		this.goDiv.className = 'transfer';
		this.goDiv.style.display = '';
		this.goDiv.style.height = obj.body.clientHeight + 'px';
	}
	// ������ʱDIV
	if(null != _CT) {
		// �����ǰģ�鴦��ĳ��֮ǰ
		this.bodyHead[this.body][_CT].parentNode.insertBefore(this.goDiv, this.bodyHead[this.body][_CT]);
		this.goDiv.style.width = 'auto';
	} else {
		// �����ǰģ�鴦��ĳ�����
		this.LCR[_CL].appendChild(this.goDiv);
		this.goDiv.style.width = 'auto';
	}
}
// ��һ���������Ƴ�ĳ����ֵ��Ӧ�����鵥Ԫ
sortDrag.prototype.remove = function(arr, key) {
	if(isNaN(key) || "undefined" == typeof(key)) {
		return arr;
	}
	var tmpArr = [];
	for(var i in arr) {
		if(key != i) {
			tmpArr[i] = arr[i];
		}
	}
	return tmpArr;
}
// �����û��ɿ�������¼��������϶�������ģ��ŵ���Ӧ��λ����
sortDrag.prototype.back = function(obj) {
	// �������ͷţ����������ֲ�
	this.transfer.style.display = 'none';
	// ����ʱ��֮ǰ���뱻�϶���ģ�飬�����ʱ�����
	this.goDiv.parentNode.insertBefore(obj.body, this.goDiv);
	this.goDiv.parentNode.removeChild(this.goDiv);
	// ���϶�ģ��Ķ�λΪ�Զ������Ϊ�Զ�
	obj.body.style.position = 'static';
	obj.body.style.width = 'auto';
	obj.body.style.height = 'auto';
	// ��ȡģ����������
	_pObj = obj.body.parentNode;
	// ��ȡ left��top
	_pLT = fetchOffset(_pObj);
	var key = null;
	// ��ȡ���϶�ģ������
	for(var i in this.bodyHead[this.body]) {
		if(obj.body == this.bodyHead[this.body][i]) {
			key = i;
		}
	}
	// ��ģ����ж�Ӧ�������У��޳���ǰģ��
	this.posObj[this.curLCR] = this.remove(this.posObj[this.curLCR], key);
	// ��ģ�����ID��Ӧ�������޳���ǰģ��
	this.key2mod = this.remove(this.key2mod, key);
	// �ѵ�ǰģ�����¼��뵽��Ӧ������
	this.key2mod[key] = _pObj.id;
	this.posObj[_pObj.id][key] = obj.body;
	// �ÿյ�ǰģ��������
	this.curLCR = null;
	this.smallMax = 'max';
	for(var i in this.bodyHead[this.content]) {
		this.bodyHead[this.content][i].style.display = '';
	}
	objLT = fetchOffset(obj.body);
	if(obj.body.clientHeight > document.documentElement.clientHeight) {
		document.documentElement.scrollTop = objLT['top'] - 10;
	} else {
		document.documentElement.scrollTop = objLT['top'] - ((document.documentElement.clientHeight - obj.body.clientHeight) / 2);
	}
}
// �������Ƴ�ģ��ʱ����
sortDrag.prototype.transferEnd = function(event, dragObj) {
	var scrollTop = document.documentElement.scrollTop;
	var scrollLeft = document.documentElement.scrollLeft;
	var x = parseInt(event.clientX) + scrollLeft;
	var y = parseInt(event.clientY) + scrollTop;
	var _LT = fetchOffset(dragObj.body);
	// �ж��������ֵ�Ƿ��ڵ�ǰ��ģ���У�������˵�ǰ��Χ�������ظ���ʱ��
	if(_LT['left'] > x || _LT['top'] > y || _LT['left'] + dragObj.body.offsetWidth < x || _LT['top'] + dragObj.body.offsetHeight < y) {
		this.transfer.style.display = 'none';
	}
}
// �����㽥��׼��
sortDrag.prototype.transferReady = function(event, dragObj) {
	//��ǰ���ڵ�blockid���������ı༭��ʹ��
	cur_block = dragObj.body.id;
	if(cur_block == 'profile' || cur_block == 'applist') {
		$('delblock').style.display='none';
	} else {
		$('delblock').style.display='';
	}
	// �����ǰ�Ѿ����϶�ģ�飬�򷵻�
	if(true == this.isDrag) {
		return false;
	}
	// ������ʱ������λ�ã���С��
	this.transfer.style.display = '';
	this.transfer.style.position = 'absolute';
	var _LT = fetchOffset(dragObj.body);
	this.transfer.style.left = _LT['left'] + 'px';
	this.transfer.style.top = _LT['top'] + 'px';
	var _BL = parseInt(dragObj.getStyle(dragObj.body, 'border-left-width'));
	var _BR = parseInt(dragObj.getStyle(dragObj.body, 'border-right-width'));
	var _BT = parseInt(dragObj.getStyle(dragObj.body, 'border-top-width'));
	var _BB = parseInt(dragObj.getStyle(dragObj.body, 'border-bottom-width'));
	_BL = isNaN(_BL) ? 0 : _BL;
	_BR = isNaN(_BR) ? 0 : _BR;
	_BT = isNaN(_BT) ? 0 : _BT;
	_BB = isNaN(_BB) ? 0 : _BB;
	this.transfer.style.width = (dragObj.body.offsetWidth - _BL - _BR) + 'px';
	this.transfer.style.height = (dragObj.body.offsetHeight - _BT - _BB) + 'px';
	// ������ʱ��� onmouseout �¼�
	this.transfer.onmouseout = this.transferEnd.bindAsEventListener(this, dragObj);
	// ������ʱ��� onmousedown �¼�
	this.transfer.onclick = dragObj.head.onmousedown;
}
// ģ��PHP�� in_array ����
sortDrag.prototype.in_array = function(ineedle, haystack, caseinsensitive) {
	var needle = new String(ineedle);
	if(needle.Length == 0) return -1;
	if(caseinsensitive) {
		needle = needle.toLowerCase();
		for(var i in haystack)	{
			if(haystack[i].toLowerCase() == needle) {
				return i;
			}
		}
	} else {
		for(var i in haystack)	{
			if(haystack[i] == needle) {
				return i;
			}
		}
	}
	return -1;
}
// ģ�� array_push ����
sortDrag.prototype.array_push = function(arr, value) {
	arr[arr.length] = value;
	return arr.length;
}
// ���� className ����ȡ����
sortDrag.prototype.getByClass = function(obj, className) {
	if('undefined' == typeof(obj.tagName)) {
		return false;
	}
	if(-1 != this.in_array(obj.className, className)) {
		this.array_push(this.bodyHead[obj.className], obj);
	}
	if(obj.hasChildNodes()) {
		var firstNode = obj.firstChild;
		this.getByClass(firstNode, className);
		while(firstNode = firstNode.nextSibling) {
			this.getByClass(firstNode, className);
		}
	}
}