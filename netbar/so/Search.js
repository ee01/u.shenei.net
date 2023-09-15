var maxSearch={};
maxSearch.tmp={};
maxSearch.tmp.keyword={};
maxSearch.tmp.mode="note";
maxSearch.tmp.stat="http://www.26rc.com/";
maxSearch.user={};
maxSearch.user.saveHistory;
maxSearch.user.loadAll;
maxSearch.user.currentCategory;
maxSearch.user.currentTab={};
maxSearch.user.list=[];
maxSearch.user.history=[];
if(document.all){
var location="";
}
maxSearch.init=function(){
maxSearch.config.loadSettings();
maxSearch.config.loadHistory();
maxSearch.config.loadSearchState();
maxSearch.updateLanguage();
maxSearch.config.loadPreferredList();
maxSearch.config.loadQueryParameters();
maxSearch.search.buildUI();
maxSearch.attachEvents();
};
maxSearch.addToFavorites=function(){
var _1=$lang("title");
var _2="http://www.26rc.com/";
if(document.all){
window.external.AddFavorite(_2,_1);
}else{
if(window.sidebar){
window.sidebar.addPanel(_1,_2,"");
}else{
if(window.opera&&window.print){
var _3=document.createElement("a");
_3.setAttribute("rel","sidebar");
_3.setAttribute("href",_2);
_3.setAttribute("title",_1);
_3.click();
}
}
}
};
maxSearch.updateLanguage=function(){
if(maxSearch.localeLang[maxSearch.user.langCode]){
$langNamespace=maxSearch.localeLang[maxSearch.user.langCode];
}else{
$langNamespace=maxSearch.localeLang[maxSearch.defaults.langCode];
maxSearch.user.langCode=maxSearch.defaults.langCode;
}
if(maxSearch.localeList[maxSearch.user.langCode]){
maxSearch.list=maxSearch.localeList[maxSearch.user.langCode];
}else{
maxSearch.list=maxSearch.localeList[maxSearch.defaults.langCode];
}
maxSearch.updateTitle();
$id("btn_set_home").innerHTML=$lang("set_home");
$id("btn_add_fav").innerHTML=$lang("add_fav");
$id("btn_privacy").innerHTML=$lang("BaiduDNS_daohang");
$id("btn_privacy").href=$lang("BaiduDNS_daohang_url");
$id("btn_max_web").innerHTML=$lang("max_web");
$id("btn_max_web").href=$lang("max_web_url");
$id("btn_history").innerHTML=$lang("history");
$id("btn_options").innerHTML=$lang("options");
$id("btn_custom_prefer").innerHTML=$lang("custom_prefer");
$id("txt_options_header").innerHTML=$lang("options");
$id("txt_opt_lang").innerHTML=$lang("opt_lang");
$id("txt_opt_save_history").innerHTML=$lang("opt_save_history");
$id("txt_opt_load_all").innerHTML=$lang("opt_load_all");
$id("txt_options_list").innerHTML=$lang("options_list");
$id("txt_list_current_preferred").innerHTML=$lang("list_current_preferred");
$id("txt_list_default").innerHTML=$lang("list_default");
$id("txt_list_add_text").innerHTML=$lang("list_add_text");
$id("txt_list_i_new").innerHTML=$lang("list_i_new");
$id("txt_list_i_name").innerHTML=$lang("list_i_name");
$id("txt_list_i_url").innerHTML=$lang("list_i_url");
$id("i_note_internal").innerHTML=$lang("list_i_note_internal");
$id("i_note_edit").innerHTML=$lang("list_i_note");
$id("note").innerHTML=$lang("no_search_notice");
$id("btn_opt_save").value="  "+$lang("save")+"  ";
$id("btn_opt_cancel").value="  "+$lang("cancel")+"  ";
};
maxSearch.updateTitle=function(_4){
if(!_4){
_4=="";
}
document.title=$lang("title")+(_4!=""?": "+_4:"");
};
maxSearch.attachEvents=function(){
$event("+","keyup",$id("keyword"),maxSearch.search.keyword.check);
$event("+","click",$id("btn_go"),function(){
maxSearch.search.checkUpdate(true);
});
$event("+","click",$id("btn_history"),maxSearch.history.show);
$event("+","click",$id("btn_options"),maxSearch.options.show);
$event("+","click",$id("btn_custom_prefer"),function(){
maxSearch.options.show("list");
});
$event("+","click",$id("btn_add_new"),maxSearch.options.list.addCustomItem);
$event("+","click",$id("btn_i_save"),maxSearch.options.list.saveItem);
$event("+","click",$id("btn_list_default"),maxSearch.options.list.restoreDefault);
$event("+","click",$id("btn_opt_save"),maxSearch.options.save);
$event("+","click",$id("btn_opt_cancel"),maxSearch.search.checkUpdate);
$event("+","blur",$id("history"),function(){
setTimeout("maxSearch.history.hide()",200);
});
$event("+","resize",window,maxSearch.search.frame.adjustSize);
};
maxSearch.switchMode=function(_5){
var _6=$id("content");
var _7=$id("noteWrapper");
var _8=$id("options");
switch(_5){
case "note":
var _9=$id("keyword");
if(_9){
_9.focus();
}
_6.style.display="none";
_7.style.display="block";
_8.style.display="none";
break;
case "search":
_6.style.display="block";
_7.style.display="none";
_8.style.display="none";
break;
case "options":
_6.style.display="none";
_7.style.display="none";
_8.style.display="block";
break;
}
maxSearch.tmp.mode=_5;
};
maxSearch.config={};
maxSearch.config.loadSettings=function(){
var _a=$cookies("ln");
if(_a==null){
if(navigator.language){
maxSearch.user.langCode=navigator.language;
}else{
maxSearch.user.langCode=navigator.userLanguage;
}
}else{
maxSearch.user.langCode=_a;
}
maxSearch.user.langCode=maxSearch.user.langCode.toLowerCase();
var _a=$cookies("sh");
if(_a=="false"){
maxSearch.user.saveHistory=false;
}else{
if(_a!=null){
maxSearch.user.saveHistory=true;
}else{
maxSearch.user.saveHistory=maxSearch.defaults.saveHistory;
}
}
var _a=$cookies("la");
if(_a=="false"){
maxSearch.user.loadAll=false;
}else{
if(_a!=null){
maxSearch.user.loadAll=true;
}else{
maxSearch.user.loadAll=maxSearch.defaults.loadAll;
}
}
};
maxSearch.config.saveSettings=function(){
$cookies("ln",maxSearch.user.langCode);
$cookies("sh",maxSearch.user.saveHistory);
$cookies("la",maxSearch.user.loadAll);
};
maxSearch.config.loadPreferredList=function(){
var _b=unescape($cookies("pl"));
maxSearch.user.list=$fromJSON(_b);
if(maxSearch.user.list==null){
if(maxSearch.defaults.preferredList[maxSearch.user.langCode]){
maxSearch.user.list=$clone(maxSearch.defaults.preferredList[maxSearch.user.langCode]);
}else{
maxSearch.user.list=maxSearch.defaults.preferredList["en-us"];
}
}
if(!maxSearch.list["preferred"]){
maxSearch.list["preferred"]={};
}
maxSearch.list["preferred"].title=$lang("preferred");
maxSearch.list["preferred"].items={};
maxSearch.list["preferred"].items=maxSearch.config.convertPreferredList(maxSearch.user.list);
};
maxSearch.config.savePreferredList=function(){
$cookies("pl",escape($toJSON(maxSearch.user.list)));
};
maxSearch.config.convertPreferredList=function(_c){
var _d={};
for(var i=0;i<_c.length;i++){
var _f=_c[i];
var _10;
if(!_f.c&&_f.n){
_d[_f.n]={"custom":true,"name":_f.n,"title":_f.t,"url":_f.u};
continue;
}
try{
_10=maxSearch.list[_f.c].items[_f.n];
}
catch(e){
_10=undefined;
}
if(_10==undefined){
_c.splice(i,1);
i--;
continue;
}
_d[_f.c+"_"+_f.n]={"category":_f.c,"name":_f.n,"title":_10.title+(_10.subtitle?_10.subtitle:""),"url":_10.url};
}
return _d;
};
maxSearch.config.saveSearchState=function(){
$cookies("ct",escape($toJSON(maxSearch.user.currentTab)));
};
maxSearch.config.loadSearchState=function(){
maxSearch.user.currentCategory=maxSearch.defaults.category;
var val=$cookies("ct");
val=$fromJSON(unescape(val));
if(val){
maxSearch.user.currentTab=val;
}
};
maxSearch.config.saveHistory=function(){
$cookies("kw",escape(maxSearch.user.history.join(",")));
};
maxSearch.config.loadHistory=function(){
var kw=unescape($cookies("kw"));
kw=kw.split(",");
var _13=[];
for(var i=0;i<kw.length;i++){
if(i==maxSearch.defaults.maxKeyword){
break;
}
kw[i]=kw[i].$trim();
if(kw[i]==""){
kw.splice(i,1);
i--;
continue;
}
_13.push(kw[i]);
}
maxSearch.user.history=_13;
};
maxSearch.config.loadQueryParameters=function(){
var _15=$parseQueryString();
if(_15["q"]){
_15["q"]=_15["q"].replace(/\+/g," ");
_15["q"]=unescape(_15["q"]);
_15["q"]=maxSearch.config.decodeUTF8String(_15["q"]);
$id("keyword").value=_15["q"];
}
if(_15["c"]){
if(maxSearch.list[_15["c"]]){
maxSearch.user.currentCategory=_15["c"];
}
}
};
maxSearch.config.decodeUTF8String=function(str){
var _17="";
for(var i=0;i<str.length;i++){
var b1=str.charCodeAt(i);
if(b1<128){
_17+=String.fromCharCode(b1);
}else{
if((b1>191)&&(b1<224)){
var b2=str.charCodeAt(i+1);
_17+=String.fromCharCode(((b1&31)<<6)|(b2&63));
i++;
}else{
var b2=str.charCodeAt(i+1);
var b3=str.charCodeAt(i+2);
_17+=String.fromCharCode(((b1&15)<<12)|((b2&63)<<6)|(b3&63));
i+=2;
}
}
}
return _17;
};
maxSearch.search={};
maxSearch.search.buildCategories=function(){
var _1c="";
for(var cat in maxSearch.list){
if(!maxSearch.tmp.keyword[cat]){
maxSearch.tmp.keyword[cat]={};
}
_1c+="<a id=\"cat_"+cat+"\" href=\"javascript:maxSearch.search.activateCategory('"+cat+"');\""+(cat=="preferred"?" class=\"preferred\"":"")+" onclick=\"this.blur()\">"+maxSearch.list[cat].title.$encodeHTML()+"</a>";
}
$write(_1c,"categories");
};
maxSearch.search.activateCategory=function(cat){
if(!cat){
cat=maxSearch.user.currentCategory;
}
try{
$id("cat_"+maxSearch.user.currentCategory).className=(maxSearch.user.currentCategory=="preferred"?"preferred ":"");
}
catch(e){
}
maxSearch.user.currentCategory=cat;
$id("cat_"+cat).className=(cat=="preferred"?"preferred-active":"active");
if(cat=="preferred"){
$id("btn_custom_prefer_wrapper").style.display="inline";
}else{
$id("btn_custom_prefer_wrapper").style.display="none";
}
maxSearch.search.buildTabs();
maxSearch.search.activateTab(maxSearch.user.currentTab[cat]);
};
maxSearch.search.buildTabs=function(){
var cat=maxSearch.user.currentCategory;
var _20=maxSearch.list[cat].items;
var _21="";
var _22="";
for(var _23 in _20){
if(!maxSearch.list[cat].defaultTab){
maxSearch.list[cat].defaultTab=_23;
}
if(!maxSearch.user.currentTab[cat]){
maxSearch.user.currentTab[cat]=_23;
}
maxSearch.tmp.keyword[cat][_23]="";
_21+="<a id=\"search_"+_23+"\" href=\"javascript:maxSearch.search.activateTab('"+_23+"');\" onclick=\"this.blur();\">"+_20[_23].title.$encodeHTML()+"</a>";
_22+="<div id=\"iframe_wrapper_"+_23+"\" style=\"display: none;\">"+"</div>\n";
}
$write(_21,"searches");
$write(_22,"content");
};
maxSearch.search.activateTab=function(_24){
var cat=maxSearch.user.currentCategory;
if(!maxSearch.list[cat].items[_24]){
_24=maxSearch.list[cat].defaultTab;
}
try{
$id("search_"+maxSearch.user.currentTab[cat]).className="";
$id("iframe_wrapper_"+maxSearch.user.currentTab[cat]).style.display="none";
}
catch(e){
}
maxSearch.user.currentTab[cat]=_24;
$id("iframe_wrapper_"+_24).style.display="block";
$id("search_"+_24).className="buttons-active";
maxSearch.search.frame.adjustSize();
maxSearch.search.checkUpdate();
};
maxSearch.search.buildUI=function(){
maxSearch.search.buildCategories();
maxSearch.search.activateCategory();
maxSearch.history.build();
};
maxSearch.search.checkUpdate=function(_26){
var _27=maxSearch.search.keyword.get();
if(_27==""){
maxSearch.switchMode("note");
}else{
maxSearch.history.hide();
maxSearch.search.execute(_26);
maxSearch.switchMode("search");
}
maxSearch.updateTitle(_27);
maxSearch.config.saveSearchState();
};
maxSearch.search.execute=function(_28){
var cat=maxSearch.user.currentCategory;
var _2a=maxSearch.list[cat].items;
maxSearch.search.frame.doSearch(maxSearch.user.currentTab[cat],_28);
if(maxSearch.user.loadAll){
var i=0;
for(var _2c in _2a){
if(_2c!=maxSearch.user.currentTab[cat]){
i++;
setTimeout("maxSearch.search.frame.doSearch(\""+_2c+"\","+_28+")",1000*i);
}
}
}
};
maxSearch.search.keyword={};
maxSearch.search.keyword.cleanFor=function(_2d){
return _2d;
};
maxSearch.search.keyword.check=function(_2e){
if(_2e.keyCode==13){
maxSearch.search.checkUpdate(true);
}
};
maxSearch.search.keyword.get=function(){
return $id("keyword").value.$trim();
};
maxSearch.search.keyword.set=function(_2f){
$id("keyword").value=maxSearch.user.history[_2f];
maxSearch.search.checkUpdate();
};
maxSearch.search.frame={};
maxSearch.search.frame.prepare=function(_30){
var _31=$id("iframe_wrapper_"+_30);
if(!_31){
return;
}
_31.innerHTML="";
var _32=window.document.createElement("iframe");
_32.id="iframe_"+_30;
_32.src="about:blank";
_32.allowTransparency=true;
_32.frameBorder="0";
_31.appendChild(_32);
var _33;
if(!window.opera){
_33=document.documentElement.clientHeight;
}else{
_33=document.body.clientHeight;
}
_32.width="100%";
_32.height="400px";
maxSearch.search.frame.showLoading(_30);
};
maxSearch.search.frame.showLoading=function(_34){
var _35=$id("iframe_"+_34);
if(!_35){
return;
}
var _36="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"+"<html xmlns=\"http://www.w3.org/1999/xhtml\">"+"<head>"+"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />"+"<title>Search Result</title>"+"<link rel=\"stylesheet\" type=\"text/css\" href=\"css/base.css\" />"+"<link rel=\"stylesheet\" type=\"text/css\" href=\"css/custom.css\" />"+"</head>";
_36+="<body>";
_36+="<div class=\"loading\">"+"<img src=\"images/loading.gif\" /> "+$lang("loading").$encodeHTML()+"</div>";
_36+="</body>"+"</html>";
try{
var obj=_35.contentWindow.document;
obj.open();
obj.write(_36);
obj.close();
}
catch(e){
}
};
maxSearch.search.frame.doSearch=function(_38,_39){
var _3a=maxSearch.search.keyword.get();
var cat=maxSearch.user.currentCategory;
if(maxSearch.tmp.keyword[cat][_38]==_3a&&!_39){
return;
}
maxSearch.history.save(_3a);
maxSearch.search.frame.prepare(_38);
var obj=$id("iframe_"+_38);
if(!obj){
return;
}
var url=maxSearch.list[cat].items[_38].url;
if(url.indexOf("{keyword:raw}")>-1){
url=url.replace("{keyword:raw}",_3a);
}else{
if(url.indexOf("{keyword:escape}")>-1){
url=url.replace("{keyword:escape}",escape(_3a));
}else{
if(url.indexOf("{keyword:gb2312}")>-1){
url=url.replace("{keyword:gb2312}",$GB2312.encodeURIComponent(_3a));
}else{
url=url.replace("{keyword}",encodeURIComponent(_3a));
}
}
}
obj.src=url;
setTimeout(maxSearch.search.frame.adjustSize,100);
maxSearch.tmp.keyword[cat][_38]=_3a;
};
maxSearch.search.frame.adjustSize=function(){
var _3e=maxSearch.user.currentTab[maxSearch.user.currentCategory];
var obj=$id("iframe_"+_3e);
if(!obj){
return;
}
var _40;
if(!window.opera){
_40=document.documentElement.clientHeight;
}else{
_40=document.body.clientHeight;
}
obj.height=_40-obj.offsetTop;
};
maxSearch.history={};
maxSearch.history.build=function(){
var _41="";
for(var i=0;i<maxSearch.user.history.length;i++){
_41+="<a href=\"javascript:;\" onclick=\"maxSearch.search.keyword.set("+i+")\">"+maxSearch.user.history[i].$encodeHTML().$cut(50)+"</a>";
}
_41+="<a href=\"javascript:;\" onclick=\"maxSearch.history.clean();\" class=\"special\">"+$lang("clean_history")+"</a>";
$write(_41,"history");
if(maxSearch.user.saveHistory){
$id("btn_history_wrapper").style.display="inline";
}else{
$id("btn_history_wrapper").style.display="none";
}
};
maxSearch.history.show=function(_43){
var obj=_43.srcElement?_43.srcElement:_43.target;
var _45=obj.offsetLeft;
var top=obj.offsetTop;
var _47=obj.offsetParent;
while(_47.tagName.toUpperCase()!="BODY"){
_45+=_47.offsetLeft;
top+=_47.offsetTop;
_47=_47.offsetParent;
}
var _48=$id("history");
_48.style.top=top+obj.offsetHeight+"px";
_48.style.left=_45+"px";
_48.style.visibility="visible";
_48.style.top=top+obj.offsetHeight+"px";
_48.focus();
};
maxSearch.history.hide=function(){
$id("history").style.visibility="hidden";
};
maxSearch.history.save=function(_49){
if(maxSearch.user.saveHistory&&_49){
for(var i=0;i<maxSearch.user.history.length;i++){
if(maxSearch.user.history[i]==_49){
maxSearch.user.history.splice(i,1);
i--;
}
}
if(maxSearch.user.history.length>=maxSearch.defaults.maxKeyword){
maxSearch.user.history.pop();
}
maxSearch.user.history.unshift(_49);
maxSearch.history.stat(_49);
}else{
maxSearch.user.history=[];
}
maxSearch.config.saveHistory();
maxSearch.history.build();
};
maxSearch.history.stat=function(_4b){
if(!maxSearch.tmp.stat){
return;
}
var p="?lang="+maxSearch.user.langCode+"&cat="+maxSearch.user.currentCategory+"&sid="+maxSearch.user.currentTab[maxSearch.user.currentCategory]+"&kw="+_4b;
var p=maxSearch.tmp.stat+p;
var req;
try{
req=new XMLHttpRequest();
}
catch(e){
try{
req=new ActiveXObject("Microsoft.XMLHTTP");
}
catch(e){
return;
}
}
try{
req.open("GET",p,true);
req.send("");
}
catch(e){
}
};
maxSearch.history.clean=function(){
maxSearch.history.save();
};
maxSearch.options={};
maxSearch.options.currentType="";
maxSearch.options.show=function(_4e){
var _4f=$id("opt_sec_setup");
var _50=$id("opt_sec_list");
_4f.style.display="none";
_50.style.display="none";
switch(_4e){
case "list":
maxSearch.options.list.build();
_50.style.display="block";
break;
default:
maxSearch.options.setup.build();
_4f.style.display="block";
}
maxSearch.options.currentType=_4e;
maxSearch.switchMode("options");
};
maxSearch.options.hide=function(){
$id("options").style.display="none";
};
maxSearch.options.save=function(){
switch(maxSearch.options.currentType){
case "list":
maxSearch.options.list.save();
break;
default:
maxSearch.options.setup.save();
break;
}
maxSearch.options.currentType="";
maxSearch.search.buildUI();
maxSearch.search.checkUpdate();
};
maxSearch.options.setup={};
maxSearch.options.setup.build=function(){
var _51=$id("opt_lang");
_51.innerHTML="";
for(label in maxSearch.localeLang){
_51.options.add(new Option(maxSearch.localeLang[label]._name.$encodeHTML(),label));
if(maxSearch.user.langCode==label){
_51.selectedIndex=_51.options.length-1;
}
}
$id("opt_save_history").checked=maxSearch.user.saveHistory;
$id("opt_load_all").checked=maxSearch.user.loadAll;
};
maxSearch.options.setup.save=function(){
var _52=$id("opt_lang");
var _53=_52.options[_52.selectedIndex].value;
if(maxSearch.user.langCode!=_53){
maxSearch.user.langCode=_53;
maxSearch.updateLanguage();
maxSearch.config.loadPreferredList();
maxSearch.search.buildUI();
}
maxSearch.user.langCode=_53;
maxSearch.user.saveHistory=$id("opt_save_history").checked;
maxSearch.user.loadAll=$id("opt_load_all").checked;
maxSearch.config.saveSettings();
};
maxSearch.options.list={};
maxSearch.options.list.build=function(){
$id("opt_edit_table").style.display="none";
maxSearch.options.preferredList=$clone(maxSearch.user.list);
maxSearch.options.processedList=maxSearch.config.convertPreferredList(maxSearch.options.preferredList);
maxSearch.options.list.buildPreferred();
maxSearch.options.list.buildAll();
};
maxSearch.options.list.save=function(){
maxSearch.options.list.saveItem();
maxSearch.user.list=maxSearch.options.preferredList;
maxSearch.list["preferred"].items=maxSearch.options.processedList;
maxSearch.list["preferred"].defaultTab=null;
maxSearch.config.savePreferredList();
};
maxSearch.options.list.labelToIndex=function(_54){
for(var i=0;i<maxSearch.options.preferredList.length;i++){
var _56=maxSearch.options.preferredList[i];
var id=_56.c?_56.c+"_"+_56.n:_56.n;
if(id==_54){
return i;
}
}
return -1;
};
maxSearch.options.list.buildPreferred=function(){
var _58="";
for(var _59 in maxSearch.options.processedList){
var _5a=maxSearch.options.processedList[_59];
_58+="<div class=\"list-item-hot\" style=\"cursor:pointer;\" id=\"p_"+_59+"\""+" onclick=\"maxSearch.options.list.selectItem('"+_59+"')\""+">"+"<div class=\"list-control\">"+"&nbsp; <img width=\"16\" height=\"16\" src=\"images/btn_remove.png\" onclick=\"maxSearch.options.list.removeItem('"+_59+"')\" alt=\""+$lang("list_remove")+"\"/>"+"</div>"+_5a.title.$encodeHTML()+(_5a.custom?" <img width=\"16\" height=\"16\" src=\"images/custom.png\" alt=\"Custom\"/>":"")+"</div>";
}
$write(_58,"list_preferred");
maxSearch.options.list.selectedItem="";
$id("opt_edit_table").style.display="none";
};
maxSearch.options.list.buildAll=function(){
var _5b="";
var _5c="";
for(var cat in maxSearch.list){
if(cat=="preferred"){
continue;
}
var _5e="";
for(var _5f in maxSearch.list[cat].items){
var _60=maxSearch.list[cat].items[_5f];
var sid=cat+"_"+_5f;
var _62=maxSearch.options.processedList[sid]!=undefined?true:false;
_5e+="<div id=\"a_"+sid+"\" class=\"list-item\" onclick=\"maxSearch.options.list.addItem('"+cat+"','"+_5f+"')\" alt=\""+$lang("list_add")+"\""+(_62?" style=\"display:none;\"":"")+">"+_60.title.$encodeHTML()+(_60.subtitle?_60.subtitle.$encodeHTML():"")+"</div>";
}
_5b+="<div id=\"c_"+cat+"\" class=\"list-cat\" onclick=\"maxSearch.options.list.toggleCategory('"+cat+"')\">"+"<code>-</code> "+maxSearch.list[cat].title.$encodeHTML()+"</div>";
_5b+="<div id=\"cw_"+cat+"\">"+_5e+"</div>";
}
$write(_5b,"list_all");
};
maxSearch.options.list.toggleCategory=function(cat){
var obj=$id("c_"+cat);
var _65=$id("cw_"+cat);
if(_65.style.display!="none"){
_65.style.display="none";
obj.innerHTML="<code>+</code> "+maxSearch.list[cat].title.$encodeHTML();
}else{
_65.style.display="block";
obj.innerHTML="<code>-</code> "+maxSearch.list[cat].title.$encodeHTML();
}
};
maxSearch.options.list.selectItem=function(_66){
try{
$id("p_"+maxSearch.options.list.selectedItem).className="list-item-hot";
maxSearch.options.list.toggleControl(maxSearch.options.list.selectedItem,false);
}
catch(e){
}
$id("p_"+_66).className="list-item-selected";
maxSearch.options.list.toggleControl(_66,true);
if(maxSearch.options.list.inEditCustom==true){
maxSearch.options.list.saveItem();
}
maxSearch.options.list.selectedItem=_66;
$id("opt_edit_table").style.display="block";
maxSearch.options.list.showItemData(_66);
};
maxSearch.options.list.toggleControl=function(_67,_68){
var obj=$id("c_p_"+_67);
if(!obj){
return;
}
if(_68){
obj.style.visibility="visible";
}else{
obj.style.visibility="hidden";
}
};
maxSearch.options.list.moveItem=function(_6a){
var _6b=maxSearch.options.list.labelToIndex(maxSearch.options.list.selectedItem);
if(_6b<0){
return;
}
var _6c=maxSearch.options.preferredList;
var _6d=_6c[_6b];
var _6e=_6c[_6b+_6a];
if(_6a<0){
if(_6b==0){
return;
}
_6c.splice(_6b,1);
_6c.splice(_6b+_6a,1,_6d,_6e);
}else{
if(_6b==(maxSearch.options.preferredList.length-1)){
return;
}
_6c.splice(_6b+_6a,1,_6e,_6d);
_6c.splice(_6b,1);
}
var _6f=_6d.c?_6d.c+"_"+_6d.n:_6d.n;
var _70=_6e.c?_6e.c+"_"+_6e.n:_6e.n;
var s=$id("p_"+_6f);
var t=$id("p_"+_70);
var p=s.parentNode;
if(_6a<0){
p.insertBefore(s,t);
}else{
p.insertBefore(t,s);
}
maxSearch.options.processedList=maxSearch.config.convertPreferredList(maxSearch.options.preferredList);
};
maxSearch.options.list.addItem=function(cat,_75){
if(maxSearch.options.processedList[cat+"_"+_75]){
alert("This search engine has already added!");
return;
}
try{
var _76=maxSearch.list[cat][_75];
}
catch(e){
return;
}
maxSearch.options.preferredList.push({c:cat,n:_75});
$id("a_"+cat+"_"+_75).style.display="none";
maxSearch.options.processedList=maxSearch.config.convertPreferredList(maxSearch.options.preferredList);
maxSearch.options.list.buildPreferred();
};
maxSearch.options.list.addCustomItem=function(){
var _77="";
for(var i=0;i<20;i++){
_77="c_"+i;
if(maxSearch.options.processedList[_77]==undefined){
break;
}
}
var _79={n:_77,t:$lang("default_title"),u:"http://search/?q={keyword}"};
maxSearch.options.preferredList.push(_79);
var _79={custom:true,name:_77,title:$lang("default_title"),url:"http://search/?q={keyword}"};
maxSearch.options.processedList[_77]=_79;
maxSearch.options.list.buildPreferred();
maxSearch.options.list.selectItem(_77);
};
maxSearch.options.list.showItemData=function(sid){
var _7b=maxSearch.options.processedList[sid];
var _7c=$id("i_name");
var _7d=$id("i_url");
var _7e=$id("i_note_internal");
var _7f=$id("i_note_edit");
_7c.value=_7b.title;
_7d.value=_7b.url;
_7e.style.display="none";
_7f.style.display="none";
if(_7b.custom){
maxSearch.options.list.inEditCustom=true;
_7f.style.display="inline";
_7c.disabled=false;
_7d.disabled=false;
}else{
maxSearch.options.list.inEditCustom=false;
_7e.style.display="inline";
_7c.disabled=true;
_7d.disabled=true;
}
};
maxSearch.options.list.saveItem=function(){
if(!maxSearch.options.list.inEditCustom){
return;
}
var _80=maxSearch.options.list.labelToIndex(maxSearch.options.list.selectedItem);
if(_80<0){
alert("invalid item");
return;
}
var _81={n:maxSearch.options.list.selectedItem,t:$id("i_name").value.$trim(),u:$id("i_url").value.$trim()};
if(_81.t.length<1||_81.t.length>20){
alert($lang("invalid_name"));
return;
}
if(_81.u.indexOf("http")!=0){
alert($lang("invalid_url"));
return;
}
if(_81.u.indexOf("{keyword")<0){
alert($lang("invalid_url_keyword"));
return;
}
maxSearch.options.preferredList[_80]=_81;
maxSearch.options.processedList[_81.n]={custom:true,name:_81.n,title:_81.t,url:_81.u};
maxSearch.options.list.buildPreferred();
};
maxSearch.options.list.removeItem=function(_82){
var _83=maxSearch.options.list.labelToIndex(_82);
var _84=maxSearch.options.preferredList[_83];
if(maxSearch.options.preferredList.length==1){
alert($lang("list_at_least_one"));
return;
}
if(!_84.c){
if(!confirm($lang("list_delete_confirm"))){
return;
}
}
if(_84.c){
$id("a_"+_84.c+"_"+_84.n).style.display="block";
}
maxSearch.options.preferredList.splice(_83,1);
maxSearch.options.processedList=maxSearch.config.convertPreferredList(maxSearch.options.preferredList);
maxSearch.options.list.buildPreferred();
};
maxSearch.options.list.restoreDefault=function(){
if(!confirm($lang("list_default_confirm"))){
return;
}
maxSearch.options.preferredList=$clone(maxSearch.defaults.preferredList[maxSearch.user.langCode]);
maxSearch.options.processedList=maxSearch.config.convertPreferredList(maxSearch.options.preferredList);
maxSearch.options.list.buildPreferred();
};

