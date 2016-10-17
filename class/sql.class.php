<?php
    include "./includes/common.php";
    class Sql
    {
        /*
         * 数据查询
         *
         * */
        function query($sqlquery)
        {
            $result = mysql_query($sqlquery);
            return $result;
        }
        /*
         *判断是否符合条件
         * */
        function re($sqlquery)
        {
            $dbresult = mysql_query($sqlquery);
            $num = mysql_num_rows($dbresult);
            $final = null;
            if ($num == 0) {
                $final = true;
            } else {
                $final = false;
            }
            return $final;
        }
        /*
         * num是获取总共多少页,page是获取当前页的三维数组数据
         * 输入动作，每页多少条数据，什么条件，第几页（可不输入）可以得到三维数组
         *
         * */
        function goodpage($do, $hpage, $what, $wpage = 1)
        {
            if ($do == "pagenum") {
                $sql = "select * from ecm_goods where " . $what;
                $csql = mysql_query($sql);
                $csqln = mysql_num_rows($csql);
                $final = ceil($csqln / $hpage);
                return $final;
            }
            if ($do == "page") {
                $sql = "select * from ecm_goods where " . $what . " limit " . $hpage * ($wpage - 1) . "," . $hpage;
                $sqla = mysql_query($sql);
                $a = "";
                $n = '$a=array_merge_recursive(';
                for ($i = 1; $i <= $hpage; $i++) {
                    $row[$i] = mysql_fetch_assoc($sqla);
                    if ($row[$i]['goods_id'] <> null) {
                        $n .= '$row[\'' . $i . '\'],';
                    } else {
                        $n .= '';
                    }
                }
                if (isset($row['1'])) {
                    $n = rtrim($n, ",");
                }
                $n .= ");";
                eval($n);
            }
            return $a;
        }
        function num($what){
                $sql = "select * from ecm_goods where " . $what;
                $csql = mysql_query($sql);
                $csqln = mysql_num_rows($csql);
                return $csqln;
        }
        /*
         * 根据商家查找商品
         * */
        function mecm_goods($text,$hpage,$wpage)
        {

            $sql = "Select * from ecm_store where store_name like '%{$text}%'";
            $sqla = mysql_query($sql);
            $sqln = mysql_num_rows($sqla);
            if($wpage>ceil($sqln/$hpage)){
                $a=null;
            }else{
                $sqlid = mysql_fetch_assoc($sqla);
                $sqlg = "store_id='{$sqlid['store_id']}'";
                $a = goodpage("page",$hpage,$sqlg,$wpage);
            }
            return $a;
        }
        /*
         * 根据商品id获取商家
         * */
        function goods_id_mname($mid){
            $sql = "Select store_name,region_id from ecm_store where store_id='{$mid}'";
            $sqla = mysql_query($sql);
            $sqlname = mysql_fetch_assoc($sqla);
            if($sqlname['region_id']<>0){
                $sqls = mysql_query("select region_name from ecm_region where region_id='{$sqlname['region_id']}'");
                $sqlfinal = mysql_fetch_assoc($sqls);
                $sqlname['region_name']=$sqlfinal['region_name'];
            }
                return $sqlname;
        }

        function goods_id_info($goods_id){
            $sqla = $this->query("Select goods_name,default_image from ecm_goods where goods_id='{$goods_id}'");
            $sqlb = $this->query("Select * from ecm_goods_spec where goods_id='{$goods_id}'");
            $sqlname = mysql_fetch_assoc($sqlb);
            $sqlinfo = mysql_fetch_assoc($sqla);
            $info = array_merge_recursive($sqlname,$sqlinfo);
            return $info;
        }

        /*
         * 获取用户信息
         */
        function user_info($name){
            $user_info = $this->query("Select * from ecm_member where user_name='{$name}'");
            $user_info = mysql_fetch_assoc($user_info);
            $store_id = $this->query("Select store_id from ecm_user_priv where user_id='{$user_info['user_id']}' and store_id>0 and privs='all'");
            $store_id = mysql_fetch_assoc($store_id);
            $info = array_merge_recursive($user_info,$store_id);
            return $info;
        }
    }