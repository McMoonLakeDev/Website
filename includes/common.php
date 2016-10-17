<?php
error_reporting(E_ALL ^ E_NOTICE);
//define('ROOT', dirname(__FILE__) . '/');
date_default_timezone_set('PRC');
header('Content-Type: text/html; charset=UTF-8');
//session_start();
require 'config.php'; //连接数据库
if (isset($db_qz)) define('DBQZ', $db_qz);
else define('DBQZ', 'wjob');
if (!isset($db_port)) $db_port = '3306';
include_once ("../class/UserInfo.class.php");
if (defined('SQLITE')) $DB = new UserDo($db_file);
else $DB = new UserDo($db_host, $db_user, $db_pwd, $db_name, $db_port);
?>