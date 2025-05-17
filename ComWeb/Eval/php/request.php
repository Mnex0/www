<?php
/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 21-May-2021 - 17:54:30
 * \\Last Modified: 30-Mar-2025 - 23:09:01
 */

  require_once('database.php');
  define('EVAL_DATETIME', '2025-04-02 08:00:00');

  // Database connexion.
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

  // Check id.
  $data = false;
  $id = array_shift($request);
  if ($id == '')
    $id = NULL;

  // Exercise 1.
  if ($requestRessource == 'ex1' && $requestMethod == 'GET' && $id != NULL)
  {
    $data = dbGetName($db, intval($id));
    if ($data)
    {
      $origin = new DateTime(EVAL_DATETIME);
      $target = new DateTime('now');
      $interval = $target->diff($origin);
      $data = array($data['name'], strval($interval->h*60 + $interval->i).'.'.
        strval($interval->s));
    }
  }

  // Exercise 2.
  if ($requestRessource == 'ex2')
  {
    if ($requestMethod == 'GET' && $id != NULL && isset($_GET['step']))
    {
      if ($_GET['step'] == '1')
        $data = dbGetProcess($db, intval($id));
      if ($_GET['step'] == '2')
        $data = dbGetSate($db, intval($id));
    }
    if ($requestMethod == 'POST' && $id != NULL && isset($_POST['answer']))
      $data = dbSetAnswer($db, intval($id), $_POST['answer']);
  }

  // Exercise 3.
  if ($requestRessource == 'ex3')
  {
  }

  // Send data to the client.
  if ($data)
  {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    if ($requestMethod == 'POST')
      header('HTTP/1.1 201 Created');
    else
      header('HTTP/1.1 200 OK');
    echo json_encode($data);
    exit;
  }

  // Bad request case.
  header('HTTP/1.1 400 Bad Request');
?>
