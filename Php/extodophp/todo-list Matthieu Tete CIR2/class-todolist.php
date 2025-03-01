<?php
date_default_timezone_set('Europe/Paris');

class TodoListController {
    private $pdo;
    public function __construct() {
        $this->initializeTasks();
        if ($this->pdo === null) //on fait un pointeur sur pdo pour pouvoir y accéder dans tout le code
        {
            $this->pdo = new PDO("mysql:dbname=todolist;host=localhost", "todolist_user", "todolist_pass");
        }
    }
    public function getTasks()
    {
        $sql = 'SELECT*
        FROM tasks
        ORDER BY task ASC';

        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        echo '<pre>';var_dump($result);echo '</pre>';
        return $result;
    }

    // Initialisation de la liste des tâches
    private function initializeTasks() {
        if (!isset($_SESSION['tasks'])) {
            $_SESSION['tasks'] = $this->getTasks();
        }
    }

    // Méthode principale pour gérer les requêtes
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['addTask'])) 
            {
                if (isset($_POST['field-task']) && $_POST['field-task'] != '') 
                {
                    $this->addTask($_POST['field-task']);
                }
                else 
                {
                    $this->addTask($_POST['select-task']);
                }
            } elseif (isset($_POST['toggleTask'])) {
                $this->toggleTask((int)$_POST['index']);
            } elseif (isset($_POST['deleteTask'])) {
                $this->deleteTask((int)$_POST['index']);
            }
            header("Location: index.php"); // Redirection vers le formulaire
            exit;
        }
    }

    // Méthode pour ajouter une tâche
    private function addTask($taskName) {
        $taskName = htmlspecialchars($taskName);
        $_SESSION['tasks'][] = ['name' => $taskName, 'completed' => false, 'date' => ''];
    }

    // Méthode pour basculer l'état complété d'une tâche
    private function toggleTask($index) {
        if (isset($_SESSION['tasks'][$index])) {
            $_SESSION['tasks'][$index]['completed'] = true;
            $_SESSION['tasks'][$index]['date'] = ' '.date("d M Y H:i:s");
        }
    }

    // Méthode pour supprimer une tâche
    private function deleteTask($index) {
        if (isset($_SESSION['tasks'][$index]) && ($_SESSION['tasks'][$index]['completed']) == 1) {
            array_splice($_SESSION['tasks'], $index, 1);
        }
    }

    // Méthode pour afficher la liste des tâches
    public function displayTasks() {
        foreach ($_SESSION['tasks'] as $index => $task) {
            $completedClass = $task['completed'] ? 'completed' : '';
            echo "<li class='$completedClass'>
            <form action='index.php' method='post' class='form-li'>
                <span>
                    <button type='submit' name='toggleTask' class='update-btn'>✓</button>
                    <span>{$task['name']}</span>
                    <input type='hidden' name='index' value='$index'>
                </span>
                <span>{$task['date']}</span>
                <button type='submit' name='deleteTask' class='delete-btn'>×</button>
            </form>
            </li>";
        }
    }
}

// Initialisation et traitement de la requête
$todoList = new TodoListController();
$todoList->handleRequest();
