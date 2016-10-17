<?php
error_reporting(0);
include "config.php";
$db = @mysql_connect($db_host.':'.$db_port, $db_user, $db_pwd);
if($db){
    mysql_select_db($db_name,$db);
    mysql_set_charset("utf8", $db);
    //echo "yes";
}else{
    $msg  = array(
        'username' =>$_GET['username'],
        'result' => "无法连接到目标数据库",
        'state' => 100
    );
    die(json_encode($msg));
    return false;
}
?>