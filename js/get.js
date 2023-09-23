var toolbar = {
	cookie:new Object(),
	Init:function(){
//		toolbar.afficheTotal = arguments[0];
//		toolbar.afficheStart();
		var tempFunc = document.onclick;
		document.onclick = function(){
			if (typeof (tempFunc) == "function"){
				tempFunc();
			}
			if(isLand()){
				toolbar.UserListClose();
				toolbar.KeyListClose();
			}
		}
	},
	KeyListShow:function(e){
		e = e?e:window.event;
		e.cancelBubble = true;
		if(document.getElementById("shortcutkey_ul").style.display != ""){
			document.getElementById("shortcutkey_ul").style.display = "";
		}else{
			document.getElementById("shortcutkey_ul").style.display = "none";
		}
	},
	KeyListClose:function(){
		document.getElementById("shortcutkey_ul").style.display = "none";
	},
	UserListShow:function(e){
		e = e?e:window.event;
		e.cancelBubble = true;
		if(document.getElementById("chageuser").style.display != ""){
			document.getElementById("chageuser").style.display = "";
		}else{
			document.getElementById("chageuser").style.display = "none";
		}
	},
	UserListClose:function(){
		document.getElementById("chageuser").style.display = "none";
	},
	ChangeUser:function(uid,uname){
		var yohoData = new CookieDataStore("yoho");
		toolbar.cookie = yohoData;
		toolbar.cookie.set("nowUid",uid);
		toolbar.cookie.set("userName",uname);
		toolbar.cookie.save();
		top.location.reload(true);
	},
	OutLand:function(){
		toolbar.cookie.clear();
		toolbar.cookie.save();
		try{
		setCookie("yoho", "",-1,"default.htm");
		}catch(e){}
		top.location.reload(true);
	},
	IsRUserList:false,
	InsertSmallToImgUrl:function(imgUrl,small){
		var index = imgUrl.lastIndexOf(".");
		imgUrl = imgUrl.substring(0,index)+small+imgUrl.substr(index);
		return imgUrl;
	}
//	},
//	afficheTotal:0,
//	afficheNowId:0,
//	intervalObj:new Object,
//	affichePause:function(){
//		try{
//			window.clearInterval(toolbar.IntervalObj);
//		}catch(e){}
//	},
//	afficheStart:function(){
//		if(toolbar.afficheTotal < 2){
//			return;
//		}
//		toolbar.IntervalObj = window.setInterval("toolbar.nextAffiche();",6000);
//	},
//	nextAffiche:function(){
//		var id;
//		if(toolbar.afficheTotal<1)
//			return;
//		if(toolbar.afficheNowId+1==toolbar.afficheTotal){
//			id=0;
//		}else{
//			id=toolbar.afficheNowId+1;
//		}
//		
//		toolbar.moveAffiche(toolbar.afficheNowId,id);
//		toolbar.afficheNowId = id;
//	},
//	moveAffiche:function(_old,_new){
//		toolbar.setAfficheOpacity(document.getElementById("toolbarAfficheList"+_old),9);
//		
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),8);",100);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),7);",150);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),6);",200);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),5);",250);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),4);",300);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),3);",350);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),2);",400);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),1);",450);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_old+"\"),0);",500);
//		window.setTimeout("toolbar.chanageAffiche(document.getElementById(\"toolbarAfficheList"+_old+"\"),document.getElementById(\"toolbarAfficheList"+_new+"\"));",600);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),3);",700);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),4);",800);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),5);",900);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),6);",950);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),7);",1000);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),8);",1050);
//		window.setTimeout("toolbar.setAfficheOpacity(document.getElementById(\"toolbarAfficheList"+_new+"\"),9);",1100);
//	},
//	chanageAffiche:function(oldObj,newObj){
//		try{
//			oldObj.style.display="none";
//			newObj.style.display="";
//			toolbar.setAfficheOpacity(newObj,2);
//		}catch(e){alert(e)}
//	},
//	setAfficheOpacity:function(obj,num){
//		if(window.ActiveXObject){
//			obj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+num+"0)";
//		}else{
//			obj.style.opacity = "0."+num;
//		}
//	}
}

function getCookie(name) {
	var text = document.cookie;
	var index = text.indexOf(name + "=");
	if (index < 0) return(null);
	var start = index + name.length + 1;
	var end = text.indexOf(";", start);
	if (end < 0) end = text.length;
	var value = text.substring(start, end);
	return decodeURIComponent(value);
}
function setCookie(name, value, expires, path, domain, secure){
	var text = name + "=" + value;
	if (expires){
		var currentDate = new Date();
		var expireDate = new Date( currentDate.getTime() + expires*24*60*60*1000 );
		text = text + ";expires=" + expireDate.toGMTString();
	}
	if (path) text = text + ";path=" + path;
	if (domain) text = text + ";domain=" + domain;
	if (secure) text = text + ";secure";
	document.cookie = text;
}
function removeCookie(name){
	setCookie(name, "", -1);
}
function CookieDataStore(name){
	this.name = name;
	this.load();
}
CookieDataStore.prototype.load = function (){
	// create a key/value store
	this.data = new Object();
	// get cookie text
	var text = getCookie(this.name);
	if (text == null) return;
	// populate the store using the cookie text
	var data = text.split('&');
	for (var i=0; i<data.length; i++){
		var datum = data[i];
		var index = datum.indexOf('=');
		if (index > 0){
			var key = datum.substring(0,index);
			var value = datum.substring(index+1);
			this.data[key] = value;
		}
	}
}
CookieDataStore.prototype.save = function(){
	// prepare a cookie string
	var text = "";
	// construct the string
	for (var key in this.data){
		var type = typeof(this.data[key]);
		if(type != "object" && type != "function"){
			var datum = key + "=" + encodeURIComponent(this.data[key]);
			text = text + datum + "&";
		}
	}
	var expires;
	try{
		if(this.data["expires"] == "1")
			expires = 2*365;
		else
			expires =false;
	}catch(e){
		expires =false;
	}
	// set it
	//setCookie(this.name, text,expires,"default.htm",".yoho.cn");
	setCookie(this.name, text,expires,"default.htm",".yoho.cn");
}
CookieDataStore.prototype.clear = function(){
	this.data = new Object();
}
CookieDataStore.prototype.set = function(key, value){
	this.data[key] = value;
}
CookieDataStore.prototype.get = function(key){
	return(this.data[key]);
}
CookieDataStore.prototype.remove = function(key){
	delete(this.data[key]);
}
CookieDataStore.prototype.count = function(){
	var i = 0;
	for (var key in this.data) {
		var type = typeof(this.data[key]);
		if(type != "object" && type != "function"){
			i++;
		}
	}
	return(i);				
}
function isLand(){
	var userid;
	try{
		var userInfos = new CookieDataStore("yoho");
		userid = parseInt(userInfos.data["nowUid"]);
	}catch(e){userid = null}
	if(typeof(userid) != "number" || isNaN(userid) || userid < 1 ){return false;}
	else{return true;}
}
  
function display(num){
        for(var i=2;i<24;i++){
            if(i!=num){
                $("width_li"+i).style.display = "none";
                $("narrow_li"+i).style.display = "";
            }
            else{
                $("width_li"+i).style.display = "";
                $("narrow_li"+i).style.display = "none";
            }
        }
    }
    
    function display1(num){
        for(var i=2;i<24;i++){
            if(i!=num){
                $("width_li_b"+i).style.display = "none";
                $("narrow_li_b"+i).style.display = "";
            }
            else{
                $("width_li_b"+i).style.display = "";
                $("narrow_li_b"+i).style.display = "none";
            }
        }
    }
	
	
   function switchList(){
        if(arguments[0]==1){
            $("week_li").className = "redon";
            $("total_li").className = "";
            $("week_ul").style.display = "";
            $("total_ul").style.display = "none";
        }
        else if(arguments[0]==2){
            $("week_li").className = "";
            $("total_li").className = "redon";
            $("week_ul").style.display = "none";
            $("total_ul").style.display = "";
        }
    }