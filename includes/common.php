<?php
error_reporting(0);
include "config.php";
$db = @mysql_connect($host,$user,$pass);
if($db){
	mysql_select_db($dbname,$db);
	mysql_set_charset("utf8", $db);
	//echo "yes";
}else{
	die('Could not connect: ' . mysql_error());
	return false;
}
