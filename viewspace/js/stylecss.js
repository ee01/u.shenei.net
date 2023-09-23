/*
* 描述 : 添加新的样式rule
* 参数 : styleSheets索引
* 代码 : var ss = new styleSheet(0);
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
* 描述 : 查找样式rule，成功返回index,否则返回-1
* 参数 : n为rule名称
* 代码 : var ss = new styleSheet(0);
*         ss.indexOf("className")
*/
stylecss.prototype.indexof = function(selector) {
	/*去掉正则换绝对的
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
* 描述 : 删除样式rule
* 参数 : n为rule索引或者名称
* 代码 : var ss = new styleSheet(0);
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
		//body #banner #title #menu #footer 这些样式在应用所有时不删除
		if(nosearch == 1 || selector.search(/^(body|#banner|#title|#menu|#footer)+?/) == -1) {
			this.sheet.removeRule ? this.sheet.removeRule(this.rules.length - 1 - j) : this.sheet.deleteRule(this.rules.length - 1 - j);
		}else{
			j++;
		}
	}
};

/*
* 描述 : 添加新的样式rule
* 参数 : selector      样式rule名称
*       styles        样式rule的style
*       n             位置
* 代码 : var ss = new styleSheet(0);
*       ss.addRule("className","color:red",0);
*/
stylecss.prototype.addrule = function(selector, styles, n) {
	if(typeof n == "undefined") {
		n = this.rules.length;
	}
	this.sheet.insertRule ? this.sheet.insertRule(selector + "{" + styles + "}", n) : this.sheet.addRule(selector, styles, n);
};

/*
* 描述 : 设置样式rule具体的属性
* 参数 : selector      样式rule名称
*       attribute     样式rule的属性
*       value        指定value值
* 代码 : ss.setrule("#logo", "color:", "green");
*/
stylecss.prototype.setrule = function(selector, attribute, value) {
	var i = this.indexof(selector);
	if(-1 == i) {
		return false;
	}
	this.rules[i].style[attribute] = value;
};

/*
* 描述 : 获得样式rule具体的属性
* 参数 : selector      样式rule名称
*       attribute      样式rule的属性
* 代码 : ss.getrule("#logo", "color");
*/
stylecss.prototype.getrule = function(selector, attribute) {
	var i = this.indexof(selector);
	if(-1 == i) {
		return false;
	}
	return this.rules[i].style[attribute];
};