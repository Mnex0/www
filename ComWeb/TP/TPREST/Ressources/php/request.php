<?php

require_once 'database.php';

$db = dbConnect();
if (!$db)
{
  header('HTTP/1.1 503 Service Unavailable');
  exit;
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['PATH_INFO'];
$request = explode('/', $request);


if ($requestMethod == "GET")
{
  isset($_GET['login'])?$login = $_GET['login']:$login = '';
  isset($_GET['text'])?$text = $_GET['text']:$text = '';
  $data = dbRequestTweets($db, $login);
  echo json_encode($data);
}
elseif ($requestMethod == "POST")
{
  isset($_POST['login'])?$login = $_POST['login']:$login = '';
  isset($_POST['text'])?$text = $_POST['text']:$text = '';
  $data = dbAddTweet($db, $login, $text);
  echo json_encode($data);
}
elseif ($requestMethod == )

exit;