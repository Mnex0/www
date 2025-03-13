<?php
/**
 * \\Author: Thibault Napoléon "Imothep"
 * \\Company: ISEN Yncréa Ouest
 * \\Email: thibault.napoleon@isen-ouest.yncrea.fr
 * \\Created Date: 22-Jan-2018 - 13:57:23
 * \\Last Modified: 26-Aug-2024 - 12:28:43
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
  //--- dbRequestPolls ---------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get the polls.
  // \param db The connected database.
  // \return The list of polls titles.
  function dbRequestPolls($db)
  {
    try
    {
      $request = 'SELECT id, title FROM polls';
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
  
  //----------------------------------------------------------------------------
  //--- dbRequestPoll ----------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get a specific poll.
  // \param db The connected database.
  // \param id The id of the wanted poll.
  // \return The poll data.
  function dbRequestPoll($db, $id)
  {
    try
    {
      $request = 'SELECT id, title, option1, option2, option3, option1score,
        option2score, option3score, participants FROM polls WHERE id=:id';
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
