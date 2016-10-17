<?php
require "../includes/Laucommon.php";
class LaunUser
{
    private $username;
    private $password;
    private $email;

    function __construct($username,$pwd,$email=""){
        $this->username = $username;
        $this->password = $pwd;
        $this->email = $email;
    }
    function SetName($name){
        $this->username = $name;
        return $this->username;
    }
    function SetPwd($pwd){
        $this->password = $pwd;
        return $this->password;
    }
    function SetEmail($email){
        $this->email = $email;
        return $this->email;
    }
//    function SetMac($mac){
//        $this->clientmac = $mac;
//        return $this->clientmac;
//    }
    function GetName(){
        return $this->username;
    }
    function GetPwd(){
        return $this->password;
    }
    function GetEmail(){
        return $this->email;
    }
//    function GetMac(){
//        return $this->clientmac;
//    }


    public function getFinalPassword($password, $salt) {
        return strtolower(md5(strtolower(md5($password)).$salt));
    }

    function login(){
        $name = mysql_escape_string($this->username);
        $namen = mysql_query("Select realname,password,salt from userdo where realname='".strtolower($name)."'");
        $namer = mysql_fetch_assoc($namen);

        if ($namen){
            if(mysql_num_rows($namen)<>0){
                $pwd = $this->getFinalPassword($this->password, $namer['salt']);
                if($namer['password'] == $pwd){
                    $msg = array(
                        "username"=>$_GET['username'],
                        "result"=>"登录成功",
                        "state"=>"200"
                    );
                }else{
                    $msg = array(
                        "username"=>$_GET['username'],
                        "result"=>"密码错误",
                        "state"=>"359"
                    );
                }
            }else{
                $msg = array(
                    "username"=>$_GET['username'],
                    "result"=>"不存在这个用户",
                    "state"=>"300"
                );
            }
        }else{
            $msg = array(
                "username"=>$_GET['username'],
                "result"=>"没有查询到结果",
                "state"=>"106"
            );
        }
        return json_encode($msg);
        }


    function get_onlineip()
    {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        if ($onlineip == "::1") {
            $onlineip = "127.0.0.1";
        }
        return $onlineip;
    }

}