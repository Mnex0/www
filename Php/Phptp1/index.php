<!--Index4.php-->

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']))
{
    $_SESSION['username'] = $_POST['username'];
}

isset($_SESSION['username']) ? $username = $_SESSION['username'] : $username = 'Invité';

if (!empty($task))
{
  $_SESSION['tasks'][] = ['task_name' => $task, 'status' => 'en_cours', 'id' => uniqid()];
}

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tp4</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <h1>Todo List</h1>
    <h2> Gestionnaire de tâches</h2>

    <form method="POST">
      <label for="item">Ajouter une tâche :</label>
      <h4></h4>
      <div class="fields">
        <input type="text" id="item" name="task" placeholder="Intitulé de la tâche" />
        <span>ou</span>
        <select name="taskchoice" id="choose">
          <option value="">Choisir une tâche...</option>
          <option value="Faire le ménage">Faire le ménage</option>
          <option value="Être poli">Être poli</option>
          <option value="Faire la fête">Faire la fête</option>
        </select>
      </div>
      <h6></h6>
      <button type="submit">Ajouter</button>
    </form>

    <?php

      if (isset($_SESSION['tasks']) && isset($_POST['task']) && !empty($_POST['task']))
      {
        array_push( $_SESSION['tasks'], $_POST['task']);
      }
      elseif(isset($_SESSION['tasks']) && isset($_POST['taskchoice']) && !empty( $_POST['taskchoice']))
      {
        array_push( $_SESSION['tasks'], $_POST['taskchoice']);
      }
      else
      {
        $_SESSION['tasks'] = [];
      }

      if (!empty($_GET['status']) && !empty($_GET['id']))
        {
          $new_status = $_GET['status'];
          $idtask = $_GET['id'];
        
          foreach ($_SESSION['tasks'] as $key => $task)
          {
            if ($key['id'] == $idtask)
            {
              $_SESSION['tasks'][$key]['status'] = $new_status == "en_cours" ? "finish" : "en_cours";
            }
          }
        }
    ?>
    <ul>
      <?php
      foreach($_SESSION['tasks'] as $task)
      {
        if($task != '')
        {
          echo '<li>' . '<a href="index.php?status="' . $task['status'] . '" &id="'.$task['id'].'">' . $task['status'] . '</a> ' . $task['task_name'] . '</li>';
        }
      }
      ?>
    </ul>
  </body>
</html>