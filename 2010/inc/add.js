function FaceChoose(n){
	var ClassName = "Face"+n;
	document.getElementById("Peview").setAttribute("class",ClassName);
	document.getElementById("Peview").setAttribute("className",ClassName);
	frmAdd.face.value = n;
}
function IconChange(n){
	var IconUrl = "images/icon"+n+".gif";
	document.getElementById("IconImg").src = IconUrl;	
	frmAdd.icon.value = n;	
}
function InputName(OriInput, GoalArea){
	document.getElementById(GoalArea).innerHTML = OriInput.value;
}
function strCounter(field){
	if (field.value.length > 30)
		field.value = field.value.substring(0, 30);
	else{
		document.getElementById("Char").innerHTML = 30 - field.value.length;
		document.getElementById("AreaText").innerHTML = field.value;
	}
}
function getTime(){
	var ThisTime = new Date();
	document.write(ThisTime.getFullYear()+"-"+(ThisTime.getMonth()+1)+"-"+ThisTime.getDate()+" "+ThisTime.getHours()+":"+ThisTime.getMinutes()+":"+ThisTime.getSeconds()); 
}
function chkAspk(obj){
    if(obj.info.value==""){
        alert("请填写[字条内容]");
        obj.info.focus();
        return false;
    }
	if(obj.send.value==""){
        alert("请填写[发送人]");
        obj.send.focus();
        return false;
    }
    if(obj.key.value==""){
        alert("请输入[验证码]");
        obj.key.focus();
        return false;
    }else{
		var noStr = obj.key.value;
		var no = parseInt(noStr);
		if(isNaN(no)){
			alert("[验证码]必须是数字");
			return false;
		}else if(no < 1){
			alert("[验证码]必须是正数");
			return false;
		}
	}
	frmAdd.submit.disabled=true;
    return true;
}