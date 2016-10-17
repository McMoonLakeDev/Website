<?php
error_reporting(0);
include "config.php";
$db = @mysql_connect($db_host,$db_user,$db_pass);
if($db){
	mysql_select_db($db_name,$db);
	mysql_set_charset("utf8", $db);
	//echo "yes";
}else{
	die('Could not connect: ' . mysql_error());
	return false;
}
