// 颜色选择类
/**
* @param:callback 回调函数
*/
selectColor = function(callback) {
	var __method = this;
	if('undefined' == typeof(callback) || '' == callback) {
		callback = null;
	}
	//回调开关
	this.nocallback = 0;
	// 回调函数
	this.callback = callback;
	// 左边的颜色选择
	this.crossHairObj = $('clrCrosshairs');
	// 右边的色系选择
	this.rangeArrowObj = $('clrRangeArrows');
	// 预览的对象层
	this.previewObj = $('clrPreview');
	// 左边的颜色背景层
	this.saturationObj = $('clrColor');
	// 颜色值显示框的对象
	this.iptObj = $('clrColorValue');
	// 右边的色系背景层
	this.hueObj = $('clrHue');
	// 颜色选择时的拖动范围
	this.hueColorObj = $('clrBGColor');
	// RGB颜色值对象
	this.rgb = {};
	// HSV颜色值对象
	this.hsv = {};
	// 监听输入框的值是否有变化，有则进行初始化
	this.iptObj.onchange = this.init.bindAsEventListener(this);
	// 初始化色系选择拖动层
	this.rangeArrowDrag = new drag('clrRangeArrows', '', 'clrHue', 'vertical', function(obj) {
		// 此函数为回调函数
		__method.hsv.h = Math.abs(parseInt(obj.body.style.top)) / 199;
		__method.hsvChange();
	}, 1);
	// 鼠标样式恢复为默认
	this.rangeArrowDrag.cursor = 'default';
	// 初始化颜色选择拖动层
	this.crossHairDrag = new drag('clrCrosshairs', '', 'clrColor', '', function(obj) {
		__method.hsv.s = 1 - (Math.abs(parseInt(obj.body.style.top)) / 199);
		__method.hsv.v = (Math.abs(parseInt(obj.body.style.left)) / 199);
		__method.hsvChange();
	}, 1);
	// 鼠标样式恢复为默认
	this.crossHairDrag.cursor = 'default';
	// 初始化
	this.init();
}
// 如果色系被改变了
selectColor.prototype.hsvChange = function() {
	// 根据当前的坐标计算RGB的颜色值
	this.rgb = rgbHexHsv.prototype.hsv2rgb(this.hsv.h, this.hsv.s, this.hsv.v);
	// 改变颜色
	this.colorChanged();
}
// 初始化
selectColor.prototype.init = function() {
	// 初始化可拖动图标，为了兼容IE
	this.fixPNG('viewspace/img/tool/sv.png', 'clrSv', this.hueColorObj);
	this.fixPNG('viewspace/img/tool/h.png', 'clrH', this.hueObj);
	// 读取颜色值
	this.color = this.iptObj.value;
	if('' == this.color) {
		this.color = '#FFFF00';
		this.iptObj.value = '#FFFF00';
		this.nocallback = 1;
	}
	// 16进制颜色值转换成RGB颜色值
	this.rgb = rgbHexHsv.prototype.hex2rgb(this.color, {r:0, g:0, b:0});
	// RGB的颜色改变
	this.rgbChanged();
}
// 为了兼容变态的IE
selectColor.prototype.fixPNG = function(imgsrc, curid, parentObj) {
	if($(curid)) {
		return false;
	}
	if(is_ie && 7 > is_ie && document.body.filters) {
		var tmpDiv = document.createElement('div');
		tmpDiv.id = curid;
		tmpDiv.style.setAttribute('filter', "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + imgsrc + "', sizingMethod='scale')");
		parentObj.appendChild(tmpDiv);
	} else {
		var tmpImg = document.createElement('img');
		tmpImg.id = curid;
		tmpImg.galleryimg = 'false';
		tmpImg.src = imgsrc;
		parentObj.appendChild(tmpImg);
	}
}
// RGB颜色值改变
selectColor.prototype.rgbChanged = function() {
	// 根据RGB的颜色改变，计算HSV颜色值
	this.hsv = rgbHexHsv.prototype.rgb2hsv(this.rgb.r, this.rgb.g, this.rgb.b);
	// 改变颜色
	this.colorChanged();
}
selectColor.prototype.colorChanged = function() {
	var __method = this;
	// 计算十六进制颜色值
	var hex = rgbHexHsv.prototype.rgb2hex(this.rgb.r, this.rgb.g, this.rgb.b);
	// 计算色系的RGB颜色值
	var hueRgb = rgbHexHsv.prototype.hsv2rgb(this.hsv.h, 1, 1);
	// 计算色系的十六进制颜色值
	var hueHex = rgbHexHsv.prototype.rgb2hex(hueRgb.r, hueRgb.g, hueRgb.b);
	// 改变预览对象的背景颜色
	this.previewObj.style.background = hex;
	// 改变输入框的颜色值
	this.iptObj.value = hex;
	// 改变混色器的背景颜色
	this.hueColorObj.style.background = hueHex;
	// 颜色选择坐标
	this.crossHairObj.style.left = (this.hsv.v * 199).toString() + 'px';
	this.crossHairObj.style.top = ((1 - this.hsv.s) * 199).toString() + 'px';
	// 色系选择坐标
	this.rangeArrowObj.style.left = '-8px';
	this.rangeArrowObj.style.top = (this.hsv.h * 199).toString() + 'px';
	// 显示
	if('none' == this.crossHairObj.style.display) {
		this.crossHairObj.style.display = '';
	}
	if('none' == this.rangeArrowObj.style.display) {
		this.rangeArrowObj.style.display = '';
	}
	// 调用回调函数
	if(this.callback && this.nocallback == 0) {
		this.callback(__method);
	}
	if(this.nocallback != 0) {//开关只第一次有效
		this.nocallback = 0;
	}
}