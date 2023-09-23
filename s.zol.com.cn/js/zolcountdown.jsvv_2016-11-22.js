(function() {
        var ZolCountDown = function(o) {
            this.init(o);
        }
        ZolCountDown.prototype = {
            //��ʼ��
            init: function(o) {
                this.callback       = o.callback; //����ʱ�ص�����
                this.endCallback    = o.endCallback; //����ʱ�����Ļص�����
                this.remainTime     = o.remainTime; //ʣ������ sec
                
                //ʱ�����
                this.oneDayTime     = 60 * 60 * 24; //sec
                this.oneHourTime    = 60 * 60; //sec
                this.oneMinuteTime  = 60; //sec
                
                //ִ�е���ʱ
                this.exec();
            },
            
            //ִ�е���ʱ
            exec: function() {
                var me              = this,
                    remainTimeObj   = {}; //ʣ��ʱ�����Ϣ

                this.timer && clearTimeout(this.timer); //��ȡ������ʱ
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
                }, 1000); //���еݹ�ص�
            },
            
            //����ʱ���
            diffTime: function() {
                var subtractTime = 0; //���ڼ���ʱ��Ĳ�ֵ
                
                this.remainDays     = Math.floor(this.remainTime / this.oneDayTime); //ʣ������
                subtractTime        = this.remainTime - this.remainDays * this.oneDayTime;
                this.remainHours    = Math.floor(subtractTime / this.oneHourTime); //ʣ���Сʱ
                subtractTime        = subtractTime - this.remainHours * this.oneHourTime;
                this.remainMinutes  = Math.floor(subtractTime / this.oneMinuteTime); //ʣ��ķ�����
                this.remainSecs     = subtractTime - this.remainMinutes * this.oneMinuteTime; //ʣ�������
            }
        };
        
        var globalObj = (function() {
            return this;
        })();
        
        globalObj.ZolCountDown = ZolCountDown;
    })();
