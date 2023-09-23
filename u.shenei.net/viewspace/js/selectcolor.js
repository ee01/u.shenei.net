// ��ɫѡ����
/**
* @param:callback �ص�����
*/
selectColor = function(callback) {
	var __method = this;
	if('undefined' == typeof(callback) || '' == callback) {
		callback = null;
	}
	//�ص�����
	this.nocallback = 0;
	// �ص�����
	this.callback = callback;
	// ��ߵ���ɫѡ��
	this.crossHairObj = $('clrCrosshairs');
	// �ұߵ�ɫϵѡ��
	this.rangeArrowObj = $('clrRangeArrows');
	// Ԥ���Ķ����
	this.previewObj = $('clrPreview');
	// ��ߵ���ɫ������
	this.saturationObj = $('clrColor');
	// ��ɫֵ��ʾ��Ķ���
	this.iptObj = $('clrColorValue');
	// �ұߵ�ɫϵ������
	this.hueObj = $('clrHue');
	// ��ɫѡ��ʱ���϶���Χ
	this.hueColorObj = $('clrBGColor');
	// RGB��ɫֵ����
	this.rgb = {};
	// HSV��ɫֵ����
	this.hsv = {};
	// ����������ֵ�Ƿ��б仯��������г�ʼ��
	this.iptObj.onchange = this.init.bindAsEventListener(this);
	// ��ʼ��ɫϵѡ���϶���
	this.rangeArrowDrag = new drag('clrRangeArrows', '', 'clrHue', 'vertical', function(obj) {
		// �˺���Ϊ�ص�����
		__method.hsv.h = Math.abs(parseInt(obj.body.style.top)) / 199;
		__method.hsvChange();
	}, 1);
	// �����ʽ�ָ�ΪĬ��
	this.rangeArrowDrag.cursor = 'default';
	// ��ʼ����ɫѡ���϶���
	this.crossHairDrag = new drag('clrCrosshairs', '', 'clrColor', '', function(obj) {
		__method.hsv.s = 1 - (Math.abs(parseInt(obj.body.style.top)) / 199);
		__method.hsv.v = (Math.abs(parseInt(obj.body.style.left)) / 199);
		__method.hsvChange();
	}, 1);
	// �����ʽ�ָ�ΪĬ��
	this.crossHairDrag.cursor = 'default';
	// ��ʼ��
	this.init();
}
// ���ɫϵ���ı���
selectColor.prototype.hsvChange = function() {
	// ���ݵ�ǰ���������RGB����ɫֵ
	this.rgb = rgbHexHsv.prototype.hsv2rgb(this.hsv.h, this.hsv.s, this.hsv.v);
	// �ı���ɫ
	this.colorChanged();
}
// ��ʼ��
selectColor.prototype.init = function() {
	// ��ʼ�����϶�ͼ�꣬Ϊ�˼���IE
	this.fixPNG('viewspace/img/tool/sv.png', 'clrSv', this.hueColorObj);
	this.fixPNG('viewspace/img/tool/h.png', 'clrH', this.hueObj);
	// ��ȡ��ɫֵ
	this.color = this.iptObj.value;
	if('' == this.color) {
		this.color = '#FFFF00';
		this.iptObj.value = '#FFFF00';
		this.nocallback = 1;
	}
	// 16������ɫֵת����RGB��ɫֵ
	this.rgb = rgbHexHsv.prototype.hex2rgb(this.color, {r:0, g:0, b:0});
	// RGB����ɫ�ı�
	this.rgbChanged();
}
// Ϊ�˼��ݱ�̬��IE
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
// RGB��ɫֵ�ı�
selectColor.prototype.rgbChanged = function() {
	// ����RGB����ɫ�ı䣬����HSV��ɫֵ
	this.hsv = rgbHexHsv.prototype.rgb2hsv(this.rgb.r, this.rgb.g, this.rgb.b);
	// �ı���ɫ
	this.colorChanged();
}
selectColor.prototype.colorChanged = function() {
	var __method = this;
	// ����ʮ��������ɫֵ
	var hex = rgbHexHsv.prototype.rgb2hex(this.rgb.r, this.rgb.g, this.rgb.b);
	// ����ɫϵ��RGB��ɫֵ
	var hueRgb = rgbHexHsv.prototype.hsv2rgb(this.hsv.h, 1, 1);
	// ����ɫϵ��ʮ��������ɫֵ
	var hueHex = rgbHexHsv.prototype.rgb2hex(hueRgb.r, hueRgb.g, hueRgb.b);
	// �ı�Ԥ������ı�����ɫ
	this.previewObj.style.background = hex;
	// �ı���������ɫֵ
	this.iptObj.value = hex;
	// �ı��ɫ���ı�����ɫ
	this.hueColorObj.style.background = hueHex;
	// ��ɫѡ������
	this.crossHairObj.style.left = (this.hsv.v * 199).toString() + 'px';
	this.crossHairObj.style.top = ((1 - this.hsv.s) * 199).toString() + 'px';
	// ɫϵѡ������
	this.rangeArrowObj.style.left = '-8px';
	this.rangeArrowObj.style.top = (this.hsv.h * 199).toString() + 'px';
	// ��ʾ
	if('none' == this.crossHairObj.style.display) {
		this.crossHairObj.style.display = '';
	}
	if('none' == this.rangeArrowObj.style.display) {
		this.rangeArrowObj.style.display = '';
	}
	// ���ûص�����
	if(this.callback && this.nocallback == 0) {
		this.callback(__method);
	}
	if(this.nocallback != 0) {//����ֻ��һ����Ч
		this.nocallback = 0;
	}
}