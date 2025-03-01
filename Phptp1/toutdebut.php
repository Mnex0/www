<?php

    error_reporting(E_ALL);
    $monblabla = "blabla";
    $tkt = 5;
    $bool = true;
    $level = rand(1,7);



?>

<style>
    h1
    {
        color: green;
    }
    h3
    {
        color: blue;
    }
</style>

<h<?php echo $level;?> class = "green">
    <?php echo $monblabla, $tkt, $bool; ?>
</h>