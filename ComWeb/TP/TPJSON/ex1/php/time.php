<?php
$date = getdate();
$h = $date['hours'];
$m = $date['minutes'];
$s = $date['seconds'];
$times = array(
    "hours" => $h,
    "minutes" => $m,
    "seconds" => $s
);
$time = ["Il est : $h:$m:$s", $times];
echo json_encode($time);

header('Content-Type: application/json ');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');