<?php
session_id('id');
session_start();
$imgpath= $_SESSION['imagepath'];
$imgpath.="/*.img";
$xml= $_SESSION['path'];
$token=$_SESSION['search'];
echo $token;
exec("python extract.py -x $xml $imgpath /var/www/html/temp.zip '$token'", $tmp);

header('Content-Type:application/zip');
header("Content-Disposition:attachment; filename=temp.zip");
readfile('temp.zip');
exec("rm temp.zip", $tmp);
?>
