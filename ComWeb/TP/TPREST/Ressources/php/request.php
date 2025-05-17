<?php

require_once 'database.php';
parse_str(file_get_contents('php://input'), $_PUT);
$db = dbConnect();
if (!$db)
{
  header('HTTP/1.1 503 Service Unavailable');
  exit;
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['PATH_INFO'];
$request = explode('/', $request);

if ($request[1] != "tweets")
{
  header('HTTP/1.1 400 Bad Request');
  exit;
}

$id = array_pop($request);

if ($requestMethod == "GET")
{
  isset($_GET['login'])?$login = $_GET['login']:$login = '';
  $data = dbRequestTweets($db, $login);
  echo json_encode($data);
}
elseif ($requestMethod == "POST")
{
  isset($_POST['login'])?$login = $_POST['login']:$login = '';
  isset($_POST['text'])?$text = strip_tags($_POST['text']):$text = '';
  $data = dbAddTweet($db, $login, $text);
  echo json_encode($data);
}
elseif ($requestMethod == "PUT")
{
  isset($_PUT['login'])?$login = $_PUT['login']:$login = '';
  isset($_PUT['text'])?$text = strip_tags($_PUT['text']):$text = '';
  $data = dbModifyTweet($db, $id, $login, $text);
  echo json_encode($data);
}
elseif ($requestMethod == "DELETE")
{
  isset($_GET['login'])?$login = $_GET['login']:$login = '';
  isset($_GET['text'])?$text = strip_tags($_GET['text']):$text = '';
  $data = dbDeleteTweet($db, $id, $login);
  echo json_encode($data);
}

exit;

// strip_tags() permet de supprimer tous les tags <> d'un string