/*
**  UCHome相册加强代码
**  唯美时尚设计工作室 出品 http://www.44-3.cn/
**  尊重作者劳动成果，转载请保留此版权信息
*/

//设置图片ID
var Photo_Parameter = document.getElementById("pic_element");

//鼠标左右指针跳转
function Album_Goto() {
  window.location.href = Album_Goto_Url;
}
function ProcessPage(intPage) {
  if (intPage == null) {
    return false
  }
  var intWidth = Photo_Parameter.width;
  var intLeft = Photo_Parameter.getBoundingClientRect()["left"] - 2;
  if (event.x - intLeft < intWidth / 2) {
    Photo_Parameter.style.cursor = "js/photo_enhance/img_pre.ani";
    Photo_Parameter.alt = "点击跳到上一张";
    Album_Goto_Url = Album_Goto_Up;
  } else {
    Photo_Parameter.style.cursor = "js/photo_enhance/img_next.ani";
    Photo_Parameter.alt = "点击跳到下一张";
    Album_Goto_Url = Album_Goto_down;
  }
}

//相册切换快捷键
document.onkeydown = function() {
  if (event.keyCode == 37) {
    window.location.href = Album_Goto_Up;
  }
  if (event.keyCode == 39) {
    window.location.href = Album_Goto_down;
  }
}

//自动定位到照片
if (window.location.hash == "") {
  window.location.href = '#playid';
}
//旋转图片
var Rotation_DuDhu = 0;
function Rotation_Way(str) {
  if (str == "1") {
    Rotation_DuDhu++;
  } else {
    Rotation_DuDhu--;
  }
  if (Rotation_DuDhu < 0) {
    Rotation_DuDhu = 3;
  }
  if (Rotation_DuDhu > 4) {
    Rotation_DuDhu = 1;
  }
  Photo_Parameter.style.filter = "progid:DXImageTransform.Microsoft.BasicImage( Rotation=" + Rotation_DuDhu + ")";
}

var Image_Zoom ="100";
//缩小图片
function Images_Small() {
  Image_Zoom =Image_Zoom / 1.1;
  Photo_Parameter.style.zoom=Image_Zoom+'%';
}
//放大图片
function Images_Big() {
  Image_Zoom =Image_Zoom * 1.1;
  Photo_Parameter.style.zoom=Image_Zoom+'%';
}