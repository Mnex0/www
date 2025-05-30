<?php
require_once('database.php');

$db = dbConnect();
$userLogin = "cir2";
$photoId = 2;
//$idCom = 2;
$text = "Mes sincères ex";

$result = dbAddComment($db, $userLogin, $photoId, $text);
print_r($result);