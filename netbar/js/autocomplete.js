try{
	var gHost = window.location.host;
	gHost = gHost.substr(5);	
}catch(e){}

var Prototype={Version:"1.5.0_rc0",ScriptFragment:"(?:<script.*?>)((\n|\r|.)*?)(?:</script>)",emptyFunction:function(){
},K:function(x){
return x;
}};
var Class={create:function(){
return function(){
this.initialize.apply(this,arguments);
};
}};
Object.extend=function(_2,_3){
for(var _4 in _3){
_2[_4]=_3[_4];
}
return _2;
};
Function.prototype.bind=function(){
var _5=this,_6=$A(arguments),_7=_6.shift();
return function(){
return _5.apply(_7,_6.concat($A(arguments)));
};
};
Function.prototype.bindAsEventListener=function(_8){
var _9=this;
return function(_a){
return _9.call(_8,_a||window.event);
};
};
var Try={these:function(){
var _b;
for(var i=0;i<arguments.length;i++){
var _d=arguments[i];
try{
_b=_d();
break;
}
catch(e){
}
}
return _b;
}};
Object.extend(String.prototype,{gsub:function(_e,_f){
var _10="",_11=this,_12;
_f=arguments.callee.prepareReplacement(_f);
while(_11.length>0){
if(_12=_11.match(_e)){
_10+=_11.slice(0,_12.index);
_10+=(_f(_12)||"").toString();
_11=_11.slice(_12.index+_12[0].length);
}else{
_10+=_11,_11="";
}
}
return _10;
},truncate:function(_13,_14){
_13=_13||30;
_14=_14===undefined?"...":_14;
return this.length>_13?this.slice(0,_13-_14.length)+_14:this;
},strip:function(){
return this.replace(/^\s+/,"").replace(/\s+$/,"");
},stripTags:function(){
return this.replace(/<\/?[^>]+>/gi,"");
},stripScripts:function(){
return this.replace(new RegExp(Prototype.ScriptFragment,"img"),"");
},extractScripts:function(){
var _15=new RegExp(Prototype.ScriptFragment,"img");
var _16=new RegExp(Prototype.ScriptFragment,"im");
return (this.match(_15)||[]).map(function(_17){
return (_17.match(_16)||["",""])[1];
});
},evalScripts:function(){
return this.extractScripts().map(function(_18){
return eval(_18);
});
},escapeHTML:function(){
var div=document.createElement("div");
div.style.zIndex = 100;//todo
var _1a=document.createTextNode(this);
div.appendChild(_1a);
return div.innerHTML;
},unescapeHTML:function(){
var div=document.createElement("div");
div.style.zIndex = 100;//todo
div.innerHTML=this.stripTags();
return div.childNodes[0]?div.childNodes[0].nodeValue:"";
},toQueryParams:function(){
var _1c=this.match(/^\??(.*)$/)[1].split("&");
return _1c.inject({},function(_1d,_1e){
var _1f=_1e.split("=");
_1d[_1f[0]]=_1f[1];
return _1d;
});
},toArray:function(){
return this.split("");
}});
String.prototype.parseQuery=String.prototype.toQueryParams;
var $break=new Object();
var $continue=new Object();
var Enumerable={each:function(_20){
var _21=0;
try{
this._each(function(_22){
try{
_20(_22,_21++);
}
catch(e){
if(e!=$continue){
throw e;
}
}
});
}
catch(e){
if(e!=$break){
throw e;
}
}
},all:function(_23){
var _24=true;
this.each(function(_25,_26){
_24=_24&&!!(_23||Prototype.K)(_25,_26);
if(!_24){
throw $break;
}
});
return _24;
},any:function(_27){
var _28=true;
this.each(function(_29,_2a){
if(_28=!!(_27||Prototype.K)(_29,_2a)){
throw $break;
}
});
return _28;
},collect:function(_2b){
var _2c=[];
this.each(function(_2d,_2e){
_2c.push(_2b(_2d,_2e));
});
return _2c;
},detect:function(_2f){
var _30;
this.each(function(_31,_32){
if(_2f(_31,_32)){
_30=_31;
throw $break;
}
});
return _30;
},findAll:function(_33){
var _34=[];
this.each(function(_35,_36){
if(_33(_35,_36)){
_34.push(_35);
}
});
return _34;
},grep:function(_37,_38){
var _39=[];
this.each(function(_3a,_3b){
var _3c=_3a.toString();
if(_3c.match(_37)){
_39.push((_38||Prototype.K)(_3a,_3b));
}
});
return _39;
},include:function(_3d){
var _3e=false;
this.each(function(_3f){
if(_3f==_3d){
_3e=true;
throw $break;
}
});
return _3e;
},inject:function(_40,_41){
this.each(function(_42,_43){
_40=_41(_40,_42,_43);
});
return _40;
},invoke:function(_44){
var _45=$A(arguments).slice(1);
return this.collect(function(_46){
return _46[_44].apply(_46,_45);
});
},max:function(_47){
var _48;
this.each(function(_49,_4a){
_49=(_47||Prototype.K)(_49,_4a);
if(_48==undefined||_49>=_48){
_48=_49;
}
});
return _48;
},min:function(_4b){
var _4c;
this.each(function(_4d,_4e){
_4d=(_4b||Prototype.K)(_4d,_4e);
if(_4c==undefined||_4d<_4c){
_4c=_4d;
}
});
return _4c;
},partition:function(_4f){
var _50=[],_51=[];
this.each(function(_52,_53){
((_4f||Prototype.K)(_52,_53)?_50:_51).push(_52);
});
return [_50,_51];
},pluck:function(_54){
var _55=[];
this.each(function(_56,_57){
_55.push(_56[_54]);
});
return _55;
},reject:function(_58){
var _59=[];
this.each(function(_5a,_5b){
if(!_58(_5a,_5b)){
_59.push(_5a);
}
});
return _59;
},sortBy:function(_5c){
return this.collect(function(_5d,_5e){
return {value:_5d,criteria:_5c(_5d,_5e)};
}).sort(function(_5f,_60){
var a=_5f.criteria,b=_60.criteria;
return a<b?-1:a>b?1:0;
}).pluck("value");
},toArray:function(){
return this.collect(Prototype.K);
},zip:function(){
var _63=Prototype.K,_64=$A(arguments);
if(typeof _64.last()=="function"){
_63=_64.pop();
}
var _65=[this].concat(_64).map($A);
return this.map(function(_66,_67){
return _63(_65.pluck(_67));
});
},inspect:function(){
return "#<Enumerable:"+this.toArray().inspect()+">";
}};
Object.extend(Enumerable,{map:Enumerable.collect,find:Enumerable.detect,select:Enumerable.findAll,member:Enumerable.include,entries:Enumerable.toArray});
var $A=Array.from=function(_68){
if(!_68){
return [];
}
if(_68.toArray){
return _68.toArray();
}else{
var _69=[];
for(var i=0;i<_68.length;i++){
_69.push(_68[i]);
}
return _69;
}
};
Object.extend(Array.prototype,Enumerable);
if(!Array.prototype._reverse){
Array.prototype._reverse=Array.prototype.reverse;
}
Object.extend(Array.prototype,{_each:function(_6b){
for(var i=0;i<this.length;i++){
_6b(this[i]);
}
},clear:function(){
this.length=0;
return this;
},first:function(){
return this[0];
},last:function(){
return this[this.length-1];
},compact:function(){
return this.select(function(_6d){
return _6d!=undefined||_6d!=null;
});
},flatten:function(){
return this.inject([],function(_6e,_6f){
return _6e.concat(_6f&&_6f.constructor==Array?_6f.flatten():[_6f]);
});
},without:function(){
var _70=$A(arguments);
return this.select(function(_71){
return !_70.include(_71);
});
},indexOf:function(_72){
for(var i=0;i<this.length;i++){
if(this[i]==_72){
return i;
}
}
return -1;
},reverse:function(_74){
return (_74!==false?this:this.toArray())._reverse();
},inspect:function(){
return "["+this.map(Object.inspect).join(", ")+"]";
}});
var Hash={_each:function(_75){
for(var key in this){
var _77=this[key];
if(typeof _77=="function"){
continue;
}
var _78=[key,_77];
_78.key=key;
_78.value=_77;
_75(_78);
}
},keys:function(){
return this.pluck("key");
},values:function(){
return this.pluck("value");
},merge:function(_79){
return $H(_79).inject($H(this),function(_7a,_7b){
_7a[_7b.key]=_7b.value;
return _7a;
});
},toQueryString:function(){
return this.map(function(_7c){
return _7c.map(encodeURIComponent).join("=");
}).join("&");
},inspect:function(){
return "#<Hash:{"+this.map(function(_7d){
return _7d.map(Object.inspect).join(": ");
}).join(", ")+"}>";
}};
function $H(_7e){
var _7f=Object.extend({},_7e||{});
Object.extend(_7f,Enumerable);
Object.extend(_7f,Hash);
return _7f;
}
ObjectRange=Class.create();
Object.extend(ObjectRange.prototype,Enumerable);
Object.extend(ObjectRange.prototype,{initialize:function(_80,end,_82){
this.start=_80;
this.end=end;
this.exclusive=_82;
},_each:function(_83){
var _84=this.start;
do{
_83(_84);
_84=_84.succ();
}while(this.include(_84));
},include:function(_85){
if(_85<this.start){
return false;
}
if(this.exclusive){
return _85<this.end;
}
return _85<=this.end;
}});
var Ajax={getTransport:function(){
return Try.these(function(){
return new XMLHttpRequest();
},function(){
return new ActiveXObject("Msxml2.XMLHTTP");
},function(){
return new ActiveXObject("Microsoft.XMLHTTP");
})||false;
},activeRequestCount:0};
Ajax.Responders={responders:[],_each:function(_86){
this.responders._each(_86);
},register:function(_87){
if(!this.include(_87)){
this.responders.push(_87);
}
},unregister:function(_88){
this.responders=this.responders.without(_88);
},dispatch:function(_89,_8a,_8b,_8c){
this.each(function(_8d){
if(_8d[_89]&&typeof _8d[_89]=="function"){
try{
_8d[_89].apply(_8d,[_8a,_8b,_8c]);
}
catch(e){
}
}
});
}};
Object.extend(Ajax.Responders,Enumerable);
Ajax.Responders.register({onCreate:function(){
Ajax.activeRequestCount++;
},onComplete:function(){
Ajax.activeRequestCount--;
}});
Ajax.Base=function(){
};
Ajax.Base.prototype={setOptions:function(_8e){
this.options={method:"post",asynchronous:true,contentType:"application/x-www-form-urlencoded",parameters:""};
Object.extend(this.options,_8e||{});
},responseIsSuccess:function(){
return this.transport.status==undefined||this.transport.status==0||(this.transport.status>=200&&this.transport.status<300);
},responseIsFailure:function(){
return !this.responseIsSuccess();
}};
Ajax.Request=Class.create();
Ajax.Request.Events=["Uninitialized","Loading","Loaded","Interactive","Complete"];
Ajax.Request.prototype=Object.extend(new Ajax.Base(),{initialize:function(url,_90){
this.transport=Ajax.getTransport();
this.setOptions(_90);
this.request(url);
},request:function(url){
var _92=this.options.parameters||"";
if(_92.length>0){
_92+="&_=";
}
try{
this.url=url;
if(this.options.method=="get"&&_92.length>0){
this.url+=(this.url.match(/\?/)?"&":"?")+_92;
}
Ajax.Responders.dispatch("onCreate",this,this.transport);
this.transport.open(this.options.method,this.url,this.options.asynchronous);
if(this.options.asynchronous){
this.transport.onreadystatechange=this.onStateChange.bind(this);
setTimeout((function(){
this.respondToReadyState(1);
}).bind(this),10);
}
this.setRequestHeaders();
var _93=this.options.postBody?this.options.postBody:_92;
this.transport.send(this.options.method=="post"?_93:null);
}
catch(e){
this.dispatchException(e);
}
},setRequestHeaders:function(){
var _94=["X-Requested-With","XMLHttpRequest","X-Prototype-Version",Prototype.Version,"Accept","text/javascript, text/html, application/xml, text/xml, */*"];
if(this.options.method=="post"){
_94.push("Content-type",this.options.contentType);
if(this.transport.overrideMimeType){
_94.push("Connection","close");
}
}
if(this.options.requestHeaders){
_94.push.apply(_94,this.options.requestHeaders);
}
for(var i=0;i<_94.length;i+=2){
this.transport.setRequestHeader(_94[i],_94[i+1]);
}
},onStateChange:function(){
var _96=this.transport.readyState;
if(_96!=1){
this.respondToReadyState(this.transport.readyState);
}
},header:function(_97){
try{
return this.transport.getResponseHeader(_97);
}
catch(e){
}
},evalJSON:function(){
try{
return eval("("+this.header("X-JSON")+")");
}
catch(e){
}
},evalResponse:function(){
try{
return eval(this.transport.responseText);
}
catch(e){
this.dispatchException(e);
}
},respondToReadyState:function(_98){
var _99=Ajax.Request.Events[_98];
var _9a=this.transport,_9b=this.evalJSON();
if(_99=="Complete"){
try{
(this.options["on"+this.transport.status]||this.options["on"+(this.responseIsSuccess()?"Success":"Failure")]||Prototype.emptyFunction)(_9a,_9b);
}
catch(e){
this.dispatchException(e);
}
if((this.header("Content-type")||"").match(/^text\/javascript/i)){
this.evalResponse();
}
}
try{
(this.options["on"+_99]||Prototype.emptyFunction)(_9a,_9b);
Ajax.Responders.dispatch("on"+_99,this,_9a,_9b);
}
catch(e){
this.dispatchException(e);
}
if(_99=="Complete"){
this.transport.onreadystatechange=Prototype.emptyFunction;
}
},dispatchException:function(_9c){
(this.options.onException||Prototype.emptyFunction)(this,_9c);
Ajax.Responders.dispatch("onException",this,_9c);
}});
Ajax.Updater=Class.create();
Object.extend(Object.extend(Ajax.Updater.prototype,Ajax.Request.prototype),{initialize:function(_9d,url,_9f){
this.containers={success:_9d.success?$(_9d.success):$(_9d),failure:_9d.failure?$(_9d.failure):(_9d.success?null:$(_9d))};
this.transport=Ajax.getTransport();
this.setOptions(_9f);
var _a0=this.options.onComplete||Prototype.emptyFunction;
this.options.onComplete=(function(_a1,_a2){
this.updateContent();
_a0(_a1,_a2);
}).bind(this);
this.request(url);
},updateContent:function(){
var _a3=this.responseIsSuccess()?this.containers.success:this.containers.failure;
var _a4=this.transport.responseText;
if(!this.options.evalScripts){
_a4=_a4.stripScripts();
}
if(_a3){
if(this.options.insertion){
new this.options.insertion(_a3,_a4);
}else{
Element.update(_a3,_a4);
}
}
if(this.responseIsSuccess()){
if(this.onComplete){
setTimeout(this.onComplete.bind(this),10);
}
}
}});
Ajax.PeriodicalUpdater=Class.create();
Ajax.PeriodicalUpdater.prototype=Object.extend(new Ajax.Base(),{initialize:function(_a5,url,_a7){
this.setOptions(_a7);
this.onComplete=this.options.onComplete;
this.frequency=(this.options.frequency||2);
this.decay=(this.options.decay||1);
this.updater={};
this.container=_a5;
this.url=url;
this.start();
},start:function(){
this.options.onComplete=this.updateComplete.bind(this);
this.onTimerEvent();
},stop:function(){
this.updater.onComplete=undefined;
clearTimeout(this.timer);
(this.onComplete||Prototype.emptyFunction).apply(this,arguments);
},updateComplete:function(_a8){
if(this.options.decay){
this.decay=(_a8.responseText==this.lastText?this.decay*this.options.decay:1);
this.lastText=_a8.responseText;
}
this.timer=setTimeout(this.onTimerEvent.bind(this),this.decay*this.frequency*1000);
},onTimerEvent:function(){
this.updater=new Ajax.Updater(this.container,this.url,this.options);
}});
function $(){
var _a9=[],_aa;
for(var i=0;i<arguments.length;i++){
_aa=arguments[i];
if(typeof _aa=="string"){
_aa=document.getElementById(_aa);
}
_a9.push(Element.extend(_aa));
}
return _a9.length<2?_a9[0]:_a9;
}
document.getElementsByClassName=function(_ac,_ad){
var _ae=($(_ad)||document.body).getElementsByTagName("*");
return $A(_ae).inject([],function(_af,_b0){
if(_b0.className.match(new RegExp("(^|\\s)"+_ac+"(\\s|$)"))){
_af.push(Element.extend(_b0));
}
return _af;
});
};
if(!window.Element){
var Element=new Object();
}
Element.extend=function(_b1){
if(!_b1){
return;
}
if(_nativeExtensions){
return _b1;
}
if(!_b1._extended&&_b1.tagName&&_b1!=window){
var _b2=Element.Methods,_b3=Element.extend.cache;
for(property in _b2){
var _b4=_b2[property];
if(typeof _b4=="function"){
_b1[property]=_b3.findOrStore(_b4);
}
}
}
_b1._extended=true;
return _b1;
};
Element.extend.cache={findOrStore:function(_b5){
return this[_b5]=this[_b5]||function(){
return _b5.apply(null,[this].concat($A(arguments)));
};
}};
Element.Methods={visible:function(_b6){
return $(_b6).style.display!="none";
},toggle:function(){
for(var i=0;i<arguments.length;i++){
var _b8=$(arguments[i]);
Element[Element.visible(_b8)?"hide":"show"](_b8);
}
},hide:function(){
for(var i=0;i<arguments.length;i++){
var _ba=$(arguments[i]);
_ba.style.display="none";
}
},show:function(){
for(var i=0;i<arguments.length;i++){
var _bc=$(arguments[i]);
_bc.style.display="";
}
},remove:function(_bd){
_bd=$(_bd);
_bd.parentNode.removeChild(_bd);
},update:function(_be,_bf){
$(_be).innerHTML=_bf.stripScripts();
setTimeout(function(){
_bf.evalScripts();
},10);
},replace:function(_c0,_c1){
_c0=$(_c0);
if(_c0.outerHTML){
_c0.outerHTML=_c1.stripScripts();
}else{
var _c2=_c0.ownerDocument.createRange();
_c2.selectNodeContents(_c0);
_c0.parentNode.replaceChild(_c2.createContextualFragment(_c1.stripScripts()),_c0);
}
setTimeout(function(){
_c1.evalScripts();
},10);
},getHeight:function(_c3){
_c3=$(_c3);
return _c3.offsetHeight;
},classNames:function(_c4){
return new Element.ClassNames(_c4);
},hasClassName:function(_c5,_c6){
if(!(_c5=$(_c5))){
return;
}
return Element.classNames(_c5).include(_c6);
},addClassName:function(_c7,_c8){
if(!(_c7=$(_c7))){
return;
}
return Element.classNames(_c7).add(_c8);
},removeClassName:function(_c9,_ca){
if(!(_c9=$(_c9))){
return;
}
return Element.classNames(_c9).remove(_ca);
},cleanWhitespace:function(_cb){
_cb=$(_cb);
for(var i=0;i<_cb.childNodes.length;i++){
var _cd=_cb.childNodes[i];
if(_cd.nodeType==3&&!/\S/.test(_cd.nodeValue)){
Element.remove(_cd);
}
}
},empty:function(_ce){
return $(_ce).innerHTML.match(/^\s*$/);
},childOf:function(_cf,_d0){
_cf=$(_cf),_d0=$(_d0);
while(_cf=_cf.parentNode){
if(_cf==_d0){
return true;
}
}
return false;
},scrollTo:function(_d1){
_d1=$(_d1);
var x=_d1.x?_d1.x:_d1.offsetLeft,y=_d1.y?_d1.y:_d1.offsetTop;
window.scrollTo(x,y);
},getStyle:function(_d4,_d5){
_d4=$(_d4);
var _d6=_d4.style[_d5.camelize()];
if(!_d6){
if(document.defaultView&&document.defaultView.getComputedStyle){
var css=document.defaultView.getComputedStyle(_d4,null);
_d6=css?css.getPropertyValue(_d5):null;
}else{
if(_d4.currentStyle){
_d6=_d4.currentStyle[_d5.camelize()];
}
}
}
if(window.opera&&["left","top","right","bottom"].include(_d5)){
if(Element.getStyle(_d4,"position")=="static"){
_d6="auto";
}
}
return _d6=="auto"?null:_d6;
},setStyle:function(_d8,_d9){
_d8=$(_d8);
for(var _da in _d9){
_d8.style[_da.camelize()]=_d9[_da];
}
},getDimensions:function(_db){
_db=$(_db);
if(Element.getStyle(_db,"display")!="none"){
return {width:_db.offsetWidth,height:_db.offsetHeight};
}
var els=_db.style;
var _dd=els.visibility;
var _de=els.position;
els.visibility="hidden";
els.position="absolute";
els.display="";
var _df=_db.clientWidth;
var _e0=_db.clientHeight;
els.display="none";
els.position=_de;
els.visibility=_dd;
return {width:_df,height:_e0};
},makePositioned:function(_e1){
_e1=$(_e1);
var pos=Element.getStyle(_e1,"position");
if(pos=="static"||!pos){
_e1._madePositioned=true;
_e1.style.position="relative";
if(window.opera){
_e1.style.top=0;
_e1.style.left=0;
}
}
},undoPositioned:function(_e3){
_e3=$(_e3);
if(_e3._madePositioned){
_e3._madePositioned=undefined;
_e3.style.position=_e3.style.top=_e3.style.left=_e3.style.bottom=_e3.style.right="";
}
},makeClipping:function(_e4){
_e4=$(_e4);
if(_e4._overflow){
return;
}
_e4._overflow=_e4.style.overflow;
if((Element.getStyle(_e4,"overflow")||"visible")!="hidden"){
_e4.style.overflow="hidden";
}
},undoClipping:function(_e5){
_e5=$(_e5);
if(_e5._overflow){
return;
}
_e5.style.overflow=_e5._overflow;
_e5._overflow=undefined;
}};
Object.extend(Element,Element.Methods);
var _nativeExtensions=false;
if(!HTMLElement&&/Konqueror|Safari|KHTML/.test(navigator.userAgent)){
var HTMLElement={};
HTMLElement.prototype=document.createElement("div").__proto__;
}
Element.addMethods=function(_e6){
Object.extend(Element.Methods,_e6||{});
if(typeof HTMLElement!="undefined"){
var _e6=Element.Methods,_e7=Element.extend.cache;
for(property in _e6){
var _e8=_e6[property];
if(typeof _e8=="function"){
HTMLElement.prototype[property]=_e7.findOrStore(_e8);
}
}
_nativeExtensions=true;
}
};
Element.addMethods();
var Toggle=new Object();
Toggle.display=Element.toggle;
Element.ClassNames=Class.create();
Element.ClassNames.prototype={initialize:function(_e9){
this.element=$(_e9);
},_each:function(_ea){
this.element.className.split(/\s+/).select(function(_eb){
return _eb.length>0;
})._each(_ea);
},set:function(_ec){
this.element.className=_ec;
},add:function(_ed){
if(this.include(_ed)){
return;
}
this.set(this.toArray().concat(_ed).join(" "));
},remove:function(_ee){
if(!this.include(_ee)){
return;
}
this.set(this.select(function(_ef){
return _ef!=_ee;
}).join(" "));
},toString:function(){
return this.toArray().join(" ");
}};
Object.extend(Element.ClassNames.prototype,Enumerable);
function $$(){
return $A(arguments).map(function(_f0){
return _f0.strip().split(/\s+/).inject([null],function(_f1,_f2){
var _f3=new Selector(_f2);
return _f1.map(_f3.findElements.bind(_f3)).flatten();
});
}).flatten();
}
var Form={serialize:function(_f4){
var _f5=Form.getElements($(_f4));
var _f6=new Array();
for(var i=0;i<_f5.length;i++){
var _f8=Form.Element.serialize(_f5[i]);
if(_f8){
_f6.push(_f8);
}
}
return _f6.join("&");
},getElements:function(_f9){
_f9=$(_f9);
var _fa=new Array();
for(var _fb in Form.Element.Serializers){
var _fc=_f9.getElementsByTagName(_fb);
for(var j=0;j<_fc.length;j++){
_fa.push(_fc[j]);
}
}
return _fa;
},getInputs:function(_fe,_ff,name){
_fe=$(_fe);
var _101=_fe.getElementsByTagName("input");
if(!_ff&&!name){
return _101;
}
var _102=new Array();
for(var i=0;i<_101.length;i++){
var _104=_101[i];
if((_ff&&_104.type!=_ff)||(name&&_104.name!=name)){
continue;
}
_102.push(_104);
}
return _102;
},disable:function(form){
var _106=Form.getElements(form);
for(var i=0;i<_106.length;i++){
var _108=_106[i];
_108.blur();
_108.disabled="true";
}
},enable:function(form){
var _10a=Form.getElements(form);
for(var i=0;i<_10a.length;i++){
var _10c=_10a[i];
_10c.disabled="";
}
},findFirstElement:function(form){
return Form.getElements(form).find(function(_10e){
return _10e.type!="hidden"&&!_10e.disabled&&["input","select","textarea"].include(_10e.tagName.toLowerCase());
});
},reset:function(form){
$(form).reset();
}};
Form.Element={serialize:function(_110){
_110=$(_110);
var _111=_110.tagName.toLowerCase();
var _112=Form.Element.Serializers[_111](_110);
if(_112){
var key=encodeURIComponent(_112[0]);
if(key.length==0){
return;
}
if(_112[1].constructor!=Array){
_112[1]=[_112[1]];
}
return _112[1].map(function(_114){
return key+"="+encodeURIComponent(_114);
}).join("&");
}
},getValue:function(_115){
_115=$(_115);
var _116=_115.tagName.toLowerCase();
var _117=Form.Element.Serializers[_116](_115);
if(_117){
return _117[1];
}
}};
if(!window.Event){
var Event=new Object();
}
Object.extend(Event,{element:function(_118){
return _118.target||_118.srcElement;
},observers:false,_observeAndCache:function(_119,name,_11b,_11c){
if(!this.observers){
this.observers=[];
}
if(_119.addEventListener){
this.observers.push([_119,name,_11b,_11c]);
_119.addEventListener(name,_11b,_11c);
}else{
if(_119.attachEvent){
this.observers.push([_119,name,_11b,_11c]);
_119.attachEvent("on"+name,_11b);
}
}
},unloadCache:function(){
if(!Event.observers){
return;
}
for(var i=0;i<Event.observers.length;i++){
Event.stopObserving.apply(this,Event.observers[i]);
Event.observers[i][0]=null;
}
Event.observers=false;
},observe:function(_11e,name,_120,_121){
var _11e=$(_11e);
_121=_121||false;
if(name=="keypress"&&(navigator.appVersion.match(/Konqueror|Safari|KHTML/)||_11e.attachEvent)){
name="keydown";
}
this._observeAndCache(_11e,name,_120,_121);
},stopObserving:function(_122,name,_124,_125){
var _122=$(_122);
_125=_125||false;
if(name=="keypress"&&(navigator.appVersion.match(/Konqueror|Safari|KHTML/)||_122.detachEvent)){
name="keydown";
}
if(_122.removeEventListener){
_122.removeEventListener(name,_124,_125);
}else{
if(_122.detachEvent){
_122.detachEvent("on"+name,_124);
}
}
}});
if(navigator.appVersion.match(/\bMSIE\b/)){
Event.observe(window,"unload",Event.unloadCache,false);
}
var Position={includeScrollOffsets:false,prepare:function(){
this.deltaX=window.pageXOffset||document.documentElement.scrollLeft||document.body.scrollLeft||0;
this.deltaY=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop||0;
},cumulativeOffset:function(_126){
var _127=0,_128=0;
do{
_127+=_126.offsetTop||0;
_128+=_126.offsetLeft||0;
_126=_126.offsetParent;
}while(_126);
return [_128,_127];
}};
if(/Konqueror|Safari|KHTML/.test(navigator.userAgent)){
Position.cumulativeOffset=function(_129){
var _12a=0,_12b=0;
do{
_12a+=_129.offsetTop||0;
_12b+=_129.offsetLeft||0;
if(_129.offsetParent==document.body){
if(Element.getStyle(_129,"position")=="absolute"){
break;
}
}
_129=_129.offsetParent;
}while(_129);
return [_12b,_12a];
};
}
var versinInfo={Version:"1.0"};
var KEY=Class.create();
Object.extend(KEY,{BACKSPACE:8,TAB:9,RETURN:13,ESC:27,LEFT:37,UP:38,RIGHT:39,DOWN:40,DELETE:46,PAGE_UP:33,PAGE_DOWN:34,END:35,HOME:36,INSERT:45,SHIFT:16,CTRL:17,ALT:18});
var timeoutInt=10;
var upDownTimeOut=10000;
var queryURL="/suggest/suggest.s?query=";
var indexAttr="aa_index";
var highlightStyle="aa_highlight";
var onSelAttr="onSelect";
var overcount=0;
var pxlf=1,pxtd=1;
var CONSTANTDOTJS=1;
var CtrlZNum=10;
var paras;
var func=null;
var AutoComplete=Class.create();
AutoComplete.prototype={initialize:function(_12c,_12d,func,_12f){
this.txtBox=$(_12d);
this.txtBox.onkeyup=this.onkeyup.bindAsEventListener(this);
this.txtBox.onkeydown=this.onkeydown.bindAsEventListener(this);
this.txtBox.onblur=this.hide.bindAsEventListener(this);
Event.observe(this.txtBox,"dblclick",this.doRequestAndSel.bindAsEventListener(this));
Event.observe(document,"click",this.hide2.bindAsEventListener(this));
this.func=func;
this.paras=_12f;
this.count=0;
this.suggestServer="";
this.searchServer="";
this.keyfrom=gHost;
this.suggestDiv=document.getElementById("suggestDiv");
this.suggestFlag=true;
this.downFlag=true;
this.clickEnabled=true;
this.iframe=document.createElement("iframe");
this.iframe.style.position="absolute";
this.iframe.style.margin="1px 0 0 0";
this.iframe.style.padding="0";
this.iframe.id="myId1";
Element.hide(this.iframe);
this.suggestDiv.appendChild(this.iframe);
this.scriptDiv=document.createElement("div");
this.scriptDiv.style.zIndex = 100;//todo
this.suggestDiv.appendChild(this.scriptDiv);
this.toShowDiv=document.createElement("div");
this.toShowDiv.style.position="absolute";
this.toShowDiv.style.zIndex = 100; //todo
this.toShowDiv.id="myId2";
Element.hide(this.toShowDiv);
this.suggestDiv.appendChild(this.toShowDiv);
this.bufferDiv=document.createElement("div");
this.bufferDiv.style.zIndex = 100; //todo
this.visible=false;
this.lastQueryStr="";
this.initView=0;
this.oldInputValue="";
this.oldInputValueForCtrlZ=new Array(CtrlZNum);
this.oldInputValueForCtrlZnum=0;
this.hasPressCtrlZFlag=false;
this.selFlag=false;
this.firstShowFlag=true;
this.isOnsubmitFlag=false;
this.pressCtrlCFlag=false;
this.hasPressCtrlFlag=false;
this.keyReturnDownFlag=false;
window.onresize=this.winReSize.bind(this);
this.myform=_12c;
this.myform.onsubmit=this.submitFunction.bind(this);
this.txtBox.onsubmit=this.submitFunction.bind(this);
setInterval(this.showSuggestIcon.bind(this),200);
this.cleanup();
},init:function(){
if(this.txtBox.value==""){
this.doRequest(";");
}else{
this.doRequest(this.txtBox.value);
}
},setKeyFrom:function(_130){
this.keyfrom=_130;
},suggestCtLog:function(href,q,_133){
if(this.keyfrom==gHost){
var i=new Image();
i.src="http://www.yodao.com/ctlog?q="+encodeURIComponent(q)+"&url="+encodeURIComponent(href)+"&action="+_133+"&type="+this.keyfrom;
return true;
}
},getAbsolutePos:function(e){
var l=e.offsetLeft;
var t=e.offsetTop;
while(e=e.offsetParent){
l+=e.offsetLeft;
t+=e.offsetTop;
}
var pos=new Object();
pos.x=l;
pos.y=t;
return pos;
},showSuggestIcon:function(){
var _139=document.getElementById("suggestImage");
var _13a=this.txtBox;
var _13b=this.getAbsolutePos(_13a);
_139.style.left=(_13b.x+_13a.offsetWidth-13*1.5)+"px";
_139.style.top=(_13b.y+(_13a.offsetHeight-10)/2)+"px";
_139.style.display="";
},doRequestAndSel:function(){
this.doRequest();
if(this.txtBox.createTextRange){
}else{
if(this.txtBox.setSelectionRange){
}
}
return true;
},hide2:function(){
if(this.clickEnabled){
this.downFlag=true;
this.hide();
this.clickEnabled=false;
setTimeout(this.enableClick.bind(this),100);
}
},enableClick:function(){
this.clickEnabled=true;
},submitFunction:function(){
this.hide();
this.isOnsubmitFlag=true;
},winReSize:function(){
if(this.visible){
this.show();
}
if(this.func!=null){
this.func(this.paras);
}
},onkeyup:function(key){
switch(key.keyCode){
case KEY.PAGE_UP:
case KEY.PAGE_DOWN:
case KEY.END:
case KEY.HOME:
case KEY.INSERT:
case KEY.CTRL:
case KEY.ALT:
case KEY.LEFT:
return false;
case KEY.SHIFT:
break;
case KEY.BACKSPACE:
this.firstShowFlag=true;
break;
case KEY.TAB:
this.hide();
return true;
case KEY.RIGHT:
if(!this.selFlag){
if(this.currentNodeIndex==-1){
return false;
}else{
break;
}
}else{
this.selFlag=false;
}
break;
case KEY.UP:
return false;
case KEY.DOWN:
if(!this.visible){
if(this.toShowDiv.childNodes.length>0){
if(this.txtBox.value==this.lastQueryStr){
this.show();
return false;
}else{
break;
}
}
break;
}else{
return false;
}
case KEY.ESC:
this.hide();
return false;
case 32:
if(this.hasPressCtrlFlag){
this.hasPressCtrlFlag=false;
return true;
}
break;
case KEY.RETURN:
if(this.lastQueryStr!=this.txtBox.value&&this.currentNodeIndex==-1){
this.keyReturnDownFlag=false;
break;
}else{
if(this.visible&&this.currentNodeIndex>-1){
this.select();
this.keyReturnDownFlag=false;
return false;
}else{
if(this.currentNodeIndex==-1){
if(!this.visible){
if(!this.isOnsubmitFlag){
this.show();
}else{
this.isOnsubmitFlag=false;
}
}
this.keyReturnDownFlag=false;
return true;
}else{
this.keyReturnDownFlag=false;
return true;
}
}
}
default:
}
if(this.timeoutId!=0){
clearTimeout(this.timeoutId);
}
this.timeoutId=setTimeout(this.doRequest.bind(this),timeoutInt);
},onkeydown:function(key){
if(key.ctrlKey){
this.hasPressCtrlFlag=true;
}else{
this.hasPressCtrlFlag=false;
}
if(key.ctrlKey&&(key.keyCode==67||key.keyCode==99)){
this.pressCtrlCFlag=true;
return true;
}else{
if(key.ctrlKey&&(key.keyCode==90||key.keyCode==122)){
if(this.oldInputValueForCtrlZnum>0){
this.txtBox.value=this.oldInputValueForCtrlZ[--this.oldInputValueForCtrlZnum];
this.hasPressCtrlZFlag=true;
}
return false;
}else{
if(key.ctrlKey&&(key.keyCode==16)){
return true;
}else{
if(key.ctrlKey&&key.keyCode==32){
return true;
}
}
}
}
switch(key.keyCode){
case KEY.RIGHT:
return true;
case KEY.TAB:
return true;
case KEY.UP:
if(this.visible){
this.up();
}
return false;
case KEY.DOWN:
if(this.visible){
this.down();
}
return false;
case KEY.RETURN:
if(this.visible&&this.currentNodeIndex>-1){
	this.hide();
	this.keyReturnDownFlag=false;
	return true;
}
return true;
case KEY.ESC:
return false;
default:
}
},isValidNode:function(node){
return (node.nodeType==1)&&(node.getAttribute(onSelAttr));
},getSiteResult:function(str){
var re=new RegExp("<[aA].*>.*</[aA]>");
m=re.exec(str);
if(m==null){
return null;
}else{
var re1=new RegExp("[hH][rR][eE][fF]=.*><[fF][oO][nN][tT]");
var m1=re1.exec(m);
if(m1[0].length<=13){
return null;
}
var res=m1[0].substr(6,m1[0].length-13);
return res;
}
},getDomainType:function(){
var str=document.domain;
var m=str.split(".");
if((m[0]!=null)&&(m[0].length>=3)&&(m[0].length<5)){
if(m[0]=="www"){
return "web";
}
return m[0];
}
return "image";
},select:function(){
if(this.currentNode()){
var str=this.currentNode().innerHTML;
var _147=this.getSiteResult(str);
if(_147!=null){
this.suggestCtLog(_147,this.txtBox.value,"suggest.direct");
window.open(_147);
this.hide();
//document.getElementById("yodaoForm").submit();
}else{
var stat=this.currentNode().getAttribute(onSelAttr);
try{
eval(stat);
if(this.oldInputValue!=this.txtBox.value){
if(this.oldInputValueForCtrlZnum<CtrlZNum){
this.oldInputValueForCtrlZ[this.oldInputValueForCtrlZnum]=this.oldInputValue;
this.oldInputValueForCtrlZnum++;
}else{
for(var i=0;i<CtrlZNum-1;++i){
this.oldInputValueForCtrlZ[i]=this.oldInputValueForCtrlZ[i+1];
}
this.oldInputValueForCtrlZ[9]=this.oldInputValue;
}
}
this.oldInputValue=this.txtBox.value;
}
catch(e){
}
this.hide();
var _14a=this.getDomainType();
if(_14a!=null){
var _14b=this.getSearchPrefix();
var _14c=encodeURI(_14b+this.keyfrom+".suggest&q="+this.txtBox.value);
window.open(_14c);
this.hide();
//document.getElementById("yodaoForm").submit();
return;
//document.location=_14c;
}
}
}
},doRequest:function(_14d){
this.firstShowFlag=true;
this.count++;
if(this.oldInputValue!=this.txtBox.value&&!this.hasPressCtrlZFlag){
if(this.oldInputValueForCtrlZnum<CtrlZNum){
this.oldInputValueForCtrlZ[this.oldInputValueForCtrlZnum]=this.oldInputValue;
this.oldInputValueForCtrlZnum++;
}else{
for(var i=0;i<=CtrlZNum-1;++i){
this.oldInputValueForCtrlZ[i]=this.oldInputValueForCtrlZ[i+1];
}
this.oldInputValueForCtrlZ[9]=this.oldInputValue;
}
}
this.hasPressCtrlZFlag=false;
this.oldInputValue=this.txtBox.value;
if(typeof (_14d)!="undefined"&&(_14d==","||_14d==";")){
this.queryStr=_14d;
}else{
this.queryStr=this.txtBox.value;
}
if(this.lastQueryStr==this.queryStr){
if(this.toShowDiv.childNodes.length!=0){
if(!this.visible&&this.queryStr!=""){
if(!this.pressCtrlCFlag){
this.show();
this.initView=0;
}else{
this.pressCtrlCFlag=false;
}
}else{
this.highlightOff();
}
return;
}else{
return;
}
}
if(!this.visible){
this.cleanup();
}
if(this.queryStr==""){
this.lastQueryStr="";
this.hide();
return;
}
this.lastQueryStr=this.queryStr;
if(this.suggestFlag==false){
return;
}
var _14f={method:"get",onComplete:this.onComplete.bindAsEventListener(this),onFailure:this.onFailure.bindAsEventListener(this)};
var _150=this.getSuggestPrefix();
var _151=encodeURIComponent(document.URL);
var URL=encodeURI(_150+this.queryStr+"&gobackurl="+_151+"&"+getTimeSuffix()+this.count);
this.bufferDiv.innerHTML="";
this.cleanup();
this.excuteCall(URL);
this.initView=0;
},onFailure:function(){
this.log("onFailure");
},cleanup:function(){
if(this.keyReturnDownFlag){
return;
}
this.size=0;
this.currentNodeIndex=-1;
this.toShowDiv.innerHTML="";
this.bufferDiv.innerHTML="";
},onComplete:function(){
setTimeout(this.updateContent.bind(this,arguments[0]),5);
},cleanScript:function(){
while(this.scriptDiv.childNodes.length>0){
this.scriptDiv.removeChild(this.scriptDiv.firstChild);
}
},onCompleteHit:function(){
setTimeout(this.showSuggestHit.bind(this,arguments[0]),5);
},updateContent:function(){
var item;
var _154=false;
this.cleanScript();
if(this.bufferDiv.innerHTML==""){
if(this.toShowDiv.innerHTML!=""&&(this.toShowDiv.getElementsByTagName("div")[0]).getAttribute("id")==this.txtBox.value){
return;
}else{
this.hide();
this.cleanup();
return;
}
}
if((this.bufferDiv.getElementsByTagName("div")[0]).getAttribute("id")!=this.txtBox.value){
if(this.toShowDiv.innerHTML!=""&&(this.toShowDiv.getElementsByTagName("div")[0]).getAttribute("id")==this.txtBox.value){
return;
}else{
this.hide();
return;
}
}
var _155=(((this.bufferDiv.getElementsByTagName("table"))[1]).getElementsByTagName("tr"));
for(var i=0;i<_155.length;i++){
item=_155[i];
if(this.isValidNode(item)){
_154=true;
break;
}
}
if(_154){
this.toShowDiv.innerHTML=this.bufferDiv.innerHTML;
_155=(((this.toShowDiv.getElementsByTagName("table"))[1]).getElementsByTagName("tr"));
this.size=0;
this.currentNodeIndex=-1;
this.children=new Array();
for(var i=0;i<_155.length;i++){
item=_155[i];
if(this.isValidNode(item)){
item.setAttribute(indexAttr,this.size);
Event.observe(item,"mouseover",this.onmouseover.bindAsEventListener(this));
Event.observe(item,"mouseout",this.onmouseout.bindAsEventListener(this));
Event.observe(item,"click",this.select.bindAsEventListener(this));
this.children.push(item);
this.size++;
}
}
if(this.toShowDiv.getElementsByTagName("table").length>=3){
var c2=(((this.toShowDiv.getElementsByTagName("table"))[2]).getElementsByTagName("A"));
for(var i=0;i<c2.length;i++){
item=c2[i];
Event.observe(item,"click",this.onclick2.bindAsEventListener(this));
Event.observe(item,"mouseover",this.onmouseover2.bindAsEventListener(this));
Event.observe(item,"mouseout",this.onmouseout2.bindAsEventListener(this));
}
}
this.firstShowFlag=true;
this.show();
}else{
this.hide();
this.cleanup();
}
},setInputField:function(_158){
this.txtBox.value=_158;
},updateInputField:function(){
var _159=this.txtBox.value;
if(_159.toUpperCase().indexOf(this.oldInputValue.toUpperCase())==0){
if(this.txtBox.createTextRange){
var u=this.txtBox.createTextRange();
u.moveStart("character",this.oldInputValue.length);
u.select();
this.selFlag=true;
}else{
if(this.txtBox.setSelectionRange){
this.txtBox.setSelectionRange(this.oldInputValue.length,_159.length);
this.selFlag=true;
}
}
}
},show:function(){
if(this.toShowDiv.childNodes.length<1){
return;
}
if(this.suggestFlag){
if((this.toShowDiv.getElementsByTagName("div")[0]).getAttribute("id")!=this.txtBox.value){
return;
}
}
this.upPoint();
var _15b=Position.cumulativeOffset(this.txtBox);
this.toShowDiv.style.top=_15b[1]+this.txtBox.offsetHeight-1+"px";
this.toShowDiv.style.left=_15b[0]+"px";
this.toShowDiv.style.cursor="default";
var _15c=(navigator&&navigator.userAgent.toLowerCase().indexOf("msie")==-1);
this.toShowDiv.style.width=this.txtBox.offsetWidth+"px";
this.iframe.style.width=this.toShowDiv.style.width;
this.iframe.style.top=this.toShowDiv.style.top;
this.iframe.style.left=this.toShowDiv.style.left;
Element.show(this.toShowDiv);
this.iframe.style.height=_15c?Element.getHeight(this.toShowDiv)-3+"px":Element.getHeight(this.toShowDiv)+"px";
this.visible=true;
if(this.firstShowFlag){
this.currentNodeIndex=-1;
this.firstShowFlag=false;
}
},showSuggestHit:function(){
if(this.toShowDiv.childNodes.length<1){
return;
}
var _15d=Position.cumulativeOffset(this.txtBox);
this.toShowDiv.style.top=_15d[1]+this.txtBox.offsetHeight-1+"px";
this.toShowDiv.style.left=_15d[0]+"px";
this.toShowDiv.style.cursor="default";
var _15e=(navigator&&navigator.userAgent.toLowerCase().indexOf("msie")==-1);
this.toShowDiv.style.width=_15e?this.txtBox.offsetWidth-3+"px":this.txtBox.offsetWidth+"px";
this.iframe.style.width=this.toShowDiv.style.width;
this.iframe.style.top=this.toShowDiv.style.top;
this.iframe.style.left=this.toShowDiv.style.left;
Element.show(this.toShowDiv);
this.iframe.style.height=_15e?Element.getHeight(this.toShowDiv)-3+"px":Element.getHeight(this.toShowDiv)+"px";
this.visible=true;
if(this.firstShowFlag){
this.currentNodeIndex=-1;
this.firstShowFlag=false;
}
},hide:function(){
if(this.keyReturnDownFlag){
return;
}
this.visible=false;
this.selFlag=false;
this.highlightOff();
Element.hide(this.toShowDiv);
Element.hide(this.iframe);
this.currentNodeIndex=-1;
this.firstShowFlag=true;
},onmousemove:function(arg0){
this.initView=1;
this.onmouseover(arg0);
},onmouseover:function(arg0){
this.txtBox.onblur=null;
var item=Event.element(arg0);
if(this.initView==0){
this.initView=1;
return;
}
while(item.parentNode&&(!item.tagName||(item.getAttribute(indexAttr)==null))){
item=item.parentNode;
}
var _162=(item.tagName)?item.getAttribute(indexAttr):-1;
if(_162==-1||_162==this.currentNodeIndex){
return;
}
this.highlightOff();
this.currentNodeIndex=_162;
this.highlight();
},onmouseout:function(){
this.highlightOff();
this.currentNodeIndex=-1;
this.txtBox.value=this.oldInputValue;
this.txtBox.onblur=this.hide.bindAsEventListener(this);
this.initView=1;
},onmouseover2:function(arg0){
this.txtBox.onblur=null;
this.intView=1;
},onmouseout2:function(){
this.initView=1;
this.txtBox.onblur=this.hide.bindAsEventListener(this);
},onclick2:function(){
this.initView=1;
return true;
},getNode:function(i){
if(this.children){
return this.children[i];
}else{
return undefined;
}
},currentNode:function(){
if(this.children){
return this.children[this.currentNodeIndex];
}else{
return undefined;
}
},highlight:function(){
if(this.currentNode()){
var t_c=this.currentNode().getElementsByTagName("td");
for(var i=0;i<t_c.length;++i){
Element.addClassName(t_c[i],highlightStyle);
}
var stat=this.currentNode().getAttribute(onSelAttr);
try{
eval(stat);
this.selFlag=false;
this.updateInputField();
}
catch(e){
}
}
},highlightOff:function(){
if(this.keyReturnDownFlag){
return;
}
if(this.currentNode()){
var t_c=this.currentNode().getElementsByTagName("td");
for(var i=0;i<t_c.length;++i){
Element.removeClassName(t_c[i],highlightStyle);
}
this.currentNodeIndex=-1;
}
},up:function(){
var _16a=this.currentNodeIndex;
if(this.currentNodeIndex>0){
this.highlightOff();
this.currentNodeIndex=_16a-1;
this.highlight();
}else{
if(this.currentNodeIndex==0){
this.highlightOff();
this.currentNodeIndex=--_16a;
this.txtBox.value=this.oldInputValue;
}else{
this.currentNodeIndex=this.size-1;
this.highlight();
}
}
},down:function(){
var _16b=this.currentNodeIndex;
if(this.currentNodeIndex==-1){
this.currentNodeIndex=++_16b;
this.highlight();
}else{
if(this.currentNodeIndex<this.size-1){
this.highlightOff();
this.currentNodeIndex=++_16b;
this.highlight();
}else{
this.highlightOff();
this.currentNodeIndex=-1;
this.txtBox.value=this.oldInputValue;
}
}
},excuteCall:function(URL){
var _16d=document.createElement("script");
_16d.src=URL;
this.scriptDiv.appendChild(_16d);
},updateCall:function(_16e){
_16e=unescape(_16e);
_16e=_16e.replace(/&lt;/g,"<");
_16e=_16e.replace(/&gt;/g,">");
_16e=_16e.replace(/&quot;/g,"\"");
_16e=_16e.replace(/&amp;/g,"&").replace(/&#39;/g,"'");
this.bufferDiv.innerHTML=_16e;
if(this.bufferDiv.childNodes.length<2){
this.toShowDiv.innerHTML=this.bufferDiv.innerHTML;
this.downFlag=true;
}else{
this.onComplete();
}
},setSuggestFlag:function(_16f){
this.suggestFlag=_16f;
},upPoint:function(){
this.downFlag=false;
},downPoint:function(){
this.downFlag=true;
},pressPoint:function(img){
if(this.clickEnabled){
this.clickEnabled=false;
setTimeout(this.enableClick.bind(this),100);
if(!Element.visible(this.toShowDiv)&&((this.txtBox.value=="")||!this.suggestFlag)){
this.downFlag=true;
}
if(this.downFlag){
if(this.suggestFlag){
if(this.txtBox.value==""){
this.insertInputHit();
}else{
if(this.toShowDiv.innerHTML==""){
this.lastQueryStr="";
this.doRequest();
}else{
if(this.toShowDiv.childNodes.length<2){
if(this.txtBox.value==this.lastQueryStr){
this.insertNoSuggestHit();
}else{
this.doRequest();
}
}else{
this.onComplete();
}
}
}
}else{
this.insertSuggestHit();
}
this.downFlag=false;
}else{
if(this.suggestFlag){
this.hide();
}else{
}
this.downFlag=true;
}
}
},setSuggestServer:function(_171){
this.suggestServer=_171;
this.cleanup();
if(this.txtBox.value==""){
}else{
this.doRequest(this.txtBox.value);
}
},setSearchServer:function(_172){
this.searchServer=_172;
},getSuggestPrefix:function(){
var _173;
if(this.suggestServer!=""){
_173=this.suggestServer+queryURL;
}else{
_173="http://"+document.domain+queryURL;
}
return _173;
},getSearchPrefix:function(){
var _174;
if(this.searchServer!=""){
return this.searchServer+"?keyfrom=";
}else{
_174="http://"+document.domain+"/search?keyfrom=";
}
return _174;
},insertSuggestHit:function(){
this.toShowDiv.innerHTML="<div style='z-index:12'><table cellpadding=0 cellspacing=1 border=0 width=100% bgcolor=#979797 align=center><tr><td valign=top><table cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr onSelect=\"this.txtBox.value=''\"><td align=left bgcolor=white class=remindtt752>\u63d0\u793a\u529f\u80fd\u5df2\u5173\u95ed</td></tr></table><table onSelect=\"this.txtBox.value=''\" cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr><td height=1px bgcolor=#ECF0EF class=jstxhuitiaoyou></td><td align=right height=17px bgcolor=#ECF0EF class=jstxhuitiaoyou align=right><a onclick=turnOnSuggest() class=jstxlan>\u6253\u5f00\u63d0\u793a\u529f\u80fd</a></td></tr></table></td></tr></table></div>";
this.onCompleteHit();
},insertInputHit:function(){
this.toShowDiv.innerHTML="<div style='z-index:12'><table cellpadding=0 cellspacing=1 border=0 width=100% bgcolor=#979797 align=center><tr><td valign=top><table cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr onSelect=\"this.txtBox.value=''\"><td align=left  bgcolor=white class=remindtt752 color=#9E9E9E>\u5728\u641c\u7d22\u6846\u4e2d\u8f93\u5165\u5173\u952e\u5b57\uff0c\u5373\u4f1a\u5728\u8fd9\u91cc\u51fa\u73b0\u63d0\u793a</td></tr></table><table onSelect=\"this.txtBox.value=''\" cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr><td height=1px bgcolor=#ECF0EF class=jstxhuitiaoyou></td><td align=right height=17px bgcolor=#ECF0EF class=jstxhuitiaoyou align=right><a onclick=turnOffSuggest() class=jstxlan>\u5173\u95ed\u63d0\u793a\u529f\u80fd</a></td></tr></table></td></tr></table></div>";
this.onCompleteHit();
},insertNoSuggestHit:function(){
this.toShowDiv.innerHTML="<div style='z-index:12'><table cellpadding=0 cellspacing=1 border=0 width=100% bgcolor=#979797 align=center><tr><td valign=top><table cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr onSelect=\"this.txtBox.value=''\"><td align=left bgcolor=white align=left class=remindtt752>\u6ca1\u6709\u53ef\u7528\u7684\u63d0\u793a</td></tr></table><table onSelect=\"this.txtBox.value=''\" cellpadding=0 cellspacing=0 border=0 width=100% align=center><tr><td height=1px bgcolor=#ECF0EF class=jstxhuitiaoyou></td><td align=right height=17px bgcolor=#ECF0EF class=jstxhuitiaoyou align=right><a onclick=turnOffSuggest() class=jstxlan>\u5173\u95ed\u63d0\u793a\u529f\u80fd</a></td></tr></table></td></tr></table></div>";
this.onCompleteHit();
}};
function turnOffSuggest(){
var i=new Image();
aa.setSuggestFlag(false);
var _176;
var _177="/suggest/setpref.s?";
if(this.suggestServer!=""){
_176=aa.suggestServer+_177;
}else{
_176="http://"+document.domain+queryURL;
}
var _178=encodeURI(_176+getTimeSuffix());
i.src=_178;
i.onLoad=onLoad();
aa.downPoint();
return false;
}
function getTimeSuffix(){
return "timeflag="+new Date();
}
function turnOnSuggest(){
var i=new Image();
aa.setSuggestFlag(true);
var _17a;
var _17b="/suggest/setpref.s?suggest=suggest";
if(this.suggestServer!=""){
_17a=aa.suggestServer+_17b;
}else{
_17a="http://"+document.domain+queryURL;
}
var _17c=encodeURI(_17a+"&"+getTimeSuffix());
i.src=_17c;
aa.cleanup();
aa.downPoint();
i.onLoad=onLoad();
return false;
}
function onLoad(){
}
function pressPoint(){
aa.pressPoint();
}
function closeSuggest(){
aa.setSuggestFlag(false);
aa.cleanScript();
}
function openSuggest(){
aa.setSuggestFlag(true);
}
function updateCall(_17d){
aa.updateCall(_17d);
}
function setSuggestServer(_17e){
aa.setSuggestServer(_17e);
}
function setSerachServer(_17f){
aa.setSearchServer(_17f);
}

