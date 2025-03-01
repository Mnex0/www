<?php
class Db
{
    private $pdo;

    public function __construct() 
    {
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
}