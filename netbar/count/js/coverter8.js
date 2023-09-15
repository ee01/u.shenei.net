function checkNum(str) {
	for (var i=0; i<str.length; i++) {
		var ch = str.substring(i, i + 1)
		if (ch!="." && ch!="+" && ch!="-" && ch!="e" && ch!="E" && (ch < "0" || ch > "9")) {
			alert("请输入有效的数字");
			return false;
		}
	}
	return true
}
var press_data = new PRESS_MEASURES();
function normalize(what,digits) {
	theInput = String(what);
	outputDigits = parseInt(digits);
	var theOutput;
	var theShift;
	var outputExp = '';
	var outputSign = '';
	if( theInput.indexOf("e") != -1) {
		outputExp = theInput.substring(theInput.indexOf("e"), theInput.length);
		theInput = theInput.substring(0, theInput.indexOf("e"));
	} else {
		if ( theInput.indexOf("E") != -1) {
			outputExp = theInput.substring(theInput.indexOf("E"), theInput.length);
			theInput = theInput.substring(0, theInput.indexOf("e"));
		}
		if(theInput.substring(0, 1) == '-') {
			outputSign = '-';
			theInput = theInput.substring(1, theInput.length);
		}
	}
	if(parseFloat(theInput) >= 1.0) {
		var pointPos = theInput.indexOf(".");
		if(pointPos == -1) pointPos = theInput.length;
		theShift = pointPos - outputDigits;
	} else {
		var notNull = String(theInput).lastIndexOf('.') + 1;
		if(notNull == 1 ) notNull += 1;
		while(String(theInput).charAt(notNull) == '0') {
			notNull++;
		}
		notNull -= 2;
		theShift = -(outputDigits + notNull);
	}
	theOutput = Math.round(theInput/Math.pow(10, theShift));
	if(theShift >= 0) {
		for(var i = 1; i <= theShift; i++) {
			theOutput += '0';
		}
	} else {
		theOutput += '';
		if(theOutput.length+theShift > 0) {
			theOutput = theOutput.substring(0, theOutput.length+theShift) + '.' + theOutput.substring((theOutput.length + theShift), theOutput.length);
		} else {
			var theOutput1 = '0.';
			for(var i =-1; i >= theOutput.length + theShift; i--) {
				theOutput1 += '0';
			}
			theOutput = theOutput1 + theOutput;
		}
		while(theOutput.charAt(theOutput.length - 1) == '0') {
			theOutput = theOutput.substring(0, theOutput.length - 1);
		}
		if(theOutput.charAt(theOutput.length-1) == '.') {
			theOutput = theOutput.substring ( 0, theOutput.length-1 );
		}
	}
	theOutput = outputSign + theOutput + outputExp;
	return theOutput;
}
function compute(obj,val,data) {
	if (obj[val].value) {
		var uval=0;
		uval = obj[val].value*data[val];
		if( (uval >= 0) && (String(obj[val].value).charAt(0) == '-') ) uval = -uval;
		for (var i in data) obj[i].value=normalize(uval/data[i],8);
	}
}
function PRESS_MEASURES() {
	this.mKilopascal = 1000
	this.mHectopascal = 100
	this.mPascal = 1
	this.mBar = 100000
	this.mMillibar = 100
	this.mAtm = 101325
	this.mMillimeter_Hg = this.mAtm / 760
	this.engInch_Hg = 25.4 * this.mMillimeter_Hg
	this.engPound_sq_inch = 6894.757 
	this.engPound_sq_foot = this.engPound_sq_inch / 144
	this.xpressKg_sq_cm = 98066.5
	this.xpressKg_sq_m = 9.80665
	this.mmmH2O = 1/0.101972
}
function resetAll(form) {
	resetValues(form,press_data);
}
