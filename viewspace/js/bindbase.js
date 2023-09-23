/*
���ܣ��޸� window.setTimeout��ʹ֮���Դ��ݲ����Ͷ������
ʹ�÷����� setTimeout(�ص�����,ʱ��,����1,,����n)
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
// ��������Ϊ�¼�������
Function.prototype.bindAsEventListener = function() {
	var __method = this;
	var args = Array.prototype.slice.call(arguments);
	var object = args.shift();
	return function(event) {
		var e = event || window.event;
		__method.apply(object, new Array(e).concat(args));
	}
}
// ��������Ϊһ������ķ�������
Function.prototype.bind = function() {
	var __method = this;
	var args = Array.prototype.slice.call(arguments);
	var object = args.shift();
	return function() {
		return __method.apply(object, args.concat());
	}
}