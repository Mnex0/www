
<?php 
session_start();
include_once('class-todolist.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
        <h1>Todo List</h1>
        <h2>Gestionnaire de tâches</h2>
        
        
        <form action="index.php?test=ok" method="post" class="form-add">
          <label for="item">Ajouter une tâche</label>

          <div class="fields">
            <input name="field-task" type="text" id="item" placeholder="Intitulé de la tâche" />
            ou
            <select name="select-task" >
              <option value="">Choisir une tâche</option>
              <option value="Passer le balai">Passer le balai</option>
              <option value="Saluer le boss">Saluer le boss</option>
              <option value="Couper l'ordi">Couper l'ordi</option>
            </select>
          </div>
          <input type="hidden" name="addTask" value="1" />
          <button type="submit">Ajouter</button>
      </div>
        </form>


        <ul class="list-todo">
          <?php
            // Affichage des tâches
            $todoList->displayTasks();
          ?>
        </ul>
</body>
</html>
