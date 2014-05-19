<?php   
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, '127.0.0.1', 1221);

$from = '';
$port = 0;
while(true)
{
socket_recvfrom($socket, $buf, 1024, 0, $from, $port);//$str[0] 对方IP   $str[1]自己IP    $str[2]昵称      $str[3]消息内容
$str=explode(";;",$buf);
echo "发送者IP: $str[2]    发送内容： $str[3]<br>" ;
if($str[3]=="quit")
     break;
	 
$str[1]=$str[1].";;".$str[2]."hello";                              //$str[0] 对方IP   $str[1]自己IP+昵称+消息内容
echo "$str[1] <br>";
socket_sendto($socket, $str[1], strlen($str[1]), 0, $str[0], 1217);

}
echo "退出";
socket_close($socket);
?>  