<?php
session_start();
require "sql.class.php";
class userdo extends sql{
    /*
     * 获取真实IP地址，无视代理
     */
    function get_onlineip() {
        $onlineip = '';
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        if($onlineip == "::1"){
            $onlineip = "127.0.0.1";
        }
        return $onlineip;
    }

    /*
     * 稀有cookie 加密
     * 如果秘钥太长，解密时会报错
     */

    function en_cookie($data, $key) {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
        return base64_encode($encrypt);
    }
    /*
     * 稀有cookie 解密
     * 如果秘钥太长，解密时会报错
     */
    function de_cookie($str, $key) {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }
    function pubsession($username,$password){
        $name = mysql_escape_string($username);
        $names = $this->query("Select * from ecm_member where user_name='{$username}' and password='{$password}'");
        $namen = mysql_num_rows($names);
        if($namen <> 0){
                $cookie = array(
                    "username" => $name,
                    "password" => $password,
                    "last_ip" => $this->get_onlineip()
                );
            $userinfo = $this->user_info($username);
                $session = array(
                    'user_id' => $userinfo['user_id'],
                    'user_name' => $userinfo['user_name'],
                    'real_name' => $userinfo['real_name'],
                    'reg_time' => $userinfo['reg_time'],
                    'last_login' =>$userinfo['last_login'],
                    'last_ip' => $userinfo['last_ip'],
                    'store_id' => $userinfo['store_id'],
                    'belong_store_id' => $userinfo['belong_store_id'],
                    'roles' => $userinfo['roles']
                );
                $encookie = $this->en_cookie($cookie,"shop");
                setcookie("userinfo",$encookie,time()+3600*24);
                $_SESSION['user_info'] = $session;
        $this->query("Update ecm_member set last_ip='{$cookie['last_ip']}' Where user_name = '{$username}'");
            $msg = '{"success":true,"msg":"登录成功！"}';
        }else{
            $msg = '{"success":false,"msg":"登录失败！"}';
        }
        return $msg;
    }
    /**
     *  设置COOKIE
     *
     *  @access public
     *  @param  string $key     要设置的COOKIE键名
     *  @param  string $value   键名对应的值
     *  @param  int    $expire  过期时间
     *  @return void
     */
    function ecm_setcookie($key, $value, $expire = 0, $cookie_path=COOKIE_PATH, $cookie_domain=COOKIE_DOMAIN)
    {
        setcookie($key, $value, $expire, $cookie_path, $cookie_domain);
    }

    /**
     *  获取COOKIE的值
     *
     *  @access public
     *  @param  string $key    为空时将返回所有COOKIE
     *  @return mixed
     */
    function ecm_getcookie($key = '')
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : 0;
    }
}