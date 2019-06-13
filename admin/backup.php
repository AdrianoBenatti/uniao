<?php
$host = 'localhost';
$user = 'pharmaspecial3';
$pass = 'E1wk@33b';
$bd = 'pharmaspecial_unido';

$time = time();
$filename = "../media/backups/bd_$time.sql";
exec("mysqldump --user='$user' --password='$pass' --host='$host' '$bd' > $filename");
?>
