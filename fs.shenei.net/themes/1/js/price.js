function sendForm()
{var str="123";if(str.length==0)
{document.getElementById("txtHint").innerHTML="";return;}
xmlHttp=GetXmlHttpObject();if(xmlHttp==null)
{alert("Your browser does not support AJAX!");return;}
var url="wp-content/themes/wpShop/time.asp";xmlHttp.onreadystatechange=stateChanged;xmlHttp.open("GET",url,true);xmlHttp.send(null);}
function GetXmlHttpObject()
{var xmlHttp=null;try
{xmlHttp=new XMLHttpRequest();}
catch(e)
{try
{xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e)
{xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}}
return xmlHttp;}
function stateChanged()
{if(xmlHttp.readyState==4)
{document.getElementById('priceForm').submit();}}
function collectAmounts(basisPrice,attrNum){var bp=Number(basisPrice);var amount=Number("0.00");var vfactor=0;var attrData="";for(i=0,a=1;i<attrNum;i++,a++){var attr="attr_"+a;var show_price="attr_price_"+a;var rawDazu=document.forms['the_product'].elements[attr].options[document.forms['the_product'].elements[attr].options.selectedIndex].value;if(rawDazu!="pch"){var parts=rawDazu.split("#");var dazu=Number(parts[0]);attrData=attrData+"#"+parts[1];amount+=dazu;vfactor++;}}
amount=amount+bp;document.getElementById('priceTotal').innerHTML=amount.toFixed(2);if(vfactor==attrNum){document.getElementById('addC').style.visibility="visible";document.getElementById('greyAdd').style.display="none";}
else{document.getElementById('addC').style.visibilty="hidden";document.getElementById('greyAdd').style.display="block";}
document.getElementById('amount').setAttribute("value",amount);document.getElementById('attrData').setAttribute("value",attrData);}
function cartButtonVisbility(){document.getElementById('addC').style.visibility="hidden";}