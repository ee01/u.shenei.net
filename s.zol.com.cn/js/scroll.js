
// 滚动特效
$.fn.Scroll = function(option){  
    var defaults  = {
           'line'  :6,
           'speed':5000,
           'timer':1000
       };
    var pt     = $.extend(defaults,option);
    var timerID;   
    var _this=$(this);
    var lineH = _this.find("li:first").height(), //获取行高   
    line = pt.line?parseInt(_this.find("li:first").height()/lineH):parseInt(_this.find("li:first").height()/lineH,1), //每次滚动的行数，默认为一屏，即父容器高度
    speed = pt.speed?parseInt(pt.speed):1000, //卷动速度，数值越大，速度越慢（毫秒） 
    timer = pt.timer ? parseInt(pt.timer):3000; //滚动的时间间隔（毫秒）
    if(line==0) line=1;   
    var upHeight=0-lineH*line;
    //滚动函数   
    var scrollUp=function(){   
        _this.animate({   
                marginTop:upHeight   
        },300,function(){
                for(var i=0;i<line;i++){
                     _this.find("li:first").appendTo(_this);    
                }
              _this.css({marginTop:0}); 
              //  console.log(upHeight);
        })  

    }   
               
    //Shawphy:自动播放   
     var autoPlay = function(){  
             if(timer){
                 timerID = window.setInterval(scrollUp,timer);
             }

     };   
    var autoStop = function(){   
            if(timer){
                window.clearInterval(timerID);
            }   
    };   
               
    _this.hover(autoStop,function(){
         setTimeout(function(){
            autoStop();
            autoPlay();
         }, 4000);
    }).mouseout();   
}


