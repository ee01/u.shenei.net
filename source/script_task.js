function playmedia(strID) {

	strID.replace(" ","%20");	
	var objDiv=document.getElementById(strID);
	objDiv.innerHTML=makemedia();
	objDiv.style.display='block';
	setTimeout("playmediaa()",500)//1000为1秒
}

function playmediaa() {
	var objDiv=document.getElementById('div_cashMedia');
	objDiv.innerHTML=makemediaa();
	objDiv.style.display='block';
}
function makemedia() {
	var strHtml;	
	strHtml ="<object id='MediaPlayer1' width='0' height='0' classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715/#Version=6,4,5,715/default.htm' align='baseline' border='0' standby='Loading Microsoft Windows Media Player components...' type='application/x-oleobject'>";
	strHtml+="<param name='invokeURLs' value='0'>";
	strHtml+="<param name='FileName' value=\"../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715/image/task/sound2.mp3\">";
	strHtml+="<param name='ShowControls' value='1'>";
	strHtml+="<param name='ShowPositionControls' value='0'>";
	strHtml+="<param name='ShowAudioControls' value='1'>";
	strHtml+="<param name='ShowTracker' value='1'>";
	strHtml+="<param name='ShowDisplay' value='0'>";
	strHtml+="<param name='ShowStatusBar' value='1'>";
	strHtml+="<param name='AutoSize' value='0'>";
	strHtml+="<param name='ShowGotoBar' value='0'>";
	strHtml+="<param name='ShowCaptioning' value='0'>";
	strHtml+="<param name='AutoStart' value='1'>";
	strHtml+="<param name='PlayCount' value='1'>";
	strHtml+="<param name='AnimationAtStart' value='0'>";
	strHtml+="<param name='TransparentAtStart' value='0'>";
	strHtml+="<param name='AllowScan' value='0'>";
	strHtml+="<param name='EnableContextMenu' value='1'>";
	strHtml+="<param name='ClickToPlay' value='0'>";
	strHtml+="<param name='DefaultFrame' value='datawindow'>";		
	strHtml+="<embed src=\"image/task/sound2.mp3\" align='baseline' border='0' width='0' height='0' type='application/x-mplayer2'";
	strHtml+="pluginspage='../www.microsoft.com/isapi/redir.dll@prd=windows&sbp=mediaplayer&ar=media&sba=plugin&amp;'";
	strHtml+="name='MediaPlayer1' showcontrols='1' showpositioncontrols='0' showaudiocontrols='1' showtracker='1' showdisplay='0' showstatusbar='1' autosize='0' showgotobar='0' showcaptioning='0' autostart='1' autorewind='0'";
	strHtml+="animationatstart='0' transparentatstart='0' allowscan='1' enablecontextmenu='1' clicktoplay='0' defaultframe='datawindow' invokeurls='0'> </embed></object>";
	return strHtml;
}
function makemediaa() {
	var strHtml;	
	strHtml ="<object id='MediaPlayer2' width='0' height='0' classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715/#Version=6,4,5,715/default.htm' align='baseline' border='0' standby='Loading Microsoft Windows Media Player components...' type='application/x-oleobject'>";
	strHtml+="<param name='invokeURLs' value='0'>";
	strHtml+="<param name='FileName' value=\"../activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715/image/task/sound1.mp3\">";
	strHtml+="<param name='ShowControls' value='1'>";
	strHtml+="<param name='ShowPositionControls' value='0'>";
	strHtml+="<param name='ShowAudioControls' value='1'>";
	strHtml+="<param name='ShowTracker' value='1'>";
	strHtml+="<param name='ShowDisplay' value='0'>";
	strHtml+="<param name='ShowStatusBar' value='1'>";
	strHtml+="<param name='AutoSize' value='0'>";
	strHtml+="<param name='ShowGotoBar' value='0'>";
	strHtml+="<param name='ShowCaptioning' value='0'>";
	strHtml+="<param name='AutoStart' value='1'>";
	strHtml+="<param name='PlayCount' value='1'>";
	strHtml+="<param name='AnimationAtStart' value='0'>";
	strHtml+="<param name='TransparentAtStart' value='0'>";
	strHtml+="<param name='AllowScan' value='0'>";
	strHtml+="<param name='EnableContextMenu' value='1'>";
	strHtml+="<param name='ClickToPlay' value='0'>";
	strHtml+="<param name='DefaultFrame' value='datawindow'>";		
	strHtml+="<embed src=\"image/task/sound1.mp3\" align='baseline' border='0' width='0' height='0' type='application/x-mplayer2'";
	strHtml+="pluginspage='../www.microsoft.com/isapi/redir.dll@prd=windows&sbp=mediaplayer&ar=media&sba=plugin&amp;'";
	strHtml+="name='MediaPlayer2' showcontrols='1' showpositioncontrols='0' showaudiocontrols='1' showtracker='1' showdisplay='0' showstatusbar='1' autosize='0' showgotobar='0' showcaptioning='0' autostart='1' autorewind='0'";
	strHtml+="animationatstart='0' transparentatstart='0' allowscan='1' enablecontextmenu='1' clicktoplay='0' defaultframe='datawindow' invokeurls='0'> </embed></object>";
	return strHtml;
}