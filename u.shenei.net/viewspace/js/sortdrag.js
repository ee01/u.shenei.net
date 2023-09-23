/**
* @param:body 可移动模块的CSS样式 className 值；
* @param:head 可拖动的位置，也是 className 值；
* @param:content 鼠标移动该对象上时会显示一个遮罩层，也是 className 值；
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
	// 遮罩层对象
	this.transfer = $('transferReady');
	this.body = body;
	this.head = head;
	this.content = content;
	//  拖动对象的相关对象集合
	this.bodyHead = {};
	this.bodyHead[body] = [];
	this.bodyHead[head] = [];
	// 是否正在拖动
	this.isDrag = false;
	// 临时层对象
	// this.tmpDiv = false;
	// 移动时，插入在各列的临时层
	this.goDiv = false;
	var actionDiv = [body, head];
	if(content) {
		this.bodyHead[content] = [];
		this.array_push(actionDiv, content);
	}
	// 拖动对象的数组集合
	this.drags = [];
	// 各列对应的模块对象
	this.posObj = [];
	// 各列对象
	this.LCR = [];
	// 可拖动模块对象到各列的对照数组
	this.key2mod = [];
	// 当前所在列
	this.curLCR = null;
	// 最大最小的判断标志
	this.smallMax = 'max';
	// 从页面中搜索符合条件的对象
	this.getByClass(document.body, actionDiv);
	// 对所有可拖动对象进行初始化
	for(var i in this.bodyHead[body]) {
		this.drags[i] = new drag(this.bodyHead[body][i], '', '', '', function(obj) {
			// 该函数为回调函数
			if(false == obj.element) {
				__method.isDrag = false;
				// 把拖动的层放到适合的位置
				__method.back(obj);
				return false;
			}
			if('none' != __method.transfer.style.display) {
				//__method.transfer.style.display = 'none';
			}
			__method.isDrag = true;
			// 计算在拖动时的位置
			__method.go(obj);
		});
		// 鼠标移到该层时，动态加载一个层，以便于修改其样式
		this.bodyHead[content][i].onmouseover = this.transferReady.bindAsEventListener(this, this.drags[i]);
	}
}
// 获取各列的可拖动模块对象信息
sortDrag.prototype.getLCR = function() {
	// 判断各列及模块对象数据是否已经存在
	if(0 < this.LCR.length || 0 < this.posObj.length) {
		return true;
	}
	for(var i in this.bodyHead[this.body]) {
		_pObj = this.bodyHead[this.body][i].parentNode;
		this.LCR[_pObj.id] = _pObj;
		// 如果各列模块数组不存在，则初始化一个数组
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
	// 把需要拖动的层的中心点移到鼠标单击的点上
	obj.windowInfo['scrollTop'] = document.documentElement.scrollTop;
	obj.windowInfo['scrollLeft'] = document.documentElement.scrollLeft;
	obj.leftTop['left'] = obj.mouseX + obj.windowInfo['scrollLeft'] - (obj.body.offsetWidth / 2);
	obj.leftTop['top'] = obj.mouseY + obj.windowInfo['scrollTop'] - (obj.body.offsetHeight / 2);
	obj.body.style.left = obj.leftTop['left'] + 'px';
	obj.body.style.top = obj.leftTop['top'] + 'px';
}
// 拖动模块对象，计算当前模块应该插入哪个位置
sortDrag.prototype.go = function(obj) {
	// 模块所处列数组的索引
	var _CL = null;
	// 第一列索引
	var _CF = null;
	// 当前模块处在哪个模块之前
	var _CT = null;
	// 对象的 left、top值
	var _LT = null;
	// 被隐藏的高度
	var scrollTop = document.documentElement.scrollTop;
	this.changeSize('small', obj);
	
	// 获取各列的可拖动模块信息，规类
	this.getLCR();
	
	// 如果是处在拖动状态中，则把临时层放到被拖动层的上面
		/*
	if('absolute' == this.transfer.style.position) {
		this.insertAfter(this.transfer, obj.body);
		if(obj.body.parentNode.lastChild)
		obj.body.parentNode.insertBefore(this.transfer, obj.body);
		this.transfer.style.position = 'static';
	}
		*/
	// 获取可拖动模块所处的列
	if(null == this.curLCR) {
		for(var i in this.bodyHead[this.body]) {
			if(obj.body == this.bodyHead[this.body][i]) {
				this.curLCR = this.key2mod[i];
			}
		}
	}
	// 获取当前鼠标位置处在哪个栏目
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
	// 获取用户的垂直位置
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
	// 如果临时层不存在
	if(false == this.goDiv) {
		this.goDiv = document.createElement('div');
		this.goDiv.className = 'transfer';
		this.goDiv.style.display = '';
		this.goDiv.style.height = obj.body.clientHeight + 'px';
	}
	// 插入临时DIV
	if(null != _CT) {
		// 如果当前模块处在某列之前
		this.bodyHead[this.body][_CT].parentNode.insertBefore(this.goDiv, this.bodyHead[this.body][_CT]);
		this.goDiv.style.width = 'auto';
	} else {
		// 如果当前模块处在某列最后
		this.LCR[_CL].appendChild(this.goDiv);
		this.goDiv.style.width = 'auto';
	}
}
// 从一个数组中移除某个键值对应的数组单元
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
// 处理用户松开鼠标后的事件，即把拖动出来的模块放到对应的位置中
sortDrag.prototype.back = function(obj) {
	// 如果鼠标释放，则隐藏遮罩层
	this.transfer.style.display = 'none';
	// 在临时层之前插入被拖动的模块，清除临时层对象
	this.goDiv.parentNode.insertBefore(obj.body, this.goDiv);
	this.goDiv.parentNode.removeChild(this.goDiv);
	// 被拖动模块的定位为自动、宽度为自动
	obj.body.style.position = 'static';
	obj.body.style.width = 'auto';
	obj.body.style.height = 'auto';
	// 获取模块所处的列
	_pObj = obj.body.parentNode;
	// 读取 left、top
	_pLT = fetchOffset(_pObj);
	var key = null;
	// 获取被拖动模块的序号
	for(var i in this.bodyHead[this.body]) {
		if(obj.body == this.bodyHead[this.body][i]) {
			key = i;
		}
	}
	// 从模块和列对应的数组中，剔除当前模块
	this.posObj[this.curLCR] = this.remove(this.posObj[this.curLCR], key);
	// 从模块和列ID对应数组中剔除当前模块
	this.key2mod = this.remove(this.key2mod, key);
	// 把当前模块重新加入到相应数组中
	this.key2mod[key] = _pObj.id;
	this.posObj[_pObj.id][key] = obj.body;
	// 置空当前模块所处列
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
// 如果鼠标移除模块时触发
sortDrag.prototype.transferEnd = function(event, dragObj) {
	var scrollTop = document.documentElement.scrollTop;
	var scrollLeft = document.documentElement.scrollLeft;
	var x = parseInt(event.clientX) + scrollLeft;
	var y = parseInt(event.clientY) + scrollTop;
	var _LT = fetchOffset(dragObj.body);
	// 判断鼠标坐标值是否处在当前的模块中，如果出了当前范围，则隐藏该临时层
	if(_LT['left'] > x || _LT['top'] > y || _LT['left'] + dragObj.body.offsetWidth < x || _LT['top'] + dragObj.body.offsetHeight < y) {
		this.transfer.style.display = 'none';
	}
}
// 弹出层渐变准备
sortDrag.prototype.transferReady = function(event, dragObj) {
	//当前所在的blockid，供弹出的编辑框使用
	cur_block = dragObj.body.id;
	if(cur_block == 'profile' || cur_block == 'applist') {
		$('delblock').style.display='none';
	} else {
		$('delblock').style.display='';
	}
	// 如果当前已经在拖动模块，则返回
	if(true == this.isDrag) {
		return false;
	}
	// 计算临时渐变层的位置，大小等
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
	// 监听临时层的 onmouseout 事件
	this.transfer.onmouseout = this.transferEnd.bindAsEventListener(this, dragObj);
	// 监听临时层的 onmousedown 事件
	this.transfer.onclick = dragObj.head.onmousedown;
}
// 模拟PHP的 in_array 函数
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
// 模拟 array_push 函数
sortDrag.prototype.array_push = function(arr, value) {
	arr[arr.length] = value;
	return arr.length;
}
// 根据 className 来读取对象
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