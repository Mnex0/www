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
error_log("DEBUG : RequestMethodUsed " . $requestMethod . " " . ($requestMethod == "POST"));
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

//Unique comment request
elseif ($requestRessource == 'comment' && $requestMethod == "GET") {
  //$id est alors l'id du commentaire
  error_log("DEBUG : Getting comment number " . $id);
  if ($id)
    $data = dbRequestUniqueComment($db, $id);
  else
    error_log("ERROR: id not defined");
}

// Comments request
elseif ($requestRessource == 'comments' && $requestMethod == "GET") {
  error_log("DEBUG : Getting comments");
  $filter = array_shift($request);
  if ($id && $filter)
    $data = dbRequestComments($db, intval($id), $filter);
  elseif ($id)
    $data = dbRequestComments($db, intval($id));
  else
    error_log("ERROR: id not defined");
  //Gestion de l'absence de commentaires à faire
}

// Add Comments request : php/request.php/comments/1?user=''&text=''
elseif ($requestRessource == 'comments' && $requestMethod == "POST") {
  $jsonData = json_decode(file_get_contents('php://input'), true);
  $user = $jsonData["user"] ?? null;
  $text = $jsonData["text"] ?? null;

  //error_log("DEBUG : I'm here !!!".$user.$text);
  if ($id && $user && $text) {
    dbAddComment($db, $user, intval($id), $text);
    $data = true;
  } else {
    $data = false;
  }
}

//Delete comments : php/request.php/request.php/comments/1?idCom=4
elseif ($requestRessource == 'comments' && $requestMethod == "DELETE") {
  $jsonData = json_decode(file_get_contents('php://input'), true);
  $idCom = $jsonData["idCom"] ?? null;

  if ($id && $idCom) {
    dbDeleteComment($db, $id, $idCom);
    $data = true;
  } else {
    $data = false;
  }
}

//Modify comments : php/request.php/request.php/comments/1?idCom=4&text='blabla'
elseif ($requestRessource == 'comments' && $requestMethod == "PUT") {
  $jsonData = json_decode(file_get_contents('php://input'), true);
  $idCom = $jsonData["idCom"] ?? null;
  $text = $jsonData["text"] ?? null;
  if ($id && $idCom) {
    dbModifyComment($db, $id, $idCom, $text);
    $data = true;
  } else {
    $data = false;
  }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if ($data) {
  header('HTTP/1.1 200 OK');
  echo json_encode($data);
} //else
//header('HTTP/1.1 400 Bad Request');

exit;