/**
* @param:sourceId 激活弹出效果的按钮对象的ID；
* @param:dstId 最后弹出的层的ID；
* @param:closeId 弹出层中，关闭按钮的样式 className 值或是ID值；
*/
transfer = function(sourceId, dstId, closeId, dragheader) {
	if('undefined' == typeof(sourceId) || '' == sourceId) {
		return false;
	}
	if('undefined' == typeof(dstId)) {
		dstId = sourceId + '_div';
	}
	if('undefined' == typeof(closeId)) {
		closeId = null;
	}
	// 变化的间隔事件，单位为 ms
	this.timer = 7;
	// 从最小到最大的最大变化次数
	this.steps = 20;
	this.dstObj = $(dstId);
	this.sourceObj = $(sourceId);
	this.closeObj = $(closeId);
	this.trandiv = new drag(this.dstObj, dragheader);
	this.trandiv.cursor = 'move';
	this.dstObj.style.display = '';
	this.dstObj_H = this.dstObj.offsetHeight;
	this.dstObj_W = this.dstObj.offsetWidth;
	this.dstObj.style.display = 'none';
	
	if(this.sourceObj && this.dstObj) {
		// 监听激活按钮的 onmousedown 事件
		this.sourceObj.onmousedown = this.maxEffect.bindAsEventListener(this);
		// 如果关闭按钮的ID不存在，则搜索匹配 className 值
		if(!this.closeObj) {
			this.getByClass(this.dstObj, closeId);
		}
		// 如果关闭对象存在，则监听其 onclick 事件
		if(this.closeObj) {
			this.closeObj.onclick = this.minEffect.bindAsEventListener(this);
		}
	}
}
// 计算每步变化的数值及最终对象的 left、top 值
transfer.prototype.getDistance = function() {
	var y = (this.windowInfo['height'] - parseInt(this.dstObj_H)) / 2;
	var x = (this.windowInfo['width'] - parseInt(this.dstObj_W)) / 2;
	if(0 > y) {
		y = 0;
	}
	if(0 > x) {
		x = 0;
	}
	var distancex = x - this.mouseX;
	var distancey = y - this.mouseY;
	x += this.scrollLeft;
	y += this.scrollTop;
	
	return {'x':x, 'y':y, 'distancex':distancex, 'distancey':distancey};
}
// 小到大的效果
transfer.prototype.maxEffect = function(event) {
	// 判断目标对象是否存在
	if('none' != this.dstObj.style.display) {
		return false;
	}

	if(this.sourceObj.id == 'bannerbg') {
		window.scrollTo(0,0);
	} else if(this.sourceObj.id == 'footerbg') {
		window.scrollTo(0,document.body.offsetHeight);
	}
	var __method = this;
	event = event || window.event;
	if(this.dstObj.id == 'fastdiy' || this.dstObj.id == 'selectshare') {
		if(stylenum > 19) {
			alert("您的模板数量最多20个，已经达到上限。\r\n请到“我的模板”至少删除1个模板记录。");
			$('mystylelist_sw').onmousedown({type: 'mousedown'});
			return false;
		}
	}
	this.curstep = 0;
	this.scrollTop = document.documentElement.scrollTop;
	this.scrollLeft = document.documentElement.scrollLeft;
	this.windowInfo = this.getWindowInfo();
	if(typeof(event.button) == 'number') {
		this.mouseX = parseInt(event.clientX);
		this.mouseY = parseInt(event.clientY);
	} else {
		this.mouseX = 1036;
		this.mouseY = 35;
	}
	// 计算每步需要变化的长、宽
	this.distance = this.getDistance();
	this.stepX = this.distance['distancex'] / this.steps;
	this.stepY = this.distance['distancey'] / this.steps;
	this.stepDivX = parseInt(this.dstObj_W) / this.steps;
	this.stepDivY = parseInt(this.dstObj_H) / this.steps;
	// 创建临时层
	this.divObj = document.createElement('div');
	this.divObj.className = 'transfer';
	this.divObj.style.display = '';
	this.divObj.style.position = 'absolute';
	// 把新创建的层加入到页面中
	document.body.appendChild(this.divObj);
	// 处理IE7以下版本的一个变态BUG
	//if(is_ie && is_ie < 7) {
		//this.iframe = true;
		if(!$('qwert')) {
			var iframe = document.createElement('div');
			iframe.id = 'qwert';
			iframe.style.zIndex = 998;
			iframe.style.display = 'none';
			iframe.style.backgroundColor="#fff";
			iframe.style.opacity="0";
			iframe.style.position = 'absolute';
			iframe.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';
			$('append_parent') ? $('append_parent').appendChild(iframe) : menuObj.parentNode.appendChild(iframe);
		}
		$('qwert').style.top = 0;
		$('qwert').style.left = 0;
		$('qwert').style.width = document.body.clientWidth + 'px';
		$('qwert').style.height = document.body.clientHeight + 70 + 'px';
		$('qwert').style.display = 'block';
	//}
	this.interval = setInterval(function(){
		__method.showDiv();
	}, this.timer);
	// 为了兼容Firefox中对图片的拖动；防止事件冒泡
	if(typeof(event.button) == 'number') {
		doane(event);		
	}
}
// 计算当前对象的大小、坐标，并显示
transfer.prototype.showDiv = function() {
	this.curstep += 1;
	var _T = this.curstep * this.stepY;
	var _L = this.curstep * this.stepX;
	var _W = this.stepDivX * this.curstep;
	var _H = this.stepDivY * this.curstep;
	if(cur_transferdiv != '') {
		$(cur_transferdiv).style.display = 'none';
	}
	if(this.dstObj.id != 'selectbg_div' && this.dstObj.id != 'sharestyle') {
		cur_transferdiv = this.dstObj.id;		
	}
	this.divObj.style.left = (this.scrollLeft + this.mouseX + _L) + 'px';
	this.divObj.style.top = (this.scrollTop + this.mouseY + _T) + 'px';
	//$('showData').innerHTML += (this.scrollLeft + this.mouseX + _L) + ',' + (this.scrollTop + this.mouseY + _T) + '<br>';
	this.divObj.style.width = _W + 'px';
	this.divObj.style.height = _H + 'px';
	this.divObj.style.zIndex = 2008;
	// 如果已经到最后一步时，则删除临时节点、清除定时器
	if(this.steps <= this.curstep) {
		this.dstObj.style.display = '';
		this.dstObj.style.position = 'absolute';
		this.dstObj.style.left = this.distance['x'] + 'px';
		this.dstObj.style.top = this.distance['y'] + 'px';
		$('clrMain').style.left = this.distance['x'] + 80 + 'px';
		$('clrMain').style.top = this.distance['y'] + 80 + 'px';
		clearInterval(this.interval);
		document.body.removeChild(this.divObj);
		
		if(cur_transferdiv != '') oldblockstyle = [];
		if(this.dstObj.id == 'fastdiy') {
			contentdiv = 'fastheaderbg';
			$('fastdiysub').style.display = 'none';
			$('faststyle').value == '';
			bgtype = '2';
			//bg_color = '1';
			//getpage(1, 'viewspace.php?do=getsyspic&type='+bgtype+'&color='+bg_color+'&inajax=1');
		}
		if(this.dstObj.id == 'selectshare') {
			bg_color = bgtype = '';
			contentdiv = 'sharelist';
			getpage(1, 'viewspace.php?do=getsharestyle&type='+bgtype+'&color='+bg_color+'&inajax=1');
		}
		if(this.dstObj.id == 'smusicp') {
			bgtype = 7;
			contentdiv = 'smusicplist';
			getpage(1, 'viewspace.php?do=getsyspic&type='+bgtype+'&inajax=1');
		}
		if(this.dstObj.id == 'mystyles') {
			getstylelist('viewspace.php?do=mystyle');
		}
		if(this.dstObj.id == 'setcursor') {
			bgtype = 6;
			contentdiv = 'cursorlist';
			getpage(1, 'viewspace.php?do=getsyspic&type='+bgtype+'&inajax=1');
		}
		if(this.dstObj.id == 'selectbg_div') {	
			if(this.sourceObj.id == 'bodybg') {
				bgtype = '1';
				$('selectbgtitle').innerHTML = '大背景图';
			}else if(this.sourceObj.id == 'bannerbg') {
				bgtype = '2';
				$('selectbgtitle').innerHTML = '头部图片';
			}else if(this.sourceObj.id == 'menu_setbg') {
				bgtype = '3';
				$('selectbgtitle').innerHTML = '导航图片';
			}else if(this.sourceObj.id == 'footerbg') {
				bgtype = '';
				$('selectbgtitle').innerHTML = '尾部图片';
			}else if(this.sourceObj.id == 'smusicp') {
				bgtype = '7';
				contentdiv = 'smusicplist';
				$('selectbgtitle').innerHTML = '播放器';
			}
			
			if(this.sourceObj.id == 'selectbg_t') {
				bgtype = '3';
				$('selectbgtitle').innerHTML = '模块标题背景';
			}else if(this.sourceObj.id == 'selectbg_c'){
				bgtype = '1';
				$('selectbgtitle').innerHTML = '模块主体背景';
			}
			showbgdiv('syspic', 'syspicli');
			getpage(1, 'viewspace.php?do=getsyspic&type='+bgtype+'&inajax=1');
		}
		if(this.dstObj.id == 'menu_set') {
			cur_block = 'menu';
		}
		
		if(this.dstObj.id == 'title_set') {
			cur_block = 'banner';
		}

		if(cur_transferdiv != 'tsetblock' && cur_transferdiv != 'menu_set' && this.dstObj.id == 'selectbg_div') {
			//不是模块编辑里弹出的背景选择框
			if(this.sourceObj.id == 'bodybg' || this.sourceObj.id == 'footerbg' || this.sourceObj.id == 'bannerbg') cur_block = this.sourceObj.id.slice(0, -2);
			$('selectbg_sub').style.display = '';
			$('cancel_bg').style.display = '';
			if(cur_block == 'footer') $('setfooter').style.display = '';
			if(cur_block == 'banner') $('setbannerheight').style.display = '';
			if(cur_block == 'body') {
				$('setbodybgstate').style.display = '';
				$('bgsetplace').style.display = 'none';
			}
			cur_bclass = '';
			
			var bgimg = getstyle("", 'backgroundImage');//主体背景、类型、位置和颜色
			$('other_oldbgimg').value = bgimg;
			var bgrepeat = getstyle("", 'backgroundRepeat');
			setselected('other_bgrepeat', bgrepeat);
			var bgposition = getstyle("", 'backgroundPosition');
			var selectp = 0;
			for(var p in place) {
				if(place[p] == bgposition) selectp = p;
			}
			bc_tdclass = tdclass = [];
			gettdclass($(this.dstObj.id), 'wcc0 iw0', 'selected');
			bc_tdclass = tdclass;
			bc_selectthis(selectp, 1);
			var bc_bgcolor = getstyle("", 'backgroundColor');
			$('other_bgcolor').value = bc_bgcolor;
			chgctrlcolor('other_bgcolor');
			if(cur_block == 'footer') {
				var footerfontcolor = getstyle("copyright p", 'color');	//尾部颜色
				$('footerfontcolor').value = footerfontcolor;
				chgctrlcolor('footerfontcolor');
			
				var footerheight = getstyle("", 'height'); //尾部高度读取
				if($('footerheight')) $('footerheight').value = footerheight;
				footerheight = (footerheight - 30) / 370 * 100;  
				$('sliderHfooter').style.left = footerheight+'px';				
			}
			if(cur_block == 'banner') {
				var bannerheight = getstyle("", 'height'); //头部高度读取
				if($('bannerheight')) $('bannerheight').value = bannerheight;
				bannerheight = (bannerheight - 70) / 430 * 100;  
				$('sliderHbanner').style.left = bannerheight+'px';				
			}
			if(cur_block == 'body') {
				var bodybgstate = getstyle("", 'backgroundAttachment'); //头部高度读取
				setselected('bodybgstate', bodybgstate);		
			}
		}
		if(this.dstObj.id == 'title_set') { //空间头信息
			var title_color = getstyle("title h1", 'color');	//名称颜色
			$('title_color').value = title_color;
			chgctrlcolor('title_color');
			
			var title_sigcolor = getstyle("title p", 'color');	//签名颜色
			$('title_sigcolor').value = title_sigcolor;
			chgctrlcolor('title_sigcolor');
		}
		if(this.dstObj.id == 'menu_set') { //导航菜单初始值
			cur_bclass = '';
			var menu_size = getstyle("a", 'fontSize');	//导航字号
			setselected('menu_size', menu_size);
			
			var menu_weight = getstyle("a", 'fontWeight');	//导航粗体
			if(menu_weight == 'bold') {
				$('menu_weight').checked = true;
			} else {
				$('menu_weight').checked = false;
			}
			
			var menu_curcolor = getstyle("active a", 'color');	//当前链接颜色
			$('menu_curcolor').value = menu_curcolor;
			chgctrlcolor('menu_curcolor');
			
			var menu_hrefcolor = getstyle("a", 'color');	//链接颜色
			$('menu_hrefcolor').value = menu_hrefcolor;
			chgctrlcolor('menu_hrefcolor');
			
			var menu_hovercolor = getstyle("a:hover", 'color');	//划过颜色
			$('menu_hovercolor').value = menu_hovercolor;
			chgctrlcolor('menu_hovercolor');
			
			var navheight_old = getstyle("", 'height'); //导航高度读取
			if($('navheight')) $('navheight').value = navheight_old;
			var navheight = parseInt((navheight_old - 31) / 170 * 100); 
			$('sliderH_nav').style.left = navheight+'px';
			
			var nav_a = getstyle("ul", 'marginTop'); //导航链接位置
			nav_a = 100 - parseInt(nav_a / (navheight_old - 31) * 100);
			$('sliderH_a').style.left = nav_a+'px';
			
			var nav_left = getstyle("ul", 'paddingLeft'); //导航链接边距
			if($('navleft')) $('navleft').value = nav_left;
			nav_left = (nav_left - 20) / 40 * 100;  
			$('sliderH_navleft').style.left = nav_left+'px';
			
			var menu_borderstyle = getstyle("", 'borderTopStyle');//导航边框类型/宽度和颜色
			setselected('menu_borderstyle', menu_borderstyle);
			var menu_borderwidth = getstyle("", 'borderTopWidth');
			setselected('menu_borderwidth', menu_borderwidth);
			var menu_bordercolor = getstyle("", 'borderTopColor');
			$('menu_bordercolor').value = menu_bordercolor;
			chgctrlcolor('menu_bordercolor');
			
			var menu_bgimg = getstyle("", 'backgroundImage');//导航背景、类型、位置和颜色
			$('menu_oldbgimg').value = menu_bgimg;
			var bgrepeat = getstyle("", 'backgroundRepeat');
			setselected('menu_repeat', bgrepeat);
			var bgposition = getstyle("", 'backgroundPosition');
			var menu_selectp = 0;
			for(var p in place) {
				if(place[p] == bgposition) selectp = p;
			}
			bc_tdclass = tdclass = [];
			gettdclass($('menu_set'), 'wcc0 iw0', 'selected');
			bc_tdclass = tdclass;
			bc_selectthis(selectp, 1);
			var menu_bgcolor = getstyle("", 'backgroundColor');
			$('menu_bgcolor').value = menu_bgcolor;
			chgctrlcolor('menu_bgcolor');
		}
		if(this.dstObj.id == 'tsetblock') {
			//如果是模块编辑就在这里读取样式默认值 cur_block当前模块ID
			//默认标题编辑
			bgtype='3';
			$('titleset').style.display='';
			$('contentset').style.display='none';
			$('playertop').style.display='none';
			$('menutitle').className = 'on';
			$('menucontent').className = '';
			cur_bclass = 'blocktitle';
			
			$('blockname').innerHTML = blocklist[cur_block];
			var titlefontsize = getstyle("blocktitle h2", 'fontSize');	//标题字号
			setselected('titlefontsize', titlefontsize);
			
			var titleweight = getstyle("blocktitle h2", 'fontWeight');	//标题粗体
			if(titleweight == 'bold') {
				$('titleweight').checked = true;
			} else {
				$('titleweight').checked = false;
			}

			var titlefontcolor = getstyle("blocktitle h2", 'color');	//标题颜色
			$('titlefontcolor').value = titlefontcolor;
			chgctrlcolor('titlefontcolor');
			
			var titleboderwidth = getstyle("blocktitle", 'height'); //标题高度读取
			if($('titleheight')) $('titleheight').value = titleboderwidth;
			titleboderwidth = (titleboderwidth - 20) / 40 * 100;  
			$('sliderH').style.left = titleboderwidth+'px';
			
			var titlepaddingleft = getstyle("blocktitle", 'paddingLeft'); //标题高度读取
			if($('titleleft')) $('titleleft').value = titlepaddingleft;
			titlepaddingleft = (titlepaddingleft - 18) / 32 * 100;  
			$('sliderH_left').style.left = titlepaddingleft+'px';
			
			var borderbottomstyle = getstyle("blocktitle", 'borderBottomStyle');//标题分割线类型/宽度和颜色
			setselected('titleborderstyle', borderbottomstyle);
			var borderbottomwidth = getstyle("blocktitle", 'borderBottomWidth');
			setselected('titleborderwidth', borderbottomwidth);
			var titlebordercolor = getstyle("blocktitle", 'borderBottomColor');
			$('titlebordercolor').value = titlebordercolor;
			chgctrlcolor('titlebordercolor');
			
			var bgimg = getstyle("blocktitle", 'backgroundImage');//标题背景、类型、位置和颜色
			$('oldbgimg_t').value = bgimg;
			var bgrepeat = getstyle("blocktitle", 'backgroundRepeat');
			setselected('bgrepeat_t', bgrepeat);
			var bgposition = getstyle("blocktitle", 'backgroundPosition');
			var selectp = 0;
			for(var p in place) {
				if(place[p] == bgposition) selectp = p;
			}
			bt_tdclass = tdclass = [];
			gettdclass($('titleset'), 'wcc0 iw0', 'selected');
			bt_tdclass = tdclass;
			bt_selectthis(selectp, 1);
			var titlebgcolor = getstyle("blocktitle", 'backgroundColor');
			$('titlebgcolor').value = titlebgcolor;
			chgctrlcolor('titlebgcolor');
			
			var bc_size = getstyle("blockcontent", 'fontSize');	//主体字号
			setselected('bc_size', bc_size);
			
			var bc_weight = getstyle("blockcontent h2", 'fontWeight');	//主体粗体
			if(bc_weight == 'bold') {
				$('bc_weight').checked = true;
			} else {
				$('bc_weight').checked = false;
			}
			
			var bc_color = getstyle("blockcontent", 'color');	//主体颜色
			$('bc_color').value = bc_color;
			chgctrlcolor('bc_color');
			
			var bc_hrefcolor = getstyle("blockcontent a", 'color');	//链接颜色
			$('bc_hrefcolor').value = bc_hrefcolor;
			chgctrlcolor('bc_hrefcolor');
			
			var bc_hovercolor = getstyle("blockcontent a:hover", 'color');	//划过颜色
			$('bc_hovercolor').value = bc_hovercolor;
			chgctrlcolor('bc_hovercolor');
			
			var bc_borderstyle = getstyle("", 'borderTopStyle');//主体边框类型/宽度和颜色
			setselected('bc_borderstyle', bc_borderstyle);
			var bc_borderwidth = getstyle("", 'borderTopWidth');
			setselected('bc_borderwidth', bc_borderwidth);
			var bc_bordercolor = getstyle("", 'borderTopColor');
			$('bc_bordercolor').value = bc_bordercolor;
			chgctrlcolor('bc_bordercolor');
			
			var bgimg = getstyle("blockcontent", 'backgroundImage');//主体背景、类型、位置和颜色
			$('bc_oldbgimg').value = bgimg;
			var bgrepeat = getstyle("blockcontent", 'backgroundRepeat');
			setselected('bc_bgrepeat', bgrepeat);
			var bgposition = getstyle("blockcontent", 'backgroundPosition');
			var bc_selectp = 0;
			for(var p in place) {
				if(place[p] == bgposition) selectp = p;
			}
			bc_tdclass = tdclass = [];
			gettdclass($('selectplace_c'), 'wcc0 iw0', 'selected');
			bc_tdclass = tdclass;
			bc_selectthis(selectp, 1);
			var bc_bgcolor = getstyle("blockcontent", 'backgroundColor');
			$('bc_bgcolor').value = bc_bgcolor;
			chgctrlcolor('bc_bgcolor');
			
			if(cur_block == 'player') {
				$('playertop').style.display='';
				var playertop = getstyle("musicbody .music", 'top'); //播放器位置
				playertop = playertop / 128 * 100;  
				$('sliderH_player').style.left = playertop+'px';
			}
		}
		if('setplayer' == this.dstObj.id) {
			for(var i in diystyle['music']) {
				$(i).value = diystyle['music'][i];
			}
		}
	}
}

function set_effectall(tag, property, newvalue) {
	if(tag != '') {
		tag = tag.replace('|', ' ');		
	}
	var selector = '.block'+(tag !='' ? ' .'+tag : '');
	//alert(selector+', '+newvalue);
	var setvalue = newvalue.replace(property+':', '');
	var property2js = property.replace(/-([a-z])/g, function($0, $1) {return $1.toUpperCase()});
	if(false == dss.setrule(selector, property2js, setvalue)){
		dss.addrule(selector, newvalue);
	}
}
// 关闭时执行，这里我只是直接隐藏，如果需要，可以仿照放大的效果，写一个对象的缩小的效果
transfer.prototype.minEffect = function(event) {
	oldblockstyle = [];//清除为取消操作生成的临时样式数组
	if(this.dstObj.id == 'sharestyle') {
		if($('stylename').value == '') {
			alert('请输入风格名称。');
			return false;	
		}
		if($('stylecolor').value == '') {
			alert('请选择风格所属色系。');
			return false;	
		}
		if($('stylecategory').value == '') {
			alert('请选择风格所属分类。');
			return false;	
		}
		ajaxpost('sharestyle_form', 'mystylelist', '');
	}
	if(cur_transferdiv == 'fastdiy') {
		if($('faststyle').value == '') {
			alert('您还没有选择色系。');
			return false;	
		}
		$('fastdiyform').submit();
	}
	if(cur_transferdiv == 'selectshare') {
		if(shareselected == false) {
			alert('您还没有选择风格。');
			return false;
		}
		$('selectshareform').submit();
	}
	if(cur_transferdiv == 'smusicp') {
               if(musicselected == false) {
			alert('您还没有选择播放器。');
			return false;
		}
		$('smusicpform').submit();
	}
	if(cur_transferdiv == 'smusicp') {

		$('smusicpform').submit();
	}
	if(this.dstObj.id == 'mystyles') {
		switchstyle('', 'mystyle');
	}
	if($('selectbg_sub'))$('selectbg_sub').style.display = 'none';
	if($('cancel_bg'))$('cancel_bg').style.display = 'none';
	if(cur_block == 'footer') $('setfooter').style.display = 'none';
	if(cur_block == 'banner') $('setbannerheight').style.display = 'none';
	if(cur_block == 'body'){
		$('setbodybgstate').style.display = 'none';
		$('bgsetplace').style.display = '';
	}
	if((this.dstObj.id == 'selectbg_div' || this.dstObj.id == 'sharestyle') && cur_transferdiv != '') {
		$(cur_transferdiv).style.display = '';
	} else {
		var mo = document.getElementsByName('mo');
		for(var i=0; i< mo.length; i++) {
			if(mo[i].checked == true && mo[i].value == 2) {
				mo[i].checked = false;
				mo[0].checked = true;
				var bln = window.confirm("此设置不能撤消，确定应用到所有吗?");
				if(bln == false) {
					return false;
				}
				dss.removerule_all();//删除所有自定义样式
				if(typeof(diystyle['effectall']) == 'undefined') {
					diystyle['effectall'] = new Array();
					diystyle['effectall'] = diystyle['block'][cur_block];//当前模块样式存入应用到所有的键值下
				} else {//如果有存在就把当前模块的内容追加进去
					for(var bclass in diystyle['block'][cur_block]) {
						if(typeof(diystyle['effectall'][bclass]) == 'undefined') diystyle['effectall'][bclass] = new Array();
						for(var tag in diystyle['block'][cur_block][bclass]) {
							diystyle['effectall'][bclass][tag] = diystyle['block'][cur_block][bclass][tag];
						}
					}
				}
				for(var j in diystyle['block']) {
					var noblock = new Array('body','banner','title','menu','footer');
					if(in_array(j, noblock)) {
						//body #banner #title #menu #footer 这些样式在应用所有时不删除
						continue;
					} else {
						diystyle['block'][j] = [];
					}
				}
				//diystyle['block'] = [];
				for(var key in diystyle['effectall']) {
					for(var subkey in diystyle['effectall'][key]) {
						set_effectall(key, subkey, diystyle['effectall'][key][subkey]);
						
					}
					//alert(key+'---'+diystyle['block'][cur_block][key]);
				}
			}
		}
		cur_transferdiv = '';
		$('qwert').style.display = 'none';
	}
	this.dstObj.style.display = 'none';
	$('clrMain').style.display = 'none';
	contentdiv = 'syspiclist';
}
// 获取窗口对象信息
transfer.prototype.getWindowInfo = function() {
	var _h = parseInt(document.documentElement.clientHeight);
	var _w = parseInt(document.documentElement.clientWidth);
	var _sL = parseInt(document.documentElement.scrollLeft);
	var _sT = parseInt(document.documentElement.scrollTop);
	var _mH = parseInt(document.body.clientHeight);
	var _mW = parseInt(document.body.clientWidth);
	return {'height':_h, 'width':_w, 'maxHeight':_mH, 'maxWidth':_mW, 'scrollLeft':_sL, 'scrollTop':_sT};
}
// 在一个对象里搜索对应的 className 为设定值的对象
transfer.prototype.getByClass = function(obj, className) {
	if('undefined' == typeof(obj.tagName)) {
		return false;
	}
	if(className == obj.className) {
		this.closeObj = obj;
		return true;
	}
	if(obj.hasChildNodes()) {
		var firstNode = obj.firstChild;
		this.getByClass(firstNode, className);
		while(firstNode = firstNode.nextSibling) {
			this.getByClass(firstNode, className);
		}
	}
}
