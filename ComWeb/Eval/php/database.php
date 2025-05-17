<?php
/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 21-May-2021 - 18:02:45
 * \\Last Modified: 30-Mar-2025 - 23:08:58
 */

  require_once('constants.php');

  //----------------------------------------------------------------------------
  //--- dbConnect --------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Create the connection to the database.
  // \return False on error and the database otherwise.
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
      return false;
    }
    return $db;
  }

  //----------------------------------------------------------------------------
  //--- dbGetName --------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get the name of a student with its id.
  // \param db The connected database.
  // \param id The id of the student.
  // \return The name of the student if id exists, false otherwise.
  function dbGetName($db, $id)
  {
    try
    {
      $request = 'SELECT name FROM evaluation WHERE id=SHA1(:id)';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_STR, 7);
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

  //----------------------------------------------------------------------------
  //--- dbInsertProcess --------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to insert the calculation process associated to a student id.
  // \param db The connected database.
  // \param id The id of the student.
  // \return True if id exists, false otherwise.
  function dbInsertProcess($db, $id)
  {
    $operators = array();
    $values = array();
    $result;
    
    // Generate calculation process.
    srand($id);
    $values[] = rand()%20;
    $result = $values[0];
    for ($i = 0; $i < 10; $i++)
    {
      $values[] = rand()%20;
      if (rand()%2)
      {
        $operators[] = '+';
        $result += end($values);
      }
      else
      {
        $operators[] = '-';
        $result -= end($values);
      }
    }
    $operators = base64_encode(implode(',', $operators));
    $values = base64_encode(implode(',', $values));
    $result = base64_encode($result);

    // Update operators, values and response.
    try
    {
      $request = 'UPDATE evaluation SET operators=:operators, data=:values, '.
        'result=:result WHERE id=SHA1(:id)';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_STR, 7);
      $statement->bindParam(':operators', $operators, PDO::PARAM_STR, 64);
      $statement->bindParam(':values', $values, PDO::PARAM_STR, 64);
      $statement->bindParam(':result', $result, PDO::PARAM_STR, 64);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return true;
  }

  //----------------------------------------------------------------------------
  //--- dbGetProcess -----------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get the calculation process associated to a student id.
  // \param db The connected database.
  // \param id The id of the student.
  // \return The operators and the values for the calculation.
  function dbGetProcess($db, $id)
  {
    dbInsertProcess($db, $id);
    try
    {
      $request = 'SELECT operators, data FROM evaluation WHERE id=SHA1(:id)';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_STR, 7);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return array(
      'operators' => explode(',', base64_decode($result['operators'])),
      'values' => array_map('intval',
        explode(',', base64_decode($result['data']))));
  }

  //----------------------------------------------------------------------------
  //--- dbSetAnswer ------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to set the answer of a student with its id.
  // \param db The connected database.
  // \param id The id of the student.
  // \param answer The answer of the student.
  // \return True if id exists, false otherwise.
  function dbSetAnswer($db, $id, $answer)
  {
    try
    {
      $request = 'UPDATE evaluation SET answer=:answer WHERE id=SHA1(:id)';
      $statement = $db->prepare($request);
      $statement->bindParam(':answer', $answer, PDO::PARAM_INT);
      $statement->bindParam(':id', $id, PDO::PARAM_STR, 7);
      $statement->execute();
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return true;
  }

  //----------------------------------------------------------------------------
  //--- dbGetSate --------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get the state of the answer of a student with its id.
  // \param db The connected database.
  // \param id The id of the student.
  // \return The state of the answer of the student if id exists, false
  // otherwise.
  function dbGetSate($db, $id)
  {
    try
    {
      $request = 'SELECT answer, result FROM evaluation WHERE '.
        'id=SHA1(:id)';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_STR, 7);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    if (base64_encode($result['answer']) ==  $result['result'])
      return '<i class="fas fa-check"></i>';
    else
      return '<i class="fas fa-times"></i>';
  }
?>
