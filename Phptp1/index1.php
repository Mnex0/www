<!--Index1.php-->

<?php

if(isset($_GET['task']))
{
  $tasks = isset($_GET['tasks']) ? explode(',', $_GET['tasks']) : [];
  $tasks[] = $_GET['task'];
}
else
{
  $tasks = ['passer le balai', 'Tel mom', 'une partie de jeu'];
}

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tp1</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <h1>Todo List</h1>
    <h2> Gestionnaire de tâches</h2>

    <a href="?liste=li1">Liste</a>
    <a href="?liste=li2">Liste en cours</a>

    <form>
      <label for="item">Ajouter une tâche</label>
      <div class="fields">
        <input type="text" id="item" name="task" placeholder="Intitulé de la tâche" />
        <span>ou</span>
        <select name="task" id="choose">
          <option value=""></option>
          <option value="Faire le ménage">Faire le ménage</option>
          <option value="Être poli">Être poli</option>
          <option value="Faire la fête">Faire la fête</option>
        </select>
        <input type="hidden" name="tasks" value="<?php echo implode(',', $tasks); ?>">
      </div>
      <button type="submit">Ajouter</button>
    </form>

    <?php
      $liste = [];
      $task = '';  
      $li1 = [];
      $li2 = [];
      if(isset($_GET['task'])) array_push( $li2, $_GET['task']);
      
      $affiche = $li2;
      $checked = '';
      if(isset($_GET['liste']))
      {
        $affiche = $_GET['liste'] == 'li1' ? $li1 : $li2;
        $checked = $_GET['liste'] == 'li1' ? 'checked' : '';
      }
      
    ?>

    <ul>
      <?php
      foreach($tasks as $task)
      {
        echo '<li><input type="checkbox" />'.htmlspecialchars($task).'</li>';
      }
      ?>
    </ul>
  </body>
</html>

