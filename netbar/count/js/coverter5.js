function HEAT_MEASURES() {
	this.Joule = 1
	this.Kgm = 9.80392157
	this.Psh = 2647603.9184538
	this.Hph = 2684563.7583893
	this.Kwh = 3599712.023038157
	this.Kcal = 4185.851820846
	this.Btu = 1055.0749103
	this.Ftlb = 1.3557483731
}
var heat_data = new HEAT_MEASURES();
function checkNum(str) {
	for (var i=0; i<str.length; i++) {
		var ch = str.substring(i, i + 1)
		if (ch!="." && ch!="+" && ch!="-" && ch!="e" && ch!="E" && (ch < "0" || ch > "9")) {
			alert("��������Ч������");
			return false;
		}
	}
	return true
}
function normalize(what,digits) {
	var str=""+what;
	var pp=Math.max(str.lastIndexOf("+"),str.lastIndexOf("-"));
	var idot=str.indexOf(".");
	if (idot>=1) {
		var ee=(pp>0)?str.substring(pp-1,str.length):"";
		digits+=idot;
		if (digits>=str.length)
			return str;
		if (pp>0 && digits>=pp)
			digits-=pp;
		var c=eval(str.charAt(digits));
		var ipos=digits-1;
		if (c>=5) {
			while (str.charAt(ipos)=="9")
				ipos--;
			if (str.charAt(ipos)==".") {
				var nc=eval(str.substring(0,idot))+1;
				if (nc==10 && ee.length>0) {
					nc=1;
					ee="e"+(eval(ee.substring(1,ee.length))+1);
				}
				return ""+nc+ee;
			}
			return str.substring(0,ipos)+(eval(str.charAt(ipos))+1)+ee;
		}
		else
			var ret=str.substring(0,digits)+ee;
		for (var i=0; i<ret.length; i++)
				if (ret.charAt(i)>"0" && ret.charAt(i)<="9")
					return ret;
		return str;
	}
	return str;
}
function compute(obj,val,data) {
	if (obj[val].value) {
		var uval=0;
		uval = obj[val].value*data[val];
		if( (uval >= 0) && (obj[val].value.indexOf("-") != -1) ) {
			uval = -uval;
		}
		for (var i in data)
			obj[i].value=normalize(uval/data[i],8);
	}
}
function resetValues(form,data) {
		for (var i in data)
			form[i].value="";
}
function resetAll(form) {
	resetValues(form,heat_data);
}
