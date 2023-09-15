window.onerror = function()
{
return true;
}
//播放
function play()
{
 if (player1.controls.isavailable('play'))
 {
  userstop=false; //用户停止取消
  player1.controls.play();
  musicSwf.SetVariable("musicName", RadioList[NowId]);
 }
}
//暂停
function pause()
{
 if (player1.controls.isavailable('pause'))
 {
  player1.controls.pause();
  clearInterval(state);
  musicSwf.SetVariable("musicName", "暂停");
 }
}
//停止
function stop()
{
 if (player1.controls.isavailable('stop'))
 {
  userstop=true; //确认用户停止
  player1.controls.stop();
  clearInterval(state);
  musicSwf.SetVariable("musicName", "停止");
 }
}
//前首
function previous()
{
NowId--;
if( NowId<0) NowId=count-1;
player1.URL=listURL[NowId];
musicSwf.SetVariable("musicName", RadioList[NowId]);
play();
userstop=false; //用户停止取消
}
//后首
function next()
{
NowId++;
if( NowId>=count) NowId=0;
player1.URL=listURL[NowId];
musicSwf.SetVariable("musicName", RadioList[NowId]);
play();	
userstop=false; //用户停止取消
}