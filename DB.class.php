<?php 


//echo "db.class.php";
class DBAccess {

//Ucenter Home ���ò��� 

private $dbhost= 'localhost'; //��������ַ 

private $dbuser= 'shenebwx_u'; //�û� 

private $dbpw= '%75%40%53%68%65%4E%65%69%30%31'; //���� 

private $dbname= 'shenebwx_u'; //���ݿ� 
 
public  $con="";

public $result=array();

public $query ;

public $sql;

//__construct(),���캯��,�������ݿ������ 

function __construct(){

$this -> dbpw = urldecode($this -> dbpw);
$this -> con = @mysql_connect ($this -> dbhost,$this -> dbuser, $this -> dbpw);


mysql_select_db($this -> dbname,$this->con);

mysql_query("SET NAMES gbk");


} 

//__destruct�������������Ͽ����� 

function __destruct(){

mysql_close($this->con);

//�˴���������...... 


} 

//ִ����� 

function query($sql){


return mysql_query($sql);


} 

//���ؽ���� ARRAY 

function fetch_all($sql) {

$arr = array();

$query = $this->query($sql);

while($data = $this->fetch_array($query)) {

$arr[] = $data;


} 

return $arr;


} 

function fetch_array($query) {

return @mysql_fetch_array($query);

} 

//����һ����¼ 

function fetch_first($sql) {

$query = $this->query($sql);

return $this->fetch_array($query);


} 

//����һ����¼�ĵ�һ�� 

function first($sql) {


$query = $this->query($sql);

$query = $this->fetch_array($query);

return $query [0];


} 


} 

?>
