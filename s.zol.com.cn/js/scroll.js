
// ������Ч
$.fn.Scroll = function(option){  
    var defaults  = {
           'line'  :6,
           'speed':5000,
           'timer':1000
       };
    var pt     = $.extend(defaults,option);
    var timerID;   
    var _this=$(this);
    var lineH = _this.find("li:first").height(), //��ȡ�и�   
    line = pt.line?parseInt(_this.find("li:first").height()/lineH):parseInt(_this.find("li:first").height()/lineH,1), //ÿ�ι�����������Ĭ��Ϊһ�������������߶�
    speed = pt.speed?parseInt(pt.speed):1000, //���ٶȣ���ֵԽ���ٶ�Խ�������룩 
    timer = pt.timer ? parseInt(pt.timer):3000; //������ʱ���������룩
    if(line==0) line=1;   
    var upHeight=0-lineH*line;
    //��������   
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
               
    //Shawphy:�Զ�����   
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


