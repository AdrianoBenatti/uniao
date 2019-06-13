<?php
$id = $_SESSION['id_usuario'];
$data = date("Y-M-d");
$query = mysql_query("UPDATE usuarios SET data='$data' WHERE id=$id", conecta());
session_destroy();
header("Location: $url");
?>