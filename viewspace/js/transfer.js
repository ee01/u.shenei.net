/**
* @param:sourceId �����Ч���İ�ť�����ID��
* @param:dstId ��󵯳��Ĳ��ID��
* @param:closeId �������У��رհ�ť����ʽ className ֵ����IDֵ��
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
	// �仯�ļ���¼�����λΪ ms
	this.timer = 7;
	// ����С���������仯����
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
		// �������ť�� onmousedown �¼�
		this.sourceObj.onmousedown = this.maxEffect.bindAsEventListener(this);
		// ����رհ�ť��ID�����ڣ�������ƥ�� className ֵ
		if(!this.closeObj) {
			this.getByClass(this.dstObj, closeId);
		}
		// ����رն�����ڣ�������� onclick �¼�
		if(this.closeObj) {
			this.closeObj.onclick = this.minEffect.bindAsEventListener(this);
		}
	}
}
// ����ÿ���仯����ֵ�����ն���� left��top ֵ
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
// С�����Ч��
transfer.prototype.maxEffect = function(event) {
	// �ж�Ŀ������Ƿ����
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
			alert("����ģ���������20�����Ѿ��ﵽ���ޡ�\r\n�뵽���ҵ�ģ�塱����ɾ��1��ģ���¼��");
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
	// ����ÿ����Ҫ�仯�ĳ�����
	this.distance = this.getDistance();
	this.stepX = this.distance['distancex'] / this.steps;
	this.stepY = this.distance['distancey'] / this.steps;
	this.stepDivX = parseInt(this.dstObj_W) / this.steps;
	this.stepDivY = parseInt(this.dstObj_H) / this.steps;
	// ������ʱ��
	this.divObj = document.createElement('div');
	this.divObj.className = 'transfer';
	this.divObj.style.display = '';
	this.divObj.style.position = 'absolute';
	// ���´����Ĳ���뵽ҳ����
	document.body.appendChild(this.divObj);
	// ����IE7���°汾��һ����̬BUG
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
	// Ϊ�˼���Firefox�ж�ͼƬ���϶�����ֹ�¼�ð��
	if(typeof(event.button) == 'number') {
		doane(event);		
	}
}
// ���㵱ǰ����Ĵ�С�����꣬����ʾ
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
	// ����Ѿ������һ��ʱ����ɾ����ʱ�ڵ㡢�����ʱ��
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
				$('selectbgtitle').innerHTML = '�󱳾�ͼ';
			}else if(this.sourceObj.id == 'bannerbg') {
				bgtype = '2';
				$('selectbgtitle').innerHTML = 'ͷ��ͼƬ';
			}else if(this.sourceObj.id == 'menu_setbg') {
				bgtype = '3';
				$('selectbgtitle').innerHTML = '����ͼƬ';
			}else if(this.sourceObj.id == 'footerbg') {
				bgtype = '';
				$('selectbgtitle').innerHTML = 'β��ͼƬ';
			}else if(this.sourceObj.id == 'smusicp') {
				bgtype = '7';
				contentdiv = 'smusicplist';
				$('selectbgtitle').innerHTML = '������';
			}
			
			if(this.sourceObj.id == 'selectbg_t') {
				bgtype = '3';
				$('selectbgtitle').innerHTML = 'ģ����ⱳ��';
			}else if(this.sourceObj.id == 'selectbg_c'){
				bgtype = '1';
				$('selectbgtitle').innerHTML = 'ģ�����屳��';
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
			//����ģ��༭�ﵯ���ı���ѡ���
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
			
			var bgimg = getstyle("", 'backgroundImage');//���屳�������͡�λ�ú���ɫ
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
				var footerfontcolor = getstyle("copyright p", 'color');	//β����ɫ
				$('footerfontcolor').value = footerfontcolor;
				chgctrlcolor('footerfontcolor');
			
				var footerheight = getstyle("", 'height'); //β���߶ȶ�ȡ
				if($('footerheight')) $('footerheight').value = footerheight;
				footerheight = (footerheight - 30) / 370 * 100;  
				$('sliderHfooter').style.left = footerheight+'px';				
			}
			if(cur_block == 'banner') {
				var bannerheight = getstyle("", 'height'); //ͷ���߶ȶ�ȡ
				if($('bannerheight')) $('bannerheight').value = bannerheight;
				bannerheight = (bannerheight - 70) / 430 * 100;  
				$('sliderHbanner').style.left = bannerheight+'px';				
			}
			if(cur_block == 'body') {
				var bodybgstate = getstyle("", 'backgroundAttachment'); //ͷ���߶ȶ�ȡ
				setselected('bodybgstate', bodybgstate);		
			}
		}
		if(this.dstObj.id == 'title_set') { //�ռ�ͷ��Ϣ
			var title_color = getstyle("title h1", 'color');	//������ɫ
			$('title_color').value = title_color;
			chgctrlcolor('title_color');
			
			var title_sigcolor = getstyle("title p", 'color');	//ǩ����ɫ
			$('title_sigcolor').value = title_sigcolor;
			chgctrlcolor('title_sigcolor');
		}
		if(this.dstObj.id == 'menu_set') { //�����˵���ʼֵ
			cur_bclass = '';
			var menu_size = getstyle("a", 'fontSize');	//�����ֺ�
			setselected('menu_size', menu_size);
			
			var menu_weight = getstyle("a", 'fontWeight');	//��������
			if(menu_weight == 'bold') {
				$('menu_weight').checked = true;
			} else {
				$('menu_weight').checked = false;
			}
			
			var menu_curcolor = getstyle("active a", 'color');	//��ǰ������ɫ
			$('menu_curcolor').value = menu_curcolor;
			chgctrlcolor('menu_curcolor');
			
			var menu_hrefcolor = getstyle("a", 'color');	//������ɫ
			$('menu_hrefcolor').value = menu_hrefcolor;
			chgctrlcolor('menu_hrefcolor');
			
			var menu_hovercolor = getstyle("a:hover", 'color');	//������ɫ
			$('menu_hovercolor').value = menu_hovercolor;
			chgctrlcolor('menu_hovercolor');
			
			var navheight_old = getstyle("", 'height'); //�����߶ȶ�ȡ
			if($('navheight')) $('navheight').value = navheight_old;
			var navheight = parseInt((navheight_old - 31) / 170 * 100); 
			$('sliderH_nav').style.left = navheight+'px';
			
			var nav_a = getstyle("ul", 'marginTop'); //��������λ��
			nav_a = 100 - parseInt(nav_a / (navheight_old - 31) * 100);
			$('sliderH_a').style.left = nav_a+'px';
			
			var nav_left = getstyle("ul", 'paddingLeft'); //�������ӱ߾�
			if($('navleft')) $('navleft').value = nav_left;
			nav_left = (nav_left - 20) / 40 * 100;  
			$('sliderH_navleft').style.left = nav_left+'px';
			
			var menu_borderstyle = getstyle("", 'borderTopStyle');//�����߿�����/��Ⱥ���ɫ
			setselected('menu_borderstyle', menu_borderstyle);
			var menu_borderwidth = getstyle("", 'borderTopWidth');
			setselected('menu_borderwidth', menu_borderwidth);
			var menu_bordercolor = getstyle("", 'borderTopColor');
			$('menu_bordercolor').value = menu_bordercolor;
			chgctrlcolor('menu_bordercolor');
			
			var menu_bgimg = getstyle("", 'backgroundImage');//�������������͡�λ�ú���ɫ
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
			//�����ģ��༭���������ȡ��ʽĬ��ֵ cur_block��ǰģ��ID
			//Ĭ�ϱ���༭
			bgtype='3';
			$('titleset').style.display='';
			$('contentset').style.display='none';
			$('playertop').style.display='none';
			$('menutitle').className = 'on';
			$('menucontent').className = '';
			cur_bclass = 'blocktitle';
			
			$('blockname').innerHTML = blocklist[cur_block];
			var titlefontsize = getstyle("blocktitle h2", 'fontSize');	//�����ֺ�
			setselected('titlefontsize', titlefontsize);
			
			var titleweight = getstyle("blocktitle h2", 'fontWeight');	//�������
			if(titleweight == 'bold') {
				$('titleweight').checked = true;
			} else {
				$('titleweight').checked = false;
			}

			var titlefontcolor = getstyle("blocktitle h2", 'color');	//������ɫ
			$('titlefontcolor').value = titlefontcolor;
			chgctrlcolor('titlefontcolor');
			
			var titleboderwidth = getstyle("blocktitle", 'height'); //����߶ȶ�ȡ
			if($('titleheight')) $('titleheight').value = titleboderwidth;
			titleboderwidth = (titleboderwidth - 20) / 40 * 100;  
			$('sliderH').style.left = titleboderwidth+'px';
			
			var titlepaddingleft = getstyle("blocktitle", 'paddingLeft'); //����߶ȶ�ȡ
			if($('titleleft')) $('titleleft').value = titlepaddingleft;
			titlepaddingleft = (titlepaddingleft - 18) / 32 * 100;  
			$('sliderH_left').style.left = titlepaddingleft+'px';
			
			var borderbottomstyle = getstyle("blocktitle", 'borderBottomStyle');//����ָ�������/��Ⱥ���ɫ
			setselected('titleborderstyle', borderbottomstyle);
			var borderbottomwidth = getstyle("blocktitle", 'borderBottomWidth');
			setselected('titleborderwidth', borderbottomwidth);
			var titlebordercolor = getstyle("blocktitle", 'borderBottomColor');
			$('titlebordercolor').value = titlebordercolor;
			chgctrlcolor('titlebordercolor');
			
			var bgimg = getstyle("blocktitle", 'backgroundImage');//���ⱳ�������͡�λ�ú���ɫ
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
			
			var bc_size = getstyle("blockcontent", 'fontSize');	//�����ֺ�
			setselected('bc_size', bc_size);
			
			var bc_weight = getstyle("blockcontent h2", 'fontWeight');	//�������
			if(bc_weight == 'bold') {
				$('bc_weight').checked = true;
			} else {
				$('bc_weight').checked = false;
			}
			
			var bc_color = getstyle("blockcontent", 'color');	//������ɫ
			$('bc_color').value = bc_color;
			chgctrlcolor('bc_color');
			
			var bc_hrefcolor = getstyle("blockcontent a", 'color');	//������ɫ
			$('bc_hrefcolor').value = bc_hrefcolor;
			chgctrlcolor('bc_hrefcolor');
			
			var bc_hovercolor = getstyle("blockcontent a:hover", 'color');	//������ɫ
			$('bc_hovercolor').value = bc_hovercolor;
			chgctrlcolor('bc_hovercolor');
			
			var bc_borderstyle = getstyle("", 'borderTopStyle');//����߿�����/��Ⱥ���ɫ
			setselected('bc_borderstyle', bc_borderstyle);
			var bc_borderwidth = getstyle("", 'borderTopWidth');
			setselected('bc_borderwidth', bc_borderwidth);
			var bc_bordercolor = getstyle("", 'borderTopColor');
			$('bc_bordercolor').value = bc_bordercolor;
			chgctrlcolor('bc_bordercolor');
			
			var bgimg = getstyle("blockcontent", 'backgroundImage');//���屳�������͡�λ�ú���ɫ
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
				var playertop = getstyle("musicbody .music", 'top'); //������λ��
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
// �ر�ʱִ�У�������ֻ��ֱ�����أ������Ҫ�����Է��շŴ��Ч����дһ���������С��Ч��
transfer.prototype.minEffect = function(event) {
	oldblockstyle = [];//���Ϊȡ���������ɵ���ʱ��ʽ����
	if(this.dstObj.id == 'sharestyle') {
		if($('stylename').value == '') {
			alert('�����������ơ�');
			return false;	
		}
		if($('stylecolor').value == '') {
			alert('��ѡ��������ɫϵ��');
			return false;	
		}
		if($('stylecategory').value == '') {
			alert('��ѡ�����������ࡣ');
			return false;	
		}
		ajaxpost('sharestyle_form', 'mystylelist', '');
	}
	if(cur_transferdiv == 'fastdiy') {
		if($('faststyle').value == '') {
			alert('����û��ѡ��ɫϵ��');
			return false;	
		}
		$('fastdiyform').submit();
	}
	if(cur_transferdiv == 'selectshare') {
		if(shareselected == false) {
			alert('����û��ѡ����');
			return false;
		}
		$('selectshareform').submit();
	}
	if(cur_transferdiv == 'smusicp') {
               if(musicselected == false) {
			alert('����û��ѡ�񲥷�����');
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
				var bln = window.confirm("�����ò��ܳ�����ȷ��Ӧ�õ�������?");
				if(bln == false) {
					return false;
				}
				dss.removerule_all();//ɾ�������Զ�����ʽ
				if(typeof(diystyle['effectall']) == 'undefined') {
					diystyle['effectall'] = new Array();
					diystyle['effectall'] = diystyle['block'][cur_block];//��ǰģ����ʽ����Ӧ�õ����еļ�ֵ��
				} else {//����д��ھͰѵ�ǰģ�������׷�ӽ�ȥ
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
						//body #banner #title #menu #footer ��Щ��ʽ��Ӧ������ʱ��ɾ��
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
// ��ȡ���ڶ�����Ϣ
transfer.prototype.getWindowInfo = function() {
	var _h = parseInt(document.documentElement.clientHeight);
	var _w = parseInt(document.documentElement.clientWidth);
	var _sL = parseInt(document.documentElement.scrollLeft);
	var _sT = parseInt(document.documentElement.scrollTop);
	var _mH = parseInt(document.body.clientHeight);
	var _mW = parseInt(document.body.clientWidth);
	return {'height':_h, 'width':_w, 'maxHeight':_mH, 'maxWidth':_mW, 'scrollLeft':_sL, 'scrollTop':_sT};
}
// ��һ��������������Ӧ�� className Ϊ�趨ֵ�Ķ���
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
