<?php

require_once('database.php');

// Database connection.
$db = dbConnect();
if (!$db) {
  header('HTTP/1.1 503 Service Unavailable');
  exit;
}

// Check the request.
$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode('/', $request);
$requestRessource = array_shift($request);

// Check the id associated to the request.
$id = array_shift($request);
if ($id == '')
  $id = NULL;
$data = false;

// Photos request.
if ($requestRessource == 'photos') {
  if ($id != NULL)
    $data = dbRequestPhoto($db, intval($id));
  else
    $data = dbRequestPhotos($db);
}

// Comments request
elseif ($requestRessource == 'comments') {
  $filter = array_shift($request);
  if ($id && $filter)
    $data = dbRequestComments($db, intval($id), $filter);
  else
    $data = dbRequestComments($db, intval($id));
  //Gestion de l'absence de commentaires à faire
}

// Add comment request : php/request.php/1/addcomment?id=&user=''&text=''
elseif ($requestRessource == 'addcomment' && $requestMethod == 'POST') {
  $user = $_POST["user"];
  $text = $_POST["text"];
  if ($id && $user && $text)
    $data = dbAddComment($db, $user, intval($id), $text);
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if ($data) {
  header('HTTP/1.1 200 OK');
  echo json_encode($data);
} else
  header('HTTP/1.1 400 Bad Request');

exit;