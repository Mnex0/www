<?php

require_once('constantes.php');


function dbConnect()
  {
    try
    {
      $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8;'.
        'port='.DB_PORT, DB_USER, DB_PASSWORD);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
    catch (PDOException $exception)
    {
      error_log('Connection error: '.$exception->getMessage());
      header('HTTP/1.1 503 Service Unavailable');
      return false;
    }
    return $db;
  }


function dbRequestPhotos($db)
{
  try
  {
    $request = 'SELECT id, small FROM photos';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}


function dbRequestPhoto($db, $id)
{
  try
  {
    $request = 'SELECT id, title, large FROM photos WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}

function dbRequestComments($db, $photoId)
{
  try
  {
    $request = 'SELECT c.id, c.userLogin, C.photoId, c.comment FROM comments c
    JOIN photos p
    ON c.photoId = p.id 
    WHERE c.photoId=:photoId;';
    $statement = $db->prepare($request);
    $statement->bindParam(':photoId', $photoId, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll to get all rows
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}
/*
function dbRequestComment($db, $photoId, $idCom)
{
  try
  {
    $request = 'SELECT * FROM comments c
    JOIN photos p
    ON c.photoId = p.id 
    WHERE c.photoId=:photoId AND c.id =:idCom';
    $statement = $db->prepare($request);
    $statement->bindParam(':photoId', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':idCom', $idCom, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}
*/
function dbAddComment($db, $userLogin, $photoId, $comment)
{
  try
  {
    $request = 'INSERT INTO comments
    VALUES(:ul,:pid,:com)';
    $statement = $db->prepare($request);
    $statement->bindParam(':ul', $userLogin, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':com', $comment, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}