<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sativaonline = "localhost";
$database_sativaonline = "sativaon_main";
$username_sativaonline = "sativaon";
$password_sativaonline = "solomon2";
$sativaonline = mysql_pconnect($hostname_sativaonline, $username_sativaonline, $password_sativaonline) or trigger_error(mysql_error(),E_USER_ERROR); 
?>