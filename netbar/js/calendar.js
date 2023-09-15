var sHtml = '\
            <style>\
            a.a1{display:block;width:21px; height:20px; background-image:url(/assist/air/images/iconleft1.jpg)}\
            a.a1:hover{background-image:url(/assist/air/images/iconleft.jpg)}\
            a.a2{display:block;width:21px; height:20px; background-image:url(/assist/air/images/iconright1.jpg)}\
            a.a2:hover{background-image:url(/assist/air/images/iconright.jpg)}\
            INPUT.button{BORDER-RIGHT: #78B3ED 1px solid;BORDER-TOP: #78B3ED 1px solid;BORDER-LEFT: #78B3ED 1px solid;\
            BORDER-BOTTOM: #78B3ED 1px solid;BACKGROUND-COLOR: #78B3ED;font-family:宋体;}\
            TD{FONT-SIZE: 12px;font-family:宋体;}\
            .tbody{border-color:#78B3ED; background-color:#FFFFFF; border-collapse:collapse; }\
            </style>\
             ';

sHtml += '\
            <div style="z-index:9999;position: absolute;left:0px;top:0px;" onselectstart="return false">\
            <table class="tbody" border="1" width=284 height=150><tr><td width=142 ><table class="tbody" width=142>\
            <tr><td width=142 height=21  bgcolor=#78B3ED>\
            <table height=21>\
            <tr align=center >\
            <td width=21 align="center">\
                   <a href="#" onclick="parent.meizzPrevM();return false;" class="a1"></a></td>\
               <td align="left">\
                   &nbsp;&nbsp;<span id=meizzYearHead></span><span id=meizzMonthHead></span></td>\
            </tr>\
            </table></td></tr>\
            <tr><td width=142 height=18 >\
            <table bgcolor="#E7F1FD">\
            <tr><td width=20 align=center>日</td>\
            <td width=20 align=center>一</td><td width=20 align=center>二</td>\
            <td width=20 align=center>三</td><td width=20 align=center>四</td>\
            <td width=20 align=center>五</td><td width=20 align=center>六</td></tr>\
            </table></td></tr>\
            <tr ><td width=142 height=120>\
            <table width=142>\
                ';
var n = 0; 
for (j = 0; j < 5; j++){ 
    sHtml +=  '<tr>'; 
    for (i=0;i<7;i++){
        sHtml += '<td width=20 height=20 align=center  id=meizzDay'+n+' style="font-size:12px;" onclick="parent.meizzDayClick(this.innerHTML,0)"></td>';
        n++;
     }
    sHtml += '</tr>';
 }
sHtml += '<tr align=center>';
for (i=35; i<37; i++){
    sHtml += '<td width=20 height=20 id=meizzDay'+i+' style="font-size:12px;"  onclick="parent.meizzDayClick(this.innerHTML,0)"></td>';
}
sHtml += '\
            <td colspan=5 align=right></td></tr>\
            </table></td></tr></table></td>\
            <td width=142><table class="tbody" width=142>\
            <tr><td width=142 height=21  bgcolor=#78B3ED>\
                <table width=142 height=21>\
                    <tr align=center >\
                           <td width=121 align="right">\
                               <span  id=meizzYearHead2></span><span id=meizzMonthHead2></span>&nbsp;&nbsp;</td>\
                        <td width=21 align="center">\
                               <a href="#" onclick="parent.meizzNextM();return false;" class="a2"></a></td>\
                       </tr>\
                   </table></td></tr>\
               <tr><td width=142 height=18 >\
                   <table bgcolor="#E7F1FD">\
               <tr><td width=20 align=center>日</td>\
            <td width=20 align=center>一</td><td width=20 align=center>二</td>\
            <td width=20 align=center>三</td><td width=20 align=center>四</td>\
            <td width=20 align=center>五</td><td width=20 align=center>六</td></tr>\
            </table></td></tr>\
            <tr ><td width=142 height=120>\
            <table width=142>\
        ';
n = 0; 
for (j=0;j<5;j++){
    sHtml +=  ' <tr align=center >'; 
    for (i=0;i<7;i++){
        sHtml += '<td width=20 height=20 id=meizzDayT'+n+' style="font-size:12px" onclick="parent.meizzDayTClick(this.innerHTML,0);"></td>';
        n++;
    }
    sHtml += '</tr>';
}
sHtml += '<tr align=center>';
for (i=35;i<37;i++){
    sHtml += '<td width=20 height=20 id=meizzDayT'+i+' style="font-size:12px"  onclick="parent.meizzDayTClick(this.innerHTML,0);"></td>';
}
sHtml += '        <td colspan=5 align=right style="color:#1478eb"><a href="#" onclick="parent.closeLayer();return false;" style="color:#006699; text-decoration:none; font-size:12px">关闭</a></td></tr>';
sHtml += '    </table></td></tr>';
sHtml += '	</table></td></tr></table></div>';



var oFrameWindow = document.getElementById("endDateLayer").contentWindow;
oFrameWindow.document.writeln(sHtml);
oFrameWindow.document.close();	



//==================================================== WEB 页面显示部分 ======================================================
var outObject;
var outButton;		//点击的按钮
var outDate = "";		//存放对象的日期


function fSetDay(oInput,obj){ //主调函数

	if (arguments.length > 2){
        alert("对不起！传入本控件的参数太多！");
        return;
    }
	if (arguments.length == 0){
        alert("对不起！您没有传回本控件任何参数！");
        return;
    }
	var oLayer = document.getElementById("endDateLayer").style;
	var oTmpInput = oInput;
	var nTop = oInput.offsetTop;	//控件的定位点高

	var nHeight = oInput.clientHeight;	//控件本身的高
	var nLeft = oInput.offsetLeft;	//控件的定位点宽

	var sType = oInput.type;	//控件的类型

	while(oTmpInput = oTmpInput.offsetParent){
        nTop += oTmpInput.offsetTop; 
        nLeft += oTmpInput.offsetLeft;
    }

	oLayer.top = (sType == "image") ? (nTop + nHeight +" px") : (nTop + nHeight + 6 + "px");
	oLayer.left = nLeft  +"px" ;
	outObject = (arguments.length == 1) ? oInput : obj;
	outButton = (arguments.length == 1) ? null : oInput;	//设定外部点击的按钮

	//根据当前输入框的日期显示日历的年月
	var reg = /^(\d+)-(\d{1,2})-(\d{1,2})/;		//不含时间
	var r = outObject.value.match(reg);
	if( r != null ){
		r[2] = r[2]-1;
		var d=new Date(r[1],r[2],r[3]);
		if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3])
		{
			outDate=d;
            meizzTheYear = r[1];
			meizzTheMonth = r[2];
			meizzTheDate = r[3];
		}else{
			outDate="";
		}
		meizzSetDay(r[1],r[2]+1);
	}else{
		outDate = "";
		meizzSetDay(new Date().getFullYear(), new Date().getMonth() + 1);
	}
	meizzWriteHead(meizzTheYear,meizzTheMonth);
	oLayer.display  =  '';	
}

var MonHead = new Array(12);	//定义阳历中每个月的最大天数

MonHead[0] = 31; MonHead[1] = 28; MonHead[2] = 31; MonHead[3] = 30; MonHead[4]  = 31; MonHead[5]  = 30;
MonHead[6] = 31; MonHead[7] = 31; MonHead[8] = 30; MonHead[9] = 31; MonHead[10] = 30; MonHead[11] = 31;

var meizzTheYear = new Date().getFullYear(); //定义年的变量的初始值
var meizzTheMonth = new Date().getMonth()+1; //定义月的变量的初始值
var meizzTheDate = new Date().getDate();	//定义日的变量的初始值
var meizzWDay = new Array(37);	//定义写日期的数组
var meizzWDayT = new Array(37);	//定义写日期的数组 第二个月


//任意点击时关闭该控件	
//ie6的情况可以由下面的切换焦点处理代替
document.onclick = function(e){
    var ev = e || event;
    var t = ev.target || ev.srcElement;
    if(t.id.indexOf("Date") != -1) 
		return ;
	with(ev)
	{
		if (t != outObject && t != outButton)
		    closeLayer();
	}
}

//按Esc键关闭，切换焦点关闭
document.onkeyup  = function(e){
    var ev = e || event;
	if (ev.keyCode==27){
		if(outObject)
            outObject.blur();
		closeLayer();
	}else if(document.activeElement){
		if(document.activeElement != outObject && document.activeElement != outButton){
			closeLayer();
		}
	}
}

function meizzWriteHead(yy,mm,ss)	//往 head 中写入当前的年与月
{
        
	oFrameWindow.document.getElementById("meizzYearHead").innerHTML	= yy + ".";
	oFrameWindow.document.getElementById("meizzMonthHead").innerHTML	= format(mm);
	if(mm==12){
	   var y2=parseInt(yy)+1;
	   oFrameWindow.document.getElementById("meizzYearHead2").innerHTML	= y2 + ".";
	    oFrameWindow.document.getElementById("meizzMonthHead2").innerHTML	=  1 ;
	}
	else{
		 oFrameWindow.document.getElementById("meizzYearHead2").innerHTML	= yy + ".";
		 oFrameWindow.document.getElementById("meizzMonthHead2").innerHTML = format(mm+1) ;
	}
}

function closeLayer()	//这个层的关闭
{
	var o = document.getElementById("endDateLayer");
	if (o != null)
	{
		o.style.display="none";
	}
}

function showLayer()	//这个层
{
	document.getElementById("endDateLayer").style.display="";
}

function IsPinYear(year)	//判断是否闰平年
{
	if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
}

function GetMonthCount(year,month)	//闰年二月为29天
{
	var c=MonHead[month-1];if((month==2)&&IsPinYear(year)) c++;return c;
}

function GetDOW(day,month,year)	//求某天的星期几
{
	var dt=new Date(year,month-1,day).getDay()/7; return dt;
}

function meizzPrevY()	//往前翻 Year
{
	if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear--;}
	else{alert("年份超出范围（1000-9999）！");}
	meizzSetDay(meizzTheYear,meizzTheMonth);
}
function meizzNextY()	//往后翻 Year
{
	if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear++;}
	else{alert("年份超出范围（1000-9999）！");}
	meizzSetDay(meizzTheYear,meizzTheMonth);
}
function setNull()
{
	outObject.value = '';
	closeLayer();
}
function meizzToday()	//Today Button
{
	parent.meizzTheYear		= new Date().getFullYear();
	parent.meizzTheMonth	= new Date().getMonth()+1;
	parent.meizzTheDate		= new Date().getDate();

	if (meizzTheMonth<10 && meizzTheMonth.length<2)	//格式化成两位数字
	{
		parent.meizzTheMonth="0"+parent.meizzTheMonth;
	}
	if (parent.meizzTheDate<10 && parent.meizzTheDate.length<2)	//格式化成两位数字
	{
		parent.meizzTheDate="0"+parent.meizzTheDate;
	}
	//meizzSetDay(meizzTheYear,meizzTheMonth);
	if(outObject)
	{
		outObject.value= parent.meizzTheYear + "-" + format( parent.meizzTheMonth) + "-" +
							format(parent.meizzTheDate); //注：在这里你可以输出改成你想要的格式

	}
	closeLayer();
}
function meizzPrevM()	//往前翻月份
{
	if(meizzTheMonth>1){
        meizzTheMonth--
    }else{
        meizzTheYear--;meizzTheMonth=12;
    }
	meizzSetDay(meizzTheYear,meizzTheMonth);
}
function meizzNextM()	//往后翻月份
{
	if(meizzTheMonth==12){meizzTheYear++;meizzTheMonth=1}else{meizzTheMonth++}
	meizzSetDay(meizzTheYear,meizzTheMonth);
}

// TODO: 整理代码
function meizzSetDay(yy,mm)	//主要的写程序**********
{
	meizzWriteHead(yy,mm);
	//设置当前年月的公共变量为传入值

	meizzTheYear=yy;
	meizzTheMonth=mm;
    //第一个月
	for (var i = 0; i < 37; i++){meizzWDay[i]=""};	//将显示框的内容全部清空

	var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();	//某月第一天的星期几
	for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++) { meizzWDay[i]=day1;day1++; }

	for (i = 0; i < 37; i++)
	{

		var da = oFrameWindow.document.getElementById("meizzDay"+i);//书写新的一个月的日期星期排列
           
		//初始化
		da.style.backgroundColor="#ffffff";
		da.onmouseover  =Function("onMouseOut(this)");
		da.onmouseout = Function("onMouseOut(this)");

		if (meizzWDay[i]!="")
		{
		//初始化边框
			da.style.color = "#1B75BE";
            da.style.fontWeight = "bold";
             
		//本月的部分
			da.innerHTML= meizzWDay[i];
			da.title=mm +"月" + meizzWDay[i] + "日";
			da.onclick = Function("meizzDayClick(this.innerHTML,0)");		//给td赋予onclick事件的处理

			//如果是当前选择的日期，则显示亮蓝色的背景；如果是当前日期，则显示暗黄色背景
			if(!outDate){
				if((yy < new Date().getFullYear())||(yy == new Date().getFullYear() && mm < new Date().getMonth()+1 )||(yy == new    Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] < new Date().getDate())){
					da.style.color="#999999";
					da.onclick = function(){return false;}
				}else if((yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate())){
					da.style.backgroundColor="#D2E9FB";
					da.onmouseover=Function("onMouseOver(this)");
					da.onmouseout=Function("onMouseOutToday(this)");
					da.style.cursor = "pointer";
				}else {
					da.onmouseover=Function("onMouseOver(this)");
					da.style.cursor = "pointer";
				}
			}else{
				if((yy < new Date().getFullYear())||(yy == new Date().getFullYear() && mm < new Date().getMonth()+1 )||(yy == new    Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] < new Date().getDate())){
					da.style.color="#999999";
					da.onclick = function(){return false;}
				}else if (yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()){
					da.style.backgroundColor="D2E9FB";
					da.onmouseover=Function("onMouseOver(this)");
					da.onmouseout=Function("onMouseOutToday(this)");
					da.style.cursor = "pointer";
				}else if(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate()){
					da.style.backgroundColor="#BEE4FA";
					da.onmouseover=Function("onMouseOver(this)");
					da.onmouseout=Function("onMouseOutSelected(this)");
					da.style.cursor = "pointer";
				}else{
					da.onmouseover=Function("onMouseOver(this)");
					da.style.cursor = "pointer";
				}
			}
            
		}else { da.innerHTML="";da.style.backgroundColor="";da.style.cursor="default";}
	}
	//第二个月
	for (i = 0; i < 37; i++){meizzWDayT[i]=""};	//将显示框的内容全部清空

	day1 = 1,day2=1,firstday = new Date(yy,mm,1).getDay();	//某月第一天的星期几

    if(mm==12){
	  yy++;mm=1;
    }

	else{
	  mm++;
	}

	for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++) { meizzWDayT[i]=day1;day1++; }

	for (i = 0; i < 37; i++)
	{
		var da2 = oFrameWindow.document.getElementById("meizzDayT"+i);//书写新的一个月的日期星期排列

		//初始化
		da2.style.backgroundColor="#ffffff";
		da2.onmouseover=Function("onMouseOut(this)");
		da2.onmouseout=Function("onMouseOut(this)");

		if (meizzWDayT[i]!="")
		{
		//初始化边框
			da2.style.color="#1B75BE";
            da2.style.fontWeight = "bold"
		//本月的部分
			da2.innerHTML= meizzWDayT[i];
			da2.title=mm +"月" + meizzWDayT[i] + "日";
			da2.onclick=Function("meizzDayTClick(this.innerHTML,0)");		//给td赋予onclick事件的处理

			//如果是当前选择的日期，则显示亮蓝色的背景；如果是当前日期，则显示暗黄色背景
			if(!outDate){
				if((yy < new Date().getFullYear())||(yy == new Date().getFullYear() && mm < new Date().getMonth()+1 )||(yy == new    Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDayT[i] < new Date().getDate())){
					da2.style.color="#999999";
					da2.onclick = function(){return false;}
				}
				else if((yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDayT[i] == new Date().getDate())){
					da2.style.backgroundColor="D2E9FB";
					da2.onmouseover=Function("onMouseOver(this)");
					da2.onmouseout=Function("onMouseOutToday(this)");
					da2.style.cursor="pointer";
				}else{
					da2.onmouseover=Function("onMouseOver(this)");
					da2.style.cursor="pointer";
				}
			}
			else
			{
				if((yy < new Date().getFullYear())||(yy == new Date().getFullYear() && mm < new Date().getMonth()+1 )||(yy == new    Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDayT[i] < new Date().getDate())){
					da2.style.color="#999999";
					da2.onclick = function(){return false;}
				}else if (yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDayT[i] == new Date().getDate()){
					da2.style.backgroundColor="D2E9FB";
					da2.onmouseover=Function("onMouseOver(this)");
					da2.onmouseout=Function("onMouseOutToday(this)");
					da2.style.cursor="pointer";
				}
				else if(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDayT[i]==outDate.getDate()){
					da2.style.backgroundColor="#BEE4FA";
					da2.onmouseover=Function("onMouseOver(this)");
					da2.onmouseout=Function("onMouseOutSelected(this)");
					da2.style.cursor="pointer";
				}else{
					da2.onmouseover=Function("onMouseOver(this)");
					da2.style.cursor="pointer";
				}
			}
			
		}
		else { da2.innerHTML="";da2.style.backgroundColor="";da2.style.cursor="default"; }
	}
}

function meizzDayClick(n,ex)	//点击显示框选取日期，主输入函数*************
{
//	parent.meizzTheDate=n;
    meizzTheDate=n;
	var yy=meizzTheYear;
	var mm = parseInt(meizzTheMonth)+ex;	//ex表示偏移量，用于选择上个月份和下个月份的日期
	//判断月份，并进行对应的处理

	if(mm<1){
		yy--;
		mm=12+mm;
	}
	else if(mm>12){
		yy++;
		mm=mm-12;
	}

	if (mm < 10)	{mm = "0" + mm;}

	if (outObject)
	{
		if (!n) {	//outObject.value="";
			return;}
		if ( n < 10){n = "0" + n;}

		WriteDateTo(yy,mm,n);

		closeLayer();
	}
	else {closeLayer(); alert("您所要输出的控件对象并不存在！");}
}



function meizzDayTClick(n,ex)	//点击显示框选取日期，主输入函数*************
{
//	parent.meizzTheDate=n;
meizzTheDate=n;
	var yy;
	var mm;
	if(meizzTheMonth==12){yy=parseInt(meizzTheYear)+1;mm=1;}
	else{yy=meizzTheYear;mm = parseInt(meizzTheMonth)+1+ex;}
	//判断月份，并进行对应的处理

	if(mm<1){
		yy--;
		mm=12+mm;
	}
	else if(mm>12){
		yy++;
		mm=mm-12;
	}

	if (mm < 10)	{mm = "0" + mm;}

	if (outObject)
	{
		if (!n) {	//outObject.value="";
			return;}
		if ( n < 10){n = "0" + n;}

		WriteDateTo(yy,mm,n);

		closeLayer();
	}
	else {closeLayer(); alert("您所要输出的控件对象并不存在！");}
}

function format(n)	//格式化数字为两位字符表示
{
	var m=new String();
	var tmp=new String(n);
	if (n<10 && tmp.length<2)
	{
		m="0"+n;
	}
	else
	{
		m=n;
	}
	return m;
}

function WriteDateTo(yy,mm,n)
{
	outObject.style.color="#000000";
	outObject.value= yy + "-" + format(mm) + "-" + format(n); //注：在这里你可以输出改成你想要的格式

}


function onMouseOver(obj){
	obj.style.backgroundColor="#BEE4FA";
}

function onMouseOut(obj){
	obj.style.backgroundColor="#ffffff";
}

function onMouseOutToday(obj){
	obj.style.backgroundColor="D2E9FB";
}
function onMouseOutSelected(obj){
	obj.style.backgroundColor="#BEE4FA";
}

function showHint(obj,type){	
    if(type){
        if(obj.value==""){
			obj.style.color="#C1C1C1";
			obj.value="yyyy-mm-dd";
		}
    }else{
        if(obj.value == "yyyy-mm-dd"){
			obj.style.color = "#000000";
            obj.value = "";
		
		}
		fSetDay(obj);
    }
}
