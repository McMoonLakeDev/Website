<?php
    require "../class/LaunUser.class.php";
    $LaunUser = new LaunUser($_GET['username'],$_GET['pwd']);
    echo $msg = $LaunUser->login();