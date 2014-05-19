<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_android = "localhost";
$database_android = "android";
$username_android = "root";
$password_android = "";
$android = mysql_pconnect($hostname_android, $username_android, $password_android) or trigger_error(mysql_error(),E_USER_ERROR); 
?>