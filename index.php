<?php
/*
 * 以下测试
 */
    require "class/MoonLake.class.php";
    $moonlake = new MoonLake();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo SetUrl; ?>">
    <meta charset="UTF-8">
    <title><?php echo SetTitle; ?></title>
</head>
<body>
<?php echo START_TIME; ?><br>
<?php echo SetTitle; ?>
</body>
</html>
