var DPSmileyInput={
	imageURL:function (){
		//设置表情图标基本URL路径
		return 'extend/attach_smiley/';
	},
	smileyCodes:function (textareaid){
		var	baseurl	=	DPSmileyInput.imageURL();
		document.writeln("<table>");
		document.writeln("<tr>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"smile.gif\" alt=\":)\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':)');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"sad.gif\" alt=\":(\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':(');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"biggrin.gif\" alt=\":D\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':D');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"cry.gif\" alt=\":'(\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':\\'(');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"huffy.gif\" alt=\":@\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':@');\" /></td>");
		document.writeln("</tr>");
		document.writeln("<tr>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"shocked.gif\" alt=\":o\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':o');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"tongue.gif\" alt=\":P\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':P');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"shy.gif\" alt=\":$\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':$');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"titter.gif\" alt=\";P\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',';P');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"sweat.gif\" alt=\":L\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':L');\" /></td>");
		document.writeln("</tr>");
		document.writeln("<tr>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"mad.gif\" alt=\":Q\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':Q');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"lol.gif\" alt=\":lol\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':lol');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"hug.gif\" alt=\":hug:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':hug:');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"victory.gif\" alt=\":victory:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':victory:');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"time.gif\" alt=\":time:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':time:');\" /></td>");
		document.writeln("</tr>");
		document.writeln("<tr>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"kiss.gif\" alt=\":kiss:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':kiss:');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"handshake.gif\" alt=\":handshake\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':handshake');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"call.gif\" alt=\":call:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':call:');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"loveliness.gif\" alt=\":loveliness:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':loveliness:');\" /></td>");
		document.writeln("<td><img border=\"0\" width=\"20\" height=\"20\" src=\""+baseurl+"funk.gif\" alt=\":funk:\" onclick=\"DPSmileyInput\.insertSmileyCode('"+textareaid+"',':funk:');\" /></td>");
		document.writeln("</tr>");
		document.writeln("</table>");
	},
	smileyMenu:function (open,id){
		if (open){
			document.getElementById(id).style.display	=	'block';
		} else {
			document.getElementById(id).style.display	=	'none';
		}
	},
	insertSmileyCode:function (id,smileycode) {
		var	myField	=	document.getElementById(id);
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = smileycode;
		}else if (myField.selectionStart|| myField.selectionStart == "0") {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0,startPos) + smileycode + myField.value.substring(endPos,myField.value.length);
		} else {
			myField.value += smileycode;
		}
		DPSmileyInput.smileyMenu(0,'dpsmileylist'+id);
	},
	showButton:function(textareaid){
		var	baseurl	=	DPSmileyInput.imageURL();
		document.writeln("<style type=\"text/css\">");
		document.writeln(".dpsmileylist{position:absolute;width:29px;height:17px;display:inline;z-index:20;}");
		document.writeln(".dpsmileylistmenu{position:absolute;left:0;top:17px;z-index:21;background:#fff;border:1px solid #d0d0d0;width:126px;height:100px;padding:1px;text-align:center;}");
		document.writeln(".dpsmileylistmenu table{display:block;background:#f9f9f9;margin:0 auto;}");
		document.writeln(".dpsmileylistmenu table tr td{width:20px;height:20px;}");
		document.writeln(".dpsmileylistmenu table tr td img{cursor:pointer;}");
		document.writeln(".dpsmileyclear{display:block;height:17px;}");
		document.writeln("</style>");
		document.writeln("<div class=\"dpsmileyclear\"><div class=\"dpsmileylist\" onmouseover=\"DPSmileyInput.smileyMenu(1,'dpsmileylist"+textareaid+"');\" onmouseout=\"DPSmileyInput.smileyMenu(0,'dpsmileylist"+textareaid+"');\">");
		document.writeln("<img src=\""+baseurl+"openlist.gif\" border=\"0\" width=\"29\" height=\"17\" alt=\"插入表情\" title=\"插入表情\" />");
		document.writeln("<div class=\"dpsmileylistmenu\" id=\"dpsmileylist"+textareaid+"\" style=\"display:none;\">");
		DPSmileyInput.smileyCodes(textareaid);
		document.writeln("</div>");
		document.writeln("</div>");
		//document.writeln("<iframe src=\"about:blank\" scrolling=\"no\" frameborder=\"0\" style=\"z-index:8000;position:absolute;top:0;left:0;width:126px;height:100px;display:block;\"></iframe>");
		document.writeln("</div>");
	}
}