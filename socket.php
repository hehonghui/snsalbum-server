<?php   
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, '127.0.0.1', 1221);

$from = '';
$port = 0;
while(true)
{
socket_recvfrom($socket, $buf, 1024, 0, $from, $port);//$str[0] �Է�IP   $str[1]�Լ�IP    $str[2]�ǳ�      $str[3]��Ϣ����
$str=explode(";;",$buf);
echo "������IP: $str[2]    �������ݣ� $str[3]<br>" ;
if($str[3]=="quit")
     break;
	 
$str[1]=$str[1].";;".$str[2]."hello";                              //$str[0] �Է�IP   $str[1]�Լ�IP+�ǳ�+��Ϣ����
echo "$str[1] <br>";
socket_sendto($socket, $str[1], strlen($str[1]), 0, $str[0], 1217);

}
echo "�˳�";
socket_close($socket);
?>  