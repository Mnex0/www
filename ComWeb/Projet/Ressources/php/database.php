<?php

require_once('constantes.php');

function dbConnect()
{
  try {
    $db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8;' .
      'port=' . DB_PORT, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $exception) {
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
      $statement->bindParam(':setting', $filter, PDO::PARAM_STR);

      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    return false;
  }
  return $result;
}

function dbRequestUniqueComment($db, $idCom)
{
  try {
    $request = 'SELECT c.id, c.userLogin, c.photoId, c.comment FROM comments c WHERE c.id=:idCom';

    $statement = $db->prepare($request);
    $statement->bindParam(':idCom', $idCom, PDO::PARAM_INT);

    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC); // Use fetch to get only one line
  } catch (PDOException $exception) {
    return false;
  }
  return $result;
}

function dbAddComment($db, $userLogin, $photoId, $comment)
{

  try {
    // No need of comment id, it's auto-increment
    $request = "INSERT INTO `comments`(`userLogin`, `photoId`, `comment`) VALUES (:ul,:pid,:com);";
    $statement = $db->prepare($request);
    $statement->bindParam(':ul', $userLogin, PDO::PARAM_STR);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':com', $comment, PDO::PARAM_STR);

    $result = $statement->execute();

    return $result;
  } catch (PDOException $exception) {
    return false;
  }
}

function dbDeleteComment($db, $photoId, $idCom)
{
  try {
    $request = "DELETE FROM comments WHERE id=:id AND photoId=:pid;";
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $idCom, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);

    $result = $statement->execute();
    return $result;
  } catch (PDOException $exception) {
    return false;
  }
}

function dbModifyComment($db, $photoId, $idCom, $text)
{
  try {
    $request = "UPDATE `comments` SET `comment`=:txt WHERE `id`=:id AND `photoId`=:pid;";
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $idCom, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':txt', $text, PDO::PARAM_STR);

    $result = $statement->execute();

    return $result;
  } catch (PDOException $exception) {
    return false;
  }
}