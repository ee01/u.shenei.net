var sUrl="http://assistant.mail.yeah.net/assistant/train.do";function fQueryS2S(){var from=$("from").value;if(from.trim()==""){alert("���������վ");$("from").focus();return false;};var end=$("end").value;if(end.trim()==""){alert("�����뵽��վ");$("end").focus();return false;};var sParam="&op=searchs2s&start=0&size=100&queryFrom="+from+"&queryTo="+end,sExtend="&extend={'op':'searchs2s','from':'"+from+"','to':'"+end+"'}";fTrainCall(sUrl,"fTrainS2SCallBack",sParam,sExtend);$("queryDiv").style.display="none";$("loadDiv").style.display="";return false;};function fQueryStation(){var station=$("station").value;if(station.trim()==""){alert("������Ҫ��ѯ�ĳ�վ");$("station").focus();return false;};var sParam="&op=searchstation&start=0&size=1000&queryStation="+station;fTrainCall(sUrl,"fTrainStationCallBack",sParam,"&extend={'op':'searchstation'}");$("queryDiv").style.display="none";$("loadDiv").style.display="";return false;};function fQueryNumber(){var stationNumber=$("number").value;if(stationNumber.trim()==""){alert("������Ҫ��ѯ�ĳ���");$("number").focus();return false;};var sParam="&op=searchcheci&start=0&size=100&cls="+stationNumber,sExtend="&extend={'op':'searchcheci','cls':'"+stationNumber+"'}";fTrainCall(sUrl,"fTrainNumberCallBack",sParam,sExtend);$("queryDiv").style.display="none";$("loadDiv").style.display="";return false;};function fGoQuery(){$("numberDiv").style.display="none";$("stationDiv").style.display="none";$("s2sDiv").style.display="none";$("failDiv").style.display="none";$("queryDiv").style.display="";return false;};function fTrainCall(sUrl,sEvent,sParams,sExtend,sCharSet){sUrl+="?event="+sEvent;if(sParams){sUrl+=sParams;};if(sExtend){sUrl+=sExtend;};sUrl+="&patch="+Date.parse(new Date());fCommonGetScript(sUrl,"gbk");};function fTrainNumberCallBack(nCode,oJson,oExtend){var nLength=oJson.length;if(nCode>=0){if(nLength==0){$("numCheci").innerHTML=oExtend.cls;$("numberResultDiv").innerHTML='<div class="Cont hbinfo" >�޴˳�����Ϣ</div>';$("numberDiv").style.display="";$("loadDiv").style.display="none";}else{$("numCheci").innerHTML=oJson[0].cls;$("numCourse").innerHTML=oJson[0].distant;$("numTime").innerHTML=oJson[0].duration;var sHtml='<table class="lieche_tab" cellspacing="0" cellpadding="0"><tr>		<th>վ��</th>		<th>վ��</th>		<th>����ʱ��</th>		<th>����ʱ��</th>		<th>ͣ��ʱ��</th>		<th>�г�ʱ��</th>		<th>���</th>		<th>Ӳ��</th>		<th>Ӳ����</th>		<th>������</th></tr>',nLength=oJson[0].detailList.length;for(var i=0;i<nLength;i++){var oTrain=oJson[0].detailList[i];sHtml+='<tr>';sHtml+='<td title="վ��">'+i+"</td>";sHtml+='<td title="վ��">'+oTrain.zhanming+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.reachtime+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.leavetime+"</td>";sHtml+='<td title="ͣ��ʱ��">'+oTrain.waitinterval+"</td>";sHtml+='<td title="�г�ʱ��">'+oTrain.duration+"</td>";sHtml+='<td title="���">'+oTrain.distant+" ����</td>";sHtml+='<td title="Ӳ��"><span class="CEA5E00">'+((oTrain.price_yingzuo&&oTrain.price_yingzuo!=0)?oTrain.price_yingzuo+" Ԫ":"-")+"</span></td>";sHtml+='<td title="Ӳ���¼۸�">'+((oTrain.price_yingwo&&oTrain.price_yingwo!=0)?oTrain.price_yingwo+" Ԫ":"-")+"</td>";sHtml+='<td title="�����¼۸�">'+((oTrain.price_ruanwo&&oTrain.price_ruanwo!=0)?oTrain.price_ruanwo+" Ԫ":"-")+"</td>";sHtml+='</tr>';};$("numberResultDiv").innerHTML=sHtml;$("numberDiv").style.display="";$("loadDiv").style.display="none";};}else{$("failDiv").style.display="";$("loadDiv").style.display="none";};};function fTrainStationCallBack(nCode,oJson,oExtend){if(nCode>=0){var sHtml='<table class="lieche_tab" cellspacing="0" cellpadding="0"><tr>	<th>������</th>	<th>�г�����</th>	<th>���վ���յ�վ</th>	<th>ʼ��ʱ���յ�ʱ</th>	<th>�����</th>	<th>��ʱ��</th>	<th>ͣ��վ</th>	<th>��վʱ��</th>	<th>ͣ��ʱ��</th></tr>  ',nLength=oJson.length;for(var i=0;i<nLength;i++){var oTrain=oJson[i];if(i==0){$("stationName").innerHTML=oTrain.reachstation;};sHtml+='<tr id="stationTr'+i+'">';sHtml+='<td title="����"><a href="#" onclick="fTrainStationExtend('+i+',false,\''+oTrain.cls+'\');return false;" >'+oTrain.cls+"</a></td>";sHtml+='<td title="�г�����">'+oTrain.type+"</td>";sHtml+='<td title="���վ���յ�վ"><a href="#" id="stationHref'+i+'" onclick="fTrainStationExtend('+i+',false,\''+oTrain.cls+'\');return false;"class="a_k">'+oTrain.startstation+"-"+oTrain.endstation+"</a></td>";sHtml+='<td title="ʼ��ʱ���յ�ʱ">'+oTrain.starttime+"-"+oTrain.endtime+"</td>";sHtml+='<td title="�����">'+oTrain.distant+"</td>";sHtml+='<td title="��ʱ��">'+oTrain.duration+"</td>";sHtml+='<td title="ͣ��վ">'+oTrain.reachstation+"</td>";sHtml+='<td title="��վʱ��">'+oTrain.reachtime+"</td>";sHtml+='<td title="ͣ��ʱ��">'+oTrain.waitinterval+"</td>";sHtml+='</tr>';sHtml+='<tr class="lieche_kcont">';sHtml+='<td id="station'+i+'" colspan="9" style="display:none"><img src="/assist/res/hbload.gif" /> ���ڲ�ѯ�г����ݣ����Ժ�...</td>';sHtml+='</tr>';};$("stationLine").innerHTML=nLength;$("stationResultDiv").innerHTML=sHtml;$("stationDiv").style.display="";$("loadDiv").style.display="none";}else{$("failDiv").style.display="";$("loadDiv").style.display="none";};};function fTrainStationExtend(index,bHide,checi){var oHref=$("stationHref"+index),oTr=$("stationTr"+index);if(oHref.className=="a_g"){$("station"+index).style.display="none";oHref.className="a_k";oTr.className="";return;};if(oHref.className=="a_k"){oHref.className="a_g";oTr.className="lieche_ktit";};var sInnerHtml=$("station"+index).innerHTML;if(sInnerHtml.toLowerCase().indexOf("<img")==0){$("station"+index).style.display="";var sParam="&op=searchcheci&cls="+checi;fTrainCall(sUrl,"fTrainStationToNumberCallBack",sParam,"&extend={'op':'searchcheci','index':"+index+"}");return;}else{$("station"+index).style.display="";return;};};function fTrainStationToNumberCallBack(nCode,oJson,oExtend){if(nCode>=0){var oTrainLine=oJson[0],nLength=oTrainLine.detailList.length,sHtml='<div class="lieche_nei"><strong>'+oTrainLine.cls+'</strong> ���г���ϸ��Ϣ<br /><table class="lieche_nei_tab" cellspacing="0" cellpadding="0"><tr>	<th>վ��</th>	<th>վ��</th>	<th>����ʱ��</th>	<th>����ʱ��</th>	<th>ͣ��ʱ��</th>	<th>�г�ʱ��</th>	<th>���</th>	<th>Ӳ��</th>	<th>Ӳ����</th>	<th>������</th></tr>';for(var i=0;i<nLength;i++){var oTrain=oTrainLine.detailList[i],sClass="";if(i%2==1){sClass="lc_trbg";};sClass+=" lc_trblack";sHtml+='<tr class="'+sClass+'">';sHtml+='<td title="վ��">'+(i+1)+"</td>";sHtml+='<td title="վ��">'+oTrain.zhanming+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.reachtime+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.leavetime+"</td>";sHtml+='<td title="ͣ��ʱ��">'+oTrain.waitinterval+"</td>";sHtml+='<td title="�г�ʱ��">'+oTrain.duration+"</td>";sHtml+='<td title="���">'+oTrain.distant+" ����</td>";sHtml+='<td title="Ӳ���۸�"><span class="CEA5E00">'+((oTrain.price_yingzuo&&oTrain.price_yingzuo!=0)?oTrain.price_yingzuo+" Ԫ":"-")+"</span></td>";sHtml+='<td title="Ӳ���¼۸�">'+((oTrain.price_yingwo&&oTrain.price_yingwo!=0)?oTrain.price_yingwo+" Ԫ":"-")+"</td>";sHtml+='<td title="�����¼۸�">'+((oTrain.price_ruanwo&&oTrain.price_ruanwo!=0)?oTrain.price_ruanwo+" Ԫ":"-")+"</td>";sHtml+='</tr>';};sHtml+='</table><div class="lieche_zk_bot">';sHtml+='���Σ�<span>'+oTrainLine.cls+'</span>';sHtml+='|  ȫ��ʱ�䣺<span>'+oTrainLine.duration+'</span> ';sHtml+='|  ȫ����̣�<span>'+oTrainLine.distant+'</span>';sHtml+='|  �˳���̣�<span>'+oTrainLine.passdistant+'</span> <a href="#" onclick="fTrainStationExtend('+oExtend.index+',true);return false">[������г���Ϣ��]</a></div></div>';$("station"+oExtend.index).innerHTML=sHtml;$("station"+oExtend.index).style.display="";}else{$("failDiv").style.display="";$("loadDiv").style.display="none";};};var aLastS2STrain;function fTrainS2SCallBack(nCode,oJson,oExtend){if(nCode>=0){aLastS2STrain=oJson;var sHtml='<table class="lieche_tab" cellspacing="0" cellpadding="0"><tr>	<th>������</th>	<th>ʼ��վ���յ�վ</th>	<th>�г�����</th>	<th>����վ��Ŀ��վ</th>	<th>����ʱ������ʱ</th>	<th>�˳�ʱ��</th>	<th>Ʊ��</th></tr>  ',nLength=oJson.length;$("s2sArea").innerHTML=oExtend.from+" - "+oExtend.to;$("s2sLine").innerHTML=nLength;for(var i=0;i<nLength;i++){var oTrain=oJson[i];sHtml+='<tr id="s2sTr'+i+'">';sHtml+='<td title="����"><a href="#" onclick="fTrainS2SExtend('+i+');return false;">'+oTrain.cls+"</a></td>";sHtml+='<td title="ʼ��վ���յ�վ">'+oTrain.startstation+" - "+oTrain.endstation+"</td>";sHtml+='<td title="�г�����">'+oTrain.type+"</td>";sHtml+='<td title="����վ��Ŀ��վ"><a href="#" id="s2sHref'+i+'" onclick="fTrainS2SExtend('+i+');return false;" class="a_k">'+oTrain.fromstation+" - "+oTrain.tostation+"</a></td>";sHtml+='<td title="����ʱ������ʱ">'+oTrain.leavetime+" - "+oTrain.reachtime+"</td>";sHtml+='<td title="�˳�ʱ��">'+(oTrain.passduration?oTrain.passduration:" - ")+"</td>";sHtml+='<td title="Ʊ��">Ӳ��:<span class="Cc00">'+((oTrain.priceyingzuo&&oTrain.priceyingzuo!=0)?oTrain.priceyingzuo:"-")+'</span> Ԫ | Ӳ����:<span class="Cc00">'+((oTrain.priceyingwodown&&oTrain.priceyingwodown!=0)?oTrain.priceyingwodown:"-")+'</span> Ԫ</td>';sHtml+='</tr>';sHtml+='<tr class="lieche_kcont">';sHtml+='<td id="s2s'+i+'" colspan="7" style="display:none"></td>';sHtml+='</tr>';};$("s2sResultDiv").innerHTML=sHtml;$("s2sDiv").style.display="";$("loadDiv").style.display="none";}else{$("failDiv").style.display="";$("loadDiv").style.display="none";};};function fTrainS2SExtend(index,bHide){var oHref=$("s2sHref"+index),oTr=$("s2sTr"+index);if(oHref.className=="a_g"){$("s2s"+index).style.display="none";oHref.className="a_k";oTr.className="";return;};if(oHref.className=="a_k"){oHref.className="a_g";oTr.className="lieche_ktit";};if($("s2s"+index).innerHTML!=""){$("s2s"+index).style.display="";return;};var oTrainLine=aLastS2STrain[index],nLength=oTrainLine.detailList.length,sHtml='<div class="lieche_nei"><strong>'+oTrainLine.cls+'</strong> ���г���ϸ��Ϣ<br /><table class="lieche_nei_tab" cellspacing="0" cellpadding="0"><tr>	<th>վ��</th>	<th>վ��</th>	<th>����ʱ��</th>	<th>����ʱ��</th>	<th>ͣ��ʱ��</th>	<th>�г�ʱ��</th>	<th>���</th>	<th>Ӳ��</th>	<th>Ӳ����</th>	<th>������</th></tr>		',bBlack=false;for(var i=0;i<nLength;i++){var oTrain=oTrainLine.detailList[i],sClass="";if(i%2==1){sClass="lc_trbg";};if(oTrainLine.fromstation==oTrain.zhanming){bBlack=true;};if(bBlack){sClass+=" lc_trblack";};if(sClass){sHtml+='<tr class="'+sClass+'">';}else{sHtml+='<tr>';};if(oTrainLine.tostation==oTrain.zhanming){bBlack=false;};sHtml+='<td title="վ��">'+oTrain.zhanci+"</td>";sHtml+='<td title="վ��">'+oTrain.zhanming+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.reachtime+"</td>";sHtml+='<td title="����ʱ��">'+oTrain.leavetime+"</td>";sHtml+='<td title="ͣ��ʱ��">'+oTrain.waitinterval+"</td>";sHtml+='<td title="�г�ʱ��">'+oTrain.duration+"</td>";sHtml+='<td title="���">'+oTrain.distant+" ����</td>";sHtml+='<td title="Ӳ���۸�">'+(oTrain.price_yingzuo&&oTrain.price_yingzuo!=0?oTrain.price_yingzuo+" Ԫ":"-")+"</td>";sHtml+='<td title="Ӳ���¼۸�">'+(oTrain.price_yingwo&&oTrain.price_yingwo!=0?oTrain.price_yingwo+" Ԫ":"-")+"</td>";sHtml+='<td title="�����¼۸�">'+(oTrain.price_ruanwo&&oTrain.price_ruanwo!=0?oTrain.price_ruanwo+" Ԫ":"-")+"</td>";sHtml+='</tr>';};sHtml+='</table><div class="lieche_zk_bot">';sHtml+='���Σ�<span>'+oTrainLine.cls+'</span>';sHtml+='|  ȫ��ʱ�䣺<span>'+oTrainLine.duration+'</span> ';sHtml+='|  ȫ����̣�<span>'+oTrainLine.distant+'</span>';sHtml+='|  �˳���̣�<span>'+oTrainLine.passdistant+'</span> <a href="#" onclick="fTrainS2SExtend('+index+',true);return false">[������г���Ϣ��]</a></div></div>';$("s2s"+index).innerHTML=sHtml;$("s2s"+index).style.display="";};