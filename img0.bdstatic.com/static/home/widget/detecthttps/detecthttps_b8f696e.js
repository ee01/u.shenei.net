define("home:widget/detectHttps/detectHttps",function(t,o,e){var i=t("common:widget/ui/base/base"),n=!1;e.exports.init=function(){var t=[0,0,0,0,0,0],o=function(o,e){t[o]=e},e=i.now(),a=["../https@img0.bdstatic.com/static/common/widget/shitu/images/drag_dot_area_92f55e0.gif","../https@img1.bdstatic.com/static/common/widget/shitu/images/drag_dot_area_92f55e0.gif","../https@img1.bdstatic.com/static/common/widget/shitu/images/drag_dot_area_92f55e0.gif","../https@imgstat.baidu.com/clientcon.gif","jsonp"],c=function(){var o=t.join(""),e=!1;""===o.replace(/1/g,"")?(n=!0,i(window).trigger("https.success",o),e=!0):o.replace(/0/g,"").length===a.length&&(i(window).trigger("https.error",o),e=!0),e&&i(window).trigger("https.complete",o)};i.ajax({url:"../https@image.baidu.com/httpsjsonp/pc",dataType:"jsonp",jsonpCallback:"imageCheckHttps",success:function(t){t&&0===parseInt(t.code,10)?o(4,1):o(4,2)},error:function(){o(4,2)},complete:function(){c()}}),i.each(a,function(t,i){if("jsonp"!==i){var n=new Image,a=function(){n.onload=n.onerror=n.onabort=null,c()};n.onload=function(){o(t,1),a()},n.onabort=n.onerror=function(){o(t,2),a()},n.src=i+(-1===i.indexOf("?")?"?":"&")+"_="+e}})},e.exports.getImageHttps=function(t,o){return n&&t&&"string"==typeof t?(0===t.indexOf("/")&&(t=location.protocol+"//"+location.host+t),o&&0!==t.indexOf("./")?t:0===t.indexOf("http://")?t.replace("http:","https:"):t):t}});