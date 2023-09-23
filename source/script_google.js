function GoogleMap(objName, divID, typeval, func) {
	this.obj = objName;
	this.divObj = divID;
	this.type = isUndefined(typeval)? 1:typeval;
	this.func = isUndefined(func) ? '': func;
	this.mapObj;//google js文件没有被读取
	this.icons = {};
	this.markerarr = [];
	this.htmlarr = [];
	return this;
};
//开始加载google 地图
GoogleMap.prototype.Initialize = function(lat, lng)
{
	with(this) {
		var instance = eval(obj);
		this.divObj = $(divObj);
		this.mapObj = new GMap2(divObj);
		instance.poweredby();
		if (type) {
            mapObj.addControl(new GMapTypeControl());
            mapObj.addControl(new GLargeMapControl());
            mapObj.addControl(new GScaleControl());
        } else {
            mapObj.addControl(new GSmallMapControl());
        }
        mapObj.setCenter(new GLatLng(36.94, 106.08), 13);
		if ( lat ) {
			var point = new GLatLng(lat, lng);
			mapObj.setCenter(point, 13);
		}
	}	
};

GoogleMap.prototype.addXmlMarkers = function(url) {	
	with (this) {        
		if (!url) return;
        var instance = eval(obj);
        var request = GXmlHttp.create();
        request.open("GET", url, true);
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                instance.parseData(request.responseXML.lastChild.firstChild.nodeValue);
            }
        }
        request.send(null);
	}
};

GoogleMap.prototype.parseData = function(str)
{
	with(this) {
		instance = eval(obj);    
		reg = new RegExp("\n", "ig");
        str = str.replace(reg, "");
		var arrs = eval('(' + str + ')');
		for (var i = 0; i < arrs[0][0]; i++)
		{
			var arr = arrs[1][i];
			posn = arr.posn;
			arr.marker = arr.marker?arr.marker:'visit';
			var icon = instance.getIcon(arr.marker);
			if ( func ) {
				html = func(arr);
			} else {
				html = htmlfunc(arr);
			}
            var marker = new GMarker(new GLatLng(posn[0], posn[1]),{icon: icon});      
			marker.bindInfoWindowHtml(html);
			htmlarr.push(html);
            markerarr.push(marker);
			mapObj.addOverlay(marker);
		}
		if ( typeof (GMP_callback) == "function" ) GMP_callback(instance);
	}
};

GoogleMap.prototype.addLogMarkers = function(markers,htmls)
{
	with(this) {
		for (var i in markers) {        
			var value = markers[i].getPoint();
			var marker = new GMarker(new GLatLng(value.lat(), value.lng()));
			marker.bindInfoWindowHtml(htmls[i]);
			this.mapObj.addOverlay(marker);
		}
	}
};

GoogleMap.prototype.getIcon = function(image) {
	with(this) {
		var icon = null;
		if (image) {
			if (icons[image]) {
				icon = icons[image];
			}else {
				icon = new GIcon();
				icon.image = "image/men.gif";
				icon.shadow = "image/map/" + image + "_shadow.png";
				icon.iconSize = new GSize(21, 24);
				icon.iconAnchor = new GPoint(10, 20);
				icon.shadowSize = new GSize(22, 20);
				icon.infoWindowAnchor = new GPoint(5, 1);
				icons[image] = icon;
			}
		}
		return icon;
	}
};

//此处为版权声明 希望大家不要移除这个链接 其实也不会抢走您的流量 谢谢合作

GoogleMap.prototype.poweredby = function() {
	var div = document.createElement('div');
	div.style.position ="absolute";
	div.style.left="180px"; 
	div.style.bottom= "2px";
	div.innerHTML = '<a href="../www.shenei.net" target="_blank" style="color:black">舍内网</a>';
	this.divObj.appendChild(div);
}

GoogleMap.prototype.randomShow = function(time) {
    with(this) {
        instance = eval(obj);
        var i = parseInt(markerarr.length * Math.random());
        if (markerarr.length) {
            mapObj.addOverlay(markerarr[i]);
            markerarr[i].openInfoWindowHtml(htmlarr[i]);
            setTimeout(obj + ".remove(" + i + ")", time);
        }
        setTimeout(obj + ".randomShow(" + time + ")", time);
    }
};
GoogleMap.prototype.remove = function(i) {
    with(this) mapObj.removeOverlay(markerarr[i]);
};

GoogleMap.prototype.getPoint = function(address) {
	var geocoder = new GClientGeocoder();
	var obj = this.mapObj
	geocoder.getLatLng(
		address,
		function(point) {
			if (point) {
				var zoom = obj.getZoom();
				if ( zoom < 9) obj.setCenter(point,9);
				obj.openInfoWindow( point, address );
			}
		}
    );
}

GoogleMap.prototype.clear = function() 
{
	with(this) {
		mapObj.clearOverlays();
		icons = {};
		markerarr = [];
		pointarr = [];
		htmlarr = [];
	}
};

GoogleMap.prototype.htmlfunc = function(arr) {
	var str = '<table cellpadding="3" cellspacing="0"><tr><td width="55"><div class="avatar48"><a href="space.php?uid='+arr.uid;
	str += '">'+arr.pic;
	str += '</a></div></td><td><p>我是<a href="space.php?uid='+arr.uid;
	str += '">'+arr.username;
	str += '</a>: '+arr.sex;
	str += '</p><p>QQ号:'+arr.qq;
	str += '</p><p>[<a href="cp.php?ac=poke&op=send&uid='+arr.uid;
	str += '" id="a_poke_'+arr.uid;
	str += '" onclick="ajaxmenu(event, this.id, 99999, \'\', -1)" title="打招呼">打招呼</a>]';
	str += '[<a href="cp.php?ac=pm&&uid='+arr.uid;
	str += '" id="a_pm_'+arr.uid;
	str += '" onclick="ajaxmenu(event, this.id, 99999, \'\', -1)" title="发消息">发消息</a>]';
	str += '</p><p>'+arr.node;
	str += '</p></td></tr></table>';
	return str;
}

function addLoadEvent(func)
{    
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            oldonload();
            func();
        }
    }
}