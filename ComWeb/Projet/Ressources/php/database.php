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
    error_log('Request error: ' . $exception->getMessage());
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
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

function dbAddComment($db, $userLogin, $photoId, $comment)
{
  error_log("DEBUG: Tentative d'ajout - userLogin: $userLogin, photoId: $photoId, comment: $comment");

  try {
    //$id = intval(dbGetMaxIdComm($db));  Auto-incrément donc inutile
    //$id += 1;
    //error_log("DEBUG: ID calculé: $id");

    $request = "INSERT INTO `comments`(`userLogin`, `photoId`, `comment`) VALUES (:ul,:pid,:com);";
    $statement = $db->prepare($request);
    //$statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->bindParam(':ul', $userLogin, PDO::PARAM_STR);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':com', $comment, PDO::PARAM_STR);

    $result = $statement->execute();
    error_log("DEBUG: Execute result: " . ($result ? 'true' : 'false'));
    //error_log("DEBUG: Rows affected: " . $statement->rowCount());

    return $result;
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
}

function dbDeleteComment($db, $photoId, $idCom)
{
  error_log("DEBUG: Tentative de suppression - photoId: $photoId, idCom: $idCom");

  try {
    $request = "DELETE FROM comments WHERE id=:id AND photoId=:pid;";
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $idCom, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);

    $result = $statement->execute();
    error_log("DEBUG: Execute result: " . ($result ? 'true' : 'false'));
    error_log("DEBUG: Rows affected: " . $statement->rowCount());

    return $result;
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
}

function dbModifyComment($db, $photoId, $idCom, $text){
  error_log("DEBUG: Tentative de modification - idCom: $idCom, text: $text");
  try {
    $request = "UPDATE `comments` SET `comment`=:txt WHERE `id`=:id AND `photoId`=:pid;";
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $idCom, PDO::PARAM_INT);
    $statement->bindParam(':pid', $photoId, PDO::PARAM_INT);
    $statement->bindParam(':txt', $text, PDO::PARAM_STR);

    $result = $statement->execute();
    //error_log("DEBUG: Execute result: " . ($result ? 'true' : 'false'));
    error_log("DEBUG: Rows affected: " . $statement->rowCount());

    return $result;
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
}

/*
function dbGetMaxIdComm($db)
{
  try {
    $request = 'SELECT MAX(id) as max_id FROM comments';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Retourner la valeur numérique, ou 0 si pas de commentaires
    return $result['max_id'] ?? 0;
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return 0; // Valeur par défaut si erreur
  }
}
  */