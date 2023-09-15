function fSetMenu(id)
{
    var oDiv = document.getElementById("menu"+id);
    var oA = document.getElementById("a"+id);
    var sDisplay  = oDiv.style.display ;
    if(sDisplay == ""){
        oDiv.style.display = "none";
        oA.className = ""
    }else{
        oDiv.style.display = "";
        oA.className = "on_m"
    }
	if(document.getElementById("aIndex")){
		document.getElementById("aIndex").className = "a_cy_ser";
	}
    return false;
}
