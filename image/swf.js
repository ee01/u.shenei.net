/*
功能: 去除IE补丁后的激活虚线框
原理: 通过脚本调用不会出现


*/

(function(){
	var fla_obj = document.getElementsByTagName("script")[document.getElementsByTagName("script").length-1];
	var fla_url = fla_obj.getAttribute("movie")||"";
	var fla_width = fla_obj.getAttribute("width")||"100%";
	var fla_height = fla_obj.getAttribute("height")||"100%";
	var fla_bgcolor = fla_obj.getAttribute("bgcolor")||"#FFFFFF";
	var fla_wmode = fla_obj.getAttribute("wmode")||"window";
	var fla_id = fla_obj.getAttribute("FID")||"";
	
	var fla_str = "";
	fla_str += "<object id=\""+fla_id+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\""+fla_width+"\" height=\""+fla_height+"\" >"
	fla_str += "<param name=\"movie\" value=\""+fla_url+"\">";
	fla_str += "<param name=\"quality\" value=\"high\">";
	fla_str += "<param name=\"bgcolor\" value=\""+fla_bgcolor+"\">"
	fla_str += "<param name=\"wmode\" value=\""+fla_wmode+"\">"
	fla_str += "<embed id=\"moz_"+fla_id+"\" src=\""+fla_url+"\" width=\""+fla_width+"\" height=\""+fla_height+"\" quality=\"high\" bgcolor=\""+fla_bgcolor+"\" wmode=\""+fla_wmode+"\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>";
	fla_str += "</object>";
	document.write (fla_str);
})();
