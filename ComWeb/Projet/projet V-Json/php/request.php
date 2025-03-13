<?php

require_once('database.php');

// Database connection.
$db = dbConnect();
if (!$db)
{
  header('HTTP/1.1 503 Service Unavailable');
  exit;
}
/*
// Récupérer la méthode de la requête
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Vérifier si PATH_INFO est défini
if (!isset($_SERVER['PATH_INFO'])) {
    http_response_code(400);
    echo 'Erreur : Ressource non spécifiée.';
    exit;
}*/

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
// Send data to the client.
header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if ($data !== false)
{
  header('HTTP/1.1 200 OK');
  echo json_encode($data);
}
else
  header('HTTP/1.1 400 Bad Request');
exit;

/* Récupérer la ressource demandée en supprimant le premier slash
$pathInfo = ltrim($_SERVER['PATH_INFO'], '/');

// Découper la chaîne en segments
$pathParts = explode('/', $pathInfo);

// Vérifier si la ressource demandée est "photos"
if ($pathParts[0] === 'photos') {
    // Créer la chaîne de test
    $response = $requestMethod .' photos';
    // Envoyer la réponse au client
    echo $response;
    exit;
} else {
    http_response_code(404);
    echo 'Erreur : Ressource non trouvée.';
    exit;
}*/