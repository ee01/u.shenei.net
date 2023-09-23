function el(objid) {
	return document.getElementById(objid);
}

function getUrl(songurl){
	el("songurl").value=songurl;
	el("blackform").submit();
}

function changeSelectflag(songurl){
	onSelectflag=1;
}

function cTrim(sInputString,iType)
{
	var sTmpStr = ' ';
	var i = -1;
	if(iType == 0 || iType == 1)
	{
		while(sTmpStr == ' '){
			++i;
			sTmpStr = sInputString.substr(i,1);
		}
		sInputString = sInputString.substring(i);
	}
	if(iType == 0 || iType == 2)
	{
		sTmpStr = ' ';
		i = sInputString.length;
		while(sTmpStr == ' '){
			--i;
			sTmpStr = sInputString.substr(i,1);
		}
		sInputString = sInputString.substring(0,i+1);
	}
	return sInputString;
}
//-->



function myflash_DOFSCommand(command,args){
	if (command=="Url"){
         getUrl(args)
	}
	if (command=="sel"){
         changeSelectflag()
	}
}

function boxcc(){
			var ss = "scrollbars=no,resizable=no,width=380,height=450,left=355,top=200"
      w=window.open("","jjcutemusiclisten",ss)  
 			w.focus();
}

function tomyboxcc(){
			var ss = "scrollbars=no,resizable=no,width=380,height=450,left=355,top=200"
      w=window.open("space.php@do=musicbox&mybox=ok&songidlist=myboxlist","jjcutemusiclisten",ss)  
 			w.focus();
}

function doplaymusic(ctype,objname,music,cid){
		//swfplay
		el(cid).src = 'images/play1_no.gif';
		el(cid).style.cursor = '';
		el(cid).onclick=function(){};
		el(objname).style.display = '';
		if(ctype==1){
			el(objname).innerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="../download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,124,0/#version=9,0,124,0/default.htm" width="100%" height="24" id="cmp"><param name="movie" value="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" /><param name="quality" value="high" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="Transparent"/><embed pluginspage="../www.adobe.com/shockwave/download/download.cgi@P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="24" name="cmp" src="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" quality="high"  allowfullscreen="true" allowscriptaccess="always" wmode="Transparent" ></embed></object>';
		}else{
			el(objname).innerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="../download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,124,0/#version=9,0,124,0/default.htm" width="100%" height="24" id="cmp"><param name="movie" value="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" /><param name="quality" value="high" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="Transparent"/><embed pluginspage="../www.adobe.com/shockwave/download/download.cgi@P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="24" name="cmp" src="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" quality="high"  allowfullscreen="true" allowscriptaccess="always" wmode="Transparent" ></embed></object>';
		}
}

function doplaymusicwin(ctype,objname,music,cid){
		//mediaplay
		el(cid).src = 'images/play1_no.gif';
		el(cid).style.cursor = '';
		el(cid).onclick=function(){};
		el(objname).style.display = '';
		if(ctype==1){
			document.getElementById(objname).innerHTML = '<object id="Player" width="100%" height="62" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112/#Version=6,4,7,1112/default.htm" align="baseline" border="0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="URL" value="'+music+'"><param name="autoStart" value="true"><param name="invokeURLs" value="false"><param name="playCount" value="100"><param name="volume" value="80"><param name="enableContextMenu" value="1"><param name="defaultFrame" value="datawindow"><embed name="player" type="application/x-mplayer2" height="62" width="100%" showstatusbar="true" autostart="true" pluginspage="../www.microsoft.com/windows/windowsmedia/default.htm" src="'+music+'"></object>';
		}else{
			document.getElementById(objname).innerHTML = '<object id="Player" width="100%" height="62" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112/#Version=6,4,7,1112/default.htm" align="baseline" border="0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="URL" value="'+music+'"><param name="autoStart" value="true"><param name="invokeURLs" value="false"><param name="playCount" value="100"><param name="volume" value="80"><param name="enableContextMenu" value="1"><param name="defaultFrame" value="datawindow"><embed name="player" type="application/x-mplayer2" height="62" width="100%" showstatusbar="true" autostart="true" pluginspage="../www.microsoft.com/windows/windowsmedia/default.htm" src="'+music+'"></object>';
		}
}

function writeplay1(music){
		//mediaplay
		document.writeln('<object id="Player" width="100%" height="62" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112/#Version=6,4,7,1112/default.htm" align="baseline" border="0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="URL" value="'+music+'"><param name="autoStart" value="true"><param name="invokeURLs" value="false"><param name="playCount" value="100"><param name="volume" value="80"><param name="enableContextMenu" value="1"><param name="defaultFrame" value="datawindow"><embed name="player" type="application/x-mplayer2" height="62" width="100%" showstatusbar="true" autostart="true" pluginspage="../www.microsoft.com/windows/windowsmedia/default.htm" src="'+music+'"></object>');
}

function writeplay2(music){
		//swfplay
		document.writeln('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="../download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,124,0/#version=9,0,124,0/default.htm" width="100%" height="24" id="cmp"><param name="movie" value="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" /><param name="quality" value="high" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="Transparent"/><embed pluginspage="../www.adobe.com/shockwave/download/download.cgi@P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="24" name="cmp" src="cmp.swf@src='+music+'&skin_src=mini/mini02.zip&auto_play=1&play_mode=1&context_menu=0&show_tip=0&plugins_disabled=1&c.swf" quality="high"  allowfullscreen="true" allowscriptaccess="always" wmode="Transparent" ></embed></object>');
}

function cc(objname){
		var bobjname = objname == '' || objname == null ? 'musicsingle' : objname;
		var ms = document.getElementsByName(bobjname);
		var ms2 = 0;
		
		for( var i = 0; i < ms.length; i++ ) {
			if (ms[i].checked) {
					ms2++;
			}
		}
		
		if(ms2 == 0) {
			alert("你还没有选择任何音乐!");
			return false;
		} else{
			var ss = "scrollbars=no,resizable=no,width=380,height=450,left=255,top=200"   
			w=window.open(hs(bobjname),"jjcutemusiclisten",ss)
			w.focus();
		}
		
		return true;
}

function delcc(objname){
		var bobjname = objname == '' || objname == null ? 'musicsingle' : objname;
		var ms = document.getElementsByName(bobjname);
		var ms2 = 0;
		
		for( var i = 0; i < ms.length; i++ ) {
			if (ms[i].checked) {
					ms2++;
			}
		}
		
		if(ms2 == 0) {
			alert("你还没有选择任何项!");
			return false;
		}
		
		return true;
}


function hs(objname){
		var bobjname = objname == '' || objname == null ? 'musicsingle' : objname;
		var s = document.getElementsByName(bobjname);
		var s2 = "";
		for( var i = 0; i < s.length; i++ ) {
			if ( s[i].checked ) {
					s2 += s[i].value+",";
			}
		}
		s2 = s2.substr(0,s2.length-1);
		return "space.php@do=musicbox&mybox=ok&songidlist="+s2;
}


function hs_ca(objname){
		var bobjname = objname == '' || objname == null ? 'musicsingle' : objname;
		var s = document.getElementsByName(bobjname);
		var s2 = 0;
		for( var i = 0; i < s.length; i++ ) {
			if (s[i].checked) {
					s2++;
			}
		}
		if(s2==s.length){
			for( var i = 0; i < s.length; i++ ) {
				s[i].checked = false;
			}
		} else{
			for( var i = 0; i < s.length; i++ ) {
				s[i].checked = true;
			}
		}
}

//专辑选择图片
function musicpicView(albumid) {
	if(albumid == 'none') {
		$('albumpic_body').innerHTML = '';
	} else {
		ajaxget('do.php@ac=ajaxm&op=albummusic&id='+albumid+'&ajaxdiv=albumpic_body', 'albumpic_body');
	}
}

function musicinsertImage(text) {
	var obj = $('mynewmusicalbum_fengmian');
	var objyl = $('mynewmusicalbum_fengmian_yl');
	obj.value = text;
	objyl.style.display = '';
	objyl.src = text;
}















//**********************************************************************************************************//
