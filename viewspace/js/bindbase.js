/*
功能：修改 window.setTimeout，使之可以传递参数和对象参数
使用方法： setTimeout(回调函数,时间,参数1,,参数n)
*/
/*var __sto = setTimeout;
window.setTimeout = function(callback, timeout, param) {
	if('function' == typeof(callback)) {
		var args = Array.prototype.slice.call(arguments, 2);
		var _cb = function() {
			callback.apply(this, args);
		}
		return __sto(_cb, timeout);
	}
	return __sto(callback, timeout);
}*/
// 将函数作为事件监听器
Function.prototype.bindAsEventListener = function() {
	var __method = this;
	var args = Array.prototype.slice.call(arguments);
	var object = args.shift();
	return function(event) {
		var e = event || window.event;
		__method.apply(object, new Array(e).concat(args));
	}
}
// 将函数作为一个对象的方法运行
Function.prototype.bind = function() {
	var __method = this;
	var args = Array.prototype.slice.call(arguments);
	var object = args.shift();
	return function() {
		return __method.apply(object, args.concat());
	}
}