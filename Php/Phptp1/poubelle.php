<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tp1</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <h1>Todo List</h1>
    <h2> Gestionnaire de tâches</h2>

    <form>
      <label for="item">Ajouter une tâche</label>
      <div class="fields">
        <input type="text" id="item" placeholder="Intitulé de la tâche" />
        <input type="hidden" name="tasks" value="<?php echo implode(',',$tasks); ?>"/>
        <span>ou</span>
      </div>
      <button type="submit">Ajouter</button>
    </form>
    <ul class="liste-todo">
        <?php
        foreach ($li1 as $key => $value)
        {
          echo '<li>'.$value.'</li>';
        }
        ?>
    </ul>
  </body>
</html>

<?php
session_start(); // Démarre la session

// Ajoute une requête dans la session
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $task = $_GET['task']; // On suppose que tu envoies des données via un formulaire

    // Stocke la requête dans la session
    if (!isset($_SESSION['task'])) {
        $_SESSION['task'] = [];
    }

    $_SESSION['task'][] = $task;
}

// Affiche les anciennes requêtes
if (isset($_SESSION['task'])) {
    echo "Anciennes task :<br>";
    foreach ($_SESSION['task'] as $ancienne_task) {
        echo $ancienne_task . "<br>";

    }
}

//VRAI CODE du prof

$task = '';
$notice = '';
$tasks = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $field_task_value = $_POST['field_task'];
    $select_task_value = $_POST['select_task'];

    if (empty($field_task_value) && empty($select_task_value)) {
        $notice = 'Oups !';
    } else {
        if (!empty($select_task_value)) $task = $select_task_value;
        else $task = $field_task_value;
        $notice = 'Tâche ajoutée';
    }

    if (!empty($task)) {
        $tasks = explode(',', $_POST['tasks']);
        $tasks[] = $task;
    }
}
?>