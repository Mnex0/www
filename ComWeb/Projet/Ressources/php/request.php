<?php

require_once('database.php');

// Database connection.
$db = dbConnect();
if (!$db)
{
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

if ($requestRessource == 'photos')
{
  if ($id != NULL)
    $data = dbRequestPhoto($db, intval($id));
  else
    $data = dbRequestPhotos($db);
}
elseif ($requestRessource == 'comments')
{
  $data = dbRequestComments($db, intval($id));
  //Gestion de l'absence de commentaires à faire
  if (!$data)
  {
    $data = [["id"=>0,"userLogin"=>"None","photoId"=>$id,"comment"=>"No comment available"]];
    exit;
  }
}
elseif ($requestRessource == 'addcomment')
{

}
header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if ($data != false)
{
  header('HTTP/1.1 200 OK');
  echo json_encode($data);
}
else
  header('HTTP/1.1 400 Bad Request');

exit;