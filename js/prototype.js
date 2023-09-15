
function $() {

  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
      element = document.getElementById(element);
      return element;
  }

}
 function switchSearch(id)
    {
        switch( id )
        {
            case 1:
                $("search_nickname").style.display = "";
                $("search_region").style.display = "none";
                $("search_profile").style.display = "none";
                document.getElementsByName("radiobutton")[0].checked = true;
                break;
            case 2:
                $("search_region").style.display = "";
                $("search_nickname").style.display = "none";
                $("search_profile").style.display = "none";
                document.getElementsByName("radiobutton")[1].checked = true;
                break;
            case 3:
                $("search_profile").style.display = "";
                $("search_nickname").style.display = "none";
                $("search_region").style.display = "none";
                document.getElementsByName("radiobutton")[2].checked = true;
                break;
            default:
                break;
        }
    }


