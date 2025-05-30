<?php
// Inclusion de vos fichiers si nécessaire
require_once('database.php');

$db = dbConnect();
$userLogin = "cir2";
$photoId = 2;
//$idCom = 2;
$text = "Mes sincères ex";

// Appel direct de la fonction
$result = dbAddComment($db, $userLogin, $photoId, $text);
print_r($result);