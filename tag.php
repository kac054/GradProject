<?php

session_id('id');
session_start();

$path= $_SESSION['imagepath'];


$tag=$_GET["q"];
$id=$_GET["z"];
exec("sudo python tagger.py $path $tag $id", $tmp);

?>
