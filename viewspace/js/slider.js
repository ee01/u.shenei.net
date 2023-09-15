/**
* 一个滑入滑出的动态效果；
* @param:slide 需要滑动的层ID；
* @param:direction 滑动方向(in/out)；
* @param:callback 回调函数；
*/
function sliderEffect(slide, direction, callback) {
	if('undefined' == typeof(slide) || '' == slide) {
		return false;
	}
	if('undefined' == typeof(direction) || '' == direction) {
		direction = null;
	}
	if('undefined' == typeof(callback) || '' == callback) {
		callback = null;
	}
	this.callback = callback;
	this.slide = $(slide);
	this.direction = direction;
	var __method = this;
	// 变化的间隔事件，单位为 ms
	this.timer = 7;
	// 从最小到最大的最大变化次数
	this.steps = 20;
	this.curstep = 0;
	this.stepY = 0;
	this.pNode = null;
	this.interval = null;
	this.curstep = 0;
	this.pNode = this.slide.parentNode;
	if('in' == this.direction) {
		this.stepY = this.slide.offsetHeight / this.steps;
	} else {
		this.stepY = this.pNode.offsetHeight / this.steps;
	}
	this.interval = setInterval(function(){
		__method.curstep ++;
		if('in' == __method.direction) {
			__method.pNode.style.height = (__method.curstep * __method.stepY) + 'px';
		} else {
			__method.pNode.style.height = ((this.steps - this.curstep) * __method.stepY) + 'px';
		}
		if(__method.curstep >= __method.steps) {
			__method.pNode = null;
			__method.curstep = 0;
			__method.stepY = 0;
			if('out' == __method.direction) {
				__method.slide.style.display = 'none';
			}
			clearInterval(__method.interval);
			if(__method.callback) {
				__method.callback();
			}
		}
	}, this.timer);
}

function slider(slideOut, slideIn) {
	if('undefined' == typeof(slideIn) || '' == slideIn) {
		slideIn = null;
	}
	if('undefined' == typeof(slideOut) || '' == slideOut) {
		slideOut = null;
	}
	var __method = this;
	this.slideIn = slideIn;
	this.slideOut = slideOut;
	if(this.slideOut) {
		sliderEffect(this.slideOut, 'out', function() {
			if(__method.slideIn) {
				$(__method.slideIn).style.display = '';
				sliderEffect(__method.slideIn, 'in');
			}
		});
	} else if(this.slideOut) {
		sliderEffect(this.slideOut, 'out');
	}
}