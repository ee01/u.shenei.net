var sHtml = '\
            <style>\
            a.a1{display:block;width:21px; height:20px; background-image:url(/assist/air/images/iconleft1.jpg)}\
            a.a1:hover{background-image:url(/assist/air/images/iconleft.jpg)}\
            a.a2{display:block;width:21px; height:20px; background-image:url(/assist/air/images/iconright1.jpg)}\
            a.a2:hover{background-image:url(/assist/air/images/iconright.jpg)}\
            INPUT.button{BORDER-RIGHT: #78B3ED 1px solid;BORDER-TOP: #78B3ED 1px solid;BORDER-LEFT: #78B3ED 1px solid;\
            BORDER-BOTTOM: #78B3ED 1px solid;BACKGROUND-COLOR: #78B3ED;font-family:����;}\
            TD{FONT-SIZE: 12px;font-family:����;}\
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
            <tr><td width=20 align=center>��</td>\
            <td width=20 align=center>һ</td><td width=20 align=center>��</td>\
            <td width=20 align=center>��</td><td width=20 align=center>��</td>\
            <td width=20 align=center>��</td><td width=20 align=center>��</td></tr>\
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
               <tr><td width=20 align=center>��</td>\
            <td width=20 align=center>һ</td><td width=20 align=center>��</td>\
            <td width=20 align=center>��</td><td width=20 align=center>��</td>\
            <td width=20 align=center>��</td><td width=20 align=center>��</td></tr>\
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
sHtml += '        <td colspan=5 align=right style="color:#1478eb"><a href="#" onclick="parent.closeLayer();return false;" style="color:#006699; text-decoration:none; font-size:12px">�ر�</a></td></tr>';
sHtml += '    </table></td></tr>';
sHtml += '	</table></td></tr></table></div>';



var oFrameWindow = document.getElementById("endDateLayer").contentWindow;
oFrameWindow.document.writeln(sHtml);
oFrameWindow.document.close();	



//==================================================== WEB ҳ����ʾ���� ======================================================
var outObject;
var outButton;		//����İ�ť
var outDate = "";		//��Ŷ��������


function fSetDay(oInput,obj){ //��������

	if (arguments.length > 2){
        alert("�Բ��𣡴��뱾�ؼ��Ĳ���̫�࣡");
        return;
    }
	if (arguments.length == 0){
        alert("�Բ�����û�д��ر��ؼ��κβ�����");
        return;
    }
	var oLayer = document.getElementById("endDateLayer").style;
	var oTmpInput = oInput;
	var nTop = oInput.offsetTop;	//�ؼ��Ķ�λ���

	var nHeight = oInput.clientHeight;	//�ؼ�����ĸ�
	var nLeft = oInput.offsetLeft;	//�ؼ��Ķ�λ���

	var sType = oInput.type;	//�ؼ�������

	while(oTmpInput = oTmpInput.offsetParent){
        nTop += oTmpInput.offsetTop; 
        nLeft += oTmpInput.offsetLeft;
    }

	oLayer.top = (sType == "image") ? (nTop + nHeight +" px") : (nTop + nHeight + 6 + "px");
	oLayer.left = nLeft  +"px" ;
	outObject = (arguments.length == 1) ? oInput : obj;
	outButton = (arguments.length == 1) ? null : oInput;	//�趨�ⲿ����İ�ť

	//���ݵ�ǰ������������ʾ����������
	var reg = /^(\d+)-(\d{1,2})-(\d{1,2})/;		//����ʱ��
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

var MonHead = new Array(12);	//����������ÿ���µ��������

MonHead[0] = 31; MonHead[1] = 28; MonHead[2] = 31; MonHead[3] = 30; MonHead[4]  = 31; MonHead[5]  = 30;
MonHead[6] = 31; MonHead[7] = 31; MonHead[8] = 30; MonHead[9] = 31; MonHead[10] = 30; MonHead[11] = 31;

var meizzTheYear = new Date().getFullYear(); //������ı����ĳ�ʼֵ
var meizzTheMonth = new Date().getMonth()+1; //�����µı����ĳ�ʼֵ
var meizzTheDate = new Date().getDate();	//�����յı����ĳ�ʼֵ
var meizzWDay = new Array(37);	//����д���ڵ�����
var meizzWDayT = new Array(37);	//����д���ڵ����� �ڶ�����


//������ʱ�رոÿؼ�	
//ie6�����������������л����㴦�����
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

//��Esc���رգ��л�����ر�
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

function meizzWriteHead(yy,mm,ss)	//�� head ��д�뵱ǰ��������
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

function closeLayer()	//�����Ĺر�
{
	var o = document.getElementById("endDateLayer");
	if (o != null)
	{
		o.style.display="none";
	}
}

function showLayer()	//�����
{
	document.getElementById("endDateLayer").style.display="";
}

function IsPinYear(year)	//�ж��Ƿ���ƽ��
{
	if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
}

function GetMonthCount(year,month)	//�������Ϊ29��
{
	var c=MonHead[month-1];if((month==2)&&IsPinYear(year)) c++;return c;
}

function GetDOW(day,month,year)	//��ĳ������ڼ�
{
	var dt=new Date(year,month-1,day).getDay()/7; return dt;
}

function meizzPrevY()	//��ǰ�� Year
{
	if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear--;}
	else{alert("��ݳ�����Χ��1000-9999����");}
	meizzSetDay(meizzTheYear,meizzTheMonth);
}
function meizzNextY()	//���� Year
{
	if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear++;}
	else{alert("��ݳ�����Χ��1000-9999����");}
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

	if (meizzTheMonth<10 && meizzTheMonth.length<2)	//��ʽ������λ����
	{
		parent.meizzTheMonth="0"+parent.meizzTheMonth;
	}
	if (parent.meizzTheDate<10 && parent.meizzTheDate.length<2)	//��ʽ������λ����
	{
		parent.meizzTheDate="0"+parent.meizzTheDate;
	}
	//meizzSetDay(meizzTheYear,meizzTheMonth);
	if(outObject)
	{
		outObject.value= parent.meizzTheYear + "-" + format( parent.meizzTheMonth) + "-" +
							format(parent.meizzTheDate); //ע�����������������ĳ�����Ҫ�ĸ�ʽ

	}
	closeLayer();
}
function meizzPrevM()	//��ǰ���·�
{
	if(meizzTheMonth>1){
        meizzTheMonth--
    }else{
        meizzTheYear--;meizzTheMonth=12;
    }
	meizzSetDay(meizzTheYear,meizzTheMonth);
}
function meizzNextM()	//�����·�
{
	if(meizzTheMonth==12){meizzTheYear++;meizzTheMonth=1}else{meizzTheMonth++}
	meizzSetDay(meizzTheYear,meizzTheMonth);
}

// TODO: �������
function meizzSetDay(yy,mm)	//��Ҫ��д����**********
{
	meizzWriteHead(yy,mm);
	//���õ�ǰ���µĹ�������Ϊ����ֵ

	meizzTheYear=yy;
	meizzTheMonth=mm;
    //��һ����
	for (var i = 0; i < 37; i++){meizzWDay[i]=""};	//����ʾ�������ȫ�����

	var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();	//ĳ�µ�һ������ڼ�
	for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++) { meizzWDay[i]=day1;day1++; }

	for (i = 0; i < 37; i++)
	{

		var da = oFrameWindow.document.getElementById("meizzDay"+i);//��д�µ�һ���µ�������������
           
		//��ʼ��
		da.style.backgroundColor="#ffffff";
		da.onmouseover  =Function("onMouseOut(this)");
		da.onmouseout = Function("onMouseOut(this)");

		if (meizzWDay[i]!="")
		{
		//��ʼ���߿�
			da.style.color = "#1B75BE";
            da.style.fontWeight = "bold";
             
		//���µĲ���
			da.innerHTML= meizzWDay[i];
			da.title=mm +"��" + meizzWDay[i] + "��";
			da.onclick = Function("meizzDayClick(this.innerHTML,0)");		//��td����onclick�¼��Ĵ���

			//����ǵ�ǰѡ������ڣ�����ʾ����ɫ�ı���������ǵ�ǰ���ڣ�����ʾ����ɫ����
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
	//�ڶ�����
	for (i = 0; i < 37; i++){meizzWDayT[i]=""};	//����ʾ�������ȫ�����

	day1 = 1,day2=1,firstday = new Date(yy,mm,1).getDay();	//ĳ�µ�һ������ڼ�

    if(mm==12){
	  yy++;mm=1;
    }

	else{
	  mm++;
	}

	for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++) { meizzWDayT[i]=day1;day1++; }

	for (i = 0; i < 37; i++)
	{
		var da2 = oFrameWindow.document.getElementById("meizzDayT"+i);//��д�µ�һ���µ�������������

		//��ʼ��
		da2.style.backgroundColor="#ffffff";
		da2.onmouseover=Function("onMouseOut(this)");
		da2.onmouseout=Function("onMouseOut(this)");

		if (meizzWDayT[i]!="")
		{
		//��ʼ���߿�
			da2.style.color="#1B75BE";
            da2.style.fontWeight = "bold"
		//���µĲ���
			da2.innerHTML= meizzWDayT[i];
			da2.title=mm +"��" + meizzWDayT[i] + "��";
			da2.onclick=Function("meizzDayTClick(this.innerHTML,0)");		//��td����onclick�¼��Ĵ���

			//����ǵ�ǰѡ������ڣ�����ʾ����ɫ�ı���������ǵ�ǰ���ڣ�����ʾ����ɫ����
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

function meizzDayClick(n,ex)	//�����ʾ��ѡȡ���ڣ������뺯��*************
{
//	parent.meizzTheDate=n;
    meizzTheDate=n;
	var yy=meizzTheYear;
	var mm = parseInt(meizzTheMonth)+ex;	//ex��ʾƫ����������ѡ���ϸ��·ݺ��¸��·ݵ�����
	//�ж��·ݣ������ж�Ӧ�Ĵ���

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
	else {closeLayer(); alert("����Ҫ����Ŀؼ����󲢲����ڣ�");}
}



function meizzDayTClick(n,ex)	//�����ʾ��ѡȡ���ڣ������뺯��*************
{
//	parent.meizzTheDate=n;
meizzTheDate=n;
	var yy;
	var mm;
	if(meizzTheMonth==12){yy=parseInt(meizzTheYear)+1;mm=1;}
	else{yy=meizzTheYear;mm = parseInt(meizzTheMonth)+1+ex;}
	//�ж��·ݣ������ж�Ӧ�Ĵ���

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
	else {closeLayer(); alert("����Ҫ����Ŀؼ����󲢲����ڣ�");}
}

function format(n)	//��ʽ������Ϊ��λ�ַ���ʾ
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
	outObject.value= yy + "-" + format(mm) + "-" + format(n); //ע�����������������ĳ�����Ҫ�ĸ�ʽ

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
