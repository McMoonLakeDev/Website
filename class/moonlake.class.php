<?php
require "userinfo.class.php";
/*--------------------------------中央控制类-------------------------------*/
/* 记录程序启动时间 */
define('START_TIME', moonlake_time());

/* 判断请求方式 */
define('IS_POST', (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'));


/* 在部分IIS上会没有REQUEST_URI变量 */
$query_string = isset($_SERVER['argv'][0]) ? $_SERVER['argv'][0] : $_SERVER['QUERY_STRING'];
if (!isset($_SERVER['REQUEST_URI']))
{
    $_SERVER['REQUEST_URI'] = PHP_SELF . '?' . $query_string;
}
else
{
    if (strpos($_SERVER['REQUEST_URI'], '?') === false && $query_string)
    {
        $_SERVER['REQUEST_URI'] .= '?' . $query_string;
    }
}
class moonlake extends userdo
    {
        private $real_ip;
//        public function _counster(){
//            init();
//        }
        function init(){
            $this->real_ip = $this->get_onlineip();
            return $this->real_ip;
        }
    }

/**
 * 危险 HTML代码过滤器
 *
 * @param   string  $html   需要过滤的html代码
 *
 * @return  string
 */
function html_filter($html)
{
    $filter = array(
        "/\s/",
        "/<(\/?)(script|i?frame|style|html|body|title|link|\?|\%)([^>]*?)>/isU",//object|meta|
        "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU",
    );

    $replace = array(
        " ",
        "&lt;\\1\\2\\3&gt;",
        "\\1\\2",
    );

    $str = preg_replace($filter,$replace,$html);
    return $str;
}
/**
 * 返回是否是通过浏览器访问的页面
 *
 * @author nihuge
 * @param  void
 * @return boolen
 */
function is_from_browser()
{
    static $ret_val = null;
    if ($ret_val === null)
    {
        $ret_val = false;
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
        if ($ua)
        {
            if ((strpos($ua, 'mozilla') !== false) && ((strpos($ua, 'msie') !== false) || (strpos($ua, 'gecko') !== false)))
            {
                $ret_val = true;
            }
            elseif (strpos($ua, 'opera'))
            {
                $ret_val = true;
            }
        }
    }
    return $ret_val;
}
/**
 *    获取当前时间的微秒数
 *
 *    @author    nihuge
 *    @return    float
 */
function moonlake_time()
{
    if (PHP_VERSION >= 5.0)
    {
        return microtime(true);
    }
    else
    {
        list($usec, $sec) = explode(" ", microtime());

        return ((float)$usec + (float)$sec);
    }
}
