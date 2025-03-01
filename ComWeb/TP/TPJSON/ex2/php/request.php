<?php
/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Yncréa Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 29-Jan-2018 - 16:48:46
 * \\Last Modified: 30-Jan-2023 - 16:27:19
 */

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

  // Polls request.
  if ($requestRessource == 'polls')
  {
    if ($id != NULL)
      $data = dbRequestPoll($db, intval($id));
    else
      $data = dbRequestPolls($db);
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
?>
