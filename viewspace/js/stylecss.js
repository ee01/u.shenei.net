/*
* ���� : ����µ���ʽrule
* ���� : styleSheets����
* ���� : var ss = new styleSheet(0);
*/
var stylecss = function(n) {
	var ss;
	if(typeof n == "number") {
		ss = document.styleSheets[n];
	}
	this.sheet = ss;
	this.rules = ss.cssRules ? ss.cssRules : ss.rules;
};

/*
* ���� : ������ʽrule���ɹ�����index,���򷵻�-1
* ���� : nΪrule����
* ���� : var ss = new styleSheet(0);
*         ss.indexOf("className")
*/
stylecss.prototype.indexof = function(selector) {
	/*ȥ�����򻻾��Ե�
	var tmpReg = new RegExp(selector, 'i');
	for(var i=0; i<this.rules.length; i++) {
		if(tmpReg.test(this.rules[i].selectorText)) {
			return i;
		}
	}
	*/
	for(i=0;i<this.rules.length;i++) {
		/*
		if(this.rules[i].selectorText.search(/.active a/i) > -1) {
		alert('---'+this.rules[i].selectorText+'---'+selector+'---');			
		}
		*/
		if(this.rules[i].selectorText.toLowerCase() == selector.toLowerCase()) {
			//alert('selector='+this.rules[i].selectorText+'---');
			return i;
		}
	}
	return -1;
};

/*
* ���� : ɾ����ʽrule
* ���� : nΪrule������������
* ���� : var ss = new styleSheet(0);
*       ss.removeRule(0) || ss.removeRule("className")
*/
stylecss.prototype.removerule = function(n) {
	if(typeof n == "number") {
		if(n < this.rules.length) {
			this.sheet.removeRule ? this.sheet.removeRule(n) : this.sheet.deleteRule(n);
		}
	} else {
		var i = this.indexof(n);
		this.sheet.removeRule ? this.sheet.removeRule(i) : this.sheet.deleteRule(i);
	}
};

stylecss.prototype.removerule_all = function(nosearch) {
	var num = this.rules.length;
	var j = 0;
	for(i=0;i<num;i++) {
		var selector = this.rules[this.rules.length - 1 - j].selectorText.toLowerCase();
		//body #banner #title #menu #footer ��Щ��ʽ��Ӧ������ʱ��ɾ��
		if(nosearch == 1 || selector.search(/^(body|#banner|#title|#menu|#footer)+?/) == -1) {
			this.sheet.removeRule ? this.sheet.removeRule(this.rules.length - 1 - j) : this.sheet.deleteRule(this.rules.length - 1 - j);
		}else{
			j++;
		}
	}
};

/*
* ���� : ����µ���ʽrule
* ���� : selector      ��ʽrule����
*       styles        ��ʽrule��style
*       n             λ��
* ���� : var ss = new styleSheet(0);
*       ss.addRule("className","color:red",0);
*/
stylecss.prototype.addrule = function(selector, styles, n) {
	if(typeof n == "undefined") {
		n = this.rules.length;
	}
	this.sheet.insertRule ? this.sheet.insertRule(selector + "{" + styles + "}", n) : this.sheet.addRule(selector, styles, n);
};

/*
* ���� : ������ʽrule���������
* ���� : selector      ��ʽrule����
*       attribute     ��ʽrule������
*       value        ָ��valueֵ
* ���� : ss.setrule("#logo", "color:", "green");
*/
stylecss.prototype.setrule = function(selector, attribute, value) {
	var i = this.indexof(selector);
	if(-1 == i) {
		return false;
	}
	this.rules[i].style[attribute] = value;
};

/*
* ���� : �����ʽrule���������
* ���� : selector      ��ʽrule����
*       attribute      ��ʽrule������
* ���� : ss.getrule("#logo", "color");
*/
stylecss.prototype.getrule = function(selector, attribute) {
	var i = this.indexof(selector);
	if(-1 == i) {
		return false;
	}
	return this.rules[i].style[attribute];
};