(function() {
        var ZolCountDown = function(o) {
            this.init(o);
        }
        ZolCountDown.prototype = {
            //初始化
            init: function(o) {
                this.callback       = o.callback; //倒计时回调函数
                this.endCallback    = o.endCallback; //倒计时结束的回调函数
                this.remainTime     = o.remainTime; //剩余秒数 sec
                
                //时间参数
                this.oneDayTime     = 60 * 60 * 24; //sec
                this.oneHourTime    = 60 * 60; //sec
                this.oneMinuteTime  = 60; //sec
                
                //执行倒计时
                this.exec();
            },
            
            //执行倒计时
            exec: function() {
                var me              = this,
                    remainTimeObj   = {}; //剩余时间的信息

                this.timer && clearTimeout(this.timer); //先取消倒计时
                this.diffTime();
                remainTimeObj.remainDays        = this.remainDays;
                remainTimeObj.remainHours       = this.remainHours;
                remainTimeObj.remainHoursStr    = this.remainHours < 10 ? '0' + this.remainHours : '' +  this.remainHours;
                remainTimeObj.remainMinutes     = this.remainMinutes;
                remainTimeObj.remainMinutesStr  = this.remainMinutes < 10 ? '0' + this.remainMinutes : '' + this.remainMinutes;
                remainTimeObj.remainSecs        = this.remainSecs;
                remainTimeObj.remainSecsStr     = this.remainSecs < 10 ? '0' + this.remainSecs : '' + this.remainSecs;
                remainTimeObj.remainTime        = this.remainTime;
                if(this.remainTime > 0) {
                    this.callback(remainTimeObj);
                } else {
                    clearTimeout(this.timer);
                    this.endCallback(remainTimeObj);
                    return;
                }
                this.remainTime--;
                
                this.timer = setTimeout(function(){
                    me.exec();
                }, 1000); //进行递归回调
            },
            
            //计算时间差
            diffTime: function() {
                var subtractTime = 0; //用于计算时间的差值
                
                this.remainDays     = Math.floor(this.remainTime / this.oneDayTime); //剩余天数
                subtractTime        = this.remainTime - this.remainDays * this.oneDayTime;
                this.remainHours    = Math.floor(subtractTime / this.oneHourTime); //剩余的小时
                subtractTime        = subtractTime - this.remainHours * this.oneHourTime;
                this.remainMinutes  = Math.floor(subtractTime / this.oneMinuteTime); //剩余的分钟数
                this.remainSecs     = subtractTime - this.remainMinutes * this.oneMinuteTime; //剩余的秒数
            }
        };
        
        var globalObj = (function() {
            return this;
        })();
        
        globalObj.ZolCountDown = ZolCountDown;
    })();
