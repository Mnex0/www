<?php

require_once('constantes.php');

function dbConnect()
{
  try {
    $db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8;' .
      'port=' . DB_PORT, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $exception) {
    error_log('Connection error: ' . $exception->getMessage());
    header('HTTP/1.1 503 Service Unavailable');
    return false;
  }
  return $db;
}

// Photos -----------------------------------------------------------

function dbRequestPhotos($db)
{
  try {
    $request = 'SELECT id, small FROM photos';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

function dbRequestPhoto($db, $id)
{
  try {
    $request = 'SELECT id, title, large FROM photos WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

// Comments ---------------------------------------------------------

function dbRequestComments($db, $photoId, $filter = null)
{
  try {
    if ($filter) { // If there is a login after the photoId then it will match comments with it
      
      $request = 'SELECT c.id, c.userLogin, c.photoId, c.comment FROM comments c
      JOIN photos p
      ON c.photoId = p.id 
      WHERE c.photoId=:photoId AND c.userLogin=:setting;'; // Apply filter
      $statement = $db->prepare($request);
      $statement->bindParam(':photoId', $photoId, PDO::PARAM_INT);
      $statement->bindParam(':setting', $filter, PDO::PARAM_INT);
      $statement->execute(); // Problème
      $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll to get all rows
    } else { // Else it will show all comments of the photo
      $request = 'SELECT c.id, c.userLogin, c.photoId, c.comment FROM comments c
      JOIN photos p
      ON c.photoId = p.id 
      WHERE c.photoId=:photoId;'; // Do not apply filter
      $statement = $db->prepare($request);
      $statement->bindParam(':photoId', $photoId, PDO::PARAM_INT);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll to get all rows
    }
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

function dbAddComment($db, $userLogin, $photoId, $comment)
{
  try {
    $id = intval(dbGetMaxIdComm($db)+1);
    $request = 'INSERT INTO comments
    VALUES(:id,:ul,:pid,:com)';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->bindParam(':ul', $userLogin, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':com', $comment, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

function dbGetMaxIdComm($db)
{
  try {
    $request = 'SELECT MAX(id) FROM comments';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}