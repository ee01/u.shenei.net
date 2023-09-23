	var phpserialize = new PHP_Serializer('gbk');
	var ss = new stylecss(0);
	var dss = new stylecss(2);
	var diystyle = new Array();
	var selectframe, setframe, tempdiv;
	var threemod = new Array('lcr','lrc','clr');
	var twomod = new Array('lc','cl');
	var divClass = [];
	var allblock = [];
	var cur_transferdiv = ''; //由transfer记录当前弹出的DIV，当有新的弹出时先关闭它
	var cur_block = ''; //当前编辑的模块
	var cur_bclass = 'blocktitle'; //当前作用样式,改头部和主体背景时用
	var shareselected = false; //判断是否选择了风格
	var musicselected = false; //判断是否选择了播放器
	var clr,cur_colortext,cur_colorclass,cur_colortag,cur_colorproperty;

	// RGB颜色值转换为十六进制值
	function rgb(r, g, b, includeHash) {
	    if (includeHash == undefined) {
	        includeHash = true;
	    }
	    
	    r = r.toString(16);
	    if (r.length == 1) {
	        r = '0' + r;
	    }
	    g = g.toString(16);
	    if (g.length == 1) {
	        g = '0' + g;
	    }
	    b = b.toString(16);
	    if (b.length == 1) {
	        b = '0' + b;
	    }
	    return ((includeHash ? '#' : '') + r + g + b).toUpperCase();
	}

	if(tempstyle != '') {
		diystyle = phpserialize.unserialize(tempstyle);
	}

	function getbyname(name) {
		return document.getElementsByName(name);
	}
	
	function getByClass(obj, className, allownone) {
		if('undefined' == typeof(obj.tagName) || (allownone != 1 && obj.style.display == 'none')) {
			return false;
		}
		if(obj.className == className) {
			divClass[divClass.length] = obj;
			return ;
		}
		if(obj.hasChildNodes()) {
			var firstNode = obj.firstChild;
			getByClass(firstNode, className, allownone);
			while(firstNode = firstNode.nextSibling) {
				getByClass(firstNode, className, allownone);
			}
		}
		
	}
	
	function changeframe(setframe) {
		var maindiv = '';
		/*
		selectframe = getbyname('selectframe');
		for (i=0;i<selectframe.length;i++){
			if(selectframe[i].checked) {
				setframe = selectframe[i].value;
			}
		}
		*/
		if(setframe != defaultset) {
			footerinnerhtml = $('footer').innerHTML;
			var footerdiv = document.createElement("div");
			footerdiv.id = 'footer';
			footerdiv.innerHTML = footerinnerhtml;
			
			//变化结构
			if(in_array(setframe, threemod)) {//三列变换
				var leftinnerhtml = rightinnerhtml = contentinnerhtml = '';
				if(in_array(defaultset, twomod)) {
					maindiv = $('wraptwo');
					//两列变三列
					var divs = maindiv.getElementsByTagName("div");
					divClass = [];
					getByClass($('dleft'), 'block', 0);
					contentinnerhtml = $('dcontent').innerHTML;
					
					var num = Math.round(divClass.length/2);//判断两列的左侧该分几个给三列的右侧
					var leftdiv = document.createElement("div");
					leftdiv.className='frame_1';
					
					var rightdiv = document.createElement("div");
					rightdiv.className='frame_1';
					var k = 0;
					for(var i in divClass) {
						if(k < num) {
							leftdiv.appendChild(divClass[i]);
						} else {
							rightdiv.appendChild(divClass[i]);
						}
						k ++;
					}
					maindiv.removeChild($('dleft'));
					maindiv.removeChild($('dcontent'));
					//maindiv.innerHTML = '';
					leftdiv.id='dleft';
					rightdiv.id='dright';
					var contentdiv = document.createElement("div");
					contentdiv.id='dcontent';
					contentdiv.className='frame_2';
					contentdiv.innerHTML=contentinnerhtml;
				} else {
					maindiv = $('wrap');
					leftinnerhtml = $('dleft').innerHTML;
					rightinnerhtml = $('dright').innerHTML;
					contentinnerhtml = $('dcontent').innerHTML;
					maindiv.removeChild($('dleft'));
					maindiv.removeChild($('dcontent'));
					maindiv.removeChild($('dright'));
					//maindiv.innerHTML = '';
					var leftdiv = document.createElement("div");
					leftdiv.id='dleft';
					leftdiv.className='frame_1';
					leftdiv.innerHTML=leftinnerhtml;
					var rightdiv = document.createElement("div");
					rightdiv.id='dright';
					rightdiv.className='frame_1';
					rightdiv.innerHTML=rightinnerhtml;
					var contentdiv = document.createElement("div");
					contentdiv.id='dcontent';
					contentdiv.className='frame_2';
					contentdiv.innerHTML=contentinnerhtml;
				}
				if(setframe == 'lcr') {
					maindiv.appendChild(leftdiv);
					maindiv.appendChild(contentdiv);
					maindiv.appendChild(rightdiv);
				} else if(setframe == 'lrc') {
					maindiv.appendChild(leftdiv);
					maindiv.appendChild(rightdiv);
					maindiv.appendChild(contentdiv);
				} else {
					maindiv.appendChild(contentdiv);
					maindiv.appendChild(leftdiv);
					maindiv.appendChild(rightdiv);
				}
				maindiv.id = 'wrap';
			} else if(in_array(setframe, twomod)) {//两列变换
				var leftinnerhtml = contentinnerhtml = '';
				if(in_array(defaultset, threemod)) {
					maindiv = $('wrap');
					//三列变两列
					leftinnerhtml = $('dleft').innerHTML + $('dright').innerHTML;
					contentinnerhtml = $('dcontent').innerHTML;
					maindiv.removeChild($('dleft'));
					maindiv.removeChild($('dright'));
					maindiv.removeChild($('dcontent'));
					//maindiv.innerHTML = '';
					var leftdiv = document.createElement("div");
					leftdiv.id='dleft';
					leftdiv.className='frame_1';
					leftdiv.innerHTML=leftinnerhtml;

					var contentdiv = document.createElement("div");
					contentdiv.id='dcontent';
					contentdiv.className='frame_3';
					contentdiv.innerHTML=contentinnerhtml;
				} else {
					maindiv = $('wraptwo');
					//两列之间
					leftinnerhtml = $('dleft').innerHTML;
					contentinnerhtml = $('dcontent').innerHTML;
					var l_classname = $('dleft').className;
					var c_classname = $('dcontent').className;
					maindiv.removeChild($('dleft'));
					maindiv.removeChild($('dcontent'));
					//maindiv.innerHTML = '';
					var leftdiv = document.createElement("div");
					leftdiv.id='dleft';
					leftdiv.className = l_classname;
					leftdiv.innerHTML = leftinnerhtml;
					
					var contentdiv = document.createElement("div");
					contentdiv.id='dcontent';
					contentdiv.className = c_classname;
					contentdiv.innerHTML = contentinnerhtml;
				}
				if(setframe == 'cl') {
					maindiv.appendChild(contentdiv);
					maindiv.appendChild(leftdiv);
				} else {
					maindiv.appendChild(leftdiv);
					maindiv.appendChild(contentdiv);
				}
				maindiv.id = 'wraptwo';
			}
			maindiv.removeChild($('footer'));
			maindiv.appendChild(footerdiv);
			defaultset = setframe;
			chgselectframe(setframe);	
		}
		//更新拖动类
		sort = new sortDrag('block', 'blocktitle', 'blockcontent');
	}

	function chgselectframe(setframe) {
		//变化选中状态
		var framelink = $('selectframe_content').getElementsByTagName('a');
		for(var fi in framelink) {
			if(framelink[fi].id != setframe+'_f' && typeof(framelink[fi].className) != 'undefined') {
				framelink[fi].className = framelink[fi].className.replace(/ onframe[\d]+/i, '');
			}
		}
		$(setframe+'_f').className = $(setframe+'_f').className+' on'+$(setframe+'_f').className;
	}
	
	function in_array(needle, haystack) {
		type = typeof needle
		if(type == 'string' || type == 'number') {
			for(var i in haystack) {
				if(haystack[i] == needle) {
					return true;
				}
			}
		}
		return false;
	}
	
	function array_push(arr, value) {
		arr[arr.length] = value;
		return arr.length;
	}

	function diysetblock(chkblock, blockid) {
		if(chkblock.checked == true) {
			if($(blockid) == null) {
				alert('还没有这个模块，请与管理员联系...');
			} else {
				$(blockid).style.display = '';
			}
		} else {
			if($(blockid) == null) {
				alert('还没有这个模块，请与管理员联系...');
			} else {
				$(blockid).style.display = 'none';
			}
		}
	}

	function closeblock(event) {
		$(cur_block).style.display='none';
		$('transferReady').style.display='none';
		$('set'+cur_block).checked=false;
		event = event || window.event;
		cur_block = '';
		doane(event);	
	}
	function setcontent_display(settype) {
			divClass = [];
			getByClass($('wrap'), 'blockcontent', 1);
		for(var i in divClass) {
			if(settype == 'close') {
				divClass[i].style.display='none';
			} else {
				divClass[i].style.display='';
			}
		}
	}

	function delblockstyle(bclass, tag, property) {
		var stylekey = bclass;
		if(bclass != '') bclass = '.'+bclass;
		var selector = (cur_block == 'body'? '' : "#")+cur_block;
		if(bclass != '') {
			selector = selector+' '+bclass;
		}
		if(tag != '') {
			selector = selector+' '+tag;
			stylekey += '|'+tag;
		}
		property2js = property.replace(/-([a-z])/g, function($0, $1) {return $1.toUpperCase()});
		var n = dss.indexof(selector);
		//alert(dss.rules.length+'---'+selector+'---'+n+'---');
		dss.removerule(n);
		diystyle['block'][cur_block][stylekey] = array_unset(diystyle['block'][cur_block][stylekey], property);
		//alert(phpserialize.serialize(diystyle));
	}

	function array_unset(srcarray, key) {
		if(typeof(srcarray[key]) == 'undefined') return false;
		var tmparray = new Array();
		for(var i in srcarray) {
			if(i != key) tmparray[i] = srcarray[i];
		}
		return tmparray;
	}
	
	function setblockstyle(newvalue, bclass, tag, property) {
		var property2js = property.replace(/-([a-z])/g, function($0, $1) {return $1.toUpperCase()});
		var prostr = property + ':' + newvalue;
		var stylekey = bclass;
		var oldseletor = bclass+'|'+tag+'|'+property;
		property2js_get = property2js.replace(/border([Color|width|style]+?)/ig, function($0, $1) {return 'borderTop'+$1});
		var oldvalue = getstyle(bclass+(bclass? ' '+tag : tag), property2js_get);
		if(tag != '') {
			stylekey += '|'+tag;
		}
		if(typeof(diystyle['block']) == 'undefined') {
			diystyle['block'] = new Array();
		}
		if(typeof(diystyle['block'][cur_block]) == 'undefined') {
			diystyle['block'][cur_block] = new Array();
		}
		if(typeof(diystyle['block'][cur_block][stylekey]) == 'undefined') {
			diystyle['block'][cur_block][stylekey] = new Array();
		}
		if('background-image' == property) {
			newvalue = 'url(' + newvalue + ')';
			prostr = property + ':'+newvalue;
		} else if('left' == property || 'padding-left' == property || 'top' == property || 'margin-top' == property || 'line-height' == property || 'font-size' == property || property.search(/(.*?)width/i) > -1 || 'height' == property || 'width' == property || 'padding-top' == property) {
			newvalue = parseInt(newvalue) + 'px';
			prostr = property + ':' + newvalue;
		}

		if(bclass != '') bclass = '.'+bclass;
		var selector = cur_block != 'body' ? "#"+cur_block : cur_block;
		if(bclass != '') {
			selector = selector+' '+bclass;
		}
		if(tag != '') {
			selector = selector+' '+tag;
		}
		//alert(property+'---'+selector+', '+property2js+', '+newvalue+'gggg');
		if(false == dss.setrule(selector, property2js, newvalue)){
			dss.addrule(selector, prostr);
			if(typeof(oldblockstyle[oldseletor]) == 'undefined') oldblockstyle[oldseletor] = oldvalue+'|'+oldseletor+'|0';
		} else {
			if(typeof(oldblockstyle[oldseletor]) == 'undefined') oldblockstyle[oldseletor] = oldvalue+'|'+oldseletor+'|1';
		}
		diystyle['block'][cur_block][stylekey][property] = prostr;
	}

	function cancelstyle() {
		for(var i in oldblockstyle) {
			var oldstyle = oldblockstyle[i].split('|');
			var oldvalue = oldstyle[0];
			var oldbclass = oldstyle[1];
			var oldtag = oldstyle[2];
			var oldproperty = oldstyle[3];
			var oldstatus = oldstyle[4];
			if(cur_block == 'banner' && oldproperty == 'top') {
				//alert('1');
				$('title').style.top = (oldvalue? oldvalue : 0)+'px';
				cur_block = 'title';
				if(oldstatus == 1) {
				//alert('12');
					setblockstyle(oldvalue, oldbclass, oldtag, oldproperty);
				} else {
				//alert('13');
					delblockstyle(oldbclass, oldtag, oldproperty);	
				}
				cur_block = 'banner';
			} else {
				//alert('2');
				if(oldstatus == 1) {
				//alert('21');
					setblockstyle(oldvalue, oldbclass, oldtag, oldproperty);
				} else {
				//alert('22'+oldblockstyle[i]+'---'+oldbclass+', '+oldtag+', '+oldproperty);
					delblockstyle(oldbclass, oldtag, oldproperty);	
				}				
			}

		}
		oldblockstyle = [];
	}
	var tdclass = [];
	var place = new Array('left top','center top','right top','left center','center center','right center', 'left bottom', 'center bottom', 'right bottom');

	function gettdclass(obj, className, selected) {
		if('undefined' == typeof(obj.tagName) || obj.style.display == 'none') {
			return false;
		}
		if(obj.className == className || obj.className == className+' '+selected) {
			tdclass[tdclass.length] = obj;
			return;
		}
		if(obj.hasChildNodes()) {
			var firstNode = obj.firstChild;
			gettdclass(firstNode, className ,selected);
			while(firstNode = firstNode.nextSibling) {
				gettdclass(firstNode, className, selected);
			}
		}
		
	}
	
	function bt_selectthis(thisobj, nosetstyle) {
		for(var i in tdclass) {
			if(i != thisobj) {
				bt_tdclass[i].id = i;
				bt_tdclass[i].className = 'wcc0 iw0';
				bt_tdclass[i].onclick = function () {
					bt_selectthis(this.id);	
				}
			} else {
				bt_tdclass[i].className = 'wcc0 iw0 selected';
				if(nosetstyle != 1) setblockstyle(place[i], cur_bclass, '', 'background-position');
			}
		}
	}
	function bc_selectthis(thisobj, nosetstyle) {
		for(var i in bc_tdclass) {
			if(i != thisobj) {
				bc_tdclass[i].id = i;
				bc_tdclass[i].className = 'wcc0 iw0';
				bc_tdclass[i].onclick = function () {
					bc_selectthis(this.id);	
				}
			} else {
				bc_tdclass[i].className = 'wcc0 iw0 selected';
				//alert(i+'---'+place[i]+', '+cur_bclass+', background-position');
				if(nosetstyle != 1) setblockstyle(place[i], cur_bclass, '', 'background-position');
			}
		}
	}

	function chgctrlcolor(srccolor) {
		var controlcolor = srccolor+'_ctrl';
		if($(srccolor).value == '') {
			$(controlcolor).className = 'control_nocolor';
		} else {
			$(controlcolor).className = 'control_color';
			$(controlcolor).style.backgroundColor = $(srccolor).value;	
		}		
	}
			
	function getColor(obj) {
		if(typeof(obj) == 'object') {
			var newvalue = obj.iptObj.value;
		}else{
			var newvalue = obj;	
		}
		$(cur_colortext).value = newvalue;
		if(newvalue == 'transparent') {
			$(cur_colortext).value = '';
			$('clrColorValue').value = '';
		}
		chgctrlcolor(cur_colortext);
		setblockstyle(newvalue, cur_colorclass, cur_colortag, cur_colorproperty);
		if(cur_colorclass == 'blocktitle' && cur_colorproperty == 'color') {
			setblockstyle(newvalue, cur_colorclass, 'em a', cur_colorproperty);
		}
		if(cur_colorclass == 'copyright' && cur_colorproperty == 'color') {
			setblockstyle(newvalue, cur_colorclass, 'p a', cur_colorproperty);
		}
		if(cur_colorclass == 'title' && cur_colortag == 'p' && cur_colorproperty == 'color') {
			setblockstyle(newvalue, cur_colorclass, 'p a', cur_colorproperty);
		}
		
	}
	
	function changeheight(obj, textid) {
		//obj.body.style.left
		var limit_min = 20;
		var limit_max = 60;
		if(typeof(obj) == 'object'){
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);
		}else{
			var newvalue = parseInt(obj);
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			$('sliderH').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('titleheight')) $('titleheight').value = newvalue;
		setblockstyle(newvalue, 'blocktitle', '', 'height');
		setblockstyle(newvalue, 'blocktitle', '', 'line-height');
	}
		
	function changeheight_left(obj) {
		//obj.body.style.left
		var limit_min = 18;
		var limit_max = 50;
		if(typeof(obj) == 'object'){
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);
		}else{
			var newvalue = parseInt(obj);
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			$('sliderH_left').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('titleleft')) $('titleleft').value = newvalue;
		setblockstyle(newvalue, 'blocktitle', '', 'padding-left');
	}
	
	function changeheight_navleft(obj) {
		//obj.body.style.left
		var limit_min = 20;
		var limit_max = 60;
		if(typeof(obj) == 'object'){
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);
		}else{
			var newvalue = parseInt(obj);
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			$('sliderH_navleft').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('navleft')) $('navleft').value = newvalue;
		setblockstyle(newvalue, '', 'ul', 'padding-left');
	}
	
	function changeheight_nav(obj) {
		var newa_top, a_top = getstyle('ul', 'marginTop');	//导航外框高度
		var oldheight = getstyle("", 'height');	//导航外框高度
		var limit_min = 31;
		var limit_max = 200;
		
		if(typeof(obj) == 'object'){
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);
		}else{
			var newvalue = parseInt(obj);
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			$('sliderH_nav').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('navheight')) $('navheight').value = newvalue;
		setblockstyle(newvalue, '', '', 'height');
		newa_top = a_top + newvalue - oldheight;
		setblockstyle((newa_top > 0 ? newa_top : 0), '', 'ul', 'margin-top');
	}
	
	function changeheight_a(obj) {
		var limit_max = getstyle("", 'height');	//导航外框高度
		if(limit_max < 30) limit_max = 30;
		limit_max = limit_max - 30;
		var newvalue = parseInt(obj.body.style.left);
		newvalue = parseInt(limit_max * (100 - newvalue) / 100);
		setblockstyle(newvalue, '', 'ul', 'margin-top');
	}
	
	function changeheight_player(obj) {
		var limit_max = 128;	//播放器位置
		var newvalue = parseInt(obj.body.style.left);
		newvalue = parseInt(limit_max * newvalue / 100);
		setblockstyle(newvalue, 'musicbody .music', '', 'top');
	}
	
	function changeheightfooter(obj) {
		//obj.body.style.left
		var limit_min = 30;
		var limit_max = 400;
		if(typeof(obj) == 'object') {
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);			
		} else {
			var newvalue = obj;
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			$('sliderHfooter').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('footerheight')) $('footerheight').value = newvalue;
		setblockstyle(newvalue, '', '', 'height');
	}	
	function changeheightbanner(obj) {
		var limit_min = 70;
		var limit_max = 500;
		var oldvalue = getstyle('', 'height');
		cur_block = 'title';//取title的顶部距离
		var titletop = getstyle('', 'top');
		cur_block = 'banner';
		if(typeof(obj) == 'object') {
			var newvalue = parseInt(obj.body.style.left);
			newvalue = parseInt(limit_min + (limit_max - limit_min) * (newvalue + 1) / 100);
		} else {
			var newvalue = parseInt(obj);
			if(newvalue < limit_min) newvalue = limit_min;
			if(newvalue > limit_max) newvalue = limit_max;
			if($('sliderHbanner')) $('sliderHbanner').style.left = ((newvalue - limit_min) / (limit_max - limit_min) * 100 )+'px';
		}
		if($('bannerheight')) $('bannerheight').value = newvalue;
		titletop = titletop + newvalue - oldvalue;
		if(titletop < 1)titletop = 1;
		if(titletop > (newvalue - 70))titletop = newvalue - 70;
		setblockstyle(newvalue, '', '', 'height');
		cur_block = 'title';
		$('title').style.top = titletop+'px';
		setblockstyle(titletop, '', '', 'top');
		cur_block = 'banner';
	}
		
	function changebold(obj, bclass) {
		var newvalue = 'normal';
		if(obj.checked == true) {
			newvalue = 'bold';
		}
		if(cur_block == 'menu') {
			setblockstyle(newvalue, bclass, 'a', 'font-weight');
			return;
		}
		setblockstyle(newvalue, bclass, 'h2', 'font-weight');
		setblockstyle(newvalue, bclass, 'em a', 'font-weight');
	}
		
	function savestyle() {
		diystyle['frame_set'] = defaultset;
		var leftdivs = contentdivs = new Array();
		divClass = [];
		var i;
		//开始统计框架及对应的模块id
		diystyle['allframe'] = new Array();
		diystyle['allframe']['dleft'] = new Array();
		getByClass($('dleft'), 'block', 0);
		for(i in divClass) {
			diystyle['allframe']['dleft'][i] = divClass[i].id;
		}
		divClass = new Array();
		diystyle['allframe']['dcontent'] = new Array();
		getByClass($('dcontent'), 'block', 0);
		for(i in divClass) {
			diystyle['allframe']['dcontent'][i] = divClass[i].id;
		}
		
		if(in_array(defaultset, threemod)) {//如果是三列则统计dright
			divClass = new Array();
			diystyle['allframe']['dright'] = new Array();
			getByClass($('dright'), 'block', 0);
			for(i in divClass) {
				diystyle['allframe']['dright'][i] = divClass[i].id;
			}	
		}
		$('diystyle').value = phpserialize.serialize(diystyle);
		$('diy_form').submit();
	}
	//恢复默认
	function stylegetback() {
		if(confirm('一但恢复，本次自定义样式将丢失，确认恢复原状吗？') == false) {
			return false;
		}
		
		$('getback').value = '1';
		$('diy_form').submit();
	}
	function getstyle(selector, property) {
		var dvalue = '';
		if(selector != '' && selector != 'ul' && selector != 'a' && selector != 'a:hover') selector = '.'+selector;
		if(selector != '') selector = ' '+selector;
		dvalue = dss.getrule((cur_block == 'body'? '' : "#")+cur_block+selector, property);

		//alert((cur_block == 'body'? '' : "#")+cur_block+selector+', '+property+'---'+dvalue);
		var re = /color/i;
		if(dvalue == false || typeof(dvalue) == 'undefined' || (property.search(re) != -1 && dvalue.slice(0,3) != 'rgb' && dvalue != 'transparent' && dvalue.slice(0,1) != '#')) {
			dvalue = dss.getrule((cur_block == 'body'? 'body' : (cur_block == 'menu'? '.menu' : '.block'))+selector, property);
			//alert(dvalue+'111');
		}
		if(dvalue == false || typeof(dvalue) == 'undefined' || (property.search(re) != -1 && dvalue.slice(0,3) != 'rgb' && dvalue != 'transparent' && dvalue.slice(0,1) != '#')) {
			dvalue = ss.getrule((cur_block == 'body'? '' : "#")+cur_block+selector, property);
			//alert(dvalue+'222');
		}
		if(dvalue == false || typeof(dvalue) == 'undefined' || (property.search(re) != -1 && dvalue.slice(0,3) != 'rgb' && dvalue != 'transparent' && dvalue.slice(0,1) != '#')) {
			//if(cur_block.search(/^title/i) != -1) cur_block = cur_block.replace('title', 'spacetitle'); 
			dvalue = ss.getrule((cur_block == 'body'? 'body' : (cur_block == 'menu' || cur_block == 'banner' || cur_block == 'footer'? '.'+cur_block : '.block'))+selector, property);
			//if(cur_block.search(/^spacetitle/i) != -1) cur_block = cur_block.replace('spacetitle', 'title'); 
		}
		if(dvalue == 'transparent' || dvalue == 'undefined' || dvalue == false) dvalue = '';
		if(dvalue != '') {
			if(dvalue.search(/URL\((.*?)\)/i) > -1) {
				dvalue = dvalue.slice(4, -1);
			} else if(property.search(/color/i) > -1) {
				if(dvalue.slice(0,3) == 'rgb') {
					dvalue = eval(dvalue);
				} else if(dvalue.slice(0,1) != '#'){
					dvalue = '';
				}
			} else if(dvalue.search(/(\d+?)px/i) > -1) {
				dvalue = parseInt(dvalue);
			}
		}
		return dvalue;
	}
	
	function setselected(selectid, svalue) {
		var obj = $(selectid);
		if(obj == null || obj == 'undefined') {
			alert('选择框没找到，请确认。');
		}
		for(var i = 0; i < obj.options.length; i++) {
			if(obj.options[i].value == svalue) {
				obj.selectedIndex = i;	
			}
		}
	}
	

//图片选择
		var contentdiv = 'syspiclist';
		var bgtype = '1';
		var bg_color = '';
		var albumid = -1;
		var s_tags = new Array();
		function getstylelist(myurl) {
			var getstyle = new Ajax();
			getstyle.get(myurl, function(s) {
				$('mystylelist').innerHTML = s;
			});
		}
		function getpage(page, myurl) {
			var getblbum = new Ajax();
			$(contentdiv).innerHTML = '<img src="viewspace/img/tool/loading.gif" class="imgloading">';
			getblbum.get(myurl+'&page='+page, function(s) {
				$(contentdiv).innerHTML = s;
			});
		}
		function changealbum(obj) {
			if(obj.value != '') {
				albumid = obj.value;
				getpage(1, 'space.php@uid='+supe_uid+'&do=selectbg&id='+albumid+'&inajax=1');				
			}
		}
		function changesyspic(obj, param, place) {
			var colorparam = '';
			if(typeof(place) == 'undefined' || place == '') place = 'syspicnav';
			if(place == 'fastpicnav') colorparam = '&color='+bg_color;
			getpage(1, 'viewspace.php@do=getsyspic&type='+bgtype+colorparam+'&inajax=1'+param);
			divClass = [];
			getByClass($(place), 'on', 1);
			for(i in divClass) {
				divClass[i].className = '';
			}
			divClass = [];
			//Showokdiv(obj);
			obj.className = 'on';
		}
		function changesysshare(obj, param, place) {
			var colorparam = '';
			if(typeof(place) == 'undefined' || place == '') place = 'syspicnav';
			if(place == 'fastpicnav') colorparam = '&color='+bg_color;
			getpage(1, 'viewspace.php@do=getsharestyle&type='+bgtype+colorparam+'&inajax=1'+param);
			divClass = [];
			getByClass($(place), 'on', 1);
			for(i in divClass) {
				divClass[i].className = '';
			}
			divClass = [];
			//Showokdiv(obj);
			obj.className = 'on';
		}
		function fastchangesyspic(obj, curcolor) {
			//getpage(1, 'viewspace.php@do=getsyspic&type='+bgtype+'&inajax=1'+param);
			$('fastdiysub').style.display = '';
			var color = new Array('black','white','pink','yellow','green','blue');
			for(var c in color) {
				$(color[c]).className = 'webcolor_'+color[c];
			}
			obj.className = obj.className+' on';
			bg_color = curcolor;
			changesyspic($('alltype'), '', 'fastpicnav');
			$('faststyle').value = obj.id;
			switchstyle(obj.id);
		}
		function changebg(obj, nobg) {
			var imgsrc = '';
			if('undefined' != typeof(obj) && obj.src != '') {
				imgsrc = obj.src;
			}
			if(nobg == 1) {
				imgsrc = '';
			}
			if(contentdiv == 'syspiclist' || contentdiv == 'fastheaderbg') {
				imgsrc = imgsrc.replace(/\/thumb\/([a-f0-9]+?)\.(.*?)/ig, function($0, $1) {$0='';$1 = './'+$1 +'_b.';return $1;});
			} else if(contentdiv == 'piclist') {
				imgsrc = imgsrc.replace(/(\.thumb\.(.){3,4})$/i, '');
			}
			divClass = [];
			getByClass($(contentdiv), 'on', 1);
			for(i in divClass) {
				divClass[i].className = '';
			}
			divClass = [];
			showokdiv(obj);
			obj.className = 'on';
			
			if(contentdiv == 'cursorlist') {
				setCursor(obj);
				return true;
			}
			if(imgsrc != '') {
				if(contentdiv == 'fastheaderbg') {
					$('fastbg').value = imgsrc;
					if(!is_ie)dss = new stylecss(document.styleSheets.length - 1);
					cur_block = 'banner';
					cur_bclass = '';
					getimgheight(imgsrc, 70, 500, changeheightbanner, 1);
					return true;
				}
				if(cur_block == '') {
					alert('cur_block is null!');
					return false;
				}
				//alert(obj.src+', '+cur_bclass+', '+ cur_block +', background-image');
				if(cur_block == 'menu') {
					//当更改导航背景时把当前链接的背景去掉
					setblockstyle('', 'active', '', 'background-image');
				}
	
				if(cur_block == 'body') {
					getimgheight(imgsrc, 70, 500);
					return;
				}
				
				if(cur_block == 'banner') {
					getimgheight(imgsrc, 70, 500, changeheightbanner);
					return;
				}
	
				if(cur_block == 'footer') {
					getimgheight(imgsrc, 30, 150, changeheightfooter);
					return;
				}
			}
			setblockstyle(imgsrc, cur_bclass, '', 'background-image');
		}
		function getimgheight(mysrc, mymin, mymax, func, getValue) {
			var newimage = new Image();
			var imgH = 0;
				newimage.src = mysrc;
				// 如果图片已经存在于浏览器缓存，直接调用回调函数
				if(newimage.complete) {
					imgH = newimage.height;
					if(imgH > 0 && typeof(mymin) != 'undefined') {
						if(imgH < mymin){
							imgH = mymin;
						} else if (imgH > mymax) {
							imgH = mymax;
						}
						if(typeof(func) != 'undefined' && func != '') func(imgH);
					}
					if(typeof(getValue) != 'undefined' && getValue == 1){
						$('fastbgheight').value = imgH;	
					}
					//alert('xxxcur_bclass='+cur_bclass+'---xxxcur_block='+cur_block)
					setblockstyle(mysrc, cur_bclass, '', 'background-image');
					delete newimage;
				}
				//图片下载完毕时异步调用callback函数。
				newimage.onload = onloadimg;
				function onloadimg() {
					imgH = newimage.height;
					if(imgH > 0 && typeof(mymin) != 'undefined') {
						if(imgH < mymin){
							imgH = mymin;
						} else if (imgH > mymax) {
							imgH = mymax;
						}
						if(typeof(func) != 'undefined' && func != '') func(imgH);
					}
					if(typeof(getValue) != 'undefined' && getValue == 1){
						$('fastbgheight').value = imgH;	
					}
					//alert('cur_bclass='+cur_bclass+'---cur_block='+cur_block)
					setblockstyle(mysrc, cur_bclass, '', 'background-image');
					newimage.onload = null;
				}
				delete newimage;
				return;
		}

		function changeselect_share(obj, id) {
			/*
			var radio = document.getElementsByName('sharestyleid');
			for(var i=0; i< radio.length; i++) {
				if(radio[i].value != id) {
					radio[i].checked = false;
				} else {
					radio[i].checked = true;	
				}
			}
			*/
			$('sharestyleid').value = id;
			divClass = [];
			getByClass($('sharelist'), 'on', 1);
			for(i in divClass) {
				divClass[i].className = '';
			}
			divClass = [];
			showokdiv(obj);
			obj.className = 'on';
			shareselected = true;
			switchstyle(id, 'sharestyle');
		}
		function changeselect_smusicp(obj, id) {
			/*
			var radio = document.getElementsByName('sharestyleid');
			for(var i=0; i< radio.length; i++) {
				if(radio[i].value != id) {
					radio[i].checked = false;
				} else {
					radio[i].checked = true;	
				}
			}
			*/
			$('smusicpid').value = id;
			divClass = [];
			getByClass($('smusicplist'), 'on', 1);
			for(i in divClass) {
				divClass[i].className = '';
			}
			
			divClass = [];
			showokdiv(obj);
			obj.className = 'on';
			musicselected = true;
		}
		function showokdiv(obj) {
			if($('okdiv')) $('okdiv').parentNode.removeChild($('okdiv'));
			var okdiv = document.createElement("div");
			okdiv.id = 'okdiv';
			okdiv.className = 'onthis';
			okdiv.innerHTML = '';
			obj.parentNode.appendChild(okdiv);	
		}
		function showchgname(obj, id, name) {
			if($('chgname')) $('chgname').parentNode.removeChild($('chgname'));
			var chgname = document.createElement("div");
			chgname.id = 'chgname';
			chgname.className = 'chgname';
			chgname.innerHTML = $('chgname_inner').innerHTML;
			obj.parentNode.appendChild(chgname);
			$('chgstyleid').value = id;	
			$('newstylename').value = name;
		}
		function showbgdiv(divid, navid) {
			if(navid == 'albumpicli') {
				contentdiv = 'piclist';
			}
			if(navid == 'syspicli') {
				contentdiv = 'syspiclist';
			}

			var allnav = new Array('syspicli', 'uploadpicli', 'urlpicli', 'albumpicli');
			for(var i in allnav) {
				if(allnav[i] == navid) {
					$(navid).className = 'on';
					$(navid.replace('li','')).style.display='';
				} else {
					$(allnav[i]).className = '';
					$(allnav[i].replace('li','')).style.display='none';	
				}
			}
		}
// 音乐地址
function setMusic(obj, del) {
	if('undefined' == typeof(diystyle['music']) || 'string' == typeof(diystyle['music'])) {
		diystyle['music'] = new Array();
	}
	if(del == 1) {
		obj.value = '';
		diystyle['music'][obj.id] = '';
		return;
	}
	diystyle['music'][obj.id] = obj.value;
}
// 鼠标样式
function setCursor(obj) {
	//var cursorHost = '../imgwowo.5d6d.net/diyimg/';
	var cursorPath = '';
	if('undefined' != typeof(obj) && '' != obj.src) {
		cursorPath = obj.src
	}
	cursorPath = cursorPath.replace(/\/thumb\/([a-f0-9]+?)\.(.*?)/ig, function($0, $1) {$0='';$1 = './'+$1 +'_b.';return $1;});
	cursorPath = cursorPath.replace('.gif', '.ani');
	var cursorReg = new RegExp('.ani$');
	var httpReg = new RegExp('^http://');

	if(cursorReg.test(cursorPath)) {
		if(!httpReg.test(cursorPath)) {
			cursorPath = cursorHost + cursorPath;
		}
	} else {
		cursorPath = 'auto';
	}
	//$('cursor').value = obj.value;

	if('auto' != cursorPath) {
		cursorPath = 'url(' + cursorPath + '), url(' + cursorPath + '), auto;';
	}
	diystyle['cursor'] = 'auto' == cursorPath ? 'auto' : cursorPath;
	dss.addrule('body', 'cursor:' + cursorPath);
}

//实时变化样式
function switchstyle(styleid, mytype) {
		if(!$('qwertadsf')) {
			var iframe = document.createElement('div');
			iframe.id = 'qwertadsf';
			iframe.style.zIndex = 99999;
			iframe.style.display = 'none';
			iframe.style.backgroundColor="#fff";
			iframe.style.opacity="0";
			iframe.style.position = 'absolute';
			iframe.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';
			$('append_parent') ? $('append_parent').appendChild(iframe) : menuObj.parentNode.appendChild(iframe);
		}
		$('qwertadsf').style.top = 0;
		$('qwertadsf').style.left = 0;
		$('qwertadsf').style.width = document.body.clientWidth + 'px';
		$('qwertadsf').style.height = document.body.clientHeight + 'px';
		$('qwertadsf').style.display = 'block';
	dss.removerule_all(1);//删除所有自定义样式
	var csstext = '';
	var getcss = new Ajax();
	if(typeof(mytype) == 'undefined') {
		mytype = 'fastdiy';
	}
	var myurl = 'viewspace.php@do=mystyle&op=getcss&type='+mytype;
	if(mytype == 'fastdiy') {
		myurl = myurl+'&fastkey='+styleid;
	} else {
		myurl = myurl+'&styleid='+styleid;
	}
	getcss.get(myurl, function(s) {
		csstext = s;
		tempstyle = $('tempstyle');
		if(is_ie) {
			var style = document.createStyleSheet('', 2);
			style.cssText = csstext;
			dss = new stylecss(2);
		} else {
			if(tempstyle) tempstyle.parentNode.removeChild(tempstyle);
			var style = document.createElement("style");
			style.id = 'tempstyle';
			style.type = "text/css";
			style.innerHTML = csstext;
			document.getElementsByTagName("HEAD").item(0).appendChild(style);
		}
		$('qwertadsf').style.zIndex = '998';
		$('qwertadsf').style.display = 'none';
	});
}

function share_mystyle(sid, sname) {
	$('stylename').value = sname;
	$('styleid').value = sid;
	$('sharestyle_sw').onmousedown({type: 'mousedown'});
}