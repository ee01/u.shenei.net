window.onerror = function()
{
return true;
}
//����
function play()
{
 if (player1.controls.isavailable('play'))
 {
  userstop=false; //�û�ֹͣȡ��
  player1.controls.play();
  musicSwf.SetVariable("musicName", RadioList[NowId]);
 }
}
//��ͣ
function pause()
{
 if (player1.controls.isavailable('pause'))
 {
  player1.controls.pause();
  clearInterval(state);
  musicSwf.SetVariable("musicName", "��ͣ");
 }
}
//ֹͣ
function stop()
{
 if (player1.controls.isavailable('stop'))
 {
  userstop=true; //ȷ���û�ֹͣ
  player1.controls.stop();
  clearInterval(state);
  musicSwf.SetVariable("musicName", "ֹͣ");
 }
}
//ǰ��
function previous()
{
NowId--;
if( NowId<0) NowId=count-1;
player1.URL=listURL[NowId];
musicSwf.SetVariable("musicName", RadioList[NowId]);
play();
userstop=false; //�û�ֹͣȡ��
}
//����
function next()
{
NowId++;
if( NowId>=count) NowId=0;
player1.URL=listURL[NowId];
musicSwf.SetVariable("musicName", RadioList[NowId]);
play();	
userstop=false; //�û�ֹͣȡ��
}